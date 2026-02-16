<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('school_requests', function (Blueprint $table) {
            $table->id();
            $table->string('school_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone');
            $table->string('program_type'); // school | community | home
            $table->integer('student_count')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('pending'); // pending | approved | rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_requests');
    }
};
