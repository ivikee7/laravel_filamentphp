<?php

namespace App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Schemas;

use App\Models\StudentClass;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StudentClassInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('className.name')
                    ->label('Class name'),
                TextEntry::make('academic_year_id')
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
                    ->visible(fn (StudentClass $record): bool => $record->trashed()),
            ]);
    }
}
