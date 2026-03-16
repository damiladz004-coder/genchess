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
        Schema::table('school_timetables', function (Blueprint $table) {
            if (!Schema::hasColumn('school_timetables', 'instructor_review_status')) {
                $table->string('instructor_review_status')->default('pending')->after('review_comment');
            }

            if (!Schema::hasColumn('school_timetables', 'instructor_review_comment')) {
                $table->text('instructor_review_comment')->nullable()->after('instructor_review_status');
            }

            if (!Schema::hasColumn('school_timetables', 'instructor_reviewed_at')) {
                $table->timestamp('instructor_reviewed_at')->nullable()->after('instructor_review_comment');
            }

            if (!Schema::hasColumn('school_timetables', 'instructor_reviewed_by')) {
                $table->foreignId('instructor_reviewed_by')->nullable()->after('instructor_reviewed_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('school_timetables', function (Blueprint $table) {
            if ($this->foreignKeyExists('school_timetables', 'school_timetables_instructor_reviewed_by_foreign')) {
                $table->dropForeign('school_timetables_instructor_reviewed_by_foreign');
            }

            $columns = array_values(array_filter([
                Schema::hasColumn('school_timetables', 'instructor_reviewed_by') ? 'instructor_reviewed_by' : null,
                Schema::hasColumn('school_timetables', 'instructor_review_status') ? 'instructor_review_status' : null,
                Schema::hasColumn('school_timetables', 'instructor_review_comment') ? 'instructor_review_comment' : null,
                Schema::hasColumn('school_timetables', 'instructor_reviewed_at') ? 'instructor_reviewed_at' : null,
            ]));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
