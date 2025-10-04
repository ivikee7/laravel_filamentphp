<?php

namespace App\Filament\Admin\Resources\SentMessages\Pages;

use App\Filament\Admin\Resources\SentMessages\SentMessageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSentMessages extends ListRecords
{
    protected static string $resource = SentMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
