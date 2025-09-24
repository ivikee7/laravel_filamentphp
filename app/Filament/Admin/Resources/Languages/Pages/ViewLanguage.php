<?php

namespace App\Filament\Admin\Resources\Languages\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Languages\LanguageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLanguage extends ViewRecord
{
    protected static string $resource = LanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
