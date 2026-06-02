<?php

namespace App\Filament\Admin\Resources\FeeHeads;

use App\Filament\Admin\Resources\FeeHeads\Pages;
use App\Models\FeeHead;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class FeeHeadResource extends Resource
{
    protected static ?string $model = FeeHead::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('code')->required()->maxLength(50)->unique(ignoreRecord: true),
            TextInput::make('charge_type')->required()->default('recurring'),
            TextInput::make('default_amount')->required()->numeric()->default(0),
            TextInput::make('sort_order')->numeric()->default(0),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('code')->searchable()->sortable()->badge(),
                TextColumn::make('charge_type')->badge(),
                TextColumn::make('default_amount')->money('INR')->sortable(),
                TextColumn::make('sort_order')->sortable(),
                ToggleColumn::make('is_active'),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeeHeads::route('/'),
            'create' => Pages\CreateFeeHead::route('/create'),
            'edit' => Pages\EditFeeHead::route('/{record}/edit'),
        ];
    }
}

