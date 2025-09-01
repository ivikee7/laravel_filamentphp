<?php

namespace App\Filament\Admin\Resources\StudentSections;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\StudentSections\Pages\ListSections;
use App\Filament\Admin\Resources\StudentSections\Pages\CreateSection;
use App\Filament\Admin\Resources\StudentSections\Pages\ViewSection;
use App\Filament\Admin\Resources\StudentSections\Pages\EditSection;
use App\Filament\Admin\Resources\StudentSectionResource\Pages;
use App\Filament\Admin\Resources\StudentSectionResource\RelationManagers;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentSection;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentSectionResource extends Resource
{
    protected static ?string $model = StudentSection::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'School Management System';

    protected static ?string $navigationLabel = 'Class Sections';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Forms\Components\Select::make('class_id')
                //     ->label('Class')
                //     ->options(function () {
                //         StudentClass::whereHas('academicYear', function (Builder $query) {
                //             $query->where('is_active', true)
                //             ;
                //         })->get()->pluck('className.name', 'id')->toArray();
                //     })
                //     ->required(),
                Select::make('student_class_id')
                    ->label('Class')
                    ->options(function () {
                        return StudentClass::with('className')
                            ->whereHas('academicYear', function ($query) {
                                $query->where('is_active', true);
                            })
                            ->get()
                            ->pluck('className.name', 'id');
                    })
                    ->searchable()
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(50),
                Select::make('room_id')
                    ->relationship('room', 'name', function ($query, $get) {
                        $query->whereDoesntHave('sections');

                        // Ensure the current room_id is always included in the dropdown
                        if ($get('room_id')) {

                            $query->orWhere('id', $get('room_id'));
                        }
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('teacher_id')
                    ->relationship('teacher', 'name', function ($query) {
                        return $query->whereHas('roles', function ($roleQuery) {
                            $roleQuery->where('name', 'Teacher');
                        });
                    })
                    ->label('Teacher')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('class.academicYear.name')
                    ->label('Academic Year')
                    ->searchable()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('class.className.name')
                    ->label('Class')
                    ->searchable()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Section')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('student_class_assignments_count')
                    ->label('Students'),
                TextColumn::make('room.name')
                    ->label('Room')
                    ->wrap()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('teacher.name')
                    ->label('Teacher')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updater.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSections::route('/'),
            'create' => CreateSection::route('/create'),
            'view' => ViewSection::route('/{record}'),
            'edit' => EditSection::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->withCount([
                'studentClassAssignments',
                'studentClassAssignments as student_class_assignments_count' => function (Builder $query) {
                    $query->whereHas('student.user', function (Builder $userQuery) {
                        $userQuery->where('is_active', false);
                        $userQuery->whereNull('deleted_at'); // Only if User model uses SoftDeletes
                    });
                },
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return StudentSection::count();
    }
}
