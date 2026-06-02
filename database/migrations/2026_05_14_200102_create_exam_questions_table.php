<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->text('question');
            $table->enum('type', ['multiple_choice', 'true_false', 'short_answer', 'essay'])->default('short_answer');
            $table->decimal('marks', 8, 2)->default(1);
            $table->unsignedInteger('order')->default(0);
            $table->json('options')->nullable();
            $table->boolean('shuffle_options')
                ->default(false)
                ->comment('Randomize answer order when displayed to students');
            $table->text('correct_answer')->nullable();
            $table->text('explanation')
                ->nullable()
                ->comment('Instructor solution / feedback explanation');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index(['exam_id', 'order']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};

