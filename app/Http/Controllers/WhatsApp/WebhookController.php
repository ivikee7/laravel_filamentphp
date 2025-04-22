<?php

namespace App\Http\Controllers\WhatsApp;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Webhook Received:', $request->all());

        return response()->json(['status' => 'received'], 200);
    }

    public function verify(Request $request)
    {
        // $mode = $request->get('hub_mode');
        // $token = $request->get('hub_verify_token');
        // $challenge = $request->get('hub_challenge');

        $mode = '';
        $token = '';
        $challenge = '';

        Log::info('Webhook verify:', '');

        $provider = WhatsAppProvider::where('verify_token', 'secret_whatsapp_token_srcspatna_com')->first();

        if ($mode === 'subscribe' && $provider) {
            Log::info('Webhook verify:', $challenge);
            Log::info('Webhook provider:', $provider);
            return response($challenge, 200);
        }

        return response('Unauthorized', 403);
    }
}
