<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Actions\SeedTrainingQuizQuestions;
use App\Actions\SyncTrainingCurriculum;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('training:sync-curriculum {course_id?}', function ($courseId = null) {
    $result = app(SyncTrainingCurriculum::class)->execute($courseId ? (int) $courseId : null);

    $this->info('Training curriculum synced successfully.');
    $this->line('Course ID: ' . $result['course_id']);
    $this->line('Modules processed: ' . $result['modules_processed']);
    $this->line('Topics processed: ' . $result['topics_processed']);
})->purpose('Sync training curriculum config into database modules/topics/quizzes/assignments.');

Artisan::command('training:seed-quiz-questions {course_id?} {--force}', function ($courseId = null) {
    $result = app(SeedTrainingQuizQuestions::class)->execute(
        $courseId ? (int) $courseId : null,
        (bool) $this->option('force')
    );

    $this->info('Quiz question seeding completed.');
    $this->line('Course ID: ' . ($result['course_id'] ?? 'all'));
    $this->line('Quizzes seeded: ' . $result['quizzes_seeded']);
    $this->line('Questions created: ' . $result['questions_created']);
    $this->line('Quizzes skipped: ' . $result['quizzes_skipped']);
})->purpose('Seed starter quiz questions from topic quiz_focus values.');
