<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referred_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('payment_status')->default('pending'); // pending | paid | failed
            $table->boolean('reward_issued')->default(false);
            $table->timestamps();

            $table->unique(['referrer_id', 'referred_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_referrals');
    }
};

