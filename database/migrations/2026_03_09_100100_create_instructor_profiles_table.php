<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('screening_id')->nullable()->unique()->constrained('instructor_screenings')->nullOnDelete();
            $table->string('genchess_instructor_id')->unique()->nullable();
            $table->string('passport_photo_path');
            $table->string('full_name');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('email');
            $table->string('phone', 30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_profiles');
    }
};
