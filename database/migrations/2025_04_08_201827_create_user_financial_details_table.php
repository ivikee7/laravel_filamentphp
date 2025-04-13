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
        Schema::create('user_financial_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');

            // Basic salary and allowances
            $table->decimal('basic_salary', 10, 2)->nullable();
            $table->decimal('house_allowance', 10, 2)->nullable();
            $table->decimal('transport_allowance', 10, 2)->nullable();
            $table->decimal('other_allowances', 10, 2)->nullable();

            // Deductions
            $table->decimal('tax_deduction', 10, 2)->nullable();
            $table->decimal('loan_deduction', 10, 2)->nullable();

            // Payment info
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable(); // or Swift/Branch code

            // Provident fund
            $table->decimal('provident_fund_contribution', 10, 2)->nullable();
            $table->string('pf_account_number')->nullable();
            $table->string('esi_number')->nullable(); // if applicable

            // Extra
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('user_financial_details');
    }
};
