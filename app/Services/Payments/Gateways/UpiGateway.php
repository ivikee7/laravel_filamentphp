<?php

namespace App\Services\Payments\Gateways;

use App\Models\FeeInvoice;
use App\Services\Payments\Contracts\GatewayInterface;

class UpiGateway implements GatewayInterface
{
    public function __construct(protected array $config = [])
    {
    }

    public function driver(): string
    {
        return 'upi';
    }

    public function createPayment(FeeInvoice $invoice, float $amount, array $context = []): array
    {
        return [
            'status' => 'pending',
            'reference' => $context['reference'] ?? 'UPI-' . now()->format('YmdHis'),
            'gateway_payload' => [
                'driver' => $this->driver(),
                'upi_id' => $this->config['upi_id'] ?? null,
            ],
        ];
    }

    public function verify(array $payload): array
    {
        return [
            'verified' => ! empty($payload['reference']),
            'status' => $payload['status'] ?? 'success',
            'reference' => $payload['reference'] ?? null,
        ];
    }

    public function refund(array $payload): array
    {
        return ['status' => 'refunded', 'reference' => $payload['reference'] ?? null];
    }
}

