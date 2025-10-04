<?php

namespace App\Filament\Admin\Resources\SentMessages\Schemas;

use App\Models\SentMessage;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SentMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('phone'),
                TextEntry::make('message')
                    ->columnSpanFull(),
                TextEntry::make('response')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('provider.name')
                    ->label('Provider'),
                TextEntry::make('created_by')
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
                    ->visible(fn (SentMessage $record): bool => $record->trashed()),
            ]);
    }
}
