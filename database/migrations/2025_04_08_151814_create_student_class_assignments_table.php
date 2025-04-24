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
        Schema::create('student_class_assignments', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('student_id');
            $table->foreignId('academic_year_id');
            $table->foreignId('class_id');
            $table->foreignId('section_id')->nullable();
            $table->boolean('is_promoted')->default(false);
            $table->boolean('is_current')->default(true);
            //
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['student_id', 'academic_year_id'], 'unique_student_academicYear');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_class_assignments');
    }
};
