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
                TextEntry::make('user.name')
                ->label('Name'),
                TextEntry::make('transportRoute.name')
                    ->label('Route name'),
                TextEntry::make('transportStoppage.name')
                    ->placeholder('-'),
                TextEntry::make('contact_number')
                    ->placeholder('-'),
                TextEntry::make('remarks')
                    ->placeholder('-'),
                TextEntry::make('createdBy.name')
                    ->label('Created by')
                    ->placeholder('-'),
                TextEntry::make('updatedBy.name')
                    ->label('Updated by')
                    ->placeholder('-'),
                TextEntry::make('deletedBy.name')
                    ->label('Deleted by')
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
