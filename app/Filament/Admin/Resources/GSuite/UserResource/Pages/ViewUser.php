<?php

namespace App\Filament\Admin\Resources\GSuite\UserResource\Pages;

use App\Filament\Admin\Resources\GSuite\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
