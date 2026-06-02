<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_fee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('fee_structure_id')->constrained('fee_structures')->cascadeOnDelete();
            $table->enum('scholarship_type', ['none', 'percent', 'fixed'])->default('none');
            $table->decimal('scholarship_value', 12, 2)->default(0);
            $table->decimal('sibling_discount_percent', 5, 2)->default(0);
            $table->json('custom_settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_fee_profiles');
    }
};

