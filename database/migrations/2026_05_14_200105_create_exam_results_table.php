<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();

            // Participant identification (one of these must be set)
            $table->enum('participant_type', ['student', 'applicant', 'external'])->default('student');
            $table->foreignId('student_id')->nullable()->constrained('students')->cascadeOnDelete();
            $table->foreignId('registration_id')->nullable()->constrained('registrations')->nullOnDelete();
            $table->string('participant_name')->nullable();
            $table->string('participant_email')->nullable();

            // Result data
            $table->decimal('score', 8, 2)->nullable();
            $table->string('grade', 10)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'graded', 'absent'])->default('pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Unique on exam_id + participant_type + (student_id OR registration_id OR participant_name)
            // Since we can't fully express this in schema, we keep it simple: one result per exam per student when student_id is set
            $table->unique(['exam_id', 'student_id']);
            $table->index(['exam_id', 'status']);
            $table->index(['participant_type', 'status']);
            $table->index('registration_id');
            $table->index('graded_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};

