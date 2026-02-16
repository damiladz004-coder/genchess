<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('term');
            $table->string('session');
            $table->integer('student_count');
            $table->integer('per_student_amount');
            $table->integer('total_due');
            $table->integer('amount_paid')->default(0);
            $table->string('status')->default('pending');
            $table->date('due_date')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_payments');
    }
};
