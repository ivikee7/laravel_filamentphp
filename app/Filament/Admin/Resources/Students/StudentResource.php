<?php

namespace App\Filament\Admin\Resources\Students;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\DB;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Textarea;
use App\Filament\Admin\Resources\Students\Pages\ListStudents;
use App\Filament\Admin\Resources\Students\Pages\CreateStudent;
use App\Filament\Admin\Resources\Students\Pages\ViewStudent;
use App\Filament\Admin\Resources\Students\Pages\EditStudent;
use App\Filament\Admin\Resources\StudentResource\Pages;
use App\Filament\Admin\Resources\Students\RelationManagers\CartRelationManager;
use App\Filament\Admin\Resources\Students\RelationManagers\InvoicesRelationManager;
use App\Filament\Admin\Resources\Students\RelationManagers\ProductsRelationManager;
use App\Filament\Exports\RegistrationExporter;
use App\Filament\Exports\StudentExporter;
use App\Filament\Exports\UserExporter;
use App\Models\AcademicYear;
use App\Models\BloodGroup;
use App\Models\StudentClass;
use App\Models\Gender;
use App\Models\MessageTemplate;
use App\Models\Quota;
use App\Models\Registration;
use App\Models\SmsProvider;
use App\Models\Student;
use App\Models\StudentSection;
use App\Models\User;
use App\Models\WhatsAppProvider;
use App\Services\SMSService;
use App\Services\WhatsApp\WhatsAppService;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'User';

    protected static ?string $modelLabel = 'Student';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Student info')
                    ->schema([
                        Grid::make(2) // Create a 2-column layout
                        ->schema([
                            // Left Column: Centered Large Avatar
                            Group::make()
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
                                ])
                                ->columnSpan(1)
                                ->extraAttributes([
                                    'style' => 'display: flex; align-items: center; justify-content: center; height: 100%;',
                                ]),

                            // Right Column: Other Input Fields
                            Group::make()
                                ->schema([
                                    Group::make([
                                        TextInput::make('name')
                                            ->required()
                                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->name)
                                            ->columnSpan(3),
                                        Toggle::make('is_active')->default(true)->inline(false)->required(),
                                    ])->columns(4),

                                    Group::make([
                                        TextInput::make('email')
                                            ->label('GSuite Email')
                                            ->email()
                                            ->visibleOn(['view', 'edit'])
                                            ->disabled(fn() => !Filament::auth()->user()?->can('update GSuiteUser')),
                                        TextInput::make('password')
                                            ->label('GSuite Password')
                                            ->password()
                                            ->revealable()
                                            ->visibleOn(['view', 'edit'])
                                            ->disabled(fn() => !Filament::auth()->user()?->can('update GSuiteUser')),
                                    ])->relationship('gSuiteUser')->columns(2),
                                ])
                                ->columnSpan(1),
                        ]),
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
                    ]),

                Section::make('Admission Info')
                    ->schema([
                        Group::make()
                            ->relationship('student')
                            ->schema([
                                Select::make('quota_id')
                                    ->label('Quota')
                                    ->relationship('quota', 'name')
                                    ->required(),
                            ])->columns(3),
                        Group::make()
                            ->relationship('student')
                            ->schema([
                                Group::make()
                                    ->relationship('classAssignment')
                                    ->schema([
                                        Select::make('academic_year_id')
                                            ->label('Academic Year')
                                            ->relationship('academicYear', 'name')
                                            ->required(),
                                        Select::make('class_id')
                                            ->label('Class')
                                            ->relationship('class.className', 'name')
                                            ->required(),
                                        Select::make('section_id')
                                            ->label('Section')
                                            ->relationship('section', 'name')
                                            ->required(),
                                    ])->columns(3),
                            ]),
                    ]),

