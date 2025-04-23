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
        $mode = $request->get('hub_mode');
        $token = $request->get('hub_verify_token');
        $challenge = $request->get('hub_challenge');

        // 🔐 Handle webhook verification only
        if ($mode === 'subscribe' && $token) {
            $provider = WhatsAppProvider::where('verify_token', $token)->first();
            if ($provider) {
                return response($challenge, 200)->header('Content-Type', 'text/plain');
            }
            return response('Unauthorized', 403);
        }

        // 📩 Handle incoming webhook events
        $payload = $request->all();

        if (isset($payload['entry'])) {
            foreach ($payload['entry'] as $entry) {
                foreach ($entry['changes'] as $change) {
                    $value = $change['value'] ?? [];

                    // ✅ Get provider by display phone number
                    $phoneNumber = $value['metadata']['display_phone_number'] ?? null;
                    $provider = WhatsAppProvider::where('phone_number', '91' . $phoneNumber)->first();

                    // Prevent null provider insert
                    if (! $provider) {
                        Log::warning('WhatsApp Provider not found for phone number', [
                            'display_phone_number' => $phoneNumber,
                            'change' => $change,
                        ]);
                        continue;
                    }

                    // ✅ Incoming messages
                    if (isset($value['messages'])) {
                        foreach ($value['messages'] as $message) {
                            WhatsAppMessage::updateOrCreate(
                                ['message_id' => $message['id']],
                                [
                                    'whatsapp_provider_id' => $provider->id,
                                    'from_number' => $message['from'] ?? null,
                                    'to' => $phoneNumber,
                                    'message' => $message['text']['body'] ?? null,
                                    'direction' => 'incoming',
                                    'status' => 'received',
                                    'received_at' => now(),
                                    'response' => json_encode($message),
                                ]
                            );
                        }
                    }

                    // ✅ Status updates (delivered, read, etc.)
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
