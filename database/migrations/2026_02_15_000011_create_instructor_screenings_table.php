<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor_screenings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 30)->nullable();
            $table->string('location')->nullable();
            $table->enum('interview_mode', ['zoom', 'physical'])->default('zoom');
            $table->unsignedSmallInteger('score');
            $table->unsignedSmallInteger('total_questions');
            $table->decimal('percentage', 5, 2);
            $table->boolean('passed')->default(false);
            $table->json('answers_json');
            $table->dateTime('started_at');
            $table->dateTime('submitted_at');
            $table->dateTime('invitation_sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_screenings');
    }
};
