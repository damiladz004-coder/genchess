<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instructor_lesson_plans', function (Blueprint $table) {
            $table->string('review_status')->default('draft')->after('status');
            $table->timestamp('submitted_at')->nullable()->after('review_status');
            $table->foreignId('reviewed_by')->nullable()->after('submitted_at')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->text('review_feedback')->nullable()->after('reviewed_at');
        });
    }

    public function down(): void
    {
        Schema::table('instructor_lesson_plans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropColumn([
                'review_status',
                'submitted_at',
                'reviewed_at',
                'review_feedback',
            ]);
        });
    }
};
