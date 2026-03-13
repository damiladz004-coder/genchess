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
            $table->date('preferred_interview_date')->nullable()->after('interview_mode');
            $table->time('preferred_interview_time')->nullable()->after('preferred_interview_date');
            $table->text('preferred_interview_notes')->nullable()->after('preferred_interview_time');

            $table->string('stage_two_meeting_type')->nullable()->after('stage_two_interviewed_at');
            $table->string('stage_two_meeting_link')->nullable()->after('stage_two_meeting_type');
            $table->string('stage_two_meeting_id')->nullable()->after('stage_two_meeting_link');
            $table->string('stage_two_passcode')->nullable()->after('stage_two_meeting_id');
            $table->date('stage_two_meeting_date')->nullable()->after('stage_two_passcode');
            $table->time('stage_two_meeting_time')->nullable()->after('stage_two_meeting_date');
            $table->dateTime('stage_two_invitation_sent_at')->nullable()->after('stage_two_meeting_time');
            $table->dateTime('stage_two_whatsapp_sent_at')->nullable()->after('stage_two_invitation_sent_at');

            $table->string('stage_three_meeting_type')->nullable()->after('stage_three_interviewed_at');
            $table->string('stage_three_meeting_link')->nullable()->after('stage_three_meeting_type');
            $table->string('stage_three_meeting_id')->nullable()->after('stage_three_meeting_link');
            $table->string('stage_three_passcode')->nullable()->after('stage_three_meeting_id');
            $table->date('stage_three_meeting_date')->nullable()->after('stage_three_passcode');
            $table->time('stage_three_meeting_time')->nullable()->after('stage_three_meeting_date');
            $table->dateTime('stage_three_invitation_sent_at')->nullable()->after('stage_three_meeting_time');
            $table->dateTime('stage_three_whatsapp_sent_at')->nullable()->after('stage_three_invitation_sent_at');

            $table->dateTime('onboarding_link_sent_at')->nullable()->after('onboarded_at');
            $table->dateTime('onboarding_whatsapp_sent_at')->nullable()->after('onboarding_link_sent_at');
        });

        Schema::table('instructor_profiles', function (Blueprint $table) {
            $table->string('location')->nullable()->after('address');
            $table->string('whatsapp_phone', 30)->nullable()->after('phone');
            $table->text('short_biography')->nullable()->after('whatsapp_phone');
            $table->text('areas_of_specialization')->nullable()->after('short_biography');
        });

        Schema::table('school_requests', function (Blueprint $table) {
            $table->string('consultation_link')->nullable()->after('meeting_time');
            $table->string('consultation_meeting_id')->nullable()->after('consultation_link');
            $table->string('consultation_passcode')->nullable()->after('consultation_meeting_id');
            $table->dateTime('consultation_invitation_sent_at')->nullable()->after('consultation_passcode');
            $table->dateTime('consultation_whatsapp_sent_at')->nullable()->after('consultation_invitation_sent_at');
            $table->dateTime('portal_link_sent_at')->nullable()->after('consultation_whatsapp_sent_at');
            $table->dateTime('portal_whatsapp_sent_at')->nullable()->after('portal_link_sent_at');
            $table->dateTime('portal_onboarded_at')->nullable()->after('portal_whatsapp_sent_at');
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
