<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_topic_assignments', function (Blueprint $table) {
            $table->dateTime('due_at')->nullable()->after('instructions');
        });
    }

    public function down(): void
    {
        Schema::table('training_topic_assignments', function (Blueprint $table) {
            $table->dropColumn('due_at');
        });
    }
};

