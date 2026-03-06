<?php

namespace App\Actions;

use App\Models\Certification;
use App\Models\TrainingEnrollment;
use App\Models\TrainingTopic;
use Illuminate\Support\Str;

class RefreshTrainingEnrollmentStatus
{
    public function execute(TrainingEnrollment $enrollment): void
    {
        $courseId = $enrollment->cohort->course_id;
        $courseTopicQuery = TrainingTopic::whereHas('module', fn ($q) => $q->where('course_id', $courseId));

        $quizTopicIds = (clone $courseTopicQuery)->whereHas('quiz')->pluck('id');
        $assignmentTopicIds = (clone $courseTopicQuery)->whereHas('assignments', fn ($q) => $q->where('required', true))->pluck('id');

        $progressByTopic = $enrollment->topicProgress()->get()->keyBy('topic_id');

        $allQuizzesPassed = $quizTopicIds->isNotEmpty()
            ? $quizTopicIds->every(fn ($topicId) => optional($progressByTopic->get($topicId))->quiz_passed === true)
            : false;

        $allAssignmentsApproved = $assignmentTopicIds->isNotEmpty()
            ? $assignmentTopicIds->every(fn ($topicId) => optional($progressByTopic->get($topicId))->assignment_status === 'approved')
            : false;

        $capstoneApproved = $enrollment->capstoneReview?->status === 'approved';
        $mentorApproved = $assignmentTopicIds->isNotEmpty()
            ? $assignmentTopicIds->every(fn ($topicId) => optional($progressByTopic->get($topicId))->mentor_approved === true)
            : false;

        $nextStatus = ($allQuizzesPassed && $allAssignmentsApproved && $capstoneApproved && $mentorApproved)
            ? 'completed'
            : ($enrollment->status === 'dropped' ? 'dropped' : 'enrolled');

        $enrollment->forceFill([
            'quizzes_completed' => $allQuizzesPassed,
            'assignments_completed' => $allAssignmentsApproved,
            'teaching_practice_completed' => $capstoneApproved,
            'mentor_approved' => $mentorApproved,
            'status' => $nextStatus,
            'completed_at' => $nextStatus === 'completed' ? now() : null,
        ])->save();

        if ($enrollment->isEligibleForCertification() && !$enrollment->certification) {
            Certification::create([
                'enrollment_id' => $enrollment->id,
                'certificate_code' => $this->generateUniqueCertificateCode(),
                'issued_at' => now(),
            ]);
        }
    }

    protected function generateUniqueCertificateCode(): string
    {
        do {
            $code = strtoupper(Str::random(10));
        } while (Certification::where('certificate_code', $code)->exists());

        return $code;
    }
}
