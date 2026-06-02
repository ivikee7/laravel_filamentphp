<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('driver');
            $table->string('payload_hash')->unique();
            $table->string('event_id')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('provider_payment_id')->nullable();
            $table->boolean('signature_valid')->default(false);
            $table->enum('status', ['received', 'processed', 'failed', 'duplicate'])->default('received');
            $table->unsignedInteger('attempts')->default(0);
            $table->json('headers')->nullable();
            $table->json('payload')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['driver', 'event_id']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_webhook_events');
    }
};

