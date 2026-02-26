<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_timetables', function (Blueprint $table) {
            $table->string('instructor_review_status')->default('pending')->after('review_comment');
            $table->text('instructor_review_comment')->nullable()->after('instructor_review_status');
            $table->timestamp('instructor_reviewed_at')->nullable()->after('instructor_review_comment');
            $table->foreignId('instructor_reviewed_by')->nullable()->after('instructor_reviewed_at')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('school_timetables', function (Blueprint $table) {
            $table->dropConstrainedForeignId('instructor_reviewed_by');
            $table->dropColumn([
                'instructor_review_status',
                'instructor_review_comment',
                'instructor_reviewed_at',
            ]);
        });
    }
};
