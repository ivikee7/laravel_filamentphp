<?php

namespace App\Filament\Admin\Resources\Transport\TransportBuses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class TransportBusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    TextInput::make('registration_number')
                        ->required(),
                    TextInput::make('model')
                        ->default(null),
                    TextInput::make('seating_capacity')
                        ->required()
                        ->numeric(),
                ])->columns(3)->columnSpan(2),
                Select::make('driver_id')
                    ->relationship('driver', 'name')->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} | {$record->father_name} — " . ($record->is_active ? 'Active' : 'Inactive'))
                    ->searchable(['name', 'father_name'])
                    ->preload()
                    ->default(null),
                Select::make('conductor_id')
                    ->relationship('conductor', 'name')->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} | {$record->father_name} — " . ($record->is_active ? 'Active' : 'Inactive'))
                    ->searchable(['name', 'father_name'])
                    ->preload()
                    ->default(null),
            ]);
    }
}
