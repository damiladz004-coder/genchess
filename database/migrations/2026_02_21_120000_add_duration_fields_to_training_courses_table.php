<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_courses', function (Blueprint $table) {
            $table->unsignedInteger('duration_hours')->nullable()->after('duration_weeks');
            $table->unsignedTinyInteger('duration_minutes')->nullable()->after('duration_hours');
        });
    }

    public function down(): void
    {
        Schema::table('training_courses', function (Blueprint $table) {
            $table->dropColumn(['duration_hours', 'duration_minutes']);
        });
    }
};
