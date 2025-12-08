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
        Schema::create('store_invoices', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('user_id');
            $table->foreignId('store_id');
            $table->foreignId('class_id')->nullable();
            $table->double('subtotal_amount');
            $table->double('discount_amount');
            $table->double('total_amount');
            $table->string('remarks', 100)->nullable();
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
        Schema::dropIfExists('store_invoices');
    }
};
