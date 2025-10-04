<?php

namespace App\Filament\Admin\Resources\AcademicYears\Schemas;

use App\Models\AcademicYear;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AcademicYearInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('start_date')
                    ->date(),
                TextEntry::make('end_date')
                    ->date(),
                IconEntry::make('is_active')
                    ->boolean(),
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
                    ->visible(fn (AcademicYear $record): bool => $record->trashed()),
            ]);
    }
}
