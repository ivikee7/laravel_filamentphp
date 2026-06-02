<?php

namespace App\Services\Payments\Gateways;

use App\Models\FeeInvoice;
use App\Services\Payments\Contracts\GatewayInterface;

class BankTransferGateway implements GatewayInterface
{
    public function __construct(protected array $config = [])
    {
    }

    public function driver(): string
    {
        return 'bank_transfer';
    }

    public function createPayment(FeeInvoice $invoice, float $amount, array $context = []): array
    {
        return [
            'status' => 'pending',
            'reference' => $context['reference'] ?? 'BANK-' . now()->format('YmdHis'),
            'gateway_payload' => [
                'driver' => $this->driver(),
                'instructions' => $this->config['instructions'] ?? 'Transfer to configured institute bank account.',
            ],
        ];
    }

    public function verify(array $payload): array
    {
        return [
            'verified' => ! empty($payload['reference']),
            'status' => $payload['status'] ?? 'pending',
            'reference' => $payload['reference'] ?? null,
        ];
    }

    public function refund(array $payload): array
    {
        return ['status' => 'refunded', 'reference' => $payload['reference'] ?? null];
    }
}

