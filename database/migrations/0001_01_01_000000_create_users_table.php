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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            //
            $table->string('name', 50);
            $table->string('email', 50);
            $table->string('official_email', 50)->nullable();
            $table->string('father_name', 50)->nullable();
            $table->string('mother_name', 60)->nullable();
            $table->string('primary_contact_number', 15)->nullable();
            $table->string('secondary_contact_number', 15)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('city', 25)->nullable();
            $table->string('state', 25)->nullable();
            $table->integer('pin_code')->nullable();
            $table->string('avatar', 100)->nullable();
            $table->boolean('is_active')->nullable();
            $table->foreignId('blood_group_id')->nullable();
            $table->foreignId('gender_id')->nullable();
            //
            $table->string('aadhaar_number', 15)->nullable();
            $table->string('mother_tongue', 50)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth', 50)->nullable();
            $table->string('notes', 100)->nullable();
            $table->date('termination_date')->nullable();
            //
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            //
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
