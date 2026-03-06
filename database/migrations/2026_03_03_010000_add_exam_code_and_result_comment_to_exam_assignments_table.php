<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_assignments', function (Blueprint $table) {
            $table->string('exam_code', 30)->unique()->nullable()->after('status');
            $table->string('result_comment', 255)->nullable()->after('exam_code');
        });
    }

    public function down(): void
    {
        Schema::table('exam_assignments', function (Blueprint $table) {
            $table->dropUnique(['exam_code']);
            $table->dropColumn(['exam_code', 'result_comment']);
        });
    }
};
