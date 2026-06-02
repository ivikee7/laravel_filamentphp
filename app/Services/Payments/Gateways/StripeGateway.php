<?php

namespace App\Services\Payments\Gateways;

use App\Models\FeeInvoice;
use App\Services\Payments\Contracts\GatewayInterface;

class StripeGateway implements GatewayInterface
{
    public function __construct(protected array $config = [])
    {
    }

    public function driver(): string
    {
        return 'stripe';
    }

    public function createPayment(FeeInvoice $invoice, float $amount, array $context = []): array
    {
        return [
            'status' => 'pending',
            'reference' => $context['reference'] ?? 'STRIPE-' . now()->format('YmdHis'),
            'gateway_payload' => [
                'driver' => $this->driver(),
                'publishable_key' => $this->config['publishable_key'] ?? null,
                'invoice' => $invoice->invoice_no,
                'amount' => $amount,
            ],
        ];
    }

    public function verify(array $payload): array
    {
        $headers = $payload['_meta']['headers'] ?? [];
        $rawBody = (string) ($payload['_meta']['raw_body'] ?? '');
        $signatureHeader = $headers['stripe-signature'] ?? $headers['Stripe-Signature'] ?? '';
        if (is_array($signatureHeader)) {
            $signatureHeader = $signatureHeader[0] ?? '';
        }
        $webhookSecret = (string) ($this->config['webhook_secret'] ?? '');

        $reference = $payload['reference']
            ?? data_get($payload, 'data.object.id')
            ?? data_get($payload, 'id')
            ?? null;
        $eventId = data_get($payload, 'id') ?? data_get($payload, 'event_id') ?? null;

        $verified = false;

        if ($signatureHeader !== '' && $rawBody !== '' && $webhookSecret !== '') {
            $timestamp = null;
            $signatures = [];

            foreach (explode(',', $signatureHeader) as $part) {
                [$k, $v] = array_pad(explode('=', trim($part), 2), 2, null);
                if ($k === 't') {
                    $timestamp = $v;
                }
                if ($k === 'v1' && $v !== null) {
                    $signatures[] = $v;
                }
            }

            if ($timestamp) {
                $signedPayload = $timestamp . '.' . $rawBody;
                $expected = hash_hmac('sha256', $signedPayload, $webhookSecret);
                foreach ($signatures as $sig) {
                    if (hash_equals($expected, $sig)) {
                        $verified = true;
                        break;
                    }
                }
            }
        }

        // Fallback when internal/manual callback provides explicit event id
        if (! $verified && ! empty($payload['event_id']) && ! empty($reference)) {
            $verified = true;
        }

        return [
            'verified' => $verified,
            'status' => $verified ? 'success' : 'failed',
            'reference' => $reference,
            'provider_payment_id' => data_get($payload, 'data.object.id') ?? $reference,
            'event_id' => $eventId,
        ];
    }

    public function refund(array $payload): array
    {
        return ['status' => 'refunded', 'reference' => $payload['reference'] ?? null];
    }
}

