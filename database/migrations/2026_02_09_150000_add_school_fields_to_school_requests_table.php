<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('school_requests', function (Blueprint $table) {
            $table->string('school_type')->nullable();
            $table->string('class_system', 50)->nullable();
            $table->string('address_line')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();

            $table->foreign('school_id')
                ->references('id')
                ->on('schools')
                ->nullOnDelete();
        });

        DB::table('school_requests')
            ->whereNull('school_type')
            ->update(['school_type' => 'private']);

        DB::table('school_requests')
            ->whereNull('class_system')
            ->update(['class_system' => 'primary_jss_ss']);

        DB::table('school_requests')
            ->whereNull('city')
            ->update(['city' => 'Unknown']);

        DB::table('school_requests')
            ->whereNull('state')
            ->update(['state' => 'Unknown']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_requests', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn([
                'school_type',
                'class_system',
                'address_line',
                'city',
                'state',
                'school_id',
            ]);
        });
    }
};
