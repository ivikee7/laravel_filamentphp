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
        Schema::create('transport_buses', function (Blueprint $table) {
            $table->id();
            //
            $table->string('registration_number', 50)->unique();
            $table->string('model', 50)->nullable();
            $table->integer('seating_capacity');
            $table->foreignId('driver_id')->constrained('users');
            $table->foreignId('conductor_id')->nullable()->constrained('users');
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
        Schema::dropIfExists('transport_buses');
    }
};
