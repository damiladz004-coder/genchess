<?php

namespace App\Http\Controllers\Instructor;

use App\Actions\RefreshTrainingEnrollmentStatus;
use App\Actions\UpdateCourseScoreAction;
use App\Http\Controllers\Controller;
use App\Models\CourseDiscussion;
use App\Models\CourseScore;
use App\Models\LiveClass;
use App\Models\TeachingPractice;
use App\Models\TrainingAssignmentSubmission;
use App\Models\TrainingCapstoneReview;
use App\Models\TrainingEnrollment;
use App\Models\TrainingEnrollmentTopicProgress;
use App\Models\TrainingTopic;
use App\Models\TrainingTopicQuizAttempt;
use Illuminate\Http\Request;

class TrainingProgressController extends Controller
{
    public function show(TrainingEnrollment $enrollment)
    {
        abort_unless($enrollment->user_id === auth()->id(), 403);
        abort_unless($enrollment->isPaid(), 403, 'Payment required.');

        $enrollment->load([
            'cohort.course.modules.topics.quiz',
            'cohort.course.modules.topics.assignments',
            'topicProgress',
            'assignmentSubmissions.assignment',
            'capstoneReview',
        ]);

        $progressByTopic = $enrollment->topicProgress->keyBy('topic_id');
        $submissionsByTopic = $enrollment->assignmentSubmissions
            ->groupBy('topic_id');

        $courseId = (int) $enrollment->cohort->course_id;
        $upcomingLiveClasses = LiveClass::where('course_id', $courseId)
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->take(10)
            ->get();
        $courseDiscussions = CourseDiscussion::with(['user', 'replies.user'])
            ->where('course_id', $courseId)
            ->whereNull('parent_id')
            ->latest()
            ->take(50)
            ->get();
        $teachingPractices = TeachingPractice::with('reviewer')
            ->where('course_id', $courseId)
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        $leaderboard = CourseScore::with('user')
            ->where('course_id', $courseId)
            ->orderByDesc('total_score')
            ->take(10)
            ->get();
        $notifications = auth()->user()->notifications()->latest()->take(10)->get();

        return view('instructor.training.enrollment', compact(
            'enrollment',
            'progressByTopic',
            'submissionsByTopic',
            'upcomingLiveClasses',
            'courseDiscussions',
            'teachingPractices',
            'leaderboard',
            'notifications'
        ));
    }

