<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreProducts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class StoreProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('academic_year_id')
                    ->label('Academic Year')
                    ->relationship('academicYear', 'name')
                    ->required()
                    ->reactive() // Required to trigger reactivity on dependent fields
                    ->afterStateUpdated(function (Set $set) {
                        $set('student_class_id', null);
                    }),
                Select::make('class_id')
                    ->label('Class')
                    ->relationship('studentClass', 'name', modifyQueryUsing: function (Builder $query, Get $get): Builder {
                        return $query->when($get('academic_year_id'), fn(Builder $q) => $q->where('academic_year_id', $get('academic_year_id')));
                    })
                    ->reactive() // Make this field reactive to trigger an update on the section field.
                    ->required()
                    ->visible(fn(Get $get) => filled($get('academic_year_id'))), // Hide until an academic year is selected
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
