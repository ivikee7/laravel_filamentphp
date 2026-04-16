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
        Schema::create('transport_assignments', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('user_id'); // student or employee
            $table->foreignId('transport_route_id');
            $table->foreignId('transport_stoppage_id')->nullable();
            $table->string('contact_number', 15)->nullable();
            $table->string('remarks', 100)->nullable();
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
        Schema::dropIfExists('transport_assignments');
    }
};
