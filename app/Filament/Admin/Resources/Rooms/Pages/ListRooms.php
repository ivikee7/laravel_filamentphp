<?php

namespace App\Filament\Admin\Resources\Rooms\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Rooms\RoomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRooms extends ListRecords
{
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
