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
            $table->foreignId('class_id');
            $table->foreignId('section_id')->nullable();
            $table->foreignId('academic_year_id');
            $table->boolean('is_promoted')->default(false);
            $table->boolean('is_current')->default(true);
            //
            $table->foreignId('creator_id')->nullable();
            $table->foreignId('updater_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['student_id', 'class_id', 'academic_year_id'], 'student_class_unique');
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
