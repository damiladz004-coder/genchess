<?php

namespace App\Services;

use App\Models\School;
use App\Models\Classroom;

class ClassGenerator
{
    private static function getSystems(): array
    {
        return [
            'primary_jss_ss' => [
                'Primary 1','Primary 2','Primary 3',
                'Primary 4','Primary 5','Primary 6',
                'JSS 1','JSS 2','JSS 3',
            ],

            'grade_1_12' => array_map(fn($i) => "Grade $i", range(1, 12)),

            'year_1_12' => array_map(fn($i) => "Year $i", range(1, 12)),
        ];
    }

    public static function generateForSchool(School $school): void
    {
        $system = $school->class_system;

        if ($system === 'primary_jss_ss') {
            self::primaryJssSs($school);
        }

        if ($system === 'grade_1_12') {
            self::gradeSystem($school);
        }

        if ($system === 'year_1_12') {
            self::yearSystem($school);
        }
    }

    private static function primaryJssSs(School $school): void
    {
        // Primary 1–6 (Subject)
        for ($i = 1; $i <= 6; $i++) {
            $school->classes()->create([
                'name' => "Primary $i",
                'level' => 'primary',
                'chess_mode' => 'subject',
                'status' => 'active',
            ]);
        }

        // JSS 1–3 (Subject)
        for ($i = 1; $i <= 3; $i++) {
            $school->classes()->create([
                'name' => "JSS $i",
                'level' => 'jss',
                'chess_mode' => 'subject',
                'status' => 'active',
            ]);
        }

        // SS 1–3 (Club)
        for ($i = 1; $i <= 3; $i++) {
            $school->classes()->create([
                'name' => "SS $i",
                'level' => 'ss',
                'chess_mode' => 'club',
                'status' => 'active',
            ]);
        }
    }

    private static function gradeSystem(School $school): void
    {
        for ($i = 1; $i <= 12; $i++) {
            $level = $i <= 6 ? 'primary' : ($i <= 9 ? 'jss' : 'ss');
            $mode  = $level === 'ss' ? 'club' : 'subject';

            $school->classes()->create([
                'name' => "Grade $i",
                'level' => $level,
                'chess_mode' => $mode,
                'status' => 'active',
            ]);
        }
    }

    private static function yearSystem(School $school): void
    {
        for ($i = 1; $i <= 12; $i++) {
            $level = $i <= 6 ? 'primary' : ($i <= 9 ? 'jss' : 'ss');
            $mode  = $level === 'ss' ? 'club' : 'subject';

            $school->classes()->create([
                'name' => "Year $i",
                'level' => $level,
                'chess_mode' => $mode,
                'status' => 'active',
            ]);
        }
    }
}
