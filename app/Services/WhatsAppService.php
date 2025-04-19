<?php

namespace App\Services;

use App\Models\WhatsAppMessage;
use App\Models\WhatsAppProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected ?WhatsAppProvider $provider;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->provider = $provider ?? WhatsAppProvider::getDefault();
    }

    public function sendMessage(string $to, string $message): WhatsAppMessage
    {
        if (!$this->provider) {
            throw new \Exception('No WhatsApp provider configured.');
        }

        // Store message in the database before sending
        $whatsappMessage = WhatsAppMessage::create([
            'whatsapp_provider_id' => $this->provider->id,
            'to' => $to,
            'message' => $message,
            'status' => 'sent',
        ]);

        try {
            // Send the message to the WhatsApp API
            $response = Http::withToken($this->provider->token)
                ->post("{$this->provider->api_url}/messages", [
                    'to' => $to,
                    'type' => 'text',
                    'text' => ['body' => $message],
                ]);

            $responseData = $response->json();

            // Update message status and store response
            $whatsappMessage->update([
                'status' => $response->successful() ? 'sent' : 'failed',
                'response' => $responseData,
            ]);

            return $whatsappMessage;
        } catch (\Exception $e) {
            Log::error('Error sending WhatsApp message: ' . $e->getMessage());
            $whatsappMessage->update([
                'status' => 'failed',
                'response' => ['error' => $e->getMessage()],
            ]);
            throw $e;
        }
    }

    public function getMessageStatus(int $messageId): array
    {
        $message = WhatsAppMessage::findOrFail($messageId);
        return $message->response ?? [];
    }
}
