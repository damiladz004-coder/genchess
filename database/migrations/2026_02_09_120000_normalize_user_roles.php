<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'superadmin')
            ->update(['role' => 'super_admin']);

        DB::table('users')
            ->where('role', 'schooladmin')
            ->update(['role' => 'school_admin']);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('role', 'super_admin')
            ->update(['role' => 'superadmin']);

        DB::table('users')
            ->where('role', 'school_admin')
            ->update(['role' => 'schooladmin']);
    }
};
