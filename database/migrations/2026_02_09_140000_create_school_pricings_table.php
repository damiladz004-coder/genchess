<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('term');
            $table->string('session');
            $table->integer('per_student_amount');
            $table->string('currency', 10)->default('NGN');
            $table->boolean('active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'term', 'session']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_pricings');
    }
};
