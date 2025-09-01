<?php

namespace App\Filament\Admin\Resources\Languages\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Languages\LanguageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLanguages extends ListRecords
{
    protected static string $resource = LanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
