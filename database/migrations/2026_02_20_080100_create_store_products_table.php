<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('price_kobo');
            $table->unsignedInteger('bulk_price_kobo')->nullable();
            $table->string('sku')->unique();
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->string('image_placeholder')->nullable();
            $table->string('product_type')->default('standard'); // standard | school_package
            $table->boolean('featured')->default(false);
            $table->boolean('allow_quote')->default(false);
            $table->boolean('has_size_options')->default(false);
            $table->boolean('has_color_options')->default(false);
            $table->string('status')->default('active'); // active | inactive
            $table->timestamps();

            $table->index(['status', 'featured']);
            $table->index(['category_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

