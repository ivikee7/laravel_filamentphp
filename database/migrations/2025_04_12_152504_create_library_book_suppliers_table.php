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
        Schema::create('library_book_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('address', 255)->nullable();
            $table->string('primary_contact_number', 15)->nullable();
            $table->string('secondary_contact_number', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('is_active')->nullable();
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
        Schema::dropIfExists('library_book_suppliers');
    }
};
