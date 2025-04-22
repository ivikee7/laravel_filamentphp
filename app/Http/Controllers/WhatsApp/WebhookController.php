<?php

namespace App\Http\Controllers\WhatsApp;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        return response()->json(['status' => 'received'], 200);
    }

    public function verify(Request $request)
    {
        Log::info($request);
        // 🔐 Handle webhook verification
        $mode = $request->get('hub_mode');
        $token = $request->get('hub_verify_token');
        $challenge = $request->get('hub_challenge');

        $provider = WhatsAppProvider::where('verify_token', $token)->first();

        if ($mode === 'subscribe' && $provider) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        // 📩 Handle incoming webhook events (messages, statuses, etc.)
        $payload = $request->all();
        // Log::info('Incoming WhatsApp Webhook:', $payload);

        if (isset($payload['entry'])) {
            foreach ($payload['entry'] as $entry) {
                foreach ($entry['changes'] as $change) {
                    $value = $change['value'] ?? [];

                    if (isset($value['messages'])) {
                        foreach ($value['messages'] as $message) {
                            $from = $message['from'] ?? null;
                            $text = $message['text']['body'] ?? null;
                            $messageId = $message['id'] ?? null;
                            $timestamp = $message['timestamp'] ?? now();

                            // Store as incoming message
                            WhatsAppMessage::updateOrCreate(
                                ['message_id' => $messageId],
                                [
                                    'whatsapp_provider_id' => $provider?->id,
                                    'from_number' => $from,
                                    'to' => $value['metadata']['display_phone_number'] ?? null,
                                    'message' => $text,
                                    'direction' => 'incoming',
                                    'status' => 'received',
                                    'received_at' => now(),
                                    'response' => json_encode($message),
                                ]
                            );
                        }
                    }

                    // Optionally handle status updates (e.g., message delivered/read)
                    if (isset($value['statuses'])) {
                        foreach ($value['statuses'] as $status) {
                            WhatsAppMessage::where('message_id', $status['id'])->update([
                                'status' => $status['status'],
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json(['success' => true]);
    }
}
