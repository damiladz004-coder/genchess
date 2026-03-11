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
            $table->enum('stage_two_status', ['pending', 'passed', 'failed'])
                ->default('pending')
                ->after('passed');
            $table->text('stage_two_notes')->nullable()->after('stage_two_status');
            $table->dateTime('stage_two_interviewed_at')->nullable()->after('stage_two_notes');

            $table->enum('stage_three_status', ['pending', 'passed', 'failed'])
                ->default('pending')
                ->after('stage_two_interviewed_at');
            $table->text('stage_three_notes')->nullable()->after('stage_three_status');
            $table->dateTime('stage_three_interviewed_at')->nullable()->after('stage_three_notes');

            $table->enum('final_status', ['pending', 'approved', 'recommended_training', 'rejected'])
                ->default('pending')
                ->after('stage_three_interviewed_at');
            $table->dateTime('approved_at')->nullable()->after('final_status');
            $table->dateTime('rejected_at')->nullable()->after('approved_at');
            $table->dateTime('training_recommended_at')->nullable()->after('rejected_at');
            $table->dateTime('certified_at')->nullable()->after('training_recommended_at');
            $table->foreignId('user_id')->nullable()->after('certified_at')->constrained()->nullOnDelete();
            $table->dateTime('onboarded_at')->nullable()->after('user_id');
        });

        DB::table('instructor_screenings')
            ->where('passed', false)
            ->update([
                'final_status' => 'recommended_training',
                'training_recommended_at' => now(),
            ]);
    }

    public function down(): void
    {
        Schema::table('instructor_screenings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn([
                'stage_two_status',
                'stage_two_notes',
                'stage_two_interviewed_at',
                'stage_three_status',
                'stage_three_notes',
                'stage_three_interviewed_at',
                'final_status',
                'approved_at',
                'rejected_at',
                'training_recommended_at',
                'certified_at',
                'onboarded_at',
            ]);
        });
    }
};
