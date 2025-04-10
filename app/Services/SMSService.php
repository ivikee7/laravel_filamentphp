<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\SmsProvider;

class SMSService
{
    protected $provider;

    public function __construct($providerId = null)
    {
        $this->provider = SmsProvider::where('id', $providerId)
            ->where('is_active', true)
            ->first();

        if (!$this->provider) {
            throw new \Exception("SMS provider not found or inactive.");
        }
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function sendSms($to, $message, $template = null)
    {
        dd($message);
        // Convert provider params into an associative array
        $params = collect($this->provider->params ?? [])
            ->mapWithKeys(fn($item) => [$item['param_name'] => $item['param_value']]);

        // Merge template params if available
        if ($template && is_array($template->params)) {
            $templateParams = collect($template->params)
                ->mapWithKeys(fn($param) => [$param['param_name'] => $param['param_value']]);
            $params = $params->merge($templateParams);
        }

        $params[$this->provider->to_key] = $to;
        $params[$this->provider->text_key] = $message;

        $url = $this->provider->base_url;

        // $response = $this->provider->method === 'post'
        //     ? Http::post($url, $params->toArray())
        //     : Http::get($url, $params->toArray());

        // $response = $this->provider->method === 'post'
        //     ? Http::asJson()->post($url, $params->toArray()) // ✅ Send JSON body
        //     : Http::get($url, $params->toArray());

        // $response = $this->provider->method === 'post'
        //     ? Http::asForm()->post($url, $params->toArray()) // 🔁 Try asForm()
        //     : Http::get($url, $params->toArray());

        // dd($url, $params->toArray());

        $response = $this->provider->method === 'post'
            ? Http::asForm()->withHeaders($this->provider->headers ?? [])->post($url, $params->toArray())
            : Http::withHeaders($this->provider->headers ?? [])->get($url, $params->toArray());

        return $response->json();
    }
}
