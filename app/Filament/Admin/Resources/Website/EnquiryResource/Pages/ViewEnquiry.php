<?php

namespace App\Filament\Admin\Resources\Website\EnquiryResource\Pages;

use App\Filament\Admin\Resources\Website\EnquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEnquiry extends ViewRecord
{
    protected static string $resource = EnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
