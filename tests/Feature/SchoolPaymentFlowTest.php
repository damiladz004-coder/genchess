<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\School;
use App\Models\SchoolPayment;
use App\Models\SchoolRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class SchoolPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_school_payment_callback_marks_invoice_partial_and_is_idempotent(): void
    {
        $school = $this->createSchool();
        $schoolPayment = $this->createSchoolPayment($school, totalDue: 10000, amountPaid: 2000);

        $payment = Payment::create([
            'email' => 'school-admin@example.com',
            'reference' => 'GC-SCH-' . strtoupper(Str::random(12)),
            'amount' => 300000, // NGN 3,000 in kobo
            'purpose' => Payment::PURPOSE_SCHOOL,
            'status' => 'pending',
            'metadata' => [
                'school_payment_id' => $schoolPayment->id,
                'school_id' => $school->id,
            ],
        ]);

        Http::fake([
            '*' => Http::response([
                'status' => true,
                'data' => [
                    'status' => 'success',
                    'amount' => 300000,
                    'reference' => $payment->reference,
                ],
            ], 200),
        ]);

        $response = $this->get(route('payments.callback', ['reference' => $payment->reference]));

        $response
            ->assertRedirect(route('school.finance.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('school_payments', [
            'id' => $schoolPayment->id,
            'amount_paid' => 5000,
            'status' => 'partial',
            'reference' => $payment->reference,
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'paid',
        ]);

        // Replaying callback should not re-apply the same payment amount.
        $this->get(route('payments.callback', ['reference' => $payment->reference]))->assertRedirect(route('school.finance.index'));

        $this->assertDatabaseHas('school_payments', [
            'id' => $schoolPayment->id,
            'amount_paid' => 5000,
            'status' => 'partial',
        ]);
    }

    public function test_school_payment_callback_failure_marks_payment_failed(): void
    {
        $school = $this->createSchool();
        $schoolPayment = $this->createSchoolPayment($school, totalDue: 10000, amountPaid: 2000);

        $payment = Payment::create([
            'email' => 'school-admin@example.com',
            'reference' => 'GC-SCH-' . strtoupper(Str::random(12)),
            'amount' => 300000,
            'purpose' => Payment::PURPOSE_SCHOOL,
            'status' => 'pending',
            'metadata' => [
                'school_payment_id' => $schoolPayment->id,
                'school_id' => $school->id,
            ],
        ]);

        Http::fake([
            '*' => Http::response([
                'status' => true,
                'data' => [
                    'status' => 'failed',
                    'amount' => 300000,
                    'reference' => $payment->reference,
                ],
            ], 200),
        ]);

        $response = $this->get(route('payments.callback', ['reference' => $payment->reference]));

        $response
            ->assertRedirect(route('school.finance.index'))
            ->assertSessionHasErrors('payment');

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'failed',
        ]);

        $this->assertDatabaseHas('school_payments', [
            'id' => $schoolPayment->id,
            'amount_paid' => 2000,
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_consultation_request_without_payment(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        $schoolRequest = SchoolRequest::create([
            'school_name' => 'Community Chess Hub',
            'contact_person' => 'Jane Doe',
            'email' => 'community@example.com',
            'phone' => '08000000000',
            'program_type' => 'community',
            'school_type' => 'private',
            'class_system' => 'primary_jss_ss',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'consultation_needed' => true,
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.enrollments.approve', $schoolRequest));

        $response
            ->assertRedirect(route('admin.enrollments.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('school_requests', [
            'id' => $schoolRequest->id,
            'status' => 'approved',
        ]);
    }

    public function test_admin_can_schedule_consultation_without_payment(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        $schoolRequest = SchoolRequest::create([
            'school_name' => 'Community Chess Hub',
            'contact_person' => 'Jane Doe',
            'email' => 'community@example.com',
            'phone' => '08000000000',
            'program_type' => 'community',
            'school_type' => 'private',
            'class_system' => 'primary_jss_ss',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'consultation_needed' => true,
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(route('admin.enrollments.consultation', $schoolRequest), [
                'meeting_type' => 'virtual',
                'meeting_date' => now()->addDays(2)->toDateString(),
                'meeting_time' => '10:30',
                'consultation_link' => 'https://meet.google.com/abc-defg-hij',
                'consultation_meeting_id' => 'abc-defg-hij',
                'consultation_passcode' => '123456',
            ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('school_requests', [
            'id' => $schoolRequest->id,
            'meeting_type' => 'virtual',
            'consultation_meeting_id' => 'abc-defg-hij',
        ]);
    }

    protected function createSchool(): School
    {
        return School::create([
            'school_name' => 'Test School',
            'school_type' => 'private',
            'class_system' => 'primary_jss_ss',
            'address_line' => '123 Test Street',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'contact_person' => 'Admin User',
            'phone' => '08012345678',
            'email' => 'school@example.com',
            'status' => 'active',
        ]);
    }

    protected function createSchoolPayment(School $school, int $totalDue, int $amountPaid): SchoolPayment
    {
        return SchoolPayment::create([
            'school_id' => $school->id,
            'term' => 'Term 1',
            'session' => '2025/2026',
            'student_count' => 50,
            'per_student_amount' => 200,
            'total_due' => $totalDue,
            'amount_paid' => $amountPaid,
            'status' => $amountPaid > 0 ? 'partial' : 'pending',
            'first_amount' => (int) floor($totalDue / 2),
            'second_amount' => $totalDue - (int) floor($totalDue / 2),
        ]);
    }
}
