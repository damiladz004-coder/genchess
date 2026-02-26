<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_enrollments', function (Blueprint $table) {
            $table->boolean('quizzes_completed')->default(false)->after('completed_at');
            $table->boolean('assignments_completed')->default(false)->after('quizzes_completed');
            $table->boolean('teaching_practice_completed')->default(false)->after('assignments_completed');
            $table->boolean('mentor_approved')->default(false)->after('teaching_practice_completed');
        });
    }

    public function down(): void
    {
        Schema::table('training_enrollments', function (Blueprint $table) {
            $table->dropColumn([
                'quizzes_completed',
                'assignments_completed',
                'teaching_practice_completed',
                'mentor_approved',
            ]);
        });
    }
};
