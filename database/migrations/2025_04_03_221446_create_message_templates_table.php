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
        Schema::create('message_templates', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('sms_provider_id');
            $table->string('name')->unique(); // Template name
            $table->text('content'); // Message with placeholders
            $table->json('variables')->nullable(); // Expected variables like ["name", "amount"]
            $table->json('params');
            $table->boolean('is_active');
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
        Schema::dropIfExists('message_templates');
    }
};
