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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('edition')->nullable();
            $table->double('price', 8, 2)->nullable();
            $table->double('pages', 8, 2)->nullable();
            $table->string('isbn_number', 100)->nullable();
            $table->date('purchased_at')->nullable();
            $table->year('published_at')->nullable();
            $table->string('notes')->nullable();
            $table->bigInteger('author_id')->unsigned()->nullable();
            $table->bigInteger('publisher_id')->unsigned()->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->bigInteger('language_id')->unsigned()->nullable();
            $table->integer('class_id')->unsigned()->nullable();
            $table->bigInteger('subject_id')->unsigned()->nullable();
            $table->bigInteger('supplier_id')->unsigned()->nullable();
            $table->bigInteger('accession_number')->nullable(); // Tempreary
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
        Schema::dropIfExists('books');
    }
};
