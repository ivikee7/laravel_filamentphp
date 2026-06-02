<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('driver');
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_default')->default(false);
            $table->json('config')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['driver', 'is_enabled']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};

