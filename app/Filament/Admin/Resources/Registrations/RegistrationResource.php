<?php

namespace App\Filament\Admin\Resources\Registrations;

use App\Filament\Admin\Resources\Students\StudentResource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use App\Filament\Admin\Resources\Registrations\Pages\ListRegistrations;
use App\Filament\Admin\Resources\Registrations\Pages\CreateRegistration;
use App\Filament\Admin\Resources\Registrations\Pages\ViewRegistration;
use App\Filament\Admin\Resources\Registrations\Pages\EditRegistration;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Schema $schema): Schema
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
                            ->maxLength(255)
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
                            ->required(),
                    ])->columns(3),
                // start only for deleteing enquiry after registration
                TextInput::make('enquiry_id')
                    ->hidden()
                    ->default(fn() => request()->query('enquiry_id')),
                // end only for deleteing enquiry after registration
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable(),
                TextColumn::make('name')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('admissionClass.className.name')
                    ->label('Class')
                    ->sortable(),
                TextColumn::make('father_name')->wrap()
                    ->searchable(),
                TextColumn::make('date_of_birth')->wrap()
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gender.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('father_qualification')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('father_occupation')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('primary_contact_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mother_name')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mother_qualification')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mother_occupation')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('secondary_contact_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('address')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('city')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('state')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pin_code')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('previous_school')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('payment_mode')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('payment_amount')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('payment_notes')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('previousClass.name')
                    ->label('Previous Class')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('updated_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)->wrap(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('admission')
                    ->label('Admission')
                    ->hidden(fn($record) => $record->student()->exists())
                    ->url(fn(Registration $record) => StudentResource::getUrl('create', [
                        'registration_id' => $record->id, // Pass enquiry ID to Registration form
                    ])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    // Export
                    ExportBulkAction::make()
                        ->exporter(RegistrationExporter::class)
                        ->formats([
                            ExportFormat::Xlsx,
                            ExportFormat::Csv,
                        ])
                ]),
                BulkActionGroup::make([
                    ExportBulkAction::make('export-xlsx')
                        ->exporter(RegistrationExporter::class)
                        ->formats([
                            ExportFormat::Xlsx,
                        ])->label('Xlsx'),
                    ExportBulkAction::make('export-csv')
                        ->exporter(RegistrationExporter::class)
                        ->formats([
                            ExportFormat::Csv,
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
            'index' => ListRegistrations::route('/'),
            'create' => CreateRegistration::route('/create'),
            'view' => ViewRegistration::route('/{record}'),
            'edit' => EditRegistration::route('/{record}/edit'),
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
