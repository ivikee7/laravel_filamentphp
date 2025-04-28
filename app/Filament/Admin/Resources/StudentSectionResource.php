<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudentSectionResource\Pages;
use App\Filament\Admin\Resources\StudentSectionResource\RelationManagers;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentSectionResource extends Resource
{
    protected static ?string $model = StudentSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'School Management System';

    protected static ?string $navigationLabel = 'Class Sections';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Select::make('class_id')
                //     ->label('Class')
                //     ->options(function () {
                //         StudentClass::whereHas('academicYear', function (Builder $query) {
                //             $query->where('is_active', true)
                //             ;
                //         })->get()->pluck('className.name', 'id')->toArray();
                //     })
                //     ->required(),
                Forms\Components\Select::make('class_id')
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
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50),
                Forms\Components\Select::make('room_id')
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
                Forms\Components\Select::make('teacher_id')
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
                Tables\Columns\TextColumn::make('class.academicYear.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('class.className.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('room.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updater.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListSections::route('/'),
            'create' => Pages\CreateSection::route('/create'),
            'view' => Pages\ViewSection::route('/{record}'),
            'edit' => Pages\EditSection::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return StudentSection::count();
    }
}
