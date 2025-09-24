<?php

namespace App\Filament\Admin\Resources\Student\UpdateSections;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\Student\UpdateSections\Pages\ListUpdateSections;
use App\Filament\Admin\Resources\Student\UpdateSectionResource\Pages;
use App\Filament\Admin\Resources\Student\UpdateSectionResource\RelationManagers;
use App\Models\AcademicYear;
use App\Models\Section;
use App\Models\Student\UpdateSection;
use App\Models\StudentClass;
use App\Models\StudentSection;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'User';

    protected static ?string $modelLabel = 'Update Sections';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                ImageColumn::make('avatar')
                    ->circular()
                    ->size(50)
                    ->label('Image')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Name')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('father_name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Father Name')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('mother_name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Motner Name')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('currentStudent.currentClassAssignment.academicYear.name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Academic Year')
                    ->toggleable(isToggledHiddenByDefault: false),
                SelectColumn::make('currentStudent.currentClassAssignment.class_id')
                    ->label('Class')
                    ->options(function ($record) {
                        $academicYearId = $record->currentStudent?->currentClassAssignment?->academic_year_id;

                        if (!$academicYearId) {
                            return [];
                        }

                        return StudentClass::where('academic_year_id', $academicYearId)
                            ->with('className')
                            ->get()
                            ->mapWithKeys(function ($studentClass) {
                                return [$studentClass->id => $studentClass->className?->name];
                            })
                            ->toArray();
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        if (!$state) return;

                        $assignment = $record->currentStudent?->currentClassAssignment;
                        if ($assignment) {
                            $assignment->update(['class_id' => $state]);
                        }
                    })
                    ->searchable()
                    ->sortable(),

                SelectColumn::make('currentStudent.currentClassAssignment.section_id')
                    ->label('Section')
                    ->options(function ($record) {
                        $classId = $record->currentStudent?->currentClassAssignment?->class_id;

                        if (!$classId) {
                            return [];
                        }

                        return StudentSection::where('class_id', $classId)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        if (!$state) return;

                        $assignment = $record->currentStudent?->currentClassAssignment;
                        if ($assignment) {
                            $assignment->update(['section_id' => $state]);
                        }
                    })
                    ->searchable()
                    ->sortable(),

            ])
            ->defaultSort('id', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                $activeAcademicYearId = AcademicYear::where('is_active', true)->value('id');

                $query->role('Student')
                    ->whereHas('currentStudent.currentClassAssignment', function ($q) use ($activeAcademicYearId) {
                        $q->where('academic_year_id', $activeAcademicYearId);
                    });
            })
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Suspended',
                    ])
                    ->label('Status')
                    ->default(true),
                SelectFilter::make('currentStudent.currentClassAssignment.class_id')
                    ->label('Class')
                    ->relationship('currentStudent.currentClassAssignment.class.className', 'name'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUpdateSections::route('/'),
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
