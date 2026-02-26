<?php

namespace App\Actions;

use App\Models\TrainingTopic;
use App\Models\TrainingTopicQuizQuestion;

class SeedTrainingQuizQuestions
{
    public function execute(?int $courseId = null, bool $force = false): array
    {
        $topicQuery = TrainingTopic::query()
            ->with('quiz.questions')
            ->whereHas('quiz');

        if ($courseId) {
            $topicQuery->whereHas('module', fn ($q) => $q->where('course_id', $courseId));
        }

        $topics = $topicQuery->get();

        $quizCount = 0;
        $createdQuestions = 0;
        $skippedQuizzes = 0;

        foreach ($topics as $topic) {
            $quiz = $topic->quiz;
            if (!$quiz) {
                continue;
            }

            if (!$force && $quiz->questions->isNotEmpty()) {
                $skippedQuizzes++;
                continue;
            }

            if ($force && $quiz->questions->isNotEmpty()) {
                $quiz->questions()->delete();
            }

            $focus = collect($topic->quiz_focus ?? [])
                ->filter(fn ($item) => is_string($item) && trim($item) !== '')
                ->map(fn ($item) => trim($item))
                ->values()
                ->all();

            if (empty($focus)) {
                $focus = [$topic->title];
            }

            $sort = 1;

            $mcqTarget = max(5, min(10, (int) ($quiz->mcq_count ?? 5)));
            for ($i = 0; $i < $mcqTarget; $i++) {
                $f = $focus[$i % count($focus)];
                $q = TrainingTopicQuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'type' => 'mcq',
                    'question' => "Which option best matches this concept: {$f}?",
                    'options' => [
                        "Core principle of {$f}",
                        "Unrelated concept to {$f}",
                        "Advanced concept not required for {$f}",
                        "Incorrect definition of {$f}",
                    ],
                    'correct_answer' => "Core principle of {$f}",
                    'explanation' => "The best answer reflects the core principle for '{$f}'.",
                    'sort_order' => $sort++,
                ]);
                $createdQuestions += $q ? 1 : 0;
            }

            $tfTarget = 2;
            for ($i = 0; $i < $tfTarget; $i++) {
                $f = $focus[$i % count($focus)];
                $q = TrainingTopicQuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'type' => 'true_false',
                    'question' => "True or False: '{$f}' is part of this topic.",
                    'options' => ['True', 'False'],
                    'correct_answer' => 'True',
                    'explanation' => "The concept '{$f}' appears in this topic's quiz focus.",
                    'sort_order' => $sort++,
                ]);
                $createdQuestions += $q ? 1 : 0;
            }

            $scenarioTarget = 1;
            for ($i = 0; $i < $scenarioTarget; $i++) {
                $f = $focus[$i % count($focus)];
                $q = TrainingTopicQuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'type' => 'scenario',
                    'question' => "Practical scenario: A learner struggles with '{$f}'. What is the best first teaching response?",
                    'options' => null,
                    'correct_answer' => "Explain {$f} with a simple demonstration and guided practice.",
                    'explanation' => 'Use clear demonstration plus guided practice before increasing difficulty.',
                    'sort_order' => $sort++,
                ]);
                $createdQuestions += $q ? 1 : 0;
            }

            $quizCount++;
        }

        return [
            'course_id' => $courseId,
            'quizzes_seeded' => $quizCount,
            'questions_created' => $createdQuestions,
            'quizzes_skipped' => $skippedQuizzes,
        ];
    }
}
