<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\InstructorLessonPlan;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonPlanController extends Controller
{
    public function index()
    {
        $instructorId = auth()->id();

        $query = InstructorLessonPlan::where('instructor_id', $instructorId)
            ->with('classroom');

        if (request('class_id')) {
            $query->where('class_id', request('class_id'));
        }

        if (request('from')) {
            $query->whereDate('lesson_date', '>=', request('from'));
        }

        if (request('to')) {
            $query->whereDate('lesson_date', '<=', request('to'));
        }

        $plans = $query->orderBy('lesson_date', 'desc')->get();
        $classes = auth()->user()->teachingClasses()->orderBy('name')->get();

        return view('instructor.lesson-plans.index', compact('plans', 'classes'));
    }

    public function create()
    {
        $classes = auth()->user()->teachingClasses()->orderBy('name')->get();

        return view('instructor.lesson-plans.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'lesson_date' => 'nullable|date',
            'topic' => 'required|string|max:255',
            'scheme_reference' => 'nullable|string|max:255',
            'objectives' => 'nullable|string',
            'notes' => 'nullable|string',
            'materials_required' => 'nullable|string',
            'resource_text_content' => 'nullable|string',
            'resource_links_text' => 'nullable|string',
            'resource_files' => 'nullable|array|max:10',
            'resource_files.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,txt,mp4,mov,avi,mkv,webm,jpg,jpeg,png,gif|max:102400',
            'wippea_warm_up' => 'nullable|string',
            'wippea_introduction' => 'nullable|string',
            'wippea_presentation' => 'nullable|string',
            'wippea_practice' => 'nullable|string',
            'wippea_evaluation' => 'nullable|string',
            'wippea_application' => 'nullable|string',
            'status' => 'required|in:planned,completed',
        ]);

        $classroom = Classroom::findOrFail($request->class_id);
        if (!$classroom->instructors->contains(auth()->id())) {
            abort(403);
        }

        $resourceLinks = $this->extractResourceLinks($request->resource_links_text);
        $resourceFiles = $this->storeResourceFiles($request);

        InstructorLessonPlan::create([
            'instructor_id' => auth()->id(),
            'class_id' => $request->class_id,
            'lesson_date' => $request->lesson_date,
            'topic' => $request->topic,
            'scheme_reference' => $request->scheme_reference,
            'objectives' => $request->objectives,
            'notes' => $request->notes,
            'materials_required' => $request->materials_required,
            'resource_text_content' => $request->resource_text_content,
            'resource_links' => $resourceLinks,
            'resource_files' => $resourceFiles,
            'wippea_warm_up' => $request->wippea_warm_up,
            'wippea_introduction' => $request->wippea_introduction,
            'wippea_presentation' => $request->wippea_presentation,
            'wippea_practice' => $request->wippea_practice,
            'wippea_evaluation' => $request->wippea_evaluation,
            'wippea_application' => $request->wippea_application,
            'status' => $request->status,
            'review_status' => 'draft',
        ]);

        return redirect()->route('instructor.lesson-plans.index')
            ->with('success', 'Lesson plan created.');
    }

    public function edit(InstructorLessonPlan $lessonPlan)
    {
        if ($lessonPlan->instructor_id !== auth()->id()) {
            abort(403);
        }

        $classes = auth()->user()->teachingClasses()->orderBy('name')->get();

        return view('instructor.lesson-plans.edit', compact('lessonPlan', 'classes'));
    }

    public function update(Request $request, InstructorLessonPlan $lessonPlan)
    {
        if ($lessonPlan->instructor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'lesson_date' => 'nullable|date',
            'topic' => 'required|string|max:255',
            'scheme_reference' => 'nullable|string|max:255',
            'objectives' => 'nullable|string',
            'notes' => 'nullable|string',
            'materials_required' => 'nullable|string',
            'resource_text_content' => 'nullable|string',
            'resource_links_text' => 'nullable|string',
            'resource_files' => 'nullable|array|max:10',
            'resource_files.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,txt,mp4,mov,avi,mkv,webm,jpg,jpeg,png,gif|max:102400',
            'remove_resource_files' => 'nullable|array',
            'remove_resource_files.*' => 'string',
            'wippea_warm_up' => 'nullable|string',
            'wippea_introduction' => 'nullable|string',
            'wippea_presentation' => 'nullable|string',
            'wippea_practice' => 'nullable|string',
            'wippea_evaluation' => 'nullable|string',
            'wippea_application' => 'nullable|string',
            'status' => 'required|in:planned,completed',
        ]);

        $classroom = Classroom::findOrFail($request->class_id);
        if (!$classroom->instructors->contains(auth()->id())) {
            abort(403);
        }

        $existingFiles = collect($lessonPlan->resource_files ?? []);
        $removePaths = collect($request->remove_resource_files ?? [])->filter()->values();

        if ($removePaths->isNotEmpty()) {
            $existingFiles = $existingFiles->reject(function ($file) use ($removePaths) {
                $path = is_array($file) ? ($file['path'] ?? null) : $file;
                return $path && $removePaths->contains($path);
            })->values();

            foreach ($removePaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $newFiles = collect($this->storeResourceFiles($request));
        $mergedFiles = $existingFiles->concat($newFiles)->values()->all();

        $newReviewStatus = in_array($lessonPlan->review_status, ['submitted', 'approved'], true)
            ? 'draft'
            : $lessonPlan->review_status;

        $lessonPlan->update([
            'class_id' => $request->class_id,
            'lesson_date' => $request->lesson_date,
            'topic' => $request->topic,
            'scheme_reference' => $request->scheme_reference,
            'objectives' => $request->objectives,
            'notes' => $request->notes,
            'materials_required' => $request->materials_required,
            'resource_text_content' => $request->resource_text_content,
            'resource_links' => $this->extractResourceLinks($request->resource_links_text),
            'resource_files' => $mergedFiles,
            'wippea_warm_up' => $request->wippea_warm_up,
            'wippea_introduction' => $request->wippea_introduction,
            'wippea_presentation' => $request->wippea_presentation,
            'wippea_practice' => $request->wippea_practice,
            'wippea_evaluation' => $request->wippea_evaluation,
            'wippea_application' => $request->wippea_application,
            'status' => $request->status,
            'review_status' => $newReviewStatus,
            'submitted_at' => $newReviewStatus === 'draft' ? null : $lessonPlan->submitted_at,
            'reviewed_by' => $newReviewStatus === 'draft' ? null : $lessonPlan->reviewed_by,
            'reviewed_at' => $newReviewStatus === 'draft' ? null : $lessonPlan->reviewed_at,
        ]);

        return redirect()->route('instructor.lesson-plans.index')
            ->with('success', 'Lesson plan updated.');
    }

    public function destroy(InstructorLessonPlan $lessonPlan)
    {
        if ($lessonPlan->instructor_id !== auth()->id()) {
            abort(403);
        }

        foreach ($lessonPlan->resource_files ?? [] as $file) {
            $path = is_array($file) ? ($file['path'] ?? null) : $file;
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $lessonPlan->delete();

        return redirect()->route('instructor.lesson-plans.index')
            ->with('success', 'Lesson plan deleted.');
    }

    public function submit(InstructorLessonPlan $lessonPlan)
    {
        if ($lessonPlan->instructor_id !== auth()->id()) {
            abort(403);
        }

        $lessonPlan->update([
            'review_status' => 'submitted',
            'submitted_at' => now(),
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        return redirect()->route('instructor.lesson-plans.index')
            ->with('success', 'Lesson plan submitted for super admin review.');
    }

    private function extractResourceLinks(?string $linksText): array
    {
        if (!$linksText) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $linksText))
            ->map(fn ($line) => trim((string) $line))
            ->filter()
            ->values()
            ->all();
    }

    private function storeResourceFiles(Request $request): array
    {
        if (!$request->hasFile('resource_files')) {
            return [];
        }

        $stored = [];

        foreach ($request->file('resource_files', []) as $file) {
            $path = $file->store('lesson-plans/resources', 'public');

            $stored[] = [
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
                'size_kb' => (int) ceil($file->getSize() / 1024),
            ];
        }

        return $stored;
    }
}
