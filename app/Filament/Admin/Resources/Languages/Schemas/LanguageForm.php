<?php

namespace App\Filament\Admin\Resources\Languages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LanguageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->maxLength(50)
                    ->required(),
            ]);
    }
}
