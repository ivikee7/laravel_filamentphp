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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('user_id');
            $table->string('admission_number')->unique()->nullable();
            $table->date('admission_date')->nullable();
            $table->enum('current_status', ['active', 'graduated', 'left'])->default('active');
            $table->enum('tc_status', ['not_requested', 'requested', 'issued'])->default('not_requested');
            $table->date('leaving_date')->nullable();
            $table->text('exit_reason')->nullable();
            //
            $table->foreignId('creator_id')->nullable();
            $table->foreignId('updater_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
