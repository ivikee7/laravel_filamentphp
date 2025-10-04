<?php

namespace App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Schemas;

use App\Models\StudentSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StudentSectionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('room.name')
                    ->label('Room'),
                TextEntry::make('teacher.name')
                    ->label('Teacher')
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
                    ->visible(fn(StudentSection $record): bool => $record->trashed()),
            ]);
    }
}
