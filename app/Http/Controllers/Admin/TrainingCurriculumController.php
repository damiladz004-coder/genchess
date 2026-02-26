<?php

namespace App\Http\Controllers\Admin;

use App\Actions\RefreshTrainingEnrollmentStatus;
use App\Http\Controllers\Controller;
use App\Models\TrainingAssignmentSubmission;
use App\Models\TrainingCapstoneReview;
use App\Models\TrainingCourse;
use App\Models\TrainingEnrollmentTopicProgress;
use App\Models\TrainingModule;
use App\Models\TrainingTopic;
use App\Models\TrainingTopicAssignment;
use App\Models\TrainingTopicQuiz;
use App\Models\TrainingTopicQuizQuestion;
use Illuminate\Http\Request;

class TrainingCurriculumController extends Controller
{
    public function showCourse(TrainingCourse $course)
    {
        $course->load([
            'modules.topics.quiz.questions',
            'modules.topics.assignments',
        ]);

        $pendingSubmissions = TrainingAssignmentSubmission::with(['enrollment.user', 'topic', 'assignment'])
            ->whereIn('topic_id', TrainingTopic::whereIn('module_id', $course->modules()->pluck('id'))->pluck('id'))
            ->where('status', 'submitted')
            ->latest()
            ->take(30)
            ->get();

        $pendingCapstone = TrainingCapstoneReview::with(['enrollment.user'])
            ->whereHas('enrollment.cohort', fn ($q) => $q->where('course_id', $course->id))
            ->whereIn('status', ['pending', 'reviewed', 'resubmission_required'])
            ->latest()
            ->take(30)
            ->get();

        return view('admin.training.curriculum', compact('course', 'pendingSubmissions', 'pendingCapstone'));
    }

