<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function indexExists(string $table, string $indexName): bool
    {
        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            return DB::table('information_schema.statistics')
                ->where('table_schema', DB::getDatabaseName())
                ->where('table_name', $table)
                ->where('index_name', $indexName)
                ->exists();
        }

        if ($driver === 'sqlite') {
            $indexes = DB::select("PRAGMA index_list('{$table}')");

            foreach ($indexes as $index) {
                if (($index->name ?? null) === $indexName) {
                    return true;
                }
            }

            return false;
        }

        return false;
    }

    public function up(): void
    {
        if ($this->indexExists('class_teachers', 'class_teachers_user_id_class_id_unique')) {
            return;
        }

        Schema::table('class_teachers', function (Blueprint $table) {
            $table->unique(['user_id', 'class_id']);
        });
    }

    public function down(): void
    {
        if (!$this->indexExists('class_teachers', 'class_teachers_user_id_class_id_unique')) {
            return;
        }

        Schema::table('class_teachers', function (Blueprint $table) {
            $table->dropUnique('class_teachers_user_id_class_id_unique');
        });
    }
};
