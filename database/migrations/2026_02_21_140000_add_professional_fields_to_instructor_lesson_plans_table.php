<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'materials_required' => fn (Blueprint $table) => $table->text('materials_required')->nullable()->after('objectives'),
            'resource_text_content' => fn (Blueprint $table) => $table->text('resource_text_content')->nullable()->after('materials_required'),
            'resource_links' => fn (Blueprint $table) => $table->json('resource_links')->nullable()->after('resource_text_content'),
            'resource_files' => fn (Blueprint $table) => $table->json('resource_files')->nullable()->after('resource_links'),
            'wippea_warm_up' => fn (Blueprint $table) => $table->text('wippea_warm_up')->nullable()->after('notes'),
            'wippea_introduction' => fn (Blueprint $table) => $table->text('wippea_introduction')->nullable()->after('wippea_warm_up'),
            'wippea_presentation' => fn (Blueprint $table) => $table->text('wippea_presentation')->nullable()->after('wippea_introduction'),
            'wippea_practice' => fn (Blueprint $table) => $table->text('wippea_practice')->nullable()->after('wippea_presentation'),
            'wippea_evaluation' => fn (Blueprint $table) => $table->text('wippea_evaluation')->nullable()->after('wippea_practice'),
            'wippea_application' => fn (Blueprint $table) => $table->text('wippea_application')->nullable()->after('wippea_evaluation'),
        ];

        foreach ($columns as $column => $definition) {
            if (!Schema::hasColumn('instructor_lesson_plans', $column)) {
                Schema::table('instructor_lesson_plans', $definition);
            }
        }
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
