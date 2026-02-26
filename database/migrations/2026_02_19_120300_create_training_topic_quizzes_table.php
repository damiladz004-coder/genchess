<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_topic_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('training_topics')->cascadeOnDelete();
            $table->unsignedTinyInteger('mcq_count')->default(5);
            $table->unsignedTinyInteger('true_false_count')->default(2);
            $table->unsignedTinyInteger('scenario_count')->default(1);
            $table->decimal('pass_mark', 5, 2)->default(70);
            $table->timestamps();

            $table->unique('topic_id');
        });

        Schema::create('training_topic_quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('training_topic_quizzes')->cascadeOnDelete();
            $table->string('type')->default('mcq'); // mcq | true_false | scenario
            $table->text('question');
            $table->json('options')->nullable();
            $table->text('correct_answer')->nullable();
            $table->text('explanation')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['quiz_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_topic_quiz_questions');
        Schema::dropIfExists('training_topic_quizzes');
    }
};
