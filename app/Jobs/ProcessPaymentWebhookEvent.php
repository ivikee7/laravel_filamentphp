<?php

namespace App\Jobs;

use App\Models\FeeTransaction;
use App\Models\PaymentWebhookEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPaymentWebhookEvent implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public int $backoff = 10;

    public function __construct(public int $eventId)
    {
    }

    public function handle(): void
    {
        $event = PaymentWebhookEvent::query()->find($this->eventId);
        if (! $event) {
            return;
        }

        if ($event->status === 'processed') {
            return;
        }

        $event->attempts = (int) $event->attempts + 1;

        if (! $event->signature_valid) {
            $event->status = 'failed';
            $event->error_message = 'Signature verification failed.';
            $event->save();
            return;
        }

        $transaction = null;

        if (! empty($event->payment_reference)) {
            $transaction = FeeTransaction::query()
                ->where('reference', $event->payment_reference)
                ->first();
        }

        if (! $transaction && ! empty($event->provider_payment_id)) {
            $transaction = FeeTransaction::query()
                ->where('provider_payment_id', $event->provider_payment_id)
                ->first();
        }

        if (! $transaction) {
            $event->status = 'failed';
            $event->error_message = 'No matching fee transaction found.';
            $event->save();
            return;
        }

        $providerStatus = data_get($event->payload, 'status', 'success');

        $transaction->status = $providerStatus;
        $transaction->gateway_driver = $event->driver;
        $transaction->provider_event_id = $event->event_id;
        $transaction->provider_payment_id = $event->provider_payment_id ?: $transaction->provider_payment_id;
        $transaction->webhook_received_at = now();
        $transaction->last_reconciled_at = now();
        $transaction->reconciliation_status = 'webhook_applied';
        $transaction->gateway_payload = array_merge($transaction->gateway_payload ?? [], [
            'webhook_event_id' => $event->id,
            'event_payload' => $event->payload,
        ]);
        $transaction->save();

        $transaction->invoice?->refreshAmounts();

        $event->status = 'processed';
        $event->processed_at = now();
        $event->error_message = null;
        $event->save();
    }
}

