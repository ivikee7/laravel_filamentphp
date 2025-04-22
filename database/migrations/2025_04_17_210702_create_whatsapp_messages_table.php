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
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('whatsapp_provider_id');
            $table->string('from_number')->nullable(); // Sender (null for outgoing messages)
            $table->string('to'); // Receiver
            $table->text('message');
            $table->enum('direction', ['incoming', 'outgoing']);
            $table->string('status')->default('pending'); // pending, sent, failed, delivered, read
            $table->json('response')->nullable(); // Store full API response
            $table->timestamp('received_at')->nullable();
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
        Schema::dropIfExists('whatsapp_messages');
    }
};
