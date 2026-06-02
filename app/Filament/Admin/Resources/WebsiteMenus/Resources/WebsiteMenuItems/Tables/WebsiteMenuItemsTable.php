<?php

namespace App\Filament\Admin\Resources\WebsiteMenus\Resources\WebsiteMenuItems\Tables;

use App\Filament\Admin\Resources\WebsiteMenus\Resources\WebsiteMenuItems\WebsiteMenuItemResource;
use App\Models\WebsiteMenuItem;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WebsiteMenuItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['page:id,slug', 'parent:id,label']))
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('menu.name')
                    ->label('Menu')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('label')
                    ->label('Menu item')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (WebsiteMenuItem $record): string => $record->destination)
                    ->formatStateUsing(fn (string $state, WebsiteMenuItem $record): string => str_repeat('↳ ', $record->depth).$state),
                TextColumn::make('item_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'category' => 'warning',
                        'page' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('destination')
                    ->label('Link')
                    ->searchable()
                    ->limit(45),
                TextColumn::make('target')
                    ->searchable(),
                TextColumn::make('icon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('sort_order')
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
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('addChild')
                    ->label('')
                    ->icon('heroicon-m-plus')
                    ->tooltip('Add child item')
                    ->url(fn (WebsiteMenuItem $record): string => WebsiteMenuItemResource::getUrl('create', [
                        'menu' => $record->website_menu_id,
                        'parent' => $record->getKey(),
                    ])),
                EditAction::make()
                    ->label('')
                    ->icon('heroicon-m-pencil-square')
                    ->tooltip('Edit'),
                DeleteAction::make()
                    ->label('')
                    ->icon('heroicon-m-trash')
                    ->tooltip('Delete'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
