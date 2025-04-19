<?php

namespace App\Jobs;

use App\Models\WhatsAppProvider;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsappMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $to, $message, $provider;

    public function __construct($to, $message, WhatsAppProvider $provider)
    {
        $this->to = $to;
        $this->message = $message;
        $this->provider = $provider;
    }

    public function handle()
    {
        app(WhatsAppService::class)->sendMessage($this->to, $this->message, $this->provider);
    }
}
