<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class QrCodeScanner extends Component
{
    public ?string $scannedUrl = null;

    public ?string $error = null;

    #[On('redirect-to-qr')]
    public function redirectToQrUrl(string $url): void
    {
        try {
            $parsed = parse_url($url);
            if ($parsed === false || !isset($parsed['scheme'])) {
                $this->error = 'Invalid URL decoded from QR code.';
                return;
            }
            $this->scannedUrl = $url;
            $this->redirect($url);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.qr-code-scanner');
    }
}

