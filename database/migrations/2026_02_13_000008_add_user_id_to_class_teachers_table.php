<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function foreignKeyExists(string $table, string $constraintName): bool
    {
        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            return DB::table('information_schema.table_constraints')
                ->where('table_schema', DB::getDatabaseName())
                ->where('table_name', $table)
                ->where('constraint_type', 'FOREIGN KEY')
                ->where('constraint_name', $constraintName)
                ->exists();
        }

        if ($driver === 'sqlite') {
            $foreignKeys = DB::select("PRAGMA foreign_key_list('{$table}')");

            foreach ($foreignKeys as $foreignKey) {
                if (($foreignKey->table ?? null) === 'users') {
                    return true;
                }
            }

            return false;
        }

        return false;
    }

    public function up(): void
    {
        if (Schema::hasColumn('class_teachers', 'user_id')) {
            return;
        }

        Schema::table('class_teachers', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('class_teachers', 'user_id')) {
            return;
        }

        Schema::table('class_teachers', function (Blueprint $table) {
            if ($this->foreignKeyExists('class_teachers', 'class_teachers_user_id_foreign')) {
                $table->dropForeign('class_teachers_user_id_foreign');
            }

            $table->dropColumn('user_id');
        });
    }
};
