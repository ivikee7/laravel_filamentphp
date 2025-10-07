<?php

namespace App\Filament\Admin\Resources\Students\Schemas;

use App\Models\BloodGroup;
use App\Models\Gender;
use App\Models\Registration;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Student info')->schema([
                    Grid::make()
                        ->schema([
                            FileUpload::make('avatar')
                                ->image()
                                ->avatar()
                                ->imageEditor()
                                ->hiddenLabel()
                                ->imagePreviewHeight(250)
                                // disk
                                ->disk('public')
                                ->directory('media/avatar')
                                ->visibility('public')
                                ->alignCenter(),
                            Group::make()
                                ->schema([
                                    Group::make()
                                        ->schema([
                                            TextInput::make('name')
                                                ->required()
                                                ->default(fn($get) => Registration::find(request()->query('registration_id'))?->name)
                                                ->columnSpan(3),
                                            Toggle::make('is_active')->default(true)->inline(false)->required(),
                                        ])
                                        ->columns(4),
                                    Group::make()
                                        ->schema([
                                            Group::make()
                                                ->relationship('gSuiteUser')
                                                ->schema([TextInput::make('email')
                                                    ->label('GSuite Email')
                                                    ->email()
                                                    ->hiddenOn('create')
                                                    ->disabled(fn() => !Filament::auth()->user()?->can('update GSuiteUser')),
                                                    TextInput::make('password')
                                                        ->label('GSuite Password')
                                                        ->password()
                                                        ->revealable()
                                                        ->hiddenOn('create')
                                                        ->disabled(fn() => !Filament::auth()->user()?->can('update GSuiteUser')),
                                                ])->columns(2),
                                        ]),
                                ]),
                        ])
                        ->columns(2),
                    Group::make()
                        ->schema([
                            DatePicker::make('date_of_birth')->required()
                                ->default(fn($get) => Registration::find(request()->query('registration_id'))?->date_of_birth),
                            Select::make('gender_id')
                                ->options(Gender::pluck('name', 'id'))
                                ->label('Gender')
                                ->required()
                                ->default(fn($get) => Registration::find(request()->query('registration_id'))?->gender_id),
                            Select::make('blood_group_id')
                                ->options(BloodGroup::pluck('name', 'id'))
                                ->label('Blood Group')
                                ->required()
                                ->default(fn($get) => Registration::find(request()->query('registration_id'))?->blood_group_id),
                        ])->columns(4),
                ])->columnSpanFull(),

                Section::make('Admission Info')
                    ->relationship('student')
                    ->schema([
                        Group::make()
                            ->schema([
                                Select::make('quota_id')
                                    ->label('Quota')
                                    ->relationship('quota', 'name')
                                    ->required(),
                            ])->columns(3),
                        Group::make()
                            ->relationship('classAssignment')
                            ->schema([
                                Select::make('academic_year_id')
                                    ->label('Academic Year')
                                    ->relationship('academicYear', 'name')
                                    ->required()
                                    ->reactive(),
                                Select::make('student_class_id')
                                    ->label('Class')
                                    ->relationship('class', 'name', modifyQueryUsing: function (Builder $builder, Get $get) {
                                        if ($get('academic_year_id')) {
                                            $builder->whereHas('academicYears', function (Builder $query) use ($get) {
                                                $query->where('academic_year_id', $get('academic_year_id'));
                                            });
                                        }
                                    })
                                    ->reactive()
                                    ->required(),
                                Select::make('section_id')
                                    ->label('Section')
                                    ->relationship('studentSection', 'name')
                                    ->required(),
                            ])->columns(3),
                    ])->columnSpanFull(),

                Section::make('Parents info')
                    ->schema([
                        TextInput::make('father_name')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->father_name),
                        TextInput::make('mother_name')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->mother_name),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Contact info')
                    ->schema([
                        TextInput::make('primary_contact_number')
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10)
                            ->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->primary_contact_number),
                        TextInput::make('secondary_contact_number')
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10)
                            ->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->secondary_contact_number),
                        TextInput::make('email')->email()->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->email),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Address')
                    ->schema([
                        TextInput::make('address')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->address),
                        TextInput::make('city')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->city),
                        TextInput::make('state')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->state),
                        TextInput::make('pin_code')
                            ->numeric()
                            ->rules(['digits:6'])
                            ->minLength(6)
                            ->maxLength(6)
                            ->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->pin_code),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Local Guardian info')
                    ->relationship('student')
                    ->schema([
                        Select::make('local_guardian_user_id')
                            ->relationship('localGuardian', 'name')
                            ->searchable()
                            ->preload()
                            ->default(null),
                        TextInput::make('local_guardian_relationship')
                            ->default(null),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Group::make()
                    ->relationship('student')
                    ->schema([
                        Repeater::make('Siblings info')
                            ->relationship('siblings')
                            ->schema([
                                Select::make('sibling_id')
                                    ->label('Siblings')
                                    ->relationship('siblings', 'name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ])
                    ]),

                // start only for deleteing registration after admission
                Group::make()
                    ->schema([
                        TextInput::make('registration_id')
                            ->hidden()
                            ->default(fn() => request()->query('registration_id')),
                    ])
                // end only for deleteing registration after admission
            ]);
    }
}
