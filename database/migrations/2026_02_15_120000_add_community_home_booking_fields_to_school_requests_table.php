<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('school_requests', function (Blueprint $table) {
            $table->string('applicant_type')->nullable()->after('program_type');
            $table->string('session_type')->nullable()->after('applicant_type');
            $table->string('physical_location')->nullable()->after('session_type');

            $table->string('children_count', 10)->nullable()->after('physical_location');
            $table->string('children_ages')->nullable()->after('children_count');
            $table->string('chess_level')->nullable()->after('children_ages');
            $table->string('preferred_schedule')->nullable()->after('chess_level');
            $table->time('parent_preferred_time')->nullable()->after('preferred_schedule');

            $table->string('organization_name')->nullable()->after('parent_preferred_time');
            $table->string('participants_estimate')->nullable()->after('organization_name');
            $table->string('age_group')->nullable()->after('participants_estimate');
            $table->string('org_program_type')->nullable()->after('age_group');

            $table->boolean('consultation_needed')->nullable()->after('org_program_type');
            $table->string('meeting_type')->nullable()->after('consultation_needed');
            $table->date('meeting_date')->nullable()->after('meeting_type');
            $table->time('meeting_time')->nullable()->after('meeting_date');

            $table->boolean('consent')->default(false)->after('meeting_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_requests', function (Blueprint $table) {
            $table->dropColumn([
                'applicant_type',
                'session_type',
                'physical_location',
                'children_count',
                'children_ages',
                'chess_level',
                'preferred_schedule',
                'parent_preferred_time',
                'organization_name',
                'participants_estimate',
                'age_group',
                'org_program_type',
                'consultation_needed',
                'meeting_type',
                'meeting_date',
                'meeting_time',
                'consent',
            ]);
        });
    }
};
