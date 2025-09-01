<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Stores\Resources\Students\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Select::make('registration_id')
                    ->relationship('registration', 'name')
                    ->default(null),
                Select::make('quota_id')
                    ->relationship('quota', 'name')
                    ->required(),
                TextInput::make('admission_number')
                    ->default(null),
                Select::make('current_status')
                    ->options(['active' => 'Active', 'graduated' => 'Graduated', 'left' => 'Left'])
                    ->default('active')
                    ->required(),
                Select::make('tc_status')
                    ->options(['not_requested' => 'Not requested', 'requested' => 'Requested', 'issued' => 'Issued'])
                    ->default('not_requested')
                    ->required(),
                DatePicker::make('leaving_date'),
                Textarea::make('exit_reason')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('local_guardian_user_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('local_guardian_relationship')
                    ->default(null),
                TextInput::make('created_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('updated_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('deleted_by')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
