<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_coupon_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained('training_enrollments')->nullOnDelete();
            $table->unsignedInteger('discount_applied_kobo');
            $table->timestamps();

            $table->unique(['coupon_id', 'user_id', 'enrollment_id'], 'coupon_user_enrollment_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_coupon_redemptions');
    }
};

