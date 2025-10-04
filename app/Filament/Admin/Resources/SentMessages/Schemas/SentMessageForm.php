<?php

namespace App\Filament\Admin\Resources\SentMessages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SentMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('response')
                    ->columnSpanFull(),
                TextInput::make('provider_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