//                        Group::make()
//                            ->relationship('student')
//                            ->schema([
//                                Group::make()
//                                    ->relationship('classAssignment')
//                                    ->schema([
//                                        Select::make('academic_year_id')
//                                            ->label('Academic Year')
//                                            ->options(AcademicYear::pluck('name', 'id'))
//                                            ->required()
//                                            ->reactive()
//                                            ->afterStateUpdated(fn($set) => [
//                                                $set('class_id', null),
//                                                $set('section_id', null),
//                                            ])
////                                            ->disabledOn('edit')
//                                            ->default(fn() => Registration::find(request()->query('registration_id'))?->academic_year_id),
//
//                                        Select::make('class_id')
//                                            ->label('Student Class')
//                                            ->options(function ($get) {
//                                                $academicYearId = $get('academic_year_id');
//                                                if (!$academicYearId) {
//                                                    return [];
//                                                }
//
//                                                return StudentClass::with('className')
//                                                    ->where('academic_year_id', $academicYearId)
//                                                    ->get()
//                                                    ->pluck('className.name', 'id')
//                                                    ->toArray();
//                                            })
//                                            ->required()
//                                            ->reactive()
//                                            ->afterStateUpdated(fn($set) => $set('section_id', null))
////                                            ->disabledOn('edit')
//                                            ->default(fn() => Registration::find(request()->query('registration_id'))?->class_id),
//
//                                        Select::make('section_id')
//                                            ->label('Section')
//                                            ->options(function ($get) {
//                                                $classId = $get('class_id');
//                                                if (!$classId) {
//                                                    return [];
//                                                }
//
//                                                return StudentSection::where('student_class_id', $classId)
//                                                    ->pluck('name', 'id')
//                                                    ->toArray();
//                                            })
//                                            ->default(fn() => Registration::find(request()->query('registration_id'))?->section_id),
//                                    ])
//                                    ->columns(1),
//                            ]),

