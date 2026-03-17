<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('school_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('school_requests', 'onboarding_token_hash')) {
                $table->string('onboarding_token_hash', 64)->nullable()->after('school_id');
            }

            if (!Schema::hasColumn('school_requests', 'onboarding_token_expires_at')) {
                $table->dateTime('onboarding_token_expires_at')->nullable()->after('onboarding_token_hash');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_requests', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('school_requests', 'onboarding_token_hash')) {
                $columns[] = 'onboarding_token_hash';
            }

            if (Schema::hasColumn('school_requests', 'onboarding_token_expires_at')) {
                $columns[] = 'onboarding_token_expires_at';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
