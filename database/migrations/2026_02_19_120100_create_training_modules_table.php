<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('training_courses')->cascadeOnDelete();
            $table->unsignedInteger('module_number');
            $table->string('title');
            $table->text('goal')->nullable();
            $table->boolean('is_capstone')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['course_id', 'module_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_modules');
    }
};
