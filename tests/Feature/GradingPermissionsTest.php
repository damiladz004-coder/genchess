<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GradingPermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_cannot_change_grading_weights(): void
    {
        $user = User::factory()->create([
            'role' => 'instructor',
        ]);

        $response = $this->actingAs($user)->patch(route('admin.grading.configuration.components.update'), [
            'components' => [
                ['id' => 1, 'weight_percentage' => 25],
                ['id' => 2, 'weight_percentage' => 25],
                ['id' => 3, 'weight_percentage' => 50],
            ],
        ]);

        $response->assertForbidden();
    }

    public function test_school_admin_cannot_open_instructor_result_edit_pages(): void
    {
        $user = User::factory()->create([
            'role' => 'school_admin',
        ]);

        $response = $this->actingAs($user)->get(route('instructor.results.create'));

        $response->assertForbidden();
    }
}
