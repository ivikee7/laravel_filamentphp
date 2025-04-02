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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            //
            $table->string('name', 50);
            $table->enum('gender', ['M', 'F', 'O'])->nullable();
            $table->Date('date_of_birth')->nullable();
            $table->string('father_name', 50)->nullable();
            $table->string('mother_name', 50)->nullable();
            $table->string('primary_contact_number', 15);
            $table->string('secondary_contact_number', 15)->nullable();
            $table->string('address')->nullable();
            $table->string('city', 25)->nullable();
            $table->string('state', 25)->nullable();
            $table->integer('pin_code')->unsigned()->nullable();
            $table->string('previous_school', 50)->nullable();
            $table->string('source', 25)->nullable();
            $table->foreignId('previous_class_id')->nullable();
            $table->foreignId('class_id')->nullable();
            //
            $table->foreignId('creator_id')->nullable();
            $table->foreignId('updater_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
