<?php

namespace App\Services\Payments;

use App\Models\PaymentGateway;
use App\Services\Payments\Contracts\GatewayInterface;
use App\Services\Payments\Gateways\BankTransferGateway;
use App\Services\Payments\Gateways\CashGateway;
use App\Services\Payments\Gateways\RazorpayGateway;
use App\Services\Payments\Gateways\StripeGateway;
use App\Services\Payments\Gateways\UpiGateway;

class GatewayManager
{
    /** @var array<string, class-string<GatewayInterface>> */
    protected array $drivers = [
        'cash' => CashGateway::class,
        'bank_transfer' => BankTransferGateway::class,
        'upi' => UpiGateway::class,
        'razorpay' => RazorpayGateway::class,
        'stripe' => StripeGateway::class,
    ];

    public function registerDriver(string $driver, string $class): void
    {
        $this->drivers[$driver] = $class;
    }

    public function enabledGateways(): array
    {
        $gateways = PaymentGateway::query()->enabled()->orderByDesc('is_default')->orderBy('name')->get();

        if ($gateways->isNotEmpty()) {
            return $gateways
                ->filter(fn (PaymentGateway $gateway) => isset($this->drivers[$gateway->driver]))
                ->mapWithKeys(fn (PaymentGateway $gateway) => [$gateway->driver => $gateway->name])
                ->all();
        }

        return array_combine(config('fee.payment_methods', []), config('fee.payment_methods', [])) ?: [];
    }

    public function make(?string $driver = null): GatewayInterface
    {
        $selected = $driver ?: $this->defaultDriver();

        if (! isset($this->drivers[$selected])) {
            throw new \InvalidArgumentException("Unsupported payment gateway driver: {$selected}");
        }

        $gatewayConfig = PaymentGateway::query()->where('driver', $selected)->first();
        $class = $this->drivers[$selected];

        $resolvedConfig = array_merge(
            config("fee.gateways.{$selected}", []),
            $gatewayConfig?->config ?? []
        );

        return app()->make($class, ['config' => $resolvedConfig]);
    }

    public function defaultDriver(): string
    {
        $default = PaymentGateway::query()->enabled()->where('is_default', true)->value('driver');

        if ($default && isset($this->drivers[$default])) {
            return $default;
        }

        $fallback = (string) (config('fee.default_gateway') ?? 'cash');

        if (isset($this->drivers[$fallback])) {
            return $fallback;
        }

        return 'cash';
    }

    public function verify(string $driver, array $payload): array
    {
        return $this->make($driver)->verify($payload);
    }

    public function refund(string $driver, array $payload): array
    {
        return $this->make($driver)->refund($payload);
    }
}

