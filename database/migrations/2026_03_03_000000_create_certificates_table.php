<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->string('instructor_name');
            $table->string('program_name');
            $table->string('certificate_number')->unique();
            $table->timestamp('issued_at');
            $table->string('pdf_path');
            $table->timestamps();

            $table->index('instructor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
