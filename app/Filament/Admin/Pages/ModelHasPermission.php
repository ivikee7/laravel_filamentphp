<?php

namespace App\Filament\Admin\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use UnitEnum;

class ModelHasPermission extends Page implements HasTable
{
    protected string $view = 'filament.admin.pages.model-has-permission';

    protected static string|UnitEnum|null $navigationGroup = 'Roles & Permissions';
    protected static ?string $navigationLabel = 'User Has Permissions';
    protected ?string $heading = "User Has Permissions";

    use InteractsWithTable;

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->with('permissions')
            )
            ->columns([
                TextColumn::make('name')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('father_name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mother_name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('roles.name')
                    ->wrap()
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('permissions.name')
                    ->searchable()
                    ->badge()
                    ->color('danger')
                    ->wrap()
                    ->label('Direct Permissions'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->schema([
                    Select::make('permissions')
                        ->relationship('permissions', 'name')
                        ->searchable()
                        ->preload()
                        ->multiple()
                        ->suffixAction(
                            Action::make('clear')
                                ->icon('heroicon-o-x-circle')
                                ->label('Clear')
                                ->action(function (Select $component) {
                                    $component->state(null);
                                })
                        ),
                ])->authorize("update ModelHasPermission"),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()->can(['view-any ModelHasPermission']);
    }
}
