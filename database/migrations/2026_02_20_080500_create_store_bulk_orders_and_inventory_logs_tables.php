<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bulk_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('organization_name');
            $table->string('contact_person');
            $table->string('phone', 30);
            $table->string('email');
            $table->string('delivery_location');
            $table->json('items_json');
            $table->text('additional_notes')->nullable();
            $table->string('status')->default('pending'); // pending | approved | quoted | invoiced | cancelled
            $table->unsignedInteger('custom_price_kobo')->nullable();
            $table->string('invoice_number')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action')->default('reduce'); // reduce | restock | adjust
            $table->integer('quantity');
            $table->integer('before_stock');
            $table->integer('after_stock');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
        Schema::dropIfExists('bulk_orders');
    }
};

