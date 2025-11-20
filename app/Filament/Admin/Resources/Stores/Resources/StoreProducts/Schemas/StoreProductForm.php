<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreProducts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StoreProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('academic_year_id')
                    ->relationship('academicYear', 'name'),
                Select::make('class_id')
                    ->relationship('class', 'name'),
                TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('₹'),
            ]);
    }
}
