<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['name' => 'Cash', 'driver' => 'cash', 'is_enabled' => true, 'is_default' => true],
            ['name' => 'Bank Transfer', 'driver' => 'bank_transfer', 'is_enabled' => true, 'is_default' => false],
            ['name' => 'UPI', 'driver' => 'upi', 'is_enabled' => true, 'is_default' => false],
            ['name' => 'Razorpay', 'driver' => 'razorpay', 'is_enabled' => false, 'is_default' => false],
            ['name' => 'Stripe', 'driver' => 'stripe', 'is_enabled' => false, 'is_default' => false],
        ];

        foreach ($defaults as $gateway) {
            PaymentGateway::query()->updateOrCreate(
                ['driver' => $gateway['driver']],
                [
                    'name' => $gateway['name'],
                    'is_enabled' => $gateway['is_enabled'],
                    'is_default' => $gateway['is_default'],
                    'config' => [],
                    'meta' => [],
                ]
            );
        }

        // Ensure exactly one default among enabled gateways.
        $default = PaymentGateway::query()->enabled()->where('is_default', true)->first();
        if (! $default) {
            $firstEnabled = PaymentGateway::query()->enabled()->first();
            if ($firstEnabled) {
                PaymentGateway::query()->whereKey($firstEnabled->id)->update(['is_default' => true]);
            }
        }
    }
}

