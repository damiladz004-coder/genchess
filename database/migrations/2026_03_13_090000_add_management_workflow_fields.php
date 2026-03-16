<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instructor_screenings', function (Blueprint $table) {
            $columns = [
                'preferred_interview_date' => fn () => $table->date('preferred_interview_date')->nullable()->after('interview_mode'),
                'preferred_interview_time' => fn () => $table->time('preferred_interview_time')->nullable()->after('preferred_interview_date'),
                'preferred_interview_notes' => fn () => $table->text('preferred_interview_notes')->nullable()->after('preferred_interview_time'),
                'stage_two_meeting_type' => fn () => $table->string('stage_two_meeting_type')->nullable()->after('stage_two_interviewed_at'),
                'stage_two_meeting_link' => fn () => $table->string('stage_two_meeting_link')->nullable()->after('stage_two_meeting_type'),
                'stage_two_meeting_id' => fn () => $table->string('stage_two_meeting_id')->nullable()->after('stage_two_meeting_link'),
                'stage_two_passcode' => fn () => $table->string('stage_two_passcode')->nullable()->after('stage_two_meeting_id'),
                'stage_two_meeting_date' => fn () => $table->date('stage_two_meeting_date')->nullable()->after('stage_two_passcode'),
                'stage_two_meeting_time' => fn () => $table->time('stage_two_meeting_time')->nullable()->after('stage_two_meeting_date'),
                'stage_two_invitation_sent_at' => fn () => $table->dateTime('stage_two_invitation_sent_at')->nullable()->after('stage_two_meeting_time'),
                'stage_two_whatsapp_sent_at' => fn () => $table->dateTime('stage_two_whatsapp_sent_at')->nullable()->after('stage_two_invitation_sent_at'),
                'stage_three_meeting_type' => fn () => $table->string('stage_three_meeting_type')->nullable()->after('stage_three_interviewed_at'),
                'stage_three_meeting_link' => fn () => $table->string('stage_three_meeting_link')->nullable()->after('stage_three_meeting_type'),
                'stage_three_meeting_id' => fn () => $table->string('stage_three_meeting_id')->nullable()->after('stage_three_meeting_link'),
                'stage_three_passcode' => fn () => $table->string('stage_three_passcode')->nullable()->after('stage_three_meeting_id'),
                'stage_three_meeting_date' => fn () => $table->date('stage_three_meeting_date')->nullable()->after('stage_three_passcode'),
                'stage_three_meeting_time' => fn () => $table->time('stage_three_meeting_time')->nullable()->after('stage_three_meeting_date'),
                'stage_three_invitation_sent_at' => fn () => $table->dateTime('stage_three_invitation_sent_at')->nullable()->after('stage_three_meeting_time'),
                'stage_three_whatsapp_sent_at' => fn () => $table->dateTime('stage_three_whatsapp_sent_at')->nullable()->after('stage_three_invitation_sent_at'),
                'onboarding_link_sent_at' => fn () => $table->dateTime('onboarding_link_sent_at')->nullable()->after('onboarded_at'),
                'onboarding_whatsapp_sent_at' => fn () => $table->dateTime('onboarding_whatsapp_sent_at')->nullable()->after('onboarding_link_sent_at'),
            ];

            foreach ($columns as $column => $addColumn) {
                if (!Schema::hasColumn('instructor_screenings', $column)) {
                    $addColumn();
                }
            }
        });

        Schema::table('instructor_profiles', function (Blueprint $table) {
            $columns = [
                'location' => fn () => $table->string('location')->nullable()->after('address'),
                'whatsapp_phone' => fn () => $table->string('whatsapp_phone', 30)->nullable()->after('phone'),
                'short_biography' => fn () => $table->text('short_biography')->nullable()->after('whatsapp_phone'),
                'areas_of_specialization' => fn () => $table->text('areas_of_specialization')->nullable()->after('short_biography'),
            ];

            foreach ($columns as $column => $addColumn) {
                if (!Schema::hasColumn('instructor_profiles', $column)) {
                    $addColumn();
                }
            }
        });

        Schema::table('school_requests', function (Blueprint $table) {
            $columns = [
                'consultation_link' => fn () => $table->string('consultation_link')->nullable()->after('meeting_time'),
                'consultation_meeting_id' => fn () => $table->string('consultation_meeting_id')->nullable()->after('consultation_link'),
                'consultation_passcode' => fn () => $table->string('consultation_passcode')->nullable()->after('consultation_meeting_id'),
                'consultation_invitation_sent_at' => fn () => $table->dateTime('consultation_invitation_sent_at')->nullable()->after('consultation_passcode'),
                'consultation_whatsapp_sent_at' => fn () => $table->dateTime('consultation_whatsapp_sent_at')->nullable()->after('consultation_invitation_sent_at'),
                'portal_link_sent_at' => fn () => $table->dateTime('portal_link_sent_at')->nullable()->after('consultation_whatsapp_sent_at'),
                'portal_whatsapp_sent_at' => fn () => $table->dateTime('portal_whatsapp_sent_at')->nullable()->after('portal_link_sent_at'),
                'portal_onboarded_at' => fn () => $table->dateTime('portal_onboarded_at')->nullable()->after('portal_whatsapp_sent_at'),
            ];

            foreach ($columns as $column => $addColumn) {
                if (!Schema::hasColumn('school_requests', $column)) {
                    $addColumn();
                }
            }
        });

        if (Schema::hasColumn('users', 'status')) {
            $driver = Schema::getConnection()->getDriverName();

            if (in_array($driver, ['mysql', 'mariadb'], true)) {
                DB::statement("ALTER TABLE users MODIFY status ENUM('active','suspended','inactive') NOT NULL DEFAULT 'active'");
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'status')) {
            $driver = Schema::getConnection()->getDriverName();

            if (in_array($driver, ['mysql', 'mariadb'], true)) {
                DB::statement("ALTER TABLE users MODIFY status ENUM('active','suspended') NOT NULL DEFAULT 'active'");
            }
        }

        Schema::table('school_requests', function (Blueprint $table) {
            $table->dropColumn([
                'consultation_link',
                'consultation_meeting_id',
                'consultation_passcode',
                'consultation_invitation_sent_at',
                'consultation_whatsapp_sent_at',
                'portal_link_sent_at',
                'portal_whatsapp_sent_at',
                'portal_onboarded_at',
            ]);
        });

        Schema::table('instructor_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'whatsapp_phone',
                'short_biography',
                'areas_of_specialization',
            ]);
        });

        Schema::table('instructor_screenings', function (Blueprint $table) {
            $table->dropColumn([
                'preferred_interview_date',
                'preferred_interview_time',
                'preferred_interview_notes',
                'stage_two_meeting_type',
                'stage_two_meeting_link',
                'stage_two_meeting_id',
                'stage_two_passcode',
                'stage_two_meeting_date',
                'stage_two_meeting_time',
                'stage_two_invitation_sent_at',
                'stage_two_whatsapp_sent_at',
                'stage_three_meeting_type',
                'stage_three_meeting_link',
                'stage_three_meeting_id',
                'stage_three_passcode',
                'stage_three_meeting_date',
                'stage_three_meeting_time',
                'stage_three_invitation_sent_at',
                'stage_three_whatsapp_sent_at',
                'onboarding_link_sent_at',
                'onboarding_whatsapp_sent_at',
            ]);
        });
    }
};
