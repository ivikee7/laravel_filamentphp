<?php

namespace App\Filament\Admin\Resources\BloodGroups\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BloodGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->maxLength(10)
                    ->required(),
            ]);
    }
}
