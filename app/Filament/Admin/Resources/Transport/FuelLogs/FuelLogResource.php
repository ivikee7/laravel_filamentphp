<?php

namespace App\Filament\Admin\Resources\Transport\FuelLogs;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\Transport\FuelLogs\Pages\ListFuelLogs;
use App\Filament\Admin\Resources\Transport\FuelLogs\Pages\CreateFuelLog;
use App\Filament\Admin\Resources\Transport\FuelLogs\Pages\ViewFuelLog;
use App\Filament\Admin\Resources\Transport\FuelLogs\Pages\EditFuelLog;
use App\Filament\Admin\Resources\Transport\FuelLogResource\Pages;
use App\Filament\Admin\Resources\Transport\FuelLogResource\RelationManagers;
use App\Models\TransportFuelLog;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FuelLogResource extends Resource
{
    protected static ?string $model = TransportFuelLog::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Transport';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('bus_id')
                    ->relationship('bus', 'registration_number', function ($query) {
                        return $query->where('is_active', true);
                    })
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TextInput::make('liters')
                    ->required()
                    ->numeric(),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->prefix('₹'),
                TextInput::make('filled_by')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bus.registration_number')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('liters')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cost')
                    ->money()
                    ->sortable(),
                TextColumn::make('filled_by')
                    ->wrap()
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFuelLogs::route('/'),
            'create' => CreateFuelLog::route('/create'),
            'view' => ViewFuelLog::route('/{record}'),
            'edit' => EditFuelLog::route('/{record}/edit'),
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
        return TransportFuelLog::count();
    }
}
