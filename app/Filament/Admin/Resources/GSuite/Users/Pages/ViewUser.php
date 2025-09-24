<?php

namespace App\Filament\Admin\Resources\GSuite\Users\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\GSuite\Users\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
