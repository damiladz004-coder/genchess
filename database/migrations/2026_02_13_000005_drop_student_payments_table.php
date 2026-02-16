<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('student_payments');
    }

    public function down(): void
    {
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->string('term');
            $table->string('session');
            $table->integer('amount_due');
            $table->integer('amount_paid')->default(0);
            $table->enum('status', ['pending', 'partial', 'paid'])->default('pending');
            $table->date('paid_at')->nullable();
            $table->string('reference')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'term', 'session']);
        });
    }
};
