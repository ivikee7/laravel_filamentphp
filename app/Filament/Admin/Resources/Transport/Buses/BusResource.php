<?php

namespace App\Filament\Admin\Resources\Transport\Buses;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\Transport\Buses\Pages\ListBuses;
use App\Filament\Admin\Resources\Transport\Buses\Pages\CreateBus;
use App\Filament\Admin\Resources\Transport\Buses\Pages\ViewBus;
use App\Filament\Admin\Resources\Transport\Buses\Pages\EditBus;
use App\Filament\Admin\Resources\Transport\BusResource\Pages;
use App\Filament\Admin\Resources\Transport\BusResource\RelationManagers;
use App\Models\TransportBus;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BusResource extends Resource
{
    protected static ?string $model = TransportBus::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Transport';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('registration_number')
                    ->required()
                    ->maxLength(255),
                TextInput::make('model')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('seating_capacity')
                    ->required()
                    ->numeric(),
                Select::make('driver_id')
                    ->relationship('driver', 'name', function ($query) {
                        return $query->role(env("ROLE_DRIVER"));
                    })
                    ->preload()
                    ->searchable()
                    ->required()
                    ->native(false) // Required to allow custom HTML rendering in the dropdown options
                    ->allowHtml()  // Explicitly permits HTML tags within the option labels
                    ->getOptionLabelFromRecordUsing(function (Model $record): string {
                        // $record here refers to the individual 'conductor' model being listed in the dropdown

                        $father_name = htmlspecialchars($record->father_name);
                        $label = htmlspecialchars($record->name); // Always escape the name for security

                        // Apply a warning color (e.g., amber-500) if the conductor is inactive
                        if (!$record->is_active) {
                            // Tailwind CSS classes applied via a span tag
                            return "{$label} | {$father_name} | (Inactive)";
                        }

                        return "{$label} | {$father_name}";
                    }),
                Select::make('conductor_id')
                    ->relationship('conductor', 'name', function ($query) {
                        return $query->role(env("ROLE_CONDUCTOR"));
                    })
                    ->preload()
                    ->searchable()
                    ->native(false) // Required to allow custom HTML rendering in the dropdown options
                    ->allowHtml()  // Explicitly permits HTML tags within the option labels
                    ->getOptionLabelFromRecordUsing(function (Model $record): string {
                        // $record here refers to the individual 'conductor' model being listed in the dropdown

                        $father_name = htmlspecialchars($record->father_name);
                        $label = htmlspecialchars($record->name); // Always escape the name for security

                        // Apply a warning color (e.g., amber-500) if the conductor is inactive
                        if (!$record->is_active) {
                            // Tailwind CSS classes applied via a span tag
                            return "{$label} | {$father_name} | (Inactive)";
                        }

                        return "{$label} | {$father_name}";
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('registration_number')
                    ->label('Vehicle Number')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('model')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('seating_capacity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('driver.name')
                    ->wrap()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('conductor.name')
                    ->wrap()
                    ->numeric()
                    ->sortable(),
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
            'index' => ListBuses::route('/'),
            'create' => CreateBus::route('/create'),
            'view' => ViewBus::route('/{record}'),
            'edit' => EditBus::route('/{record}/edit'),
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
        return TransportBus::count();
    }
}
