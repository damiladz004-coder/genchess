<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_topics', function (Blueprint $table) {
            $table->string('lesson_video_url')->nullable()->after('video_structure');
        });
    }

    public function down(): void
    {
        Schema::table('training_topics', function (Blueprint $table) {
            $table->dropColumn('lesson_video_url');
        });
    }
};

