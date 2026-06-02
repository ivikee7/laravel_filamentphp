<?php

namespace Tests\Unit\Payments;

use App\Services\Payments\Gateways\RazorpayGateway;
use App\Services\Payments\Gateways\StripeGateway;
use PHPUnit\Framework\TestCase;

class GatewaySignatureUnitTest extends TestCase
{
    public function test_razorpay_checkout_signature_verification(): void
    {
        $gateway = new RazorpayGateway([
            'key_secret' => 'rzp_test_secret',
        ]);

        $orderId = 'order_123';
        $paymentId = 'pay_123';
        $signature = hash_hmac('sha256', $orderId . '|' . $paymentId, 'rzp_test_secret');

        $result = $gateway->verify([
            'razorpay_order_id' => $orderId,
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $signature,
        ]);

        $this->assertTrue($result['verified']);
        $this->assertSame('success', $result['status']);
        $this->assertSame($paymentId, $result['reference']);
    }

    public function test_stripe_webhook_signature_verification(): void
    {
        $gateway = new StripeGateway([
            'webhook_secret' => 'whsec_test',
        ]);

        $rawBody = json_encode([
            'id' => 'evt_123',
            'data' => ['object' => ['id' => 'pi_123']],
        ], JSON_UNESCAPED_SLASHES);

        $timestamp = time();
        $signedPayload = $timestamp . '.' . $rawBody;
        $v1 = hash_hmac('sha256', $signedPayload, 'whsec_test');
        $header = "t={$timestamp},v1={$v1}";

        $result = $gateway->verify([
            '_meta' => [
                'raw_body' => $rawBody,
                'headers' => [
                    'stripe-signature' => $header,
                ],
            ],
            'data' => ['object' => ['id' => 'pi_123']],
        ]);

        $this->assertTrue($result['verified']);
        $this->assertSame('success', $result['status']);
        $this->assertSame('pi_123', $result['reference']);
    }
}

