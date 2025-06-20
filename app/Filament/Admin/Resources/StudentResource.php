<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudentResource\Pages;
use App\Filament\Admin\Resources\StudentResource\RelationManagers\CartRelationManager;
use App\Filament\Admin\Resources\StudentResource\RelationManagers\InvoicesRelationManager;
use App\Filament\Admin\Resources\StudentResource\RelationManagers\ProductsRelationManager;
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
use App\Models\Section as ModelsSection;
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
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'User';

    protected static ?string $modelLabel = 'Student';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Student info')
                    ->schema([
                        Forms\Components\Grid::make(2) // Create a 2-column layout
                        ->schema([
                            // Left Column: Centered Large Avatar
                            Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\FileUpload::make('avatar')
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
                            Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\Group::make([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->name)
                                            ->columnSpan(3),
                                        Forms\Components\Toggle::make('is_active')->default(true)->inline(false)->required(),
                                    ])->columns(4),

                                    Forms\Components\Group::make([
                                        Forms\Components\TextInput::make('email')
                                            ->label('GSuite Email')
                                            ->email()
                                            ->visibleOn(['view', 'edit'])
                                            ->disabled(fn() => !Filament::auth()->user()?->can('update GSuiteUser')),
                                        Forms\Components\TextInput::make('password')
                                            ->label('GSuite Password')
                                            ->password()
                                            ->revealable()
                                            ->visibleOn(['view', 'edit'])
                                            ->disabled(fn() => !Filament::auth()->user()?->can('update GSuiteUser')),
                                    ])->relationship('gSuiteUser')->columns(2),
                                ])
                                ->columnSpan(1),
                        ]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\DatePicker::make('date_of_birth')->required()
                                    ->default(fn($get) => Registration::find(request()->query('registration_id'))?->date_of_birth),
                                Forms\Components\Select::make('gender_id')
                                    ->options(Gender::pluck('name', 'id'))
                                    ->required()
                                    ->default(fn($get) => Registration::find(request()->query('registration_id'))?->gender_id),
                                Forms\Components\Select::make('blood_group_id')
                                    ->options(BloodGroup::pluck('name', 'id'))
                                    ->required()
                                    ->default(fn($get) => Registration::find(request()->query('registration_id'))?->blood_group_id),
                            ])->columns(4),
                    ]),

                Section::make('Admission Info')
                    ->schema([
                        Forms\Components\Group::make()
                            ->relationship('currentStudent')
                            ->schema([
                                Forms\Components\Select::make('quota_id')
                                    ->options(Quota::pluck('name', 'id'))
                                    ->required(),
                            ])->columns(3),

                        Forms\Components\Group::make()
                            ->relationship('currentStudent')
                            ->schema([
                                Forms\Components\Group::make()
                                    ->relationship('currentClassAssignment')
                                    ->schema([
                                        Forms\Components\Select::make('academic_year_id')
                                            ->label('Academic Year')
                                            ->options(\App\Models\AcademicYear::pluck('name', 'id'))
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn($set) => [
                                                $set('class_id', null),
                                                $set('section_id', null),
                                            ])
                                            ->default(fn() => \App\Models\Registration::find(request()->query('registration_id'))?->academic_year_id),

                                        Forms\Components\Select::make('class_id')
                                            ->label('Student Class')
                                            ->options(function ($get) {
                                                $academicYearId = $get('academic_year_id');
                                                if (!$academicYearId) {
                                                    return [];
                                                }

                                                return \App\Models\StudentClass::with('className')
                                                    ->where('academic_year_id', $academicYearId)
                                                    ->get()
                                                    ->pluck('className.name', 'id')
                                                    ->toArray();
                                            })
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn($set) => $set('section_id', null))
                                            ->default(fn() => \App\Models\Registration::find(request()->query('registration_id'))?->class_id),

                                        Forms\Components\Select::make('section_id')
                                            ->label('Section')
                                            ->options(function ($get) {
                                                $classId = $get('class_id');
                                                if (!$classId) {
                                                    return [];
                                                }

                                                return \App\Models\StudentSection::where('student_class_id', $classId)
                                                    ->pluck('name', 'id')
                                                    ->toArray();
                                            })
                                            ->default(fn() => \App\Models\Registration::find(request()->query('registration_id'))?->section_id),
                                    ])
                                    ->columns(3),
                            ]),

                    ]),
                Section::make('Parents info')
                    ->schema([
                        Forms\Components\TextInput::make('father_name')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->father_name),
                        Forms\Components\TextInput::make('mother_name')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->mother_name),
                    ])->columns(2),
                Section::make('Contact info')
                    ->schema([
                        Forms\Components\TextInput::make('primary_contact_number')
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10)
                            ->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->primary_contact_number),
                        Forms\Components\TextInput::make('secondary_contact_number')
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10)
                            ->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->secondary_contact_number),
                        Forms\Components\TextInput::make('email')->email()->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->email),
                    ])->columns(2),
                Section::make('Address')
                    ->schema([
                        Forms\Components\TextInput::make('address')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->address),
                        Forms\Components\TextInput::make('city')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->city),
                        Forms\Components\TextInput::make('state')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->state),
                        Forms\Components\TextInput::make('pin_code')
                            ->numeric()
                            ->rules(['digits:6'])
                            ->minLength(6)
                            ->maxLength(6)
                            ->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->pin_code),
                    ])->columns(2),
                Section::make('Record creation info')
                    ->schema([
                        Forms\Components\TextInput::make('createdBy.name')->label('Created By'),
                        Forms\Components\TextInput::make('updatedBy.name')->label('Updated By'),
                        Forms\Components\TextInput::make('deletedBy.name')->label('Deleted By'),
                        Forms\Components\TextInput::make('created_at'),
                        Forms\Components\TextInput::make('updated_at'),
                        Forms\Components\TextInput::make('deleted_at'),
                    ])
                    ->columns(3)
                    ->visibleOn(['view']),

                // start only for deleteing registration after admission
                Forms\Components\TextInput::make('registration_id')
                    ->hidden()
                    ->default(fn() => request()->query('registration_id')),
                // end only for deleteing registration after admission
            ])
            ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->size(50)
                    ->label('Image')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Name')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('father_name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Father Name')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('mother_name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Mother Name')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('currentStudent.currentClassAssignment.class.className.name')
                    ->searchable()
                    ->sortable()
                    ->label('Class')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('currentStudent.currentClassAssignment.section.name')
                    ->searchable()
                    ->sortable()
                    ->label('Section')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->searchable()
                    ->sortable()
                    ->label('DOB')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('currentStudent.currentClassAssignment.academicYear.name')
                    ->searchable()
                    ->sortable()
                    ->label('Academic Year')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('primary_contact_number')
                    ->searchable()
                    ->sortable()
                    ->label('Primary Contact')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('secondary_contact_number')
                    ->searchable()
                    ->sortable()
                    ->label('Secondary Contact')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('full_address')
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
                Tables\Columns\TextColumn::make('student.quota.name')
                    ->sortable()->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bloodGroup.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('gender.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Email'),
                Tables\Columns\TextColumn::make('gSuiteUser.email')->label('GSuite Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('gSuiteUser.password')->label('GSuite Pwd')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('is_active')
                    ->label('Status')
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Suspended')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created At')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Updated At')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Suspended',
                    ])
                    ->label('Status')
                    ->default(true),
                Tables\Filters\SelectFilter::make('class_name')
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
                            fn(Builder $query, $value): Builder => $query->whereHas('currentStudent.currentClassAssignment.class.className', function (Builder $query) use ($value) {
                                $query->where('class_names.name', '=', $value);
                            })
                        );
                    }),
                Tables\Filters\SelectFilter::make('section_name')
                    ->label('Section')
                    ->options(function () {
                        return StudentSection::distinct('name')->pluck('name', 'name')->toArray();
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn(Builder $query, $value): Builder => $query->whereHas('currentStudent.currentClassAssignment.section', function (Builder $query) use ($value) {
                                $query->where(\Illuminate\Support\Facades\DB::raw('LOWER(name)'), '=', strtolower($value));
                            }),
                        );
                    }),
            ])->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('resetPassword')
                    ->label('')
                    ->icon('heroicon-o-key')
                    ->form([
                        Forms\Components\TextInput::make('new_password')
                            ->label('New Password')
                            ->password()
                            ->required()
                            ->confirmed(),
                        Forms\Components\TextInput::make('new_password_confirmation')
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make('export-xlsx')
                        ->exporter(StudentExporter::class)
                        ->formats([
                            Actions\Exports\Enums\ExportFormat::Xlsx,
                        ])->label('Xlsx'),
                    Tables\Actions\ExportBulkAction::make('export-csv')
                        ->exporter(StudentExporter::class)
                        ->formats([
                            Actions\Exports\Enums\ExportFormat::Csv,
                        ])->label('CSV'),
                ])
                    ->label('Export'),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('printIdCards')
                        ->label('Print ID Cards')
                        ->icon('heroicon-o-printer')
                        ->action(function (Collection $records) {
                            if ($records->isEmpty()) {
                                \Filament\Notifications\Notification::make()
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
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('send_bulk_sms')
                        ->label('Send Bulk SMS')
                        ->form([
                            Forms\Components\Select::make('provider_id')
                                ->label('SMS Provider')
                                ->options(SMSProvider::query()->where('is_active', true)->pluck('name', 'id'))
                                ->searchable()
                                ->required(),

                            Forms\Components\Select::make('template_id')
                                ->label('Message Template')
                                ->options(MessageTemplate::all()->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $provider = SmsProvider::find($data['provider_id']);

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
                    Tables\Actions\BulkAction::make('sendWhatsappMessage')
                        ->label('Send WhatsApp Message')
                        ->form([
                            Forms\Components\Select::make('provider_id')
                                ->label('Select WhatsApp Provider')
                                ->options(WhatsAppProvider::all()->pluck('name', 'id'))
                                ->required(),
                            Forms\Components\Textarea::make('message')
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('update-promote')
                        ->label('Promote Students')
                        ->form([
                            Forms\Components\Select::make('new_academic_year_id')
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

                            Forms\Components\Select::make('new_class_id')
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

                            Forms\Components\Select::make('new_section_id')
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
                    Tables\Actions\BulkAction::make('update-status')
                        ->label('Update Status') // Label for the action button
                        ->icon('heroicon-o-adjustments-horizontal') // Optional: Add an icon
                        ->color('info') // Optional: Set a color
                        ->form([ // Define the form for the modal
                            Forms\Components\Toggle::make('new_status')
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
            ProductsRelationManager::class,
            CartRelationManager::class,
            InvoicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
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
                'currentStudent.currentClassAssignment.class',
                'currentStudent.currentClassAssignment.section',
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
