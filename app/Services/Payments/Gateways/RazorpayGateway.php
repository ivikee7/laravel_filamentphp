<?php

namespace App\Services\Payments\Gateways;

use App\Models\FeeInvoice;
use App\Services\Payments\Contracts\GatewayInterface;

class RazorpayGateway implements GatewayInterface
{
    public function __construct(protected array $config = [])
    {
    }

    public function driver(): string
    {
        return 'razorpay';
    }

    public function createPayment(FeeInvoice $invoice, float $amount, array $context = []): array
    {
        return [
            'status' => 'pending',
            'reference' => $context['reference'] ?? 'RAZORPAY-' . now()->format('YmdHis'),
            'gateway_payload' => [
                'driver' => $this->driver(),
                'merchant_key' => $this->config['key_id'] ?? null,
                'invoice' => $invoice->invoice_no,
                'amount' => $amount,
            ],
        ];
    }

    public function verify(array $payload): array
    {
        $webhookSecret = (string) ($this->config['webhook_secret'] ?? '');
        $keySecret = (string) ($this->config['key_secret'] ?? '');

        $headers = $payload['_meta']['headers'] ?? [];
        $rawBody = (string) ($payload['_meta']['raw_body'] ?? '');

        $reference = $payload['reference']
            ?? $payload['razorpay_payment_id']
            ?? data_get($payload, 'payload.payment.entity.id')
            ?? null;
        $eventId = data_get($payload, 'id') ?? data_get($payload, 'event') ?? null;

        $verified = false;

        // Webhook signature verification
        $headerSignature = $headers['x-razorpay-signature'] ?? $headers['X-Razorpay-Signature'] ?? null;
        if (is_array($headerSignature)) {
            $headerSignature = $headerSignature[0] ?? null;
        }
        if (! empty($headerSignature) && $rawBody !== '' && $webhookSecret !== '') {
            $expected = hash_hmac('sha256', $rawBody, $webhookSecret);
            $verified = hash_equals($expected, (string) $headerSignature);
        }

        // Checkout callback verification fallback
        if (! $verified && ! empty($payload['razorpay_order_id']) && ! empty($payload['razorpay_payment_id']) && ! empty($payload['razorpay_signature']) && $keySecret !== '') {
            $signed = $payload['razorpay_order_id'] . '|' . $payload['razorpay_payment_id'];
            $expected = hash_hmac('sha256', $signed, $keySecret);
            $verified = hash_equals($expected, (string) $payload['razorpay_signature']);
            $reference = $payload['razorpay_payment_id'];
        }

        return [
            'verified' => $verified,
            'status' => $verified ? 'success' : 'failed',
            'reference' => $reference,
            'provider_payment_id' => $reference,
            'event_id' => $eventId,
        ];
    }

    public function refund(array $payload): array
    {
        return ['status' => 'refunded', 'reference' => $payload['reference'] ?? null];
    }
}