    public function submitTopic(
        Request $request,
        TrainingEnrollment $enrollment,
        TrainingTopic $topic,
        RefreshTrainingEnrollmentStatus $refreshEnrollmentStatus
    ) {
        abort_unless($enrollment->user_id === auth()->id(), 403);
        abort_unless($enrollment->isPaid(), 403, 'Payment required.');
        abort_unless($topic->module->course_id === $enrollment->cohort->course_id, 422, 'Topic does not belong to this enrollment course.');

        $data = $request->validate([
            'submission_text' => 'nullable|string',
            'submission_url' => 'nullable|url|max:2048',
        ]);

        if (($data['submission_text'] ?? null) === null && ($data['submission_url'] ?? null) === null) {
            return back()->withErrors(['submission' => 'Provide assignment text or a submission URL.']);
        }

        $progress = TrainingEnrollmentTopicProgress::firstOrCreate([
            'enrollment_id' => $enrollment->id,
            'topic_id' => $topic->id,
        ]);

        $progress->update([
            'assignment_status' => 'submitted',
            'assignment_submitted_at' => now(),
            'mentor_approved' => false,
            'completed_at' => null,
        ]);

        $assignment = $topic->assignments()->orderBy('sort_order')->first();
        if ($assignment) {
            TrainingAssignmentSubmission::create([
                'enrollment_id' => $enrollment->id,
                'topic_id' => $topic->id,
                'assignment_id' => $assignment->id,
                'submission_text' => $data['submission_text'] ?? null,
                'submission_url' => $data['submission_url'] ?? null,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);
        }

        $refreshEnrollmentStatus->execute($enrollment);

        return back()->with('success', 'Topic submission sent for mentor review.');
    }

    public function showQuiz(TrainingEnrollment $enrollment, TrainingTopic $topic)
    {
        abort_unless($enrollment->user_id === auth()->id(), 403);
        abort_unless($enrollment->isPaid(), 403, 'Payment required.');
        abort_unless($topic->module->course_id === $enrollment->cohort->course_id, 422, 'Topic does not belong to this enrollment course.');

        $topic->load('quiz.questions');
        abort_unless($topic->quiz, 404);

        $attempts = TrainingTopicQuizAttempt::where('enrollment_id', $enrollment->id)
            ->where('topic_id', $topic->id)
            ->latest('submitted_at')
            ->take(5)
            ->get();

        return view('instructor.training.quiz', compact('enrollment', 'topic', 'attempts'));
    }

    public function submitQuiz(
        Request $request,
        TrainingEnrollment $enrollment,
        TrainingTopic $topic,
        RefreshTrainingEnrollmentStatus $refreshEnrollmentStatus,
        UpdateCourseScoreAction $updateCourseScoreAction
    ) {
        abort_unless($enrollment->user_id === auth()->id(), 403);
        abort_unless($enrollment->isPaid(), 403, 'Payment required.');
        abort_unless($topic->module->course_id === $enrollment->cohort->course_id, 422, 'Topic does not belong to this enrollment course.');

        $topic->load('quiz.questions');
        abort_unless($topic->quiz, 404);

        $answers = $request->input('answers', []);
        $total = $topic->quiz->questions->count();

        if ($total < 1) {
            return back()->withErrors(['quiz' => 'This quiz has no questions yet.']);
        }

        $correct = 0;
        $normalizedAnswers = [];

        foreach ($topic->quiz->questions as $question) {
            $raw = $answers[$question->id] ?? null;
            $given = is_string($raw) ? trim($raw) : '';
            $expected = trim((string) $question->correct_answer);
            $isCorrect = mb_strtolower($given) === mb_strtolower($expected);

            if ($isCorrect) {
                $correct++;
            }

            $normalizedAnswers[(string) $question->id] = [
                'answer' => $given,
                'correct' => $expected,
                'is_correct' => $isCorrect,
            ];
        }

        $score = round(($correct / $total) * 100, 2);
        $passed = $score >= (float) $topic->quiz->pass_mark;

        TrainingTopicQuizAttempt::create([
            'enrollment_id' => $enrollment->id,
            'topic_id' => $topic->id,
            'quiz_id' => $topic->quiz->id,
            'answers_json' => $normalizedAnswers,
            'total_questions' => $total,
            'correct_answers' => $correct,
            'score' => $score,
            'passed' => $passed,
            'submitted_at' => now(),
        ]);

        $progress = TrainingEnrollmentTopicProgress::firstOrCreate([
            'enrollment_id' => $enrollment->id,
            'topic_id' => $topic->id,
        ]);

        $progress->update([
            'quiz_score' => $score,
            'quiz_passed' => $passed,
            'quiz_attempts' => $progress->quiz_attempts + 1,
            'completed_at' => ($passed && $progress->assignment_status === 'approved') ? now() : null,
        ]);

        $refreshEnrollmentStatus->execute($enrollment);
        $updateCourseScoreAction->execute($enrollment);

        return redirect()
            ->route('instructor.training.topics.quiz.show', [$enrollment, $topic])
            ->with('success', $passed ? "Quiz passed ({$score}%)." : "Quiz submitted ({$score}%). Minimum pass mark is {$topic->quiz->pass_mark}%.");
    }

    public function submitCapstone(
        Request $request,
        TrainingEnrollment $enrollment,
        RefreshTrainingEnrollmentStatus $refreshEnrollmentStatus
    ) {
        abort_unless($enrollment->user_id === auth()->id(), 403);
        abort_unless($enrollment->isPaid(), 403, 'Payment required.');

        $data = $request->validate([
            'video_url' => 'required|url|max:2048',
        ]);

        TrainingCapstoneReview::updateOrCreate(
            ['enrollment_id' => $enrollment->id],
            [
                'video_url' => $data['video_url'],
                'status' => 'pending',
                'mentor_feedback' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'approved_at' => null,
            ]
        );

        $refreshEnrollmentStatus->execute($enrollment);

        return back()->with('success', 'Capstone submitted for mentor review.');
    }

    public function postDiscussion(Request $request, TrainingEnrollment $enrollment)
    {
        abort_unless($enrollment->user_id === auth()->id(), 403);
        abort_unless($enrollment->isPaid(), 403, 'Payment required.');

        $data = $request->validate([
            'message' => 'required|string|max:5000',
            'parent_id' => 'nullable|exists:course_discussions,id',
            'topic_id' => 'nullable|exists:training_topics,id',
        ]);

        $courseId = (int) $enrollment->cohort->course_id;
        $parentId = $data['parent_id'] ?? null;
        if ($parentId) {
            $parent = CourseDiscussion::findOrFail($parentId);
            abort_unless((int) $parent->course_id === $courseId, 422, 'Invalid discussion parent.');
        }

        $message = trim($data['message']);
        if (!empty($data['topic_id'])) {
            $topic = TrainingTopic::findOrFail($data['topic_id']);
            abort_unless((int) $topic->module->course_id === $courseId, 422, 'Invalid topic for this course.');
            $message = "[Lesson: {$topic->title}] {$message}";
        }

        CourseDiscussion::create([
            'course_id' => $courseId,
            'user_id' => auth()->id(),
            'message' => $message,
            'parent_id' => $parentId,
        ]);

        return back()->with('success', 'Discussion posted.');
    }

    public function submitTeachingPractice(
        Request $request,
        TrainingEnrollment $enrollment,
        UpdateCourseScoreAction $updateCourseScoreAction
    ) {
        abort_unless($enrollment->user_id === auth()->id(), 403);
        abort_unless($enrollment->isPaid(), 403, 'Payment required.');

        $data = $request->validate([
            'lesson_topic' => 'required|string|max:255',
            'video_url' => 'required|url|max:2000',
            'description' => 'nullable|string|max:5000',
        ]);

        TeachingPractice::create([
            'user_id' => auth()->id(),
            'course_id' => $enrollment->cohort->course_id,
            'lesson_topic' => $data['lesson_topic'],
            'video_url' => $data['video_url'],
            'description' => $data['description'] ?? null,
        ]);

        $updateCourseScoreAction->execute($enrollment);

        return back()->with('success', 'Teaching practice submitted for instructor review.');
    }
}
