<?php

namespace App\Filament\Admin\Resources\Registrations\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Registrations\RegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegistrations extends ListRecords
{
    protected static string $resource = RegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
