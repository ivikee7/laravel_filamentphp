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
            $table->enum('gender', ['M', 'F', 'O']);
            $table->dateTime('date_of_birth')->nullable();
            $table->string('father_name', 50);
            $table->string('mother_name', 50);
            $table->string('father_contact_name', 10);
            $table->string('mother_contact_name', 10);
            $table->foreignId('class_id');
            $table->string('message', 255);
            $table->string('address', 255)->nullable();
            $table->string('city', 25)->nullable();
            $table->string('state', 25)->nullable();
            $table->integer('pin_code')->nullable();
            $table->string('last_attended_school', 50)->nullable();
            $table->foreignId('last_attended_class_id')->nullable();
            $table->string('source', 25)->nullable();
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
