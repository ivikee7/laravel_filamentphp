<?php

namespace App\Filament\Admin\Resources\WebsiteMenus\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;

class WebsiteMenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Placeholder::make('menu_usage_note')
                    ->label('Usage note')
                    ->content('Main navigation in the Public panel reads from menu ID 1.'),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('location')
                    ->default(null),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
