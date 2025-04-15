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
            $table->string('name', 25);
            $table->date('date_of_birth')->nullable();
            $table->foreignId('gender_id')->nullable();
            $table->string('father_name', 50)->nullable();
            $table->string('father_qualification', 50)->nullable();
            $table->string('father_occupation', 50)->nullable();
            $table->string('primary_contact_number', 15)->nullable();
            $table->string('mother_name', 50)->nullable();
            $table->string('mother_qualification', 50)->nullable();
            $table->string('mother_occupation', 50)->nullable();
            $table->string('secondary_contact_number', 15)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('city', 25)->nullable();
            $table->string('state', 25)->nullable();
            $table->integer('pin_code')->nullable();
            $table->string('previous_school', 100)->nullable();
            $table->double('payment_amount');
            $table->string('payment_mode', 15);
            $table->string('payment_notes', 50)->nullable();
            $table->foreignId('previous_class_id')->nullable();
            $table->foreignId('academic_year_id')->nullable();
            $table->foreignId('class_id')->nullable();
            $table->foreignId('enquiry_id')->nullable();
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
        Schema::dropIfExists('registrations');
    }
};
