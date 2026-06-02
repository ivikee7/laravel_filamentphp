<?php

namespace Tests\Unit\Payments;

use App\Models\PaymentGateway;
use App\Services\Payments\GatewayManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GatewayManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_enabled_dynamic_gateways(): void
    {
        PaymentGateway::create([
            'name' => 'Cash Counter',
            'driver' => 'cash',
            'is_enabled' => true,
            'is_default' => true,
            'config' => [],
        ]);

        PaymentGateway::create([
            'name' => 'UPI Live',
            'driver' => 'upi',
            'is_enabled' => true,
            'is_default' => false,
            'config' => ['upi_id' => 'school@upi'],
        ]);

        PaymentGateway::create([
            'name' => 'Disabled Stripe',
            'driver' => 'stripe',
            'is_enabled' => false,
            'is_default' => false,
            'config' => [],
        ]);

        $manager = app(GatewayManager::class);
        $enabled = $manager->enabledGateways();

        $this->assertArrayHasKey('cash', $enabled);
        $this->assertArrayHasKey('upi', $enabled);
        $this->assertArrayNotHasKey('stripe', $enabled);
        $this->assertSame('cash', $manager->defaultDriver());
    }
}

