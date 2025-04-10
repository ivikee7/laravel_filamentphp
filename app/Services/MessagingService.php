<?php

namespace App\Services;

use App\Jobs\SendSmsJob;
use App\Models\SentMessage;

class MessagingService
{
    // public static function send($phone, $message, SMSService $provider, $template = null): void
    // {
    //     SendSmsJob::dispatch($phone, $message, $provider, $template);
    // }

    public static function send($phone, $message, SMSService $provider, $template = null)
    {
        $response = $provider->sendSms($phone, $message, $template);

        // Store message and response
        SentMessage::create([
            'phone' => $phone,
            'message' => $message,
            'provider_id' => $provider->getProvider()->id ?? null,
            'template_id' => $template->id ?? null,
            'response' => $response,
        ]);

        // Optionally notify based on response (if your SMSService exposes keys to show)
        // ...

        return $response;
    }
}
