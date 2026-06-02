<?php

namespace App\Services\Payments\Contracts;

use App\Models\FeeInvoice;

interface GatewayInterface
{
    public function driver(): string;

    public function createPayment(FeeInvoice $invoice, float $amount, array $context = []): array;

    public function verify(array $payload): array;

    public function refund(array $payload): array;
}

