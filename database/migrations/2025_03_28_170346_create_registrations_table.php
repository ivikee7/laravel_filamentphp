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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            //
            $table->string('name', 50);
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['M', 'F', 'O']);
            $table->string('father_name', 50)->nullable();
            $table->string('father_qualification', 50)->nullable();
            $table->string('father_occupation', 50)->nullable();
            $table->string('father_contact_number', 15)->nullable();
            $table->string('mother_name', 50)->nullable();
            $table->string('mother_qualification', 50)->nullable();
            $table->string('mother_occupation', 50)->nullable();
            $table->string('mother_contact_number', 15)->nullable();
            $table->string('address')->nullable();
            $table->string('city', 25)->nullable();
            $table->string('state', 25)->nullable();
            $table->integer('pin_code')->nullable();
            $table->foreignId('class_id');
            $table->string('last_attended_school')->nullable();
            $table->foreignId('last_attended_class_id')->nullable();
            $table->string('payment_mode', 15);
            $table->foreignId('creator_id');
            $table->foreignId('updater_id')->nullable();
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
        Schema::dropIfExists('registrations');
    }
};
