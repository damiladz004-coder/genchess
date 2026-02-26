<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('training_modules')->cascadeOnDelete();
            $table->unsignedInteger('topic_number')->nullable();
            $table->string('title');
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->string('level')->nullable(); // beginner | advanced
            $table->json('objectives')->nullable();
            $table->json('video_structure')->nullable();
            $table->json('lesson_notes')->nullable();
            $table->json('quiz_focus')->nullable();
            $table->json('assessment')->nullable();
            $table->json('practical_assignment')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['module_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_topics');
    }
};
