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
        Schema::create('whatsapp_providers', function (Blueprint $table) {
            $table->id();

            // 🔧 Basic Config
            $table->string('name');
            $table->string('base_url');
            $table->string('send_message_endpoint')->nullable();
            $table->string('api_token')->nullable();
            $table->json('headers')->nullable();
            $table->string('verify_token')->nullable();
            $table->string('encryption_key')->nullable();

            // 📞 WhatsApp API Details
            $table->string('phone_number')->nullable();
            $table->string('phone_number_id')->nullable();
            $table->string('business_account_id')->nullable();
            $table->timestamp('token_expires_at')->nullable();

            // 🔁 Webhook Management
            $table->string('webhook_url')->nullable();
            $table->timestamp('webhook_received_at')->nullable();
            $table->string('webhook_status')->nullable();
            $table->text('last_error_message')->nullable();
            $table->integer('failed_webhook_count')->default(0);
            $table->timestamp('last_successful_response')->nullable();

            // 🔐 Meta App Info
            $table->string('meta_app_id')->nullable();
            $table->string('meta_app_secret')->nullable();

            // ⚙️ Operational Controls
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->enum('environment', ['live', 'sandbox'])->default('live'); // added for clarity and isolation

            // 👤 Auditing
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
        Schema::dropIfExists('whatsapp_providers');
    }
};
