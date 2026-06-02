<?php

namespace App\Console\Commands;

use App\Jobs\ReconcileFeeTransaction;
use App\Models\FeeTransaction;
use Illuminate\Console\Command;

class ReconcileFeeTransactions extends Command
{
    protected $signature = 'fee:reconcile-transactions {--queue : Dispatch reconciliation to queue} {--status=* : Filter statuses (pending,success,failed,refunded)}';

    protected $description = 'Reconcile fee transactions and refresh related invoice balances';

    public function handle(): int
    {
        $statuses = $this->option('status');

        $query = FeeTransaction::query()->orderByDesc('id');

        if (! empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        $transactions = $query->get();

        if ($transactions->isEmpty()) {
            $this->warn('No fee transactions found for reconciliation.');
            return self::SUCCESS;
        }

        $queued = (bool) $this->option('queue');

        foreach ($transactions as $transaction) {
            if ($queued) {
                ReconcileFeeTransaction::dispatch($transaction->id);
                $this->line("Queued transaction #{$transaction->id}");
            } else {
                ReconcileFeeTransaction::dispatchSync($transaction->id);
                $this->line("Reconciled transaction #{$transaction->id}");
            }
        }

        $this->info('Fee transaction reconciliation completed.');

        return self::SUCCESS;
    }
}

