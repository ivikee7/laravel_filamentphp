<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;

class EnquiryWidget extends ChartWidget
{
    protected ?string $heading = 'Enquiry Widget';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
