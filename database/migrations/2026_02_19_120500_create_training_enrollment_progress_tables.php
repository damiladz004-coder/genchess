<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_enrollment_topic_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('training_enrollments')->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained('training_topics')->cascadeOnDelete();
            $table->decimal('quiz_score', 5, 2)->nullable();
            $table->boolean('quiz_passed')->default(false);
            $table->unsignedInteger('quiz_attempts')->default(0);
            $table->string('assignment_status')->default('not_started'); // not_started | submitted | needs_revision | approved
            $table->dateTime('assignment_submitted_at')->nullable();
            $table->dateTime('assignment_reviewed_at')->nullable();
            $table->boolean('mentor_approved')->default(false);
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['enrollment_id', 'topic_id']);
        });

        Schema::create('training_assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('training_enrollments')->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained('training_topics')->cascadeOnDelete();
            $table->foreignId('assignment_id')->constrained('training_topic_assignments')->cascadeOnDelete();
            $table->longText('submission_text')->nullable();
            $table->string('submission_url')->nullable();
            $table->string('status')->default('submitted'); // submitted | needs_revision | approved | rejected
            $table->longText('mentor_feedback')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['enrollment_id', 'topic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_assignment_submissions');
        Schema::dropIfExists('training_enrollment_topic_progress');
    }
};
