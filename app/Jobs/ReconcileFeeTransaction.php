<?php

namespace App\Jobs;

use App\Models\FeeTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ReconcileFeeTransaction implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(public int $transactionId)
    {
    }

    public function handle(): void
    {
        $transaction = FeeTransaction::query()->find($this->transactionId);

        if (! $transaction) {
            return;
        }

        $transaction->last_reconciled_at = now();

        if ($transaction->status === 'pending') {
            $transaction->reconciliation_status = 'pending_review';
        } elseif (in_array($transaction->status, ['success', 'refunded'], true)) {
            $transaction->reconciliation_status = 'reconciled';
        } else {
            $transaction->reconciliation_status = 'attention_required';
        }

        $transaction->save();

        $transaction->invoice?->refreshAmounts();
    }
}

