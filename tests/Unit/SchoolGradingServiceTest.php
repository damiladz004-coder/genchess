<?php

namespace Tests\Unit;

use App\Services\SchoolGradingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolGradingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_weighted_total_accuracy(): void
    {
        $service = new SchoolGradingService();

        $total = $service->calculateWeightedTotal([
            ['percentage' => 80, 'weight' => 25],
            ['percentage' => 60, 'weight' => 25],
            ['percentage' => 70, 'weight' => 50],
        ]);

        $this->assertSame(70.0, $total);
    }

    public function test_grade_boundary_correctness(): void
    {
        $service = new SchoolGradingService();

        $this->assertSame('A', $service->assignLetterGrade(70));
        $this->assertSame('B', $service->assignLetterGrade(69.5));
        $this->assertSame('C', $service->assignLetterGrade(50));
        $this->assertSame('D', $service->assignLetterGrade(30));
        $this->assertSame('F', $service->assignLetterGrade(29.9));
    }
}
