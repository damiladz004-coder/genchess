<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instructor_lesson_plans', function (Blueprint $table) {
            $table->text('materials_required')->nullable()->after('objectives');
            $table->text('resource_text_content')->nullable()->after('materials_required');
            $table->json('resource_links')->nullable()->after('resource_text_content');
            $table->json('resource_files')->nullable()->after('resource_links');
            $table->text('wippea_warm_up')->nullable()->after('notes');
            $table->text('wippea_introduction')->nullable()->after('wippea_warm_up');
            $table->text('wippea_presentation')->nullable()->after('wippea_introduction');
            $table->text('wippea_practice')->nullable()->after('wippea_presentation');
            $table->text('wippea_evaluation')->nullable()->after('wippea_practice');
            $table->text('wippea_application')->nullable()->after('wippea_evaluation');
        });
    }

    public function down(): void
    {
        Schema::table('instructor_lesson_plans', function (Blueprint $table) {
            $table->dropColumn([
                'materials_required',
                'resource_text_content',
                'resource_links',
                'resource_files',
                'wippea_warm_up',
                'wippea_introduction',
                'wippea_presentation',
                'wippea_practice',
                'wippea_evaluation',
                'wippea_application',
            ]);
        });
    }
};
