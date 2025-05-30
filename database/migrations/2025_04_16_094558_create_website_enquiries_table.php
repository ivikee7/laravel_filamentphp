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
        Schema::create('website_enquiries', function (Blueprint $table) {
            $table->id();
            //
            $table->string('name', 50)->nullable();
            $table->string('contact_number', 15)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('message', 255)->nullable();
            $table->string('notes', 150)->nullable();
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
        Schema::dropIfExists('website_enquiries');
    }
};
