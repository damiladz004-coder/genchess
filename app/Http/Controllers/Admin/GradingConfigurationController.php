<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeScale;
use App\Models\GradingComponent;
use App\Models\School;
use Illuminate\Http\Request;

class GradingConfigurationController extends Controller
{
    public function index(Request $request)
    {
        $components = GradingComponent::whereNull('school_id')->orderBy('id')->get();
        $scales = GradeScale::orderByDesc('min_percentage')->get();
        $schools = School::orderBy('school_name')->get(['id', 'school_name']);
        $selectedSchoolId = $request->filled('school_id') ? (int) $request->school_id : null;
        $selectedSchool = $selectedSchoolId ? $schools->firstWhere('id', $selectedSchoolId) : null;

        $schoolComponentRows = collect();
        if ($selectedSchoolId) {
            $overrides = GradingComponent::where('school_id', $selectedSchoolId)
                ->get()
                ->keyBy('name');

            $schoolComponentRows = $components->map(function ($component) use ($overrides) {
                $override = $overrides->get($component->name);
                return [
                    'name' => $component->name,
                    'weight_percentage' => $override?->weight_percentage ?? $component->weight_percentage,
                    'is_override' => (bool) $override,
                ];
            });
        }

        return view(
            'admin.grading.configuration',
            compact('components', 'scales', 'schools', 'selectedSchool', 'selectedSchoolId', 'schoolComponentRows')
        );
    }

    public function updateComponents(Request $request)
    {
        $request->validate([
            'components' => ['required', 'array', 'min:1'],
            'components.*.name' => ['required', 'string', 'in:test,practical,exam'],
            'components.*.weight_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'school_id' => ['nullable', 'integer', 'exists:schools,id'],
        ]);

        $total = collect($request->components)->sum(fn ($component) => (float) $component['weight_percentage']);
        if (round($total, 2) !== 100.00) {
            return redirect()->back()->with('error', 'Grading component weights must equal 100%.');
        }

        $schoolId = $request->filled('school_id') ? (int) $request->school_id : null;
        if ($schoolId) {
            foreach ($request->components as $componentData) {
                GradingComponent::updateOrCreate(
                    [
                        'name' => $componentData['name'],
                        'school_id' => $schoolId,
                    ],
                    [
                        'weight_percentage' => $componentData['weight_percentage'],
                        'created_by' => auth()->id(),
                    ]
                );
            }

            return redirect()->route('admin.grading.configuration.index', ['school_id' => $schoolId])
                ->with('success', 'School grading override updated.');
        }

        foreach ($request->components as $componentData) {
            GradingComponent::where('name', $componentData['name'])
                ->whereNull('school_id')
                ->update([
                    'weight_percentage' => $componentData['weight_percentage'],
                    'created_by' => auth()->id(),
                ]);
        }

        return redirect()->route('admin.grading.configuration.index')->with('success', 'Global grading weights updated.');
    }

    public function resetSchoolComponents(Request $request)
    {
        $request->validate([
            'school_id' => ['required', 'integer', 'exists:schools,id'],
        ]);

        GradingComponent::where('school_id', $request->school_id)->delete();

        return redirect()->route('admin.grading.configuration.index', ['school_id' => $request->school_id])
            ->with('success', 'School override reset. Global defaults now apply.');
    }

    public function updateScales(Request $request)
    {
        $request->validate([
            'scales' => ['required', 'array', 'min:1'],
            'scales.*.id' => ['required', 'exists:grade_scales,id'],
            'scales.*.min_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'scales.*.max_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'scales.*.letter_grade' => ['required', 'string', 'max:5'],
        ]);

        foreach ($request->scales as $scaleData) {
            if ((float) $scaleData['min_percentage'] > (float) $scaleData['max_percentage']) {
                return redirect()->back()->with('error', 'Each grade scale min value must be less than or equal to max value.');
            }
        }

        $sortedScales = collect($request->scales)
            ->map(function ($scale) {
                return [
                    'id' => (int) $scale['id'],
                    'min' => (float) $scale['min_percentage'],
                    'max' => (float) $scale['max_percentage'],
                    'grade' => strtoupper((string) $scale['letter_grade']),
                ];
            })
            ->sortBy('min')
            ->values();

        if ($sortedScales->first()['min'] !== 0.0) {
            return redirect()->back()->with('error', 'Grade scale must start at 0%.');
        }

        if ($sortedScales->last()['max'] !== 100.0) {
            return redirect()->back()->with('error', 'Grade scale must end at 100%.');
        }

        $duplicateGrades = $sortedScales->pluck('grade')
            ->duplicates()
            ->unique()
            ->values();
        if ($duplicateGrades->isNotEmpty()) {
            return redirect()->back()->with('error', 'Each letter grade must be unique. Duplicate: ' . $duplicateGrades->join(', '));
        }

        for ($i = 1; $i < $sortedScales->count(); $i++) {
            $previous = $sortedScales[$i - 1];
            $current = $sortedScales[$i];

            if ($current['min'] <= $previous['max']) {
                return redirect()->back()->with(
                    'error',
                    "Grade scale overlap detected between {$previous['grade']} ({$previous['min']}-{$previous['max']}) and {$current['grade']} ({$current['min']}-{$current['max']})."
                );
            }

            $gap = round($current['min'] - $previous['max'], 2);
            if ($gap > 0.01) {
                return redirect()->back()->with(
                    'error',
                    "Grade scale gap detected between {$previous['grade']} ({$previous['max']}) and {$current['grade']} ({$current['min']})."
                );
            }
        }

        foreach ($request->scales as $scaleData) {
            GradeScale::where('id', $scaleData['id'])->update([
                'min_percentage' => $scaleData['min_percentage'],
                'max_percentage' => $scaleData['max_percentage'],
                'letter_grade' => strtoupper($scaleData['letter_grade']),
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->back()->with('success', 'Grade scale updated.');
    }
}
