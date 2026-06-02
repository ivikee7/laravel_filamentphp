<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('description')->nullable();
            $table->foreign('exam_type_id')->references('id')->on('exam_types')->nullOnDelete();
            $table->decimal('total_marks', 8, 2)->default(100);
            $table->decimal('passing_marks', 8, 2)->default(40);
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->date('exam_date')->nullable();
            $table->time('start_time')->nullable();
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed'])->default('draft');
            $table->unsignedTinyInteger('max_attempts')->nullable()->default(1)->comment('null = unlimited attempts');
            $table->text('instructions')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'exam_date']);
            $table->index('course_id');
            $table->index('academic_year_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};

