<?php

namespace App\Filament\Admin\Resources\BloodGroups\Schemas;

use App\Models\BloodGroup;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BloodGroupInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
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
                    ->visible(fn (BloodGroup $record): bool => $record->trashed()),
            ]);
    }
}
