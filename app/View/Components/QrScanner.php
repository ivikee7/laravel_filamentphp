<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class QrScanner extends Component
{
    public string $dispatchTo;

    /**
     * Create a new component instance.
     */
    public function __construct(string $dispatchTo = 'qr-code-scanned')
    {
        $this->dispatchTo = $dispatchTo;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.qr-scanner');
    }
}
