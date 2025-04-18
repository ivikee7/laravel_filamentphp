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
        Log::info('🔥 WhatsApp Webhook Hit');
        Log::info('Raw Input: ' . file_get_contents('php://input'));
        Log::info('Webhook Payload:', $request->all());


        return response()->json(['status' => 'received'], 200);
    }

    public function verify(Request $request)
    {
        $mode = $request->get('hub_mode');
        $token = $request->get('hub_verify_token');
        $challenge = $request->get('hub_challenge');

        $provider = WhatsAppProvider::where('verify_token', $token)->first();

        if ($mode === 'subscribe' && $provider) {
            return response($challenge, 200);
        }

        return response('Unauthorized', 403);
    }
}
