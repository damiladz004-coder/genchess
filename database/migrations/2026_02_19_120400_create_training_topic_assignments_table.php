<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_topic_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('training_topics')->cascadeOnDelete();
            $table->string('type')->default('teaching_simulation');
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->boolean('required')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['topic_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_topic_assignments');
    }
};
