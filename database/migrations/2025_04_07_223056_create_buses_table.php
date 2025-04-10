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
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            //
            $table->string('registration_number')->unique();
            $table->string('model')->nullable();
            $table->integer('seating_capacity');
            $table->foreignId('driver_id')->constrained('users');
            $table->foreignId('conductor_id')->nullable()->constrained('users');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('buses');
    }
};
