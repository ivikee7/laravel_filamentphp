<?php

namespace App\Filament\Resources\School\EnquiryResource\Pages;

use App\Filament\Resources\School\EnquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnquiries extends ListRecords
{
    protected static string $resource = EnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
