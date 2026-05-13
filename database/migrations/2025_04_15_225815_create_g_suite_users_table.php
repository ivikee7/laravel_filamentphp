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
        Schema::create('g_suite_users', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('user_id')->nullable();
            $table->string('email', 50)->nullable();
            $table->string('password', 20)->nullable();
            //
            $table->string('google_id')->nullable()->index();
            $table->json('google_access_token')->nullable();
            $table->timestamp('google_token_expires_at')->nullable();
            $table->text('google_token_scopes')->nullable();
            $table->timestamp('google_last_synced_at')->nullable();
            //
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            //
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('g_suite_users');
    }
};
