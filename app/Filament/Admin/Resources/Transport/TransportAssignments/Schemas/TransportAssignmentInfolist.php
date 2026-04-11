<?php

namespace App\Filament\Admin\Resources\Transport\TransportAssignments\Schemas;

use App\Models\TransportAssignment;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransportAssignmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('route_id')
                    ->numeric(),
                TextEntry::make('stoppage_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('bus_id')
                    ->numeric()
                    ->placeholder('-'),
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
                    ->visible(fn (TransportAssignment $record): bool => $record->trashed()),
            ]);
    }
}
