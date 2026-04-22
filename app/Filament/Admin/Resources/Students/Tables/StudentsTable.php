<?php

namespace App\Filament\Admin\Resources\Students\Tables;

use App\Filament\Exports\StudentExporter;
use App\Models\AcademicYear;
use App\Models\MessageTemplate;
use App\Models\SmsProvider;
use App\Models\StudentClass;
use App\Models\StudentSection;
use App\Models\User;
use App\Models\WhatsAppProvider;
use App\Services\WhatsAppService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                ImageColumn::make('avatar')
                    ->disk('public')
                    ->visibility('public')
                    ->circular()
                    ->default(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('name')
                    ->searchable()->sortable()->wrap(),
                TextColumn::make('father_name')
                    ->searchable()->sortable()->wrap()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('mother_name')
                    ->searchable()->sortable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.classAssignment.class.name')
                    ->searchable()->sortable()->wrap()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('student.classAssignment.section.name')
                    ->searchable()->sortable()->wrap()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('date_of_birth')
                    ->searchable()->sortable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.classAssignment.academicYear.name')
                    ->searchable()->sortable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('primary_contact_number')
                    ->searchable()->label('Primary contact')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('secondary_contact_number')
                    ->searchable()->label('Secondary contact')->toggleable(isToggledHiddenByDefault: true),
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
                    ->searchable()->sortable()->wrap()
                    ->toggleable(isToggledHiddenByDefault: false),
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
            ])->filtersFormColumns(2)
            ->columnManagerColumns(4)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('resetPassword')
                        ->label('Change Password')
                        ->color('danger')
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
                ]),
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
                        ])->label('Xlsx')
                        // 1. Change layout to 3 columns
                        ->columnMappingColumns(3)
                        // 2. Make modal wider to fit columns
                        ->modalWidth('4xl')
                        // 3. "Check All" behavior
                        // By default, Filament checks all. To match your table's current view:
                        ->enableVisibleTableColumnsByDefault(),
                    ExportBulkAction::make('export-csv')
                        ->exporter(StudentExporter::class)
                        ->formats([
                            ExportFormat::Csv,
                        ])->label('CSV')
                        // 1. Change layout to 3 columns
                        ->columnMappingColumns(3)
                        // 2. Make modal wider to fit columns
                        ->modalWidth('4xl')
                        // 3. "Check All" behavior
                        // By default, Filament checks all. To match your table's current view:
                        ->enableVisibleTableColumnsByDefault(),
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
                            Select::make('provider_id')
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
                    BulkAction::make('promote_students')
                        ->label('Promote Students')
                        ->icon('heroicon-o-arrow-trending-up')
                        ->color('success')
                        ->schema([
                            Select::make('new_academic_year_id')
                                ->label('Academic Year')
                                ->options(AcademicYear::where('is_active', true)->pluck('name', 'id'))
                                ->required()
                                ->live() // Faster and cleaner than reactive()
                                ->afterStateUpdated(fn($set) => $set('new_class_id', null))
                                ->searchable(),

                            Select::make('new_class_id')
                                ->label('New Class')
                                ->placeholder(fn($get) => $get('new_academic_year_id') ? 'Select a class' : 'Select year first')
                                ->options(fn($get) => StudentClass::where('academic_year_id', $get('new_academic_year_id'))
                                    ->join('class_names', 'student_classes.class_name_id', '=', 'class_names.id')
                                    ->pluck('class_names.name', 'student_classes.id')
                                )
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn($set) => $set('new_section_id', null))
                                ->searchable()
                                ->key('new_class_select'), // Helps Filament track state changes

                            Select::make('new_section_id')
                                ->label('Section')
                                ->options(fn($get) => StudentSection::where('student_class_id', $get('new_class_id'))
                                    ->pluck('name', 'id')
                                )
                                ->searchable()
                                ->placeholder('Optional'),
                        ])
                        ->action(function (Collection $records, array $data) {
                            \DB::transaction(fn() => $records->each->promoteStudent($data));

                            Notification::make()
                                ->title('Promotion Successful')
                                ->success()
                                ->body("{$records->count()} students have been moved to the new academic year.")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
//                    BulkAction::make('update-promote')
//                        ->label('Promote Students')
//                        ->form([
//                            Select::make('new_academic_year_id')
//                                ->label('Academic Year')
//                                ->options(
//                                    AcademicYear::where('is_active', true)
//                                        ->pluck('name', 'id')
//                                        ->toArray()
//                                )
//                                ->reactive()
//                                ->afterStateUpdated(fn(callable $set) => $set('new_class_id', null))
//                                ->searchable()
//                                ->required(),
//
//                            Select::make('new_class_id')
//                                ->label('New Class')
//                                ->options(function (callable $get) {
//                                    $academicYearId = $get('new_academic_year_id');
//
//                                    if (!$academicYearId) return [];
//
//                                    return StudentClass::with('className')  // Eager load the related className
//                                    ->where('academic_year_id', $academicYearId)
//                                        ->get()
//                                        ->pluck('className.name', 'id')  // Pluck related className's name
//                                        ->toArray();
//                                })
//                                ->reactive()
//                                ->afterStateUpdated(fn(callable $set) => $set('new_section_id', null))
//                                ->searchable()
//                                ->required(),
//
//                            Select::make('new_section_id')
//                                ->label('Section')
//                                ->options(function (callable $get) {
//                                    $classId = $get('new_class_id');
//
//                                    if (!$classId) return [];
//
//                                    return StudentSection::where('student_class_id', $classId)
//                                        ->pluck('name', 'id')
//                                        ->toArray();
//                                })
//                                ->searchable(),
//                        ])
//                        ->action(function (Collection $records, array $data) {
//                            foreach ($records as $user) {
//                                $student = $user->student;
//
//                                if (!$student) {
//                                    continue;
//                                }
//
//                                // Check if a record exists for the student and academic year.
//                                $existingAssignment = $student->classAssignments()
//                                    ->where('academic_year_id', $data['new_academic_year_id'])
//                                    ->first();
//
//                                if ($existingAssignment) {
//                                    // Update the existing record.
//                                    $existingAssignment->update([
//                                        'class_id' => $data['new_class_id'],
//                                        'section_id' => $data['new_section_id'],
//                                    ]);
//                                } else {
//                                    // Create a new record.
//                                    $student->classAssignments()->create([
//                                        'class_id' => $data['new_class_id'],
//                                        'section_id' => $data['new_section_id'],
//                                        'academic_year_id' => $data['new_academic_year_id'],
//                                        'is_promoted' => true,
//                                        'student_id' => $student->id,
//                                    ]);
//                                }
//                            }
//                        })
//                        ->requiresConfirmation()
//                        ->deselectRecordsAfterCompletion(),
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
}
