<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ModelHasPermissionResource\Pages;
use App\Filament\Admin\Resources\ModelHasPermissionResource\RelationManagers;
use App\Models\ModelHasPermission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModelHasPermissionResource extends Resource
{
    protected static ?string $model = ModelHasPermission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Roles and Permissions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('model_id')
                    ->label('User')
                    ->relationship('model', 'name') // Assumes a 'name' column in permissions table
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('permission_id')
                    ->label('Permission')
                    ->relationship('permission', 'name') // Assumes a 'name' column in permissions table
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('model_type')
                    ->label('Assigned Model Type')
                    ->required()
                    ->options([
                        'App\\Models\\User' => 'User', // Example: Add your User model FQCN here
                        // 'App\\Models\\Post' => 'Post', // Example: Add other models if they have permissions
                        // 'App\\Models\\Team' => 'Team', // Example: Another common model type
                        // Add more model types as needed in your application
                    ])
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('permission.name') // Access permission name via relationship
                ->label('Permission')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('model_type')
                    ->label('Model Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('model_id')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('permission.name')
            ->filters([
                Tables\Filters\SelectFilter::make('permission_id')
                    ->relationship('permission', 'name')
                    ->label('Filter by Permission'),
                Tables\Filters\SelectFilter::make('model_type')
                    ->options(function () {
                        // Dynamically get distinct model types from the database for filters
                        return ModelHasPermission::distinct('model_type')->pluck('model_type', 'model_type')->toArray();
                    })
                    ->label('Filter by Model Type'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListModelHasPermissions::route('/'),
            'create' => Pages\CreateModelHasPermission::route('/create'),
//            'view' => Pages\ViewModelHasPermission::route('/{record}'),
//            'edit' => Pages\EditModelHasPermission::route('/{record}/edit'),
        ];
    }

    public static function getRecordKey(Model $record): string
    {
        // Concatenate the composite primary key components to form a unique string key.
        // Ensure all parts are cast to string to prevent nulls from causing issues.
        $permissionId = (string) $record->permission_id;
        $modelType = (string) $record->model_type;
        $modelId = (string) $record->model_id;

        // TEMPORARY DEBUGGING LINE: THIS IS THE KEY TO FINDING YOUR PROBLEM
        dd("Key components:", $permissionId, $modelType, $modelId, "Full record attributes:", $record->getAttributes());

        return "{$permissionId}-{$modelType}-{$modelId}";
    }

}
