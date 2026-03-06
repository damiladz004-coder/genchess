<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('address_verified_at')->nullable()->after('paid_at');
            $table->timestamp('stock_confirmed_at')->nullable()->after('address_verified_at');
            $table->timestamp('packed_at')->nullable()->after('stock_confirmed_at');
            $table->timestamp('quality_checked_at')->nullable()->after('packed_at');
            $table->string('courier_name')->nullable()->after('quality_checked_at');
            $table->string('tracking_number')->nullable()->after('courier_name');
            $table->timestamp('delivery_confirmed_at')->nullable()->after('delivered_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'address_verified_at',
                'stock_confirmed_at',
                'packed_at',
                'quality_checked_at',
                'courier_name',
                'tracking_number',
                'delivery_confirmed_at',
            ]);
        });
    }
};

