<?php

namespace App\Filament\Admin\Resources\Enquiries\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Enquiries\EnquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEnquiry extends ViewRecord
{
    protected static string $resource = EnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
