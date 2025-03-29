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
        Schema::create('store_invoice_items', function (Blueprint $table) {
            $table->id();
            //
            $table->integer('quantity');
            $table->foreignId('product_invoice_id');
            $table->double('price', 10, 2);
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
        Schema::dropIfExists('store_invoice_items');
    }
};
