<?php

namespace App\Filament\Admin\Resources\SentMessages\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\SentMessages\SentMessageResource;
use Filament\Actions;
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
