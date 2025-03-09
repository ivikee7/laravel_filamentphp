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
            $table->integer('quantity')->nullable()->unsigned();
            $table->foreignId('creator_id')->nullable()->unsigned();
            $table->foreignId('updater_id')->nullable()->unsigned();
            $table->foreignId('product_invoice_id')->nullable()->unsigned();
            $table->double('price', 10, 2);
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
