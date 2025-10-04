<?php

namespace App\Filament\Admin\Resources\Enquiries\Schemas;

use App\Models\Gender;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EnquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Student info')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(50),
                        Select::make('gender_id')
                            ->options(Gender::pluck('name', 'id'))
                            ->required(),
                        DatePicker::make('date_of_birth'),
                    ])->columns(3),
                Section::make('Preveius School info')
                    ->schema([
                        TextInput::make('previous_school')
                            ->maxLength(50)
                            ->default(null),
                        Select::make('previous_class_id')
                            ->label('Previous Class')
                            ->relationship('class', 'name')
                            ->default(null),
                    ])->columns(3),
                Section::make('Admission info')
                    ->schema([
                        Select::make('class_id')
                            ->label('Enquiry Class')
                            ->relationship('class', 'name')
                            ->default(null),
                        Textarea::make('notes')
                            ->required()
                            ->columnSpan(2)
                            ->maxLength(100)
                            ->rows(5)
                            ->cols(1)
                    ])->columns(3),
                Section::make('Parents info')
                    ->schema([
                        TextInput::make('father_name')
                            ->maxLength(50)
                            ->default(null),
                        TextInput::make('mother_name')
                            ->maxLength(50)
                            ->default(null),
                        TextInput::make('email')->email(),
                        TextInput::make('primary_contact_number')->required()
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10),
                        TextInput::make('secondary_contact_number')
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10),
                    ])->columns(3),
                Section::make('Mother info')
                    ->schema([
                        TextInput::make('address')
                            ->maxLength(255)
                            ->default(null),
                        TextInput::make('city')
                            ->maxLength(25)
                            ->default(null),
                        TextInput::make('state')
                            ->maxLength(25)
                            ->default(null),
                        TextInput::make('pin_code')
                            ->numeric()
                            ->rules(['digits:6'])
                            ->minLength(6)
                            ->maxLength(6),
                    ])->columns(3),
                Section::make('Other info')
                    ->schema([
                        Select::make('source')
                            ->options([
                                'OTHER' => 'OTHER',
                                'HOADING' => 'HOADING',
                                'RELEVENT' => 'RELEVENT',
                                'SOCIAL MEDIA' => 'SOCIAL MEDIA',
                                'WEBSITE' => 'WEBSITE',
                            ])
                            ->default(null)
                            ->required(),
                    ])->columns(3),
            ])->columns(1);
    }
}
