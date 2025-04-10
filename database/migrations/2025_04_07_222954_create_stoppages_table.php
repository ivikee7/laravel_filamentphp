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
        Schema::create('stoppages', function (Blueprint $table) {
            $table->id();
            //
            $table->string('name');
            $table->foreignId('route_id')->constrained();
            $table->string('location')->nullable(); // Coordinates or address
            $table->integer('order')->default(0); // Order in the route
            $table->boolean('is_active')->default(true);
            //
            $table->foreignId('creator_id')->nullable();
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
        Schema::dropIfExists('stoppages');
    }
};
