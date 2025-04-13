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
        Schema::create('sms_providers', function (Blueprint $table) {
            $table->id();
            //
            $table->string('name')->unique();
            $table->string('base_url');
            $table->enum('method', ['get', 'post'])->default('get');
            $table->string('to_key');
            $table->string('text_key');
            $table->json('params');
            $table->json('headers')->nullable();
            $table->json('responses')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('sms_providers');
    }
};
