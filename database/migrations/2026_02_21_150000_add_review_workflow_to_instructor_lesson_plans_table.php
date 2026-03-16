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
        Schema::table('instructor_lesson_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('instructor_lesson_plans', 'review_status')) {
                $table->string('review_status')->default('draft')->after('status');
            }

            if (!Schema::hasColumn('instructor_lesson_plans', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('review_status');
            }

            if (!Schema::hasColumn('instructor_lesson_plans', 'reviewed_by')) {
                $table->foreignId('reviewed_by')->nullable()->after('submitted_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('instructor_lesson_plans', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }

            if (!Schema::hasColumn('instructor_lesson_plans', 'review_feedback')) {
                $table->text('review_feedback')->nullable()->after('reviewed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('instructor_lesson_plans', function (Blueprint $table) {
            if ($this->foreignKeyExists('instructor_lesson_plans', 'instructor_lesson_plans_reviewed_by_foreign')) {
                $table->dropForeign('instructor_lesson_plans_reviewed_by_foreign');
            }

            $columns = array_values(array_filter([
                Schema::hasColumn('instructor_lesson_plans', 'reviewed_by') ? 'reviewed_by' : null,
                Schema::hasColumn('instructor_lesson_plans', 'review_status') ? 'review_status' : null,
                Schema::hasColumn('instructor_lesson_plans', 'submitted_at') ? 'submitted_at' : null,
                Schema::hasColumn('instructor_lesson_plans', 'reviewed_at') ? 'reviewed_at' : null,
                Schema::hasColumn('instructor_lesson_plans', 'review_feedback') ? 'review_feedback' : null,
            ]));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
