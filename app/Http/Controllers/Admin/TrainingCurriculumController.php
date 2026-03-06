<?php

namespace App\Http\Controllers\Admin;

use App\Actions\RefreshTrainingEnrollmentStatus;
use App\Actions\UpdateCourseScoreAction;
use App\Http\Controllers\Controller;
use App\Models\LiveClass;
use App\Models\TrainingAssignmentSubmission;
use App\Models\TrainingCapstoneReview;
use App\Models\TrainingCourse;
use App\Models\TrainingEnrollmentTopicProgress;
use App\Models\TrainingEnrollment;
use App\Models\TeachingPractice;
use App\Models\TrainingModule;
use App\Models\TrainingTopic;
use App\Models\TrainingTopicAssignment;
use App\Models\TrainingTopicQuiz;
use App\Models\TrainingTopicQuizQuestion;
use App\Notifications\AssignmentDueNotification;
use App\Notifications\InstructorFeedbackNotification;
use App\Notifications\LiveClassScheduledNotification;
use App\Notifications\NewLessonReleasedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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

        $liveClasses = $course->liveClasses()->get();
        $teachingPractices = TeachingPractice::with(['user', 'reviewer'])
            ->where('course_id', $course->id)
            ->latest()
            ->take(50)
            ->get();

        return view('admin.training.curriculum', compact('course', 'pendingSubmissions', 'pendingCapstone', 'liveClasses', 'teachingPractices'));
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
            'lesson_video_url' => 'nullable|url|max:2000',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        $topic = TrainingTopic::create([
            'module_id' => $module->id,
            'topic_number' => $data['topic_number'] ?? null,
            'title' => $data['title'],
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'level' => $data['level'] ?? 'beginner',
            'lesson_video_url' => $data['lesson_video_url'] ?? null,
            'sort_order' => $data['sort_order'] ?? ($data['topic_number'] ?? 0),
        ]);

        $this->notifyEnrolledUsers(
            $module->course_id,
            new NewLessonReleasedNotification($topic->title, $module->course->title ?? 'Course')
        );

        return back()->with('success', 'Topic added.');
    }

    public function updateTopic(Request $request, TrainingTopic $topic)
    {
        $data = $request->validate([
            'topic_number' => 'nullable|integer|min:1|max:99',
            'title' => 'required|string|max:255',
            'duration_minutes' => 'nullable|integer|min:1|max:600',
            'level' => 'nullable|in:beginner,advanced',
            'lesson_video_url' => 'nullable|url|max:2000',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        $topic->update([
            'topic_number' => $data['topic_number'] ?? null,
            'title' => $data['title'],
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'level' => $data['level'] ?? 'beginner',
            'lesson_video_url' => $data['lesson_video_url'] ?? null,
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
            'due_at' => 'nullable|date',
            'required' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        TrainingTopicAssignment::create([
            'topic_id' => $topic->id,
            'title' => $data['title'],
            'type' => $data['type'],
            'instructions' => $data['instructions'] ?? null,
            'due_at' => $data['due_at'] ?? null,
            'required' => (bool) ($data['required'] ?? true),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        $this->notifyEnrolledUsers(
            $topic->module->course_id,
            new AssignmentDueNotification(
                $data['title'],
                $topic->module->course->title ?? 'Course',
                !empty($data['due_at']) ? \Illuminate\Support\Carbon::parse($data['due_at'])->format('F j, Y g:i A') : null
            )
        );

        return back()->with('success', 'Assignment added.');
    }

    public function updateAssignment(Request $request, TrainingTopicAssignment $assignment)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'instructions' => 'nullable|string',
            'due_at' => 'nullable|date',
            'required' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        $assignment->update([
            'title' => $data['title'],
            'type' => $data['type'],
            'instructions' => $data['instructions'] ?? null,
            'due_at' => $data['due_at'] ?? null,
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

        if (!empty($data['mentor_feedback'])) {
            $submission->enrollment->user?->notify(
                new InstructorFeedbackNotification('Assignment Review', $data['mentor_feedback'])
            );
        }

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

    public function storeLiveClass(Request $request, TrainingCourse $course)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'meeting_link' => 'required|url|max:2000',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $liveClass = LiveClass::create([
            'course_id' => $course->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'meeting_link' => $data['meeting_link'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'created_by' => auth()->id(),
        ]);

        $this->notifyEnrolledUsers(
            $course->id,
            new LiveClassScheduledNotification(
                $liveClass->title,
                $liveClass->start_time->format('F j, Y g:i A')
            )
        );

        return back()->with('success', 'Live class scheduled.');
    }

    public function destroyLiveClass(LiveClass $liveClass)
    {
        $liveClass->delete();

        return back()->with('success', 'Live class removed.');
    }

    public function reviewTeachingPractice(
        Request $request,
        TeachingPractice $practice,
        UpdateCourseScoreAction $updateCourseScoreAction
    ) {
        $data = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'instructor_feedback' => 'required|string|max:5000',
        ]);

        $practice->update([
            'score' => $data['score'],
            'instructor_feedback' => $data['instructor_feedback'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $enrollment = TrainingEnrollment::whereHas('cohort', fn ($q) => $q->where('course_id', $practice->course_id))
            ->where('user_id', $practice->user_id)
            ->latest('id')
            ->first();

        if ($enrollment) {
            $updateCourseScoreAction->execute($enrollment);
        }

        $practice->user?->notify(
            new InstructorFeedbackNotification('Teaching Practice Review', $data['instructor_feedback'])
        );

        return back()->with('success', 'Teaching practice reviewed.');
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

    private function notifyEnrolledUsers(int $courseId, object $notification): void
    {
        $enrollmentQuery = TrainingEnrollment::whereHas('cohort', function ($q) use ($courseId) {
            $q->where('course_id', $courseId);
        });

        if (Schema::hasColumn('training_enrollments', 'payment_status')) {
            $enrollmentQuery->where('payment_status', 'paid');
        }

        $users = \App\Models\User::whereIn('id', $enrollmentQuery->pluck('user_id')->unique())->get();

        foreach ($users as $user) {
            $user->notify($notification);
        }
    }
}
