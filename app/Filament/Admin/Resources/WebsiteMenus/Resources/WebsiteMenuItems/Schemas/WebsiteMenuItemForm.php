<?php

namespace App\Filament\Admin\Resources\WebsiteMenus\Resources\WebsiteMenuItems\Schemas;

use App\Models\WebsiteMenuItem;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class WebsiteMenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hierarchy')
                    ->schema([
                        Select::make('website_menu_id')
                            ->label('Menu')
                            ->relationship('menu', 'name')
                            ->required()
                            ->default(fn (): ?int => request()->integer('menu') ?: null)
                            ->preload()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('parent_id', null)),
                        Select::make('parent_id')
                            ->label('Parent menu item')
                            ->relationship(
                                'parent',
                                'label',
                                modifyQueryUsing: fn (Builder $query, Get $get): Builder => $query
                                    ->when($get('website_menu_id'), fn (Builder $q, $menuId): Builder => $q->where('website_menu_id', $menuId))
                                    ->orderBy('sort_order')
                            )
                            ->getOptionLabelFromRecordUsing(fn (WebsiteMenuItem $record): string => $record->label_with_depth)
                            ->searchable()
                            ->preload()
                            ->default(fn (): ?int => request()->integer('parent') ?: null)
                            ->helperText('Leave blank for top-level menu items.'),
                        Select::make('website_page_id')
                            ->label('Website page')
                            ->relationship('page', 'title')
                            ->searchable()
                            ->preload()
                            ->default(null)
                            ->helperText('Optional: selects an internal page.'),
                    ])
                    ->columns(3),
                Section::make('Link and display')
                    ->schema([
                        TextInput::make('label')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('url')
                            ->url()
                            ->default(null)
                            ->helperText('Optional if a Website page is selected.'),
                        Select::make('target')
                            ->required()
                            ->options([
                                '_self' => 'Same tab',
                                '_blank' => 'New tab',
                            ])
                            ->default('_self'),
                        TextInput::make('icon')
                            ->default(null),
                        Toggle::make('is_active')
                            ->required()
                            ->default(true),
                        TextInput::make('sort_order')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(3),
            ]);
    }
}
