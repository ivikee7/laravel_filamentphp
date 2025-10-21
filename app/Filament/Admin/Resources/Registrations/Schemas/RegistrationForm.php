<?php

namespace App\Filament\Admin\Resources\Registrations\Schemas;

use App\Models\AcademicYear;
use App\Models\Enquiry;
use App\Models\Gender;
use App\Models\StudentClass;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Student info')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(25)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->name),
                        DatePicker::make('date_of_birth')
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->date_of_birth),
                        Select::make('gender_id')
                            ->options(Gender::pluck('name', 'id'))
                            ->required()
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->gender_id),
                    ])->columns(3),
                Section::make('Previous School info')
                    ->schema([
                        TextInput::make('previous_school')
                            ->maxLength(100)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->previous_school),
                        Select::make('previous_class_id')
                            ->relationship('previousClass', 'name')
                            ->label('Previous Class')
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->previous_class_id),
                    ])->columns(3),
                Section::make('Admission info')
                    ->schema([
                        Select::make('enquiryClass')
                            ->label('Enquiry for admission in')
                            ->relationship('class', 'name')
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->class_id)
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn($get) => filled($get('enquiry_id'))),

                        Select::make('academic_year_id')
                            ->label('Academic Year')
                            ->options(AcademicYear::pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn($set) => [
                                $set('class_id', null),
                            ]),

                        Select::make('class_id')
                            ->label('Registration Class')
                            ->options(function ($get) {
                                $academicYearId = $get('academic_year_id'); // Get the academic_year_id dynamically
                                return $academicYearId
                                    ? StudentClass::with('className')  // Eager load the className relationship
                                    ->where('academic_year_id', $academicYearId)
                                        ->get()
                                        ->pluck('className.name', 'id') // Pluck name from className relation and id as value
                                    : []; // Empty array if no academic_year_id is set
                            })
                            ->required()
                            ->reactive()
                            ->default(function ($get) {
                                // Default the class_id based on the enquiry record, pulling the class_id from the Enquiry model
                                return Enquiry::find(request()->query('enquiry_id'))?->class_id;
                            }),
                    ])->columns(3),
                Section::make('Father info')
                    ->schema([
                        TextInput::make('father_name')
                            ->maxLength(50)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->father_name),
                        TextInput::make('father_qualification')
                            ->maxLength(50)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->father_qualification),
                        TextInput::make('father_occupation')
                            ->maxLength(50)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->father_occupation),
                    ])->columns(3),
                Section::make('Mother info')
                    ->schema([
                        TextInput::make('mother_name')
                            ->maxLength(50)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->mother_name),
                        TextInput::make('mother_qualification')
                            ->maxLength(50)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->mother_qualification),
                        TextInput::make('mother_occupation')
                            ->maxLength(50)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->mother_occupation),
                    ])->columns(3),
                Section::make('Contact info')
                    ->schema([
                        TextInput::make('primary_contact_number')
                            ->tel()
                            ->rules(['digits:10'])
                            ->required()
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->primary_contact_number),
                        TextInput::make('secondary_contact_number')
                            ->tel()
                            ->rules(['digits:10'])
                            ->required()
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->secondary_contact_number),
                        TextInput::make('email')->email()->required()
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->email),
                    ])->columns(3),
                Section::make('Address')
                    ->schema([
                        TextInput::make('address')
                            ->maxLength(150)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->address),
                        TextInput::make('city')
                            ->maxLength(25)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->city),
                        TextInput::make('state')
                            ->maxLength(25)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->state),
                        TextInput::make('pin_code')
                            ->numeric()
                            ->rules(['digits:6'])
                            ->required()
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->pin_code),
                    ])->columns(3),
                Section::make('Payment info')
                    ->schema([
                        TextInput::make('payment_amount')
                            ->numeric()
                            ->required(),
                        Select::make('payment_mode')
                            ->options([
                                'Online' => 'Online',
                                'QR_Code' => 'QR_Code',
                                'Cash' => 'Cash',
                            ])
                            ->required(),
                        TextInput::make('payment_notes')
                            ->maxLength(50)
                            ->required(),
                    ])->columns(3),
                // start only for deleteing enquiry after registration
                TextInput::make('enquiry_id')
                    ->hidden()
                    ->default(fn() => request()->query('enquiry_id')),
                // end only for deleteing enquiry after registration
            ])->columns(1);
    }
}
