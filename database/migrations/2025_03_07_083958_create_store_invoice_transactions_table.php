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
        Schema::create('store_invoice_transactions', function (Blueprint $table) {
            $table->id();
            $table->double('paid', 10, 2);
            $table->string('rematks');
            $table->foreignId('store_id');
            $table->foreignId('store_invoice_id');
            $table->foreignId('creator_id');
            $table->foreignId('updater_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_invoice_transactions');
    }
};
