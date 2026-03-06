<?php

namespace App\Actions;

use App\Models\TrainingCourse;
use App\Models\TrainingEnrollment;
use App\Models\TrainingEnrollmentTopicProgress;
use App\Models\TrainingModule;
use App\Models\TrainingTopic;
use App\Models\TrainingTopicAssignment;
use App\Models\TrainingTopicQuiz;
use Illuminate\Support\Facades\DB;

class SyncTrainingCurriculum
{
    private const STANDARD_PROGRAM_TITLE = 'Genchess Certified Chess Instructor Program (GCCIP)';

    public function execute(?int $courseId = null): array
    {
        $curriculum = config('training_curriculum');

        if ($courseId) {
            $course = TrainingCourse::findOrFail($courseId);
        } else {
            $course = TrainingCourse::query()
                ->whereIn('title', [
                    self::STANDARD_PROGRAM_TITLE,
                    'Certified Genchess Instructor - Level 2',
                    'Genchess Instructor Training Program',
                    'Genchess Instructor Training Programme',
                ])
                ->orderBy('id')
                ->first();

            if ($course) {
                $course->update([
                    'title' => self::STANDARD_PROGRAM_TITLE,
                    'description' => 'Structured 8-module instructor training with capstone teaching practice.',
                    'duration_weeks' => 12,
                    'active' => true,
                ]);
            } else {
                $course = TrainingCourse::create([
                    'title' => self::STANDARD_PROGRAM_TITLE,
                    'description' => 'Structured 8-module instructor training with capstone teaching practice.',
                    'duration_weeks' => 12,
                    'active' => true,
                ]);
            }
        }

        $createdModules = 0;
        $createdTopics = 0;

        DB::transaction(function () use ($curriculum, $course, &$createdModules, &$createdTopics): void {
            foreach ($curriculum['modules'] as $moduleIndex => $moduleData) {
                $moduleNumber = $moduleIndex + 1;

                $module = TrainingModule::updateOrCreate(
                    [
                        'course_id' => $course->id,
                        'module_number' => $moduleNumber,
                    ],
                    [
                        'title' => $moduleData['title'],
                        'goal' => $moduleData['goal'] ?? null,
                        'is_capstone' => false,
                        'sort_order' => $moduleNumber,
                    ]
                );

                $createdModules++;

                foreach (($moduleData['topics'] ?? []) as $topicIndex => $topicData) {
                    $topicNumber = $topicIndex + 1;
                    $isAdvanced = $moduleNumber >= 5;
                    $practical = $curriculum['standard_template']['practical_assignment'] ?? [];

                    if (!$isAdvanced) {
                        $practical = array_values(array_filter(
                            $practical,
                            fn (string $item): bool => stripos($item, 'Written reflection') === false
                        ));
                    }

                    $topic = TrainingTopic::updateOrCreate(
                        [
                            'module_id' => $module->id,
                            'topic_number' => $topicNumber,
                        ],
                        [
                            'title' => $topicData['title'],
                            'duration_minutes' => null,
                            'level' => $isAdvanced ? 'advanced' : 'beginner',
                            'objectives' => null,
                            'video_structure' => $topicData['video'] ?? ($curriculum['standard_template']['video_structure'] ?? []),
                            'lesson_notes' => $curriculum['standard_template']['lesson_notes'] ?? [],
                            'quiz_focus' => $topicData['quiz'] ?? [],
                            'assessment' => $topicData['assessment'] ?? ($moduleData['assessment'] ?? []),
                            'practical_assignment' => $practical,
                            'sort_order' => $topicNumber,
                        ]
                    );

                    $createdTopics++;

                    if (!empty($topicData['quiz'])) {
                        TrainingTopicQuiz::updateOrCreate(
                            ['topic_id' => $topic->id],
                            [
                                'mcq_count' => 5,
                                'true_false_count' => 2,
                                'scenario_count' => 1,
                                'pass_mark' => 70,
                            ]
                        );
                    }

                    $assignmentTitles = $practical;
                    if (!empty($moduleData['every_topic_requires'])) {
                        $assignmentTitles = array_merge($assignmentTitles, $moduleData['every_topic_requires']);
                    }
                    $assignmentTitles = array_values(array_unique($assignmentTitles));

                    foreach ($assignmentTitles as $assignmentIndex => $assignmentTitle) {
                        TrainingTopicAssignment::updateOrCreate(
                            [
                                'topic_id' => $topic->id,
                                'title' => $assignmentTitle,
                            ],
                            [
                                'type' => $this->inferAssignmentType($assignmentTitle),
                                'instructions' => $assignmentTitle,
                                'required' => true,
                                'sort_order' => $assignmentIndex + 1,
                            ]
                        );
                    }
                }
            }

            $capstoneModule = TrainingModule::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'module_number' => 9,
                ],
                [
                    'title' => $curriculum['capstone']['title'] ?? 'Capstone - Teaching Practice',
                    'goal' => 'Applied teaching practice and mentor validation.',
                    'is_capstone' => true,
                    'sort_order' => 9,
                ]
            );

            $capstoneTopic = TrainingTopic::updateOrCreate(
                [
                    'module_id' => $capstoneModule->id,
                    'topic_number' => 1,
                ],
                [
                    'title' => 'Teaching Practice Submission',
                    'duration_minutes' => 15,
                    'level' => 'advanced',
                    'objectives' => null,
                    'video_structure' => $curriculum['capstone']['workflow'] ?? [],
                    'lesson_notes' => $curriculum['standard_template']['lesson_notes'] ?? [],
                    'quiz_focus' => [],
                    'assessment' => $curriculum['certification']['requirements'] ?? [],
                    'practical_assignment' => ['Upload 15-min teaching video'],
                    'sort_order' => 1,
                ]
            );

            TrainingTopicAssignment::updateOrCreate(
                [
                    'topic_id' => $capstoneTopic->id,
                    'title' => 'Upload 15-min teaching video',
                ],
                [
                    'type' => 'capstone_video',
                    'instructions' => 'Upload a 15-minute teaching demonstration for mentor review.',
                    'required' => true,
                    'sort_order' => 1,
                ]
            );

            $topicsByModule = TrainingTopic::whereIn(
                'module_id',
                TrainingModule::where('course_id', $course->id)->pluck('id')
            )->pluck('id');

            TrainingEnrollment::whereHas('cohort', fn ($q) => $q->where('course_id', $course->id))
                ->pluck('id')
                ->each(function (int $enrollmentId) use ($topicsByModule): void {
                    foreach ($topicsByModule as $topicId) {
                        TrainingEnrollmentTopicProgress::firstOrCreate([
                            'enrollment_id' => $enrollmentId,
                            'topic_id' => $topicId,
                        ]);
                    }
                });
        });

        return [
            'course_id' => $course->id,
            'modules_processed' => $createdModules,
            'topics_processed' => $createdTopics,
        ];
    }

    private function inferAssignmentType(string $title): string
    {
        $lower = strtolower($title);

        return match (true) {
            str_contains($lower, 'board demonstration') => 'board_demo',
            str_contains($lower, 'puzzle') => 'puzzle_solving',
            str_contains($lower, 'teaching simulation') => 'teaching_simulation',
            str_contains($lower, 'written reflection'),
            str_contains($lower, '2-page reflection') => 'written_reflection',
            str_contains($lower, 'lesson plan') => 'lesson_plan',
            str_contains($lower, 'peer review') => 'peer_review',
            str_contains($lower, 'teaching demo video') => 'teaching_demo_video',
            str_contains($lower, 'motivational speech') => 'motivational_speech',
            str_contains($lower, 'tournament prep plan') => 'tournament_plan',
            default => 'custom',
        };
    }
}
