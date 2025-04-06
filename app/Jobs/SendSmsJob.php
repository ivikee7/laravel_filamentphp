<?php

namespace App\Jobs;

use App\Models\SentMessage;
use App\Services\SMSService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use InteractsWithQueue;
    use SerializesModels;

    protected $phone;
    protected $message;
    protected $provider;
    protected $template;

    /**
     * Create a new job instance.
     */
    public function __construct($phone, $message, $provider, $template = null)
    {
        $this->phone = $phone;
        $this->message = $message;
        $this->provider = $provider;
        $this->template = $template;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $smsService = new SMSService($this->provider);
        $response = $smsService->sendSms($this->phone, $this->message, $this->template = null);

        // Store message and response
        SentMessage::create([
            'phone' => $this->phone,
            'message' => $this->message,
            'provider_id' => $this->provider->getProvider()->id ?? null,
            'template_id' => $template->id ?? null,
            'response' => $response,
        ]);

        // Optionally notify based on response (if your SMSService exposes keys to show)
        // ...

        // return $response;
    }
}
