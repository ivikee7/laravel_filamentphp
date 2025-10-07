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
                TextEntry::make('name')
                    ->label('Name'),
                TextEntry::make('academicYear.name')
                    ->label('Academic year')
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
                    ->visible(fn (StudentClass $record): bool => $record->trashed()),
            ]);
    }
}
