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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            //
            // Change this line to reference the 'users' table
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Invoice belongs to a User (Client)

            $table->string('invoice_number')->unique()->nullable();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->decimal('sub_total', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('total', 10, 2);
            $table->string('currency', 3)->default('INR');
            $table->text('notes')->nullable();
            $table->string('status')->default('draft');
            $table->string('invoice_type')->default('standard');

            // Polymorphic relationship to the billable entity remains the same
            $table->morphs('invoiceable');

            //
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
