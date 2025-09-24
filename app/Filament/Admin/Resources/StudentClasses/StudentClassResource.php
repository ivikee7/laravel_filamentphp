<?php

namespace App\Filament\Admin\Resources\StudentClasses;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\StudentClasses\RelationManagers\SectionsRelationManager;
use App\Filament\Admin\Resources\StudentClasses\Pages\ListClasses;
use App\Filament\Admin\Resources\StudentClasses\Pages\CreateClasses;
use App\Filament\Admin\Resources\StudentClasses\Pages\ViewClasses;
use App\Filament\Admin\Resources\StudentClasses\Pages\EditClasses;
use App\Filament\Admin\Resources\StudentClassResource\Pages;
use App\Filament\Admin\Resources\StudentClassResource\RelationManagers;
use App\Models\StudentClass;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentClassResource extends Resource
{
    protected static ?string $model = StudentClass::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Academic Classes';

    protected static string | \UnitEnum | null $navigationGroup = 'School Management System';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('academic_year_id')
                    ->relationship('academicYear', 'name')
                    ->required(),
                Select::make('class_name_id')
                    ->relationship('className', 'name')
                    ->required(),
//                Forms\Components\TextInput::make('name')
//                    ->required()
//                    ->maxLength(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable(),
                TextColumn::make('academicYear.name')
                    ->searchable(),
                TextColumn::make('className.name')
                    ->searchable(),
                TextColumn::make('createdBy.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updatedBy.name')
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
            SectionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClasses::route('/'),
            'create' => CreateClasses::route('/create'),
            'view' => ViewClasses::route('/{record}'),
            'edit' => EditClasses::route('/{record}/edit'),
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
        return StudentClass::count();
    }
}
