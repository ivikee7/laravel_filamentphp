<?php

namespace App\Filament\Admin\Resources\Users;

use App\Filament\Admin\Resources\Users\Pages\ViewUserAttendanceReport;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Filament\Admin\Resources\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Users\Pages\MonthlyAttendance;
use App\Filament\Admin\Resources\Users\Pages\ViewUser;
use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Users\Pages\Transport;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\Users\Pages\IDCard;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Filament\Exports\UserExporter;
use App\Jobs\SendWhatsappMessageJob;
use App\Models\AcademicYear;
use App\Models\BloodGroup;
use App\Models\Gender;
use App\Models\StudentClass;
use App\Models\StudentSection;
use App\Models\User;
use App\Models\WhatsAppProvider;
use App\Models\TransportStoppage;
use App\Services\WhatsApp\WhatsAppService;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
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

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static string|\UnitEnum|null $navigationGroup = 'User';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User info')
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
                                    TextInput::make('name')->required(),
                                    Group::make([
                                        TextInput::make('email')
                                            ->label('GSuite Email')
                                            ->email()
                                            ->disabled(fn() => !Filament::auth()->user()?->can('update GSuiteUser')),
                                        TextInput::make('password')
                                            ->label('GSuite Password')
                                            ->password()
                                            ->revealable()
                                            ->visible(fn() => Filament::auth()->user()?->isSuperAdmin())
                                            ->disabled(fn() => !Filament::auth()->user()?->isSuperAdmin()),
                                    ])->relationship('gSuiteUser')->columns(2),
                                    Group::make()
                                        ->schema([
                                            Select::make('gender_id')
                                                ->options(Gender::pluck('name', 'id'))
                                                ->label('Gender')
                                                ->required(),
                                            Select::make('blood_group_id')
                                                ->options(BloodGroup::pluck('name', 'id'))
                                                ->label('Blood Group')
                                                ->required(),
                                        ])->columns(2),
                                ])
                                ->columnSpan(1),
                        ]),
                        Group::make()->schema([
                            DatePicker::make('date_of_birth')->required(),
                            TextInput::make('aadhaar_number')
                                ->rules(['digits:12', 'min_digits:12', 'max_digits:12'])
                                ->minLength(12)
                                ->maxLength(12)
                                ->required(),
                            TextInput::make('place_of_birth')->required(),
                            TextInput::make('mother_tongue')->required(),
                            TextInput::make('notes'),
                            DatePicker::make('termination_date')->required()->visibleOn(['view']),
                        ])->columns(3),
                    ]),
                Section::make('Parents info')
                    ->schema([
                        TextInput::make('father_name')->required(),
                        TextInput::make('mother_name')->required(),
                    ])->columns(2),
                Section::make('Contact info')
                    ->schema([
                        TextInput::make('primary_contact_number')
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10)
                            ->required(),
                        TextInput::make('secondary_contact_number')
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10)
                            ->required(),
                        TextInput::make('email')->email(),
                    ])->columns(2),
                Section::make('Address')
                    ->schema([
                        TextInput::make('address')->required(),
                        TextInput::make('city')->required(),
                        TextInput::make('state')->required(),
                        TextInput::make('pin_code')
                            ->numeric()
                            ->rules(['digits:6'])
                            ->minLength(6)
                            ->maxLength(6)
                            ->required(),
                    ])->columns(2),
                Section::make('Financial Info')
                    ->schema([
                        Group::make()
                            ->relationship('financialDetails')
                            ->schema([
                                // Basic salary and allowances
                                TextInput::make('basic_salary')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('house_allowance')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('transport_allowance')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('other_allowances')
                                    ->numeric()
                                    ->required(),

                                // Deductions
                                TextInput::make('tax_deduction')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('loan_deduction')
                                    ->numeric()
                                    ->required(),

                                // Payment info
                                TextInput::make('bank_name')
                                    ->required(),
                                TextInput::make('account_number')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('ifsc_code')
                                    ->required(),

                                // Provident fund
                                TextInput::make('provident_fund_contribution')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('pf_account_number')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('esi_number')
                                    ->numeric()
                                    ->required(),
                            ])->columns(3)
                    ]),
                Section::make('Authentication info')
                    ->schema([
                        Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name', function ($query) {
                                $query->whereNotIn('name', ['Student']); // Exclude 'Student'

                                // If the authenticated user is NOT a "Super Admin", also exclude "Super Admin"
                                if (!Auth::user()->hasRole('Super Admin')) {
                                    $query->where('name', '!=', 'Super Admin');
                                }

                                return $query;
                            }),
                        Toggle::make('is_active')->inline(false)->required(),
                    ])->columns(2),
                Section::make('Record creation info')
                    ->schema([
                        TextInput::make('createdBy.name')->label('Created By'),
                        TextInput::make('updatedBy.name')->label('Updated By'),
                        TextInput::make('deletedBy.name')->label('Deleted By'),
                        DateTimePicker::make('created_at'),
                        DateTimePicker::make('updated_at'),
                        DateTimePicker::make('deleted_at'),
                    ])
                    ->columns(3)
                    ->visibleOn(['view'])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                ImageColumn::make('avatar')
                    ->disk('public')
                    ->visibility('public')
                    ->circular()
                    ->default(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name)),
                TextColumn::make('name')
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('father_name')->wrap()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('mother_name')->wrap()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('bloodGroup.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gender.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mother_name')->wrap()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gSuiteUser.email')->label('GSuite Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gSuiteUser.password')->label('GSuite Pwd')
                    ->searchable()
                    ->visible(fn() => Filament::auth()->user()?->isSuperAdmin())
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('roles.name')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Suspended')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('createdBy.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updatedBy.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deletedBy.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                // if (!Auth::user()->hasRole('Super Admin')) {
                //     $query->withoutRole('Super Admin');
                // }
                // $query->withoutRole('Student');
            })
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Suspended',
                    ])
                    ->label('Status')
                    ->default(true)
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
                            $provider = WhatsappProvider::find($data['provider_id']);

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
                ]),
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
                            return redirect()->to(route('print.user_id_cards', ['ids' => $ids]));
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->modalHeading('Print Selected ID Cards?')
                        ->modalDescription('This will open a new window to print ID cards. Please ensure your printer is ready.')
                        ->modalSubmitActionLabel('Yes, Print')
                        ->color('success'),
                    BulkAction::make('printOutPass')
                        ->label('Print Out Pass')
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
                            return redirect()->to(route('print.user_out_pass', ['ids' => $ids]));
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->modalHeading('Print User Out Pass?')
                        ->modalDescription('This will open a new window to print Out Pass. Please ensure your printer is ready.')
                        ->modalSubmitActionLabel('Yes, Print')
                        ->color('warning'),
                ])->label('Print'),
                BulkActionGroup::make([
                    ExportBulkAction::make('export-xlsx')
                        ->exporter(UserExporter::class)
                        ->formats([
                            ExportFormat::Xlsx,
                        ])->label('Xlsx'),
                    ExportBulkAction::make('export-csv')
                        ->exporter(UserExporter::class)
                        ->formats([
                            ExportFormat::Csv,
                        ])->label('CSV'),
                ])
                    ->label('Export'),
                BulkActionGroup::make([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
            'view-user-attendance-report' => ViewUserAttendanceReport::route('/{record}/view-user-attendance-report'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->with(['createdBy', 'updatedBy', 'deletedBy'])
            ->whereHas('roles', function ($query) {
                if (!Auth::user()->hasRole('Super Admin')) {
                    $query->whereNot('name', 'Super Admin');
                }
                $query->whereNot('name', 'Student');
            });
    }

    public static function getNavigationBadge(): ?string
    {
        return User::where('is_active', true)
            ->whereHas('roles', function ($query) {
                if (!Auth::user()->hasRole('Super Admin')) {
                    $query->whereNot('name', 'Super Admin');
                }
                $query->whereNot('name', 'Student');
            })
            ->count();
    }

    public static function canView(Model $record): bool
    {
        $authUser = Auth::user();
        // Super Admin can view all users, including other Super Admins
        if ($authUser->hasRole('Super Admin')) {
            return true;
        }
        // Other users cannot view Super Admin users
        return !$record->hasRole('Super Admin') && !$record->hasRole('Student');
    }

    public static function canEdit(Model $record): bool
    {
        $authUser = Auth::user();
        // Super Admin can edit any user, including other Super Admins
        if ($authUser->hasRole('Super Admin')) {
            return true;
        }
        // Other users cannot edit Super Admin or Student users
        return !$record->hasRole('Super Admin') && !$record->hasRole('Student');
    }

    public static function canDelete(Model $record): bool
    {
        $authUser = Auth::user();
        // Super Admin can delete any user, including other Super Admins
        if ($authUser->hasRole('Super Admin')) {
            return true;
        }
        // Other users cannot delete Super Admin or Student users
        return !$record->hasRole('Super Admin') && !$record->hasRole('Student');
    }
}
