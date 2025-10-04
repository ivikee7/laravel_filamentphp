<?php

namespace App\Filament\Admin\Resources\MessageTemplates\Schemas;

use App\Models\MessageTemplate;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MessageTemplateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('smsProvider.name')
                    ->label('Sms provider'),
                TextEntry::make('name'),
                TextEntry::make('content')
                    ->columnSpanFull(),
                TextEntry::make('variables')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('params')
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('updated_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('deleted_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (MessageTemplate $record): bool => $record->trashed()),
            ]);
    }
}
