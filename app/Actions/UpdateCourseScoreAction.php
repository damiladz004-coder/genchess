<?php

namespace App\Actions;

use App\Models\CourseScore;
use App\Models\TeachingPractice;
use App\Models\TrainingEnrollment;
use App\Models\TrainingTopicQuizAttempt;

class UpdateCourseScoreAction
{
    public function execute(TrainingEnrollment $enrollment): void
    {
        $courseId = (int) $enrollment->cohort->course_id;
        $userId = (int) $enrollment->user_id;

        $quizAverage = (float) TrainingTopicQuizAttempt::where('enrollment_id', $enrollment->id)->avg('score');
        $practiceAverage = (float) TeachingPractice::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->whereNotNull('score')
            ->avg('score');

        if ($practiceAverage > 0) {
            $totalScore = round(($quizAverage * 0.7) + ($practiceAverage * 0.3), 2);
        } else {
            $totalScore = round($quizAverage, 2);
        }

        CourseScore::updateOrCreate(
            [
                'course_id' => $courseId,
                'user_id' => $userId,
            ],
            [
                'total_score' => max(0, min(100, $totalScore)),
            ]
        );
    }
}

