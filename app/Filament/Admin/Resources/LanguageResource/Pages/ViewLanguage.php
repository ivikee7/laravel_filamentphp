<?php

namespace App\Filament\Admin\Resources\LanguageResource\Pages;

use App\Filament\Admin\Resources\LanguageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLanguage extends ViewRecord
{
    protected static string $resource = LanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
