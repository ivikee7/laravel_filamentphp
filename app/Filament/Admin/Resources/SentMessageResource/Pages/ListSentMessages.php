<?php

namespace App\Filament\Admin\Resources\SentMessageResource\Pages;

use App\Filament\Admin\Resources\SentMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSentMessages extends ListRecords
{
    protected static string $resource = SentMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
