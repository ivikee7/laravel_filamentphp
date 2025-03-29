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
        Schema::create('store_carts', function (Blueprint $table) {
            $table->id();
            //
            $table->double('price', 10, 2);
            $table->integer('quantity');
            $table->foreignId('store_id');
            $table->foreignId('store_product_id');
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
        Schema::dropIfExists('store_carts');
    }
};
