<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grading_components', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->decimal('weight_percentage', 5, 2);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
            $table->timestamps();

            $table->unique(['name', 'school_id']);
        });

        Schema::create('grade_scales', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_percentage', 5, 2);
            $table->decimal('max_percentage', 5, 2);
            $table->string('letter_grade', 5);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('student_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->string('term', 30);
            $table->string('academic_session', 20);
            $table->decimal('test_score', 8, 2)->nullable();
            $table->decimal('test_max', 8, 2)->nullable();
            $table->decimal('practical_score', 8, 2)->nullable();
            $table->decimal('practical_max', 8, 2)->nullable();
            $table->decimal('exam_score', 8, 2)->nullable();
            $table->decimal('exam_max', 8, 2)->nullable();
            $table->enum('exam_mode', ['manual', 'automatic'])->default('manual');
            $table->decimal('final_percentage', 6, 2)->nullable();
            $table->string('grade', 5)->nullable();
            $table->text('instructor_comment')->nullable();
            $table->text('system_feedback')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'term', 'academic_session'], 'student_term_session_unique');
        });

        Schema::create('student_result_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_result_id')->constrained('student_results')->cascadeOnDelete();
            $table->string('action', 50);
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('before_values')->nullable();
            $table->json('after_values')->nullable();
            $table->timestamp('changed_at');
            $table->timestamps();
        });

        if (Schema::hasTable('exam_questions')) {
            Schema::table('exam_questions', function (Blueprint $table) {
                if (!Schema::hasColumn('exam_questions', 'type')) {
                    $table->string('type', 30)->default('multiple_choice')->after('question_text');
                }
                if (!Schema::hasColumn('exam_questions', 'created_by')) {
                    $table->foreignId('created_by')->nullable()->after('type')->constrained('users')->nullOnDelete();
                }
            });
        }

        DB::table('grading_components')->insert([
            ['name' => 'test', 'weight_percentage' => 25, 'created_by' => null, 'school_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'practical', 'weight_percentage' => 25, 'created_by' => null, 'school_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'exam', 'weight_percentage' => 50, 'created_by' => null, 'school_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('grade_scales')->insert([
            ['min_percentage' => 70, 'max_percentage' => 100, 'letter_grade' => 'A', 'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['min_percentage' => 60, 'max_percentage' => 69.99, 'letter_grade' => 'B', 'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['min_percentage' => 50, 'max_percentage' => 59.99, 'letter_grade' => 'C', 'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['min_percentage' => 30, 'max_percentage' => 49.99, 'letter_grade' => 'D', 'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['min_percentage' => 0, 'max_percentage' => 29.99, 'letter_grade' => 'F', 'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        if (Schema::hasTable('exam_questions')) {
            Schema::table('exam_questions', function (Blueprint $table) {
                if (Schema::hasColumn('exam_questions', 'created_by')) {
                    $table->dropConstrainedForeignId('created_by');
                }
                if (Schema::hasColumn('exam_questions', 'type')) {
                    $table->dropColumn('type');
                }
            });
        }

        Schema::dropIfExists('student_result_audits');
        Schema::dropIfExists('student_results');
        Schema::dropIfExists('grade_scales');
        Schema::dropIfExists('grading_components');
    }
};