    public function storeModule(Request $request, TrainingCourse $course)
    {
        $data = $request->validate([
            'module_number' => 'required|integer|min:1|max:99',
            'title' => 'required|string|max:255',
            'goal' => 'nullable|string',
            'is_capstone' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        TrainingModule::updateOrCreate(
            ['course_id' => $course->id, 'module_number' => $data['module_number']],
            [
                'title' => $data['title'],
                'goal' => $data['goal'] ?? null,
                'is_capstone' => (bool) ($data['is_capstone'] ?? false),
                'sort_order' => $data['sort_order'] ?? $data['module_number'],
            ]
        );

        return back()->with('success', 'Module saved.');
    }

    public function updateModule(Request $request, TrainingModule $module)
    {
        $data = $request->validate([
            'module_number' => 'required|integer|min:1|max:99',
            'title' => 'required|string|max:255',
            'goal' => 'nullable|string',
            'is_capstone' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        $module->update([
            'module_number' => $data['module_number'],
            'title' => $data['title'],
            'goal' => $data['goal'] ?? null,
            'is_capstone' => (bool) ($data['is_capstone'] ?? false),
            'sort_order' => $data['sort_order'] ?? $data['module_number'],
        ]);

        return back()->with('success', 'Module updated.');
    }

    public function destroyModule(TrainingModule $module)
    {
        $module->delete();

        return back()->with('success', 'Module deleted.');
    }

    public function storeTopic(Request $request, TrainingModule $module)
    {
        $data = $request->validate([
            'topic_number' => 'nullable|integer|min:1|max:99',
            'title' => 'required|string|max:255',
            'duration_minutes' => 'nullable|integer|min:1|max:600',
            'level' => 'nullable|in:beginner,advanced',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        TrainingTopic::create([
            'module_id' => $module->id,
            'topic_number' => $data['topic_number'] ?? null,
            'title' => $data['title'],
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'level' => $data['level'] ?? 'beginner',
            'sort_order' => $data['sort_order'] ?? ($data['topic_number'] ?? 0),
        ]);

        return back()->with('success', 'Topic added.');
    }

    public function updateTopic(Request $request, TrainingTopic $topic)
    {
        $data = $request->validate([
            'topic_number' => 'nullable|integer|min:1|max:99',
            'title' => 'required|string|max:255',
            'duration_minutes' => 'nullable|integer|min:1|max:600',
            'level' => 'nullable|in:beginner,advanced',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        $topic->update([
            'topic_number' => $data['topic_number'] ?? null,
            'title' => $data['title'],
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'level' => $data['level'] ?? 'beginner',
            'sort_order' => $data['sort_order'] ?? ($data['topic_number'] ?? 0),
        ]);

        return back()->with('success', 'Topic updated.');
    }

    public function destroyTopic(TrainingTopic $topic)
    {
        $topic->delete();

        return back()->with('success', 'Topic deleted.');
    }

    public function upsertQuiz(Request $request, TrainingTopic $topic)
    {
        $data = $request->validate([
            'mcq_count' => 'required|integer|min:5|max:10',
            'true_false_count' => 'required|integer|min:2|max:2',
            'scenario_count' => 'required|integer|min:1|max:1',
            'pass_mark' => 'required|numeric|min:1|max:100',
        ]);

        TrainingTopicQuiz::updateOrCreate(
            ['topic_id' => $topic->id],
            $data
        );

        return back()->with('success', 'Quiz settings updated.');
    }

    public function storeQuizQuestion(Request $request, TrainingTopicQuiz $quiz)
    {
        $data = $request->validate([
            'type' => 'required|in:mcq,true_false,scenario',
            'question' => 'required|string',
            'options_text' => 'nullable|string',
            'correct_answer' => 'required|string|max:255',
            'explanation' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        TrainingTopicQuizQuestion::create([
            'quiz_id' => $quiz->id,
            'type' => $data['type'],
            'question' => $data['question'],
            'options' => $this->normalizeOptions($data['type'], $data['options_text'] ?? null),
            'correct_answer' => trim($data['correct_answer']),
            'explanation' => $data['explanation'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return back()->with('success', 'Quiz question added.');
    }

    public function updateQuizQuestion(Request $request, TrainingTopicQuizQuestion $question)
    {
        $data = $request->validate([
            'type' => 'required|in:mcq,true_false,scenario',
            'question' => 'required|string',
            'options_text' => 'nullable|string',
            'correct_answer' => 'required|string|max:255',
            'explanation' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        $question->update([
            'type' => $data['type'],
            'question' => $data['question'],
            'options' => $this->normalizeOptions($data['type'], $data['options_text'] ?? null),
            'correct_answer' => trim($data['correct_answer']),
            'explanation' => $data['explanation'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return back()->with('success', 'Quiz question updated.');
    }

    public function destroyQuizQuestion(TrainingTopicQuizQuestion $question)
    {
        $question->delete();

        return back()->with('success', 'Quiz question deleted.');
    }

    public function storeAssignment(Request $request, TrainingTopic $topic)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'instructions' => 'nullable|string',
            'required' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        TrainingTopicAssignment::create([
            'topic_id' => $topic->id,
            'title' => $data['title'],
            'type' => $data['type'],
            'instructions' => $data['instructions'] ?? null,
            'required' => (bool) ($data['required'] ?? true),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return back()->with('success', 'Assignment added.');
    }

    public function updateAssignment(Request $request, TrainingTopicAssignment $assignment)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'instructions' => 'nullable|string',
            'required' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        $assignment->update([
            'title' => $data['title'],
            'type' => $data['type'],
            'instructions' => $data['instructions'] ?? null,
            'required' => (bool) ($data['required'] ?? true),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return back()->with('success', 'Assignment updated.');
    }

    public function destroyAssignment(TrainingTopicAssignment $assignment)
    {
        $assignment->delete();

        return back()->with('success', 'Assignment deleted.');
    }

    public function reviewSubmission(
        Request $request,
        TrainingAssignmentSubmission $submission,
        RefreshTrainingEnrollmentStatus $refreshEnrollmentStatus
    ) {
        $data = $request->validate([
            'status' => 'required|in:needs_revision,approved,rejected',
            'mentor_feedback' => 'nullable|string',
        ]);

        $submission->update([
            'status' => $data['status'],
            'mentor_feedback' => $data['mentor_feedback'] ?? null,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        $progress = TrainingEnrollmentTopicProgress::firstOrCreate([
            'enrollment_id' => $submission->enrollment_id,
            'topic_id' => $submission->topic_id,
        ]);

        $progress->update([
            'assignment_status' => $data['status'] === 'approved' ? 'approved' : ($data['status'] === 'needs_revision' ? 'needs_revision' : 'submitted'),
            'assignment_reviewed_at' => now(),
            'mentor_approved' => $data['status'] === 'approved',
            'completed_at' => ($progress->quiz_passed && $data['status'] === 'approved') ? now() : null,
        ]);

        $refreshEnrollmentStatus->execute($submission->enrollment);

        return back()->with('success', 'Submission reviewed.');
    }

    public function reviewCapstone(
        Request $request,
        TrainingCapstoneReview $capstoneReview,
        RefreshTrainingEnrollmentStatus $refreshEnrollmentStatus
    ) {
        $data = $request->validate([
            'status' => 'required|in:reviewed,resubmission_required,approved',
            'mentor_feedback' => 'nullable|string',
        ]);

        $capstoneReview->update([
            'status' => $data['status'],
            'mentor_feedback' => $data['mentor_feedback'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'approved_at' => $data['status'] === 'approved' ? now() : null,
        ]);

        $refreshEnrollmentStatus->execute($capstoneReview->enrollment);

        return back()->with('success', 'Capstone review updated.');
    }

    private function normalizeOptions(string $type, ?string $optionsText): ?array
    {
        if ($type === 'true_false') {
            return ['True', 'False'];
        }

        if ($type === 'scenario') {
            return null;
        }

        $options = collect(preg_split('/\r\n|\r|\n/', (string) $optionsText))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();

        return empty($options) ? null : $options;
    }
}
