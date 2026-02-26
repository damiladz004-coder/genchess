<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_courses', function (Blueprint $table) {
            $table->unsignedInteger('price_kobo')->default(3500000)->after('duration_weeks');
            $table->string('currency', 3)->default('NGN')->after('price_kobo');
            $table->unsignedInteger('discount_price_kobo')->default(2500000)->after('currency');
        });

        Schema::table('training_enrollments', function (Blueprint $table) {
            $table->string('enrollment_status')->default('not_enrolled')->after('status');
            $table->string('payment_status')->default('pending')->after('enrollment_status');
            $table->unsignedInteger('amount_due_kobo')->nullable()->after('payment_status');
            $table->unsignedInteger('amount_paid_kobo')->nullable()->after('amount_due_kobo');
            $table->timestamp('paid_at')->nullable()->after('amount_paid_kobo');
        });
    }

    public function down(): void
    {
        Schema::table('training_enrollments', function (Blueprint $table) {
            $table->dropColumn([
                'enrollment_status',
                'payment_status',
                'amount_due_kobo',
                'amount_paid_kobo',
                'paid_at',
            ]);
        });

        Schema::table('training_courses', function (Blueprint $table) {
            $table->dropColumn(['price_kobo', 'currency', 'discount_price_kobo']);
        });
    }
};

