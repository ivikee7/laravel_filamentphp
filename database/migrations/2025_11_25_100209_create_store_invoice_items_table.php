<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_invoice_items', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('store_invoice_id');
            $table->foreignId('store_product_id');
            $table->string('name');
            $table->double('price');
            $table->double('quantity');
            $table->double('total');
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
        Schema::dropIfExists('store_invoice_items');
    }
};
