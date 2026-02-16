<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_payments', function (Blueprint $table) {
            $table->date('term_start_date')->nullable()->after('session');
            $table->date('term_end_date')->nullable()->after('term_start_date');
            $table->date('first_due_date')->nullable()->after('term_end_date');
            $table->date('second_due_date')->nullable()->after('first_due_date');
            $table->integer('first_amount')->nullable()->after('second_due_date');
            $table->integer('second_amount')->nullable()->after('first_amount');
        });
    }

    public function down(): void
    {
        Schema::table('school_payments', function (Blueprint $table) {
            $table->dropColumn([
                'term_start_date',
                'term_end_date',
                'first_due_date',
                'second_due_date',
                'first_amount',
                'second_amount',
            ]);
        });
    }
};
