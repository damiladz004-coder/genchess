<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_topic_quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('training_enrollments')->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained('training_topics')->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained('training_topic_quizzes')->cascadeOnDelete();
            $table->json('answers_json')->nullable();
            $table->unsignedInteger('total_questions')->default(0);
            $table->unsignedInteger('correct_answers')->default(0);
            $table->decimal('score', 5, 2)->default(0);
            $table->boolean('passed')->default(false);
            $table->dateTime('submitted_at');
            $table->timestamps();

            $table->index(['enrollment_id', 'topic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_topic_quiz_attempts');
    }
};
