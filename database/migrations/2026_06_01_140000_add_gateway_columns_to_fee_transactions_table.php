<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fee_transactions', function (Blueprint $table) {
            $table->string('gateway_driver')->nullable()->after('method');
            $table->string('provider_payment_id')->nullable()->after('reference');
            $table->string('provider_event_id')->nullable()->after('provider_payment_id');
            $table->timestamp('webhook_received_at')->nullable()->after('payment_date');
            $table->timestamp('last_reconciled_at')->nullable()->after('webhook_received_at');
            $table->string('reconciliation_status')->nullable()->after('last_reconciled_at');

            $table->index(['gateway_driver', 'provider_payment_id']);
            $table->index(['status', 'payment_date']);
        });
    }

    public function down(): void
    {
        Schema::table('fee_transactions', function (Blueprint $table) {
            $table->dropIndex(['gateway_driver', 'provider_payment_id']);
            $table->dropIndex(['status', 'payment_date']);
            $table->dropColumn([
                'gateway_driver',
                'provider_payment_id',
                'provider_event_id',
                'webhook_received_at',
                'last_reconciled_at',
                'reconciliation_status',
            ]);
        });
    }
};

