<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_invoice_id')->constrained('fee_invoices')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('method');
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('success');
            $table->string('reference')->nullable();
            $table->dateTime('payment_date');
            $table->json('gateway_payload')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['payment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_transactions');
    }
};

