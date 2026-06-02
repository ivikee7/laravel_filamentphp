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
        Schema::create('exam_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('attempt_number')->default(1);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'submitted', 'graded'])->default('pending');
            $table->unsignedTinyInteger('max_attempts')->nullable()->default(1)->comment('null = unlimited');
            $table->integer('time_taken_minutes')->nullable();
            $table->decimal('score', 8, 2)->nullable();
            $table->string('grade')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->index('exam_id');
            // Unique per attempt — allows multiple attempts per student per exam.
            $table->unique(['exam_id', 'student_id', 'attempt_number'], 'exam_submissions_exam_student_attempt_unique');
            $table->index(['student_id', 'status']);
            $table->index(['exam_id', 'status']);
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_submissions');
    }
};
