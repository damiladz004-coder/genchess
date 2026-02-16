<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instructor_lesson_plans', function (Blueprint $table) {
            $table->string('scheme_reference')->nullable()->after('topic');
        });
    }

    public function down(): void
    {
        Schema::table('instructor_lesson_plans', function (Blueprint $table) {
            $table->dropColumn('scheme_reference');
        });
    }
};