//                    ]),

                Section::make('Parents info')
                    ->schema([
                        TextInput::make('father_name')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->father_name),
                        TextInput::make('mother_name')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->mother_name),
                    ])->columns(2),
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
                    ])->columns(2),
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
                    ])->columns(2),
                Section::make('Record creation info')
                    ->schema([
                        TextInput::make('createdBy.name')->label('Created By'),
                        TextInput::make('updatedBy.name')->label('Updated By'),
                        TextInput::make('deletedBy.name')->label('Deleted By'),
                        TextInput::make('created_at'),
                        TextInput::make('updated_at'),
                        TextInput::make('deleted_at'),
                    ])
                    ->columns(3)
                    ->visibleOn(['view']),

                // start only for deleteing registration after admission
                TextInput::make('registration_id')
                    ->hidden()
                    ->default(fn() => request()->query('registration_id')),
                // end only for deleteing registration after admission
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                ImageColumn::make('avatar')
                    ->circular()
                    ->size(50)
                    ->label('Image')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Name')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('father_name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Father Name')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('mother_name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Mother Name')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.classAssignment.class.className.name')
                    ->searchable()
                    ->sortable()
                    ->label('Class')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('student.classAssignment.section.name')
                    ->searchable()
                    ->sortable()
                    ->label('Section')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('date_of_birth')
                    ->searchable()
                    ->sortable()
                    ->label('DOB')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.classAssignment.academicYear.name')
                    ->searchable()
                    ->sortable()
                    ->label('Academic Year')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('primary_contact_number')
                    ->searchable()
                    ->sortable()
                    ->label('Primary Contact')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('secondary_contact_number')
                    ->searchable()
                    ->sortable()
                    ->label('Secondary Contact')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('full_address')
                    ->label('Address')
                    ->getStateUsing(function ($record) {
                        return collect([
                            $record->address,
                            $record->city,
                            $record->state,
                            $record->pin_code,
                        ])
                            ->filter() // Remove null/empty values
                            ->implode(', ');
                    })
                    ->wrap()
                    ->searchable(query: function ($query, string $search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('address', 'like', "%{$search}%")
                                ->orWhere('city', 'like', "%{$search}%")
                                ->orWhere('state', 'like', "%{$search}%")
                                ->orWhere('pin_code', 'like', "%{$search}%");
                        });
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.quota.name')
                    ->sortable()->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bloodGroup.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gender.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Email'),
                TextColumn::make('gSuiteUser.email')->label('GSuite Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gSuiteUser.password')->label('GSuite Pwd')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('roles.name')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Suspended')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created At')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Updated At')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Deleted At')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                $query->Role('Student');
            })
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Suspended',
                    ])
                    ->label('Status')
                    ->default(true),
                SelectFilter::make('class_name')
                    ->label('Class')
                    ->options(function () {
                        return StudentClass::with('className')  // Ensure eager load 'className'
                        ->distinct() // Use distinct for unique class names
                        ->get()
                            ->pluck('className.name', 'className.name')  // Pluck className.name for both key and value
                            ->toArray();
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn(Builder $query, $value): Builder => $query->whereHas('student.classAssignment.class.className', function (Builder $query) use ($value) {
                                $query->where('class_names.name', '=', $value);
                            })
                        );
                    }),
                SelectFilter::make('section_name')
                    ->label('Section')
                    ->options(function () {
                        return StudentSection::distinct('name')->pluck('name', 'name')->toArray();
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn(Builder $query, $value): Builder => $query->whereHas('student.classAssignment.section', function (Builder $query) use ($value) {
                                $query->where(DB::raw('LOWER(name)'), '=', strtolower($value));
                            }),
                        );
                    }),
            ])->filtersFormColumns(2)
            ->recordActions([
                ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                Action::make('resetPassword')
                    ->label('')
                    ->icon('heroicon-o-key')
                    ->schema([
                        TextInput::make('new_password')
                            ->label('New Password')
                            ->password()
                            ->required()
                            ->confirmed(),
                        TextInput::make('new_password_confirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->required(),
                    ])
                    ->action(function (array $data, User $record) {
                        $record->password = Hash::make($data['new_password']);
                        $record->save();
                        Notification::make()
                            ->title('Password Reset')
                            ->body("Password for {$record->name} has been reset successfully.")
                            ->success()
                            ->send();
                    })
                    ->visible(fn(): bool => Auth::user()->can('resetUserPassword', User::class)), // Optional permission check
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
                BulkActionGroup::make([
                    ExportBulkAction::make('export-xlsx')
                        ->exporter(StudentExporter::class)
                        ->formats([
                            ExportFormat::Xlsx,
                        ])->label('Xlsx'),
                    ExportBulkAction::make('export-csv')
                        ->exporter(StudentExporter::class)
                        ->formats([
                            ExportFormat::Csv,
                        ])->label('CSV'),
                ])
                    ->label('Export'),
                BulkActionGroup::make([
                    BulkAction::make('printIdCards')
                        ->label('Print ID Cards')
                        ->icon('heroicon-o-printer')
                        ->action(function (Collection $records) {
                            if ($records->isEmpty()) {
                                Notification::make()
                                    ->title('No records selected for printing.')
                                    ->warning()
                                    ->send();
                                return;
                            }

                            // Get IDs of selected records
                            $ids = $records->pluck('id')->implode(',');

                            // Redirect to a new tab/window to trigger print
                            // Use 'new_tab' or similar if you want it to open in a new tab
                            // (this might be browser-dependent for instant print)
                            return redirect()->to(route('print.student_id_cards', ['ids' => $ids]));
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->modalHeading('Print Selected ID Cards?')
                        ->modalDescription('This will open a new window to print ID cards. Please ensure your printer is ready.')
                        ->modalSubmitActionLabel('Yes, Print')
                        ->color('success'),
                ])->label('Print'),
                BulkActionGroup::make([
                    BulkAction::make('send_bulk_sms')
                        ->label('Send Bulk SMS')
                        ->form([
                            Select::make('sms_provider_id')
                                ->label('SMS Provider')
                                ->options(SMSProvider::query()->where('is_active', true)->pluck('name', 'id'))
                                ->searchable()
                                ->required(),

                            Select::make('template_id')
                                ->label('Message Template')
                                ->options(MessageTemplate::all()->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $provider = SmsProvider::find($data['sms_provider_id']);

                            if (!$provider || !$provider->is_active) {
                                Notification::make()
                                    ->title('SMS Provider Error')
                                    ->body('SMS provider not found or inactive.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $template = MessageTemplate::find($data['template_id']);
                            $smsService = new SMSService($provider->toArray()); // assuming SMSService accepts provider

                            foreach ($records as $student) {
                                $message = str_replace(
                                    ['{{name}}', '{{time}}'],
                                    [
                                        $student->name,
                                        optional($student->class)->name ?? '',
                                        $student->roll_no ?? '',
                                    ],
                                    $template->content
                                );

                                $smsService->sendSms($student->primary_contact_number, $message, $template);
                            }

                            Notification::make()
                                ->title('SMS Sent')
                                ->body('Bulk SMS sent successfully!')
                                ->success()
                                ->send();
                        }),
                    BulkAction::make('sendWhatsappMessage')
                        ->label('Send WhatsApp Message')
                        ->form([
                            Select::make('provider_id')
                                ->label('Select WhatsApp Provider')
                                ->options(WhatsAppProvider::all()->pluck('name', 'id'))
                                ->required(),
                            Textarea::make('message')
                                ->label('Message')
                                ->rows(4)
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            $provider = WhatsAppProvider::find($data['provider_id']);

                            foreach ($records as $user) {
                                $to = $user->primary_contact_number;
                                $message = $data['message'];

                                if ($to && $provider) {

                                    // dispatch(new SendWhatsappMessageJob($to, $message, $provider));
                                    $response = app(WhatsAppService::class)->sendMessage($to, $message, $provider);

                                    // Check for error in response
                                    if (isset($response['error'])) {
                                        $error = $response['error'];

                                        $metaTitle = $error['type'] ?? 'Error';
                                        $metaMessage = $error['message'] ?? 'An error occurred while sending the message.';

                                        Notification::make()
                                            ->title("Error: {$metaTitle}")
                                            ->body($metaMessage)
                                            ->danger() // red alert
                                            ->send();
                                    } else {
                                        $metaTitle = 'Message Sent';
                                        $metaMessage = 'Message was successfully sent via WhatsApp.';

                                        Notification::make()
                                            ->title($metaTitle)
                                            ->body($metaMessage)
                                            ->success() // green alert
                                            ->send();
                                    }
                                }
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->color('success')
                        ->icon('heroicon-o-chat-bubble-left-right'),

                ])
                    ->label('Message'),
                BulkActionGroup::make([
                    BulkAction::make('update-promote')
                        ->label('Promote Students')
                        ->form([
                            Select::make('new_academic_year_id')
                                ->label('Academic Year')
                                ->options(
                                    AcademicYear::where('is_active', true)
                                        ->pluck('name', 'id')
                                        ->toArray()
                                )
                                ->reactive()
                                ->afterStateUpdated(fn(callable $set) => $set('new_class_id', null))
                                ->searchable()
                                ->required(),

                            Select::make('new_class_id')
                                ->label('New Class')
                                ->options(function (callable $get) {
                                    $academicYearId = $get('new_academic_year_id');

                                    if (!$academicYearId) return [];

                                    return StudentClass::with('className')  // Eager load the related className
                                    ->where('academic_year_id', $academicYearId)
                                        ->get()
                                        ->pluck('className.name', 'id')  // Pluck related className's name
                                        ->toArray();
                                })
                                ->reactive()
                                ->afterStateUpdated(fn(callable $set) => $set('new_section_id', null))
                                ->searchable()
                                ->required(),

                            Select::make('new_section_id')
                                ->label('Section')
                                ->options(function (callable $get) {
                                    $classId = $get('new_class_id');

                                    if (!$classId) return [];

                                    return StudentSection::where('student_class_id', $classId)
                                        ->pluck('name', 'id')
                                        ->toArray();
                                })
                                ->searchable(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            foreach ($records as $user) {
                                $student = $user->student;

                                if (!$student) {
                                    continue;
                                }

                                // Check if a record exists for the student and academic year.
                                $existingAssignment = $student->classAssignments()
                                    ->where('academic_year_id', $data['new_academic_year_id'])
                                    ->first();

                                if ($existingAssignment) {
                                    // Update the existing record.
                                    $existingAssignment->update([
                                        'class_id' => $data['new_class_id'],
                                        'section_id' => $data['new_section_id'],
                                    ]);
                                } else {
                                    // Create a new record.
                                    $student->classAssignments()->create([
                                        'class_id' => $data['new_class_id'],
                                        'section_id' => $data['new_section_id'],
                                        'academic_year_id' => $data['new_academic_year_id'],
                                        'is_promoted' => true,
                                        'student_id' => $student->id,
                                    ]);
                                }
                            }
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('update-status')
                        ->label('Update Status') // Label for the action button
                        ->icon('heroicon-o-adjustments-horizontal') // Optional: Add an icon
                        ->color('info') // Optional: Set a color
                        ->form([ // Define the form for the modal
                            Toggle::make('new_status')
                                ->label('Set Status to Active?') // Label for the toggle switch
                                ->hint('Toggle to set selected items as Active or Suspended.') // Helpful hint
                                ->default(false), // Default state when the modal opens
                        ])
                        ->action(function (Collection $records, array $data): void {
                            // Loop through the selected records and update their status
                            foreach ($records as $record) {
                                $record->update([
                                    'is_active' => $data['new_status'], // Use the status value from the form
                                ]);
                            }

                            // Optional: Send a notification to the user after completion
                            Notification::make()
                                ->title('Status Updated')
                                ->body('Selected records have been updated successfully!')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion() // Deselect records after the action
                        ->requiresConfirmation(),
                ])->label('Update'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
//            ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'view' => ViewStudent::route('/{record}'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->with([
                'student.quota',
                'bloodGroup',
                'gender',
                'student.classAssignment.class',
                'student.classAssignment.section',
                // ... other relationships
            ])
            ->Role('Student');
    }

    public static function canView(Model $record): bool
    {
        return $record->hasRole('Student');
    }

    public static function canEdit(Model $record): bool
    {
        return $record->hasRole('Student');
    }

    public static function canDelete(Model $record): bool
    {
        return $record->hasRole('Student');
    }

    public static function getNavigationBadge(): ?string
    {
        return User::where('is_active', 1)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Student');
            })
            ->count();
    }
}
