<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppMessage;
use App\Models\WhatsAppProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function sendMessage($to, $message, WhatsAppProvider $provider)
    {
        // Convert headers array of objects into associative array
        $headers = collect($provider->headers)
            ->pluck('header_value', 'header_name')
            ->toArray();

        $response = Http::withHeaders($headers)
            ->withToken($provider->api_token)
            ->post($provider->base_url . $provider->send_message_endpoint, [
                'messaging_product' => 'whatsapp',
                'to' => '91' . $to,
                'type' => 'text',
                'text' => [
                    'body' => $message,
                ],
            ]);

        WhatsAppMessage::create([
            'whatsapp_provider_id' => $provider->id,
            'to' => $to,
            'message' => $message,
            'message_id' => $response['messages'][0]['id'] ?? null,
            'status' => $response['status'] ?? ($response->successful() ? 'sent' : 'failed'),
            'response' => $response->body(),
            'created_by' => Auth::id(),
        ]);

        return $response->json();
    }

    public function sendTemplateMessage($to, $templateName, $languageCode, $parameters, $provider, $messagingProduct = 'whatsapp', $type = 'template')
    {
        $templateParams = collect($parameters)
            ->map(fn($value) => ['type' => 'text', 'text' => $value])
            ->toArray();

        $payload = [
            'messaging_product' => $messagingProduct,
            'to' => $to,
            'type' => $type,
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $languageCode,
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => $templateParams,
                    ],
                ],
            ],
        ];

        $headers = json_decode($provider->headers, true) ?? [];

        $response = Http::withHeaders($headers)
            ->withToken($provider->token)
            ->post($provider->base_url . $provider->send_message_endpoint, $payload);

        // Optionally log or store the message
        WhatsAppMessage::create([
            'whatsapp_provider_id' => $provider->id,
            'to' => $to,
            'message' => json_encode($payload),
            'status' => $response->successful() ? 'sent' : 'failed',
            'response' => $response->body(),
        ]);

        return $response->json();
    }
}
