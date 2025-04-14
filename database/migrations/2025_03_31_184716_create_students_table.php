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
            $table->foreignId('registration_id')->nullable();
            $table->foreignId('quota_id');
            $table->string('admission_number', 25)->unique()->nullable();
            $table->enum('current_status', ['active', 'graduated', 'left'])->default('active');
            $table->enum('tc_status', ['not_requested', 'requested', 'issued'])->default('not_requested');
            $table->date('leaving_date')->nullable();
            $table->text('exit_reason', 255)->nullable();
            //
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
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
