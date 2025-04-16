<?php

namespace App\Filament\Admin\Resources\GSuite\UserResource\Pages;

use App\Filament\Admin\Resources\GSuite\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
