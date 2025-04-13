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
        Schema::create('book_publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 50)->nullable();
            $table->string('mobile', 10)->nullable();
            $table->string('phone', 10)->nullable();
            $table->string('location', 100)->nullable();
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
        Schema::dropIfExists('book_publishers');
    }
};
