<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RegistrationResource\Pages;
use App\Filament\Admin\Resources\RegistrationResource\RelationManagers;
use App\Filament\Exports\RegistrationExporter;
use App\Models\AcademicYear;
use App\Models\Enquiry;
use App\Models\Gender;
use App\Models\Registration;
use App\Models\StudentClass;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Symfony\Contracts\Service\Attribute\Required;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Student info')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(25)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->name),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->date_of_birth),
                        Forms\Components\Select::make('gender_id')
                            ->options(Gender::pluck('name', 'id'))
                            ->required()
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->gender_id),
                    ])->columns(3),
                Section::make('Previous School info')
                    ->schema([
                        Forms\Components\TextInput::make('previous_school')
                            ->maxLength(100)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->previous_school),
                        Forms\Components\Select::make('previous_class_id')
                            ->relationship('previousClass', 'name')
                            ->label('Previous Class')
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->previous_class_id),
                    ])->columns(3),
                Section::make('Admission info')
                    ->schema([
                        Forms\Components\Select::make('enquiryClass')
                            ->label('Enquiry for admission in')
                            ->relationship('class', 'name')
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->class_id)
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn($get) => filled($get('enquiry_id'))),

                        Forms\Components\Select::make('academic_year_id')
                            ->label('Academic Year')
                            ->options(AcademicYear::pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn($set) => [
                                $set('class_id', null),
                            ]),

                        Forms\Components\Select::make('class_id')
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
                        Forms\Components\TextInput::make('father_name')
                            ->maxLength(50)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->father_name),
                        Forms\Components\TextInput::make('father_qualification')
                            ->maxLength(50)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->father_qualification),
                        Forms\Components\TextInput::make('father_occupation')
                            ->maxLength(50)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->father_occupation),
                    ])->columns(3),
                Section::make('Mother info')
                    ->schema([
                        Forms\Components\TextInput::make('mother_name')
                            ->maxLength(50)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->mother_name),
                        Forms\Components\TextInput::make('mother_qualification')
                            ->maxLength(50)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->mother_qualification),
                        Forms\Components\TextInput::make('mother_occupation')
                            ->maxLength(50)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->mother_occupation),
                    ])->columns(3),
                Section::make('Contact info')
                    ->schema([
                        Forms\Components\TextInput::make('primary_contact_number')
                            ->tel()
                            ->rules(['digits:10'])
                            ->required()
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->primary_contact_number),
                        Forms\Components\TextInput::make('secondary_contact_number')
                            ->tel()
                            ->rules(['digits:10'])
                            ->required()
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->secondary_contact_number),
                        Forms\Components\TextInput::make('email')->email()->required()
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->email),
                    ])->columns(3),
                Section::make('Address')
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->maxLength(255)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->address),
                        Forms\Components\TextInput::make('city')
                            ->maxLength(25)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->city),
                        Forms\Components\TextInput::make('state')
                            ->maxLength(25)
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->state),
                        Forms\Components\TextInput::make('pin_code')
                            ->numeric()
                            ->rules(['digits:6'])
                            ->required()
                            ->default(fn($get) => Enquiry::find(request()->query('enquiry_id'))?->pin_code),
                    ])->columns(3),
                Section::make('Payment info')
                    ->schema([
                        Forms\Components\TextInput::make('payment_amount')
                            ->numeric()
                            ->required(),
                        Forms\Components\Select::make('payment_mode')
                            ->options([
                                'Online' => 'Online',
                                'QR_Code' => 'QR_Code',
                                'Cash' => 'Cash',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('payment_notes')
                            ->required(),
                    ])->columns(3),
                // start only for deleteing enquiry after registration
                Forms\Components\TextInput::make('enquiry_id')
                    ->hidden()
                    ->default(fn() => request()->query('enquiry_id')),
                // end only for deleteing enquiry after registration
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('admissionClass.className.name')
                    ->label('Class')
                    ->sortable(),
                Tables\Columns\TextColumn::make('father_name')->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')->wrap()
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('gender.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('father_qualification')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('father_occupation')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('primary_contact_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mother_name')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mother_qualification')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mother_occupation')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('secondary_contact_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('address')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pin_code')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('previous_school')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_mode')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('previousClass.name')
                    ->label('Previous Class')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)->wrap(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('admission')
                    ->label('Admission')
                    ->url(fn(Registration $record) => StudentResource::getUrl('create', [
                        'registration_id' => $record->id, // Pass enquiry ID to Registration form
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    // Export
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(RegistrationExporter::class)
                        ->formats([
                            Actions\Exports\Enums\ExportFormat::Xlsx,
                            Actions\Exports\Enums\ExportFormat::Csv,
                        ])
                ]),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make('export-xlsx')
                        ->exporter(RegistrationExporter::class)
                        ->formats([
                            Actions\Exports\Enums\ExportFormat::Xlsx,
                        ])->label('Xlsx'),
                    Tables\Actions\ExportBulkAction::make('export-csv')
                        ->exporter(RegistrationExporter::class)
                        ->formats([
                            Actions\Exports\Enums\ExportFormat::Csv,
                        ])->label('CSV'),
                ])
                    ->label('Export'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'view' => Pages\ViewRegistration::route('/{record}'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return Registration::count();
    }
}
