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
        Schema::create('store_products', function (Blueprint $table) {
            $table->id();
            //
            $table->string('name');
            $table->string('price');
            $table->foreignId('class_id');
            $table->foreignId('session_id');
            $table->foreignId('store_id');
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
        Schema::dropIfExists('store_products');
    }
};
