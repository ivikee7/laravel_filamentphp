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
        Schema::create('admission_promotions', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('admission_id');
            $table->foreignId('acadamic_session_id');
            $table->foreignId('class_id');
            $table->foreignId('section_id');
            //
            $table->foreignId('creator_id');
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
        Schema::dropIfExists('admission_promotions');
    }
};
