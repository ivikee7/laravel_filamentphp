<?php

namespace App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\RelationManagers;

use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\StudentSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class StudentSectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'studentSections';

    protected static ?string $relatedResource = StudentSectionResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
