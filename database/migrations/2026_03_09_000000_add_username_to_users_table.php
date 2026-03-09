<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
        });

        $existing = DB::table('users')->select('id', 'name', 'email')->get();
        $used = [];

        foreach ($existing as $user) {
            $base = Str::of($user->email ?? $user->name ?? 'user')
                ->before('@')
                ->lower()
                ->replaceMatches('/[^a-z0-9_]/', '_')
                ->trim('_')
                ->value();

            if ($base === '') {
                $base = 'user';
            }

            $candidate = $base;
            $counter = 1;
            while (in_array($candidate, $used, true) || DB::table('users')->where('username', $candidate)->exists()) {
                $candidate = $base.$counter;
                $counter++;
            }

            $used[] = $candidate;

            DB::table('users')->where('id', $user->id)->update([
                'username' => $candidate,
            ]);
        }

    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
