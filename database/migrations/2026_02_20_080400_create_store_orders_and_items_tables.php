<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('phone', 30);
            $table->string('email');
            $table->text('delivery_address');
            $table->string('state');
            $table->string('order_type')->default('individual'); // individual | school | organization
            $table->string('payment_method')->default('paystack'); // paystack | bank_transfer
            $table->string('payment_status')->default('pending'); // pending | paid | failed
            $table->string('status')->default('pending'); // pending | paid | processing | shipped | delivered | cancelled
            $table->unsignedInteger('subtotal_kobo');
            $table->unsignedInteger('delivery_fee_kobo')->default(0);
            $table->unsignedInteger('total_kobo');
            $table->string('currency', 3)->default('NGN');
            $table->text('notes')->nullable();
            $table->string('reference')->nullable()->unique();
            $table->json('paystack_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'payment_status']);
            $table->index(['created_at']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('unit_price_kobo');
            $table->unsignedInteger('total_price_kobo');
            $table->json('options_json')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};

