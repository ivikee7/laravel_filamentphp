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
        Schema::create('transport_fuel_logs', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('bus_id');
            $table->date('date');
            $table->decimal('liters', 8, 2);
            $table->decimal('cost', 10, 2);
            $table->string('filled_by', 50)->nullable();
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
        Schema::dropIfExists('transport_fuel_logs');
    }
};
