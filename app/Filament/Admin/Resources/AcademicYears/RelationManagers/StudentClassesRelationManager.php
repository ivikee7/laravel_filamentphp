<?php

namespace App\Filament\Admin\Resources\AcademicYears\RelationManagers;

use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\StudentClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class StudentClassesRelationManager extends RelationManager
{
    protected static string $relationship = 'studentClasses';

    protected static ?string $relatedResource = StudentClassResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
