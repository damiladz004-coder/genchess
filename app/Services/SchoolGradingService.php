<?php

namespace App\Services;

use App\Models\GradeScale;
use App\Models\GradingComponent;

class SchoolGradingService
{
    public function calculateComponentPercentage($score, $max): float
    {
        if ($score === null || $max === null) {
            return 0.0;
        }

        $scoreValue = (float) $score;
        $maxValue = (float) $max;
        if ($maxValue <= 0) {
            return 0.0;
        }

        $percent = ($scoreValue / $maxValue) * 100;

        return round(max(0, min($percent, 100)), 2);
    }

    public function calculateWeightedTotal($components): float
    {
        $total = 0.0;
        foreach ($components as $component) {
            $weight = (float) ($component['weight'] ?? 0);
            $percentage = (float) ($component['percentage'] ?? 0);
            $total += ($percentage * $weight) / 100;
        }

        return round($total, 2);
    }

    public function assignLetterGrade($percentage): string
    {
        $value = (float) $percentage;
        $scale = GradeScale::where('min_percentage', '<=', $value)
            ->where('max_percentage', '>=', $value)
            ->orderBy('min_percentage', 'desc')
            ->first();

        return $scale?->letter_grade ?? 'F';
    }

    public function generateFeedback($componentPercentages): string
    {
        $feedback = [];
        foreach ($componentPercentages as $name => $value) {
            $label = ucfirst($name);
            if ($value >= 70) {
                $feedback[] = "{$label}: excellent performance.";
            } elseif ($value >= 50) {
                $feedback[] = "{$label}: fair performance, can improve.";
            } else {
                $feedback[] = "{$label}: needs significant improvement.";
            }
        }

        return implode(' ', $feedback);
    }

    public function defaultWeightsForSchool(?int $schoolId = null): array
    {
        $query = GradingComponent::query();
        if ($schoolId) {
            $schoolComponents = (clone $query)->where('school_id', $schoolId)->get();
            if ($schoolComponents->isNotEmpty()) {
                return $schoolComponents->keyBy('name')->map(fn ($component) => (float) $component->weight_percentage)->all();
            }
        }

        return GradingComponent::whereNull('school_id')
            ->get()
            ->keyBy('name')
            ->map(fn ($component) => (float) $component->weight_percentage)
            ->all();
    }
}
