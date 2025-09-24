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
        Schema::create('library_book_borrows', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('book_id');
            $table->foreignId('user_id');
            $table->date('due_date')->nullable();
            $table->string('notes', 100)->nullable();
            $table->foreignId('received_by')->nullable();
            $table->timestamp('received_at')->nullable();
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
        Schema::dropIfExists('library_book_borrows');
    }
};
