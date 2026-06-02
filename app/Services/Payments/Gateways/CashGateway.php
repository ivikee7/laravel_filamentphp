<?php

namespace App\Services\Payments\Gateways;

use App\Models\FeeInvoice;
use App\Services\Payments\Contracts\GatewayInterface;

class CashGateway implements GatewayInterface
{
    public function __construct(protected array $config = [])
    {
    }

    public function driver(): string
    {
        return 'cash';
    }

    public function createPayment(FeeInvoice $invoice, float $amount, array $context = []): array
    {
        return [
            'status' => 'success',
            'reference' => $context['reference'] ?? 'CASH-' . now()->format('YmdHis'),
            'gateway_payload' => [
                'driver' => $this->driver(),
                'mode' => 'offline',
            ],
        ];
    }

    public function verify(array $payload): array
    {
        return ['verified' => true, 'status' => 'success', 'reference' => $payload['reference'] ?? null];
    }

    public function refund(array $payload): array
    {
        return ['status' => 'refunded', 'reference' => $payload['reference'] ?? null];
    }
}

