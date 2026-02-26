<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('training_payments')->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->unsignedInteger('subtotal_kobo');
            $table->unsignedInteger('discount_kobo')->default(0);
            $table->unsignedInteger('total_kobo');
            $table->string('currency', 3)->default('NGN');
            $table->timestamp('issued_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_invoices');
    }
};

