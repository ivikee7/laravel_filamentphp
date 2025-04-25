<?php

namespace App\Filament\Admin\Resources\Student;

use App\Filament\Admin\Resources\Student\UpdateSectionResource\Pages;
use App\Filament\Admin\Resources\Student\UpdateSectionResource\RelationManagers;
use App\Models\AcademicYear;
use App\Models\Section;
use App\Models\Student\UpdateSection;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UpdateSectionResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'User';

    protected static ?string $modelLabel = 'Update Sections';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->size(50)
                    ->label('Image')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Name')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('father_name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Father Name')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('mother_name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Motner Name')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('currentStudent.currentClassAssignment.academicYear.name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Academic Year')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\SelectColumn::make('currentStudent.currentClassAssignment.class_id')
                    ->label('Class')
                    ->options(function ($record) {
                        $academicYearId = $record->currentStudent?->currentClassAssignment?->academic_year_id;

                        return $academicYearId
                            ? \App\Models\Classes::where('academic_year_id', $academicYearId)->pluck('name', 'id')->toArray()
                            : [];
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        if (!$state) return; // Don't update if state is null

                        $assignment = $record->currentStudent->currentClassAssignment;
                        if ($assignment) {
                            $assignment->update(['class_id' => $state]);
                        }
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('currentStudent.currentClassAssignment.section_id')
                    ->label('Section')
                    ->options(function ($record) {
                        // Get the class_id from the currentClassAssignment
                        $classId = $record->currentStudent?->currentClassAssignment?->class_id;

                        // If class_id exists, return sections associated with the class
                        return $classId
                            ? \App\Models\Section::where('class_id', $classId)->pluck('name', 'id')->toArray()
                            : [];
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        // Update the section_id in the currentClassAssignment
                        $assignment = $record->currentStudent?->currentClassAssignment;
                        if ($assignment) {
                            $assignment->update(['section_id' => $state]);
                        }
                    }),
            ])
            ->defaultSort('id', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                $activeAcademicYearId = \App\Models\AcademicYear::where('is_active', true)->value('id');

                $query->role('Student')
                    ->whereHas('currentStudent.currentClassAssignment', function ($q) use ($activeAcademicYearId) {
                        $q->where('academic_year_id', $activeAcademicYearId);
                    });
            })
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Suspended',
                    ])
                    ->label('Status')
                    ->default(true),
                Tables\Filters\SelectFilter::make('currentStudent.currentClassAssignment.class_id')
                    ->label('Class')
                    ->relationship('currentStudent.currentClassAssignment.class', 'name'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUpdateSections::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canCreate(): bool
    {
        return false; // Hide the "Create" button
    }
}
