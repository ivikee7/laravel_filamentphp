<?php

namespace App\Http\Controllers\Api\Payments;

use App\Jobs\ProcessPaymentWebhookEvent;
use App\Models\PaymentWebhookEvent;
use App\Services\Payments\GatewayManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GatewayWebhookController
{
    public function __invoke(Request $request, string $driver, GatewayManager $manager): JsonResponse
    {
        $payload = $request->all();

        $payload['_meta'] = [
            'raw_body' => $request->getContent(),
            'headers' => $request->headers->all(),
        ];

        $verification = $manager->verify($driver, $payload);

        $rawBody = (string) ($payload['_meta']['raw_body'] ?? json_encode($payload));
        $payloadHash = hash('sha256', $rawBody);

        $event = PaymentWebhookEvent::query()->firstOrCreate(
            ['payload_hash' => $payloadHash],
            [
                'driver' => $driver,
                'event_id' => $verification['event_id'] ?? data_get($payload, 'id'),
                'payment_reference' => $verification['reference'] ?? null,
                'provider_payment_id' => $verification['provider_payment_id'] ?? null,
                'signature_valid' => (bool) ($verification['verified'] ?? false),
                'status' => 'received',
                'headers' => $payload['_meta']['headers'] ?? [],
                'payload' => array_merge($payload, ['status' => $verification['status'] ?? 'success']),
            ]
        );

        if (! $event->wasRecentlyCreated) {
            return response()->json([
                'ok' => true,
                'duplicate' => true,
                'event_id' => $event->id,
            ]);
        }

        ProcessPaymentWebhookEvent::dispatch($event->id);

        return response()->json([
            'ok' => true,
            'queued' => true,
            'event_id' => $event->id,
        ]);
    }
}

