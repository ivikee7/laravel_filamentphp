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
            $table->double('gross_total', 10, 2);
            $table->double('discount', 10, 2);
            $table->double('net_total', 10, 2);
            $table->double('net_due', 10, 2);
            $table->string('rematks');
            $table->foreignId('store_id');
            $table->foreignId('buyer_id');
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
        Schema::dropIfExists('store_invoices');
    }
};
