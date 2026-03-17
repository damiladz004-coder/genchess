<?php

namespace Tests\Feature;

use App\Mail\SchoolPortalAccessMail;
use App\Mail\SchoolRequestReceived;
use App\Models\School;
use App\Models\SchoolRequest;
use App\Models\User;
use App\Services\SchoolOnboardingLinkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SchoolOnboardingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_school_registration_sends_confirmation_email(): void
    {
        Mail::fake();
        config()->set('paystack.fees.school', 0);

        $response = $this->post(route('school.enroll'), [
            'program_type' => 'school',
            'school_name' => 'Kingdom College',
            'contact_person' => 'Jane Principal',
            'email' => 'school@example.com',
            'phone' => '08000000001',
            'school_type' => 'private',
            'class_system' => 'primary_jss_ss',
            'city' => 'Lagos',
            'state' => 'Lagos',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('school_requests', [
            'school_name' => 'Kingdom College',
            'email' => 'school@example.com',
            'status' => 'pending',
        ]);

        Mail::assertSent(SchoolRequestReceived::class, function (SchoolRequestReceived $mail): bool {
            return $mail->hasTo('school@example.com');
        });
    }

    public function test_admin_approval_sends_school_portal_access_link(): void
    {
        Mail::fake();
        config()->set('paystack.fees.school', 0);

        $admin = User::factory()->create([
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        $schoolRequest = SchoolRequest::create([
            'school_name' => 'Royal Academy',
            'contact_person' => 'Aisha Bello',
            'email' => 'royal@example.com',
            'phone' => '08000000002',
            'program_type' => 'school',
            'school_type' => 'private',
            'class_system' => 'primary_jss_ss',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.enrollments.approve', $schoolRequest));

        $response->assertRedirect(route('admin.enrollments.index'));

        $schoolRequest->refresh();
        $this->assertSame('approved', $schoolRequest->status);
        $this->assertNotNull($schoolRequest->school_id);
        $this->assertNotNull($schoolRequest->onboarding_token_hash);
        $this->assertNotNull($schoolRequest->portal_link_sent_at);

        Mail::assertSent(SchoolPortalAccessMail::class, function (SchoolPortalAccessMail $mail): bool {
            return $mail->hasTo('royal@example.com')
                && str_contains($mail->onboardingUrl, '/create-account/')
                && str_contains($mail->onboardingUrl, 'school.localhost');
        });
    }

    public function test_school_can_create_account_from_secure_link_and_reach_dashboard(): void
    {
        $school = School::create([
            'school_name' => 'Crown School',
            'school_type' => 'private',
            'class_system' => 'primary_jss_ss',
            'address_line' => '10 Chess Road',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'contact_person' => 'Ade Manager',
            'phone' => '08000000003',
            'email' => 'crown@example.com',
            'status' => 'active',
        ]);

        $schoolRequest = SchoolRequest::create([
            'school_name' => 'Crown School',
            'contact_person' => 'Ade Manager',
            'email' => 'crown@example.com',
            'phone' => '08000000003',
            'program_type' => 'school',
            'school_type' => 'private',
            'class_system' => 'primary_jss_ss',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'status' => 'approved',
            'school_id' => $school->id,
        ]);

        $onboardingUrl = app(SchoolOnboardingLinkService::class)->issue($schoolRequest);

        $response = $this->post($onboardingUrl, [
            'name' => 'Ade Manager',
            'username' => 'crown_admin',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
        ]);

        $response->assertRedirect('http://school.localhost/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'crown@example.com',
            'username' => 'crown_admin',
            'role' => 'school_admin',
            'school_id' => $school->id,
        ]);

        $schoolRequest->refresh();
        $this->assertNull($schoolRequest->onboarding_token_hash);
        $this->assertNotNull($schoolRequest->portal_onboarded_at);
    }
}
