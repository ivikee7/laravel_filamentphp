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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('user_id');
            $table->date('date')->nullable();
            $table->timestamp('in')->nullable();
            $table->timestamp('out')->nullable();
            $table->timestamp('entred_in_bus')->nullable();
            $table->timestamp('exit_from_bus')->nullable();
            $table->boolean('is_absent');
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
        Schema::dropIfExists('attendances');
    }
};
