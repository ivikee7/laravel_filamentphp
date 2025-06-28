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
        Schema::create('library_books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->string('edition', 50)->nullable();
            $table->string('author', 100)->nullable();
            $table->double('price', 8, 2)->nullable();
            $table->double('pages', 8, 2)->nullable();
            $table->string('isbn_number', 100)->nullable();
            $table->date('purchased_at')->nullable();
            $table->year('published_at')->nullable();
            $table->string('notes')->nullable();
            $table->foreignId('author_id')->unsigned()->nullable();
            $table->foreignId('publisher_id')->unsigned()->nullable();
            $table->foreignId('category_id')->unsigned()->nullable();
            $table->foreignId('location_id')->unsigned()->nullable();
            $table->foreignId('language_id')->unsigned()->nullable();
            $table->foreignId('class_id')->unsigned()->nullable();
            $table->foreignId('subject_id')->unsigned()->nullable();
            $table->foreignId('supplier_id')->unsigned()->nullable();
            $table->string('accession_number', 50)->nullable(); // Tempreary
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
        Schema::dropIfExists('library_books');
    }
};
