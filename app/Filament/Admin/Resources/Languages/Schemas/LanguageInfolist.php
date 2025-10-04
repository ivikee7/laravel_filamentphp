<?php

namespace App\Filament\Admin\Resources\Languages\Schemas;

use App\Models\Language;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LanguageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('createdBy.name')
                    ->label('Created By')
                    ->placeholder('-'),
                TextEntry::make('updatedBy.name')
                    ->label('Updated By')
                    ->placeholder('-'),
                TextEntry::make('deletedBy.name')
                    ->label('Deleted By')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Language $record): bool => $record->trashed()),
            ]);
    }
}
