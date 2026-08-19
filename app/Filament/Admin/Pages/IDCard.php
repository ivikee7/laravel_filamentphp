<?php

namespace App\Filament\Admin\Pages;

use App\Models\Attendance;
use App\Models\MessageTemplate;
use App\Models\SmsProvider;
use App\Models\User;
use App\Services\SMSService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Phiki\Phast\Text;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class IDCard extends Page implements HasInfolists, HasTable
{
    use InteractsWithInfolists;
    use InteractsWithTable;

    protected string $view = 'filament.admin.pages.i-d-card';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'id-cards/{record}';

    public ?User $record = null;

    public function mount($record): void
    {
        $this->record = $record;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Attendance::where('user_id', $this->record->id))
            ->columns([
                TextColumn::make('created_at')
                    ->searchable()
                    ->label('Created at'),
                TextColumn::make('type')
                    ->searchable()
                    ->label('Type'),
                TextColumn::make('createdBy.name')
                    ->searchable()
                    ->label('Created By')
            ])->defaultSort('id', 'desc');
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->record)
            ->components([
                Section::make()
                    ->schema([
                        Group::make([
                            Grid::make(2)
                                ->schema([
                                    ImageEntry::make('avatar')
                                        ->imageSize(150)
                                        ->disk('public')
                                        ->square()
                                        ->hiddenLabel()
                                        ->alignCenter()
                                        ->default(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name)),
                                    ImageEntry::make('qrcode')
                                        ->imageSize(150)
                                        ->state(self::getQRCode())
                                        ->gap(false)
                                        ->square()
                                        ->hiddenLabel()
                                        ->alignCenter(),
                                ])
                                ->columns(['sm' => 2, 'md' => 2]),
                            TextEntry::make('name')
                                ->extraAttributes(['style' => 'font-size:2rem;'])
                                ->alignCenter()
                                ->hiddenLabel()
                                ->wrap(),
                            TextEntry::make('roles.name')
                                ->extraAttributes(['style' => 'text-align: center;font-size:1rem;'])
                                ->color('primary')
                                ->hiddenLabel()->wrap(),
                        ]),
                        Group::make([
                            TextEntry::make('email')->prefix('Email: ')
                                ->hiddenLabel()->wrap(),
                            TextEntry::make('primary_contact_number')->prefix('Primary Contact: ')
                                ->hiddenLabel()->wrap(),
                            TextEntry::make('secondary_contact_number')->prefix('Secondary Contact: ')
                                ->hiddenLabel()->wrap(),
                            TextEntry::make('father_name')->prefix('Father\'s name: ')->hiddenLabel()->wrap(),
                            TextEntry::make('mother_name')->prefix('Mother\'s name: ')->hiddenLabel()->wrap(),
                            TextEntry::make('full_address')
                                ->prefix('Address: ')->hiddenLabel()
                                ->getStateUsing(function ($record): string {
                                    $addressParts = [
                                        $record->address,
                                        $record->city,
                                        $record->state,
                                        $record->pin_code,
                                    ];
                                    // Filter out any empty parts to avoid extra commas.
                                    $filteredParts = array_filter($addressParts);
                                    // Join the parts with a comma and a space.
                                    return implode(', ', $filteredParts);
                                })
                                ->wrap(),
                        ])
                    ])
                    ->columns(2)
                    ->headerActions([
                        Action::make('print')
                            ->label('Print ID Card')
                            ->icon('heroicon-o-printer')
                            ->color('success')
                            // Using 'openUrlInNewTab' is better for printing
                            // so the user doesn't lose their place in the admin panel
                            ->url(fn ($record): string =>
                            $record->hasRole('Student')
                                ? route('print.student_id_card', ['user' => $record->id])
                                : route('print.user_id_card', ['user' => $record->id])
                            , shouldOpenInNewTab: true)
                            ->requiresConfirmation()
                            ->modalHeading('Print Student ID Card?')
                            ->modalDescription('Confirming will open the print preview for this student.')
                            ->modalSubmitActionLabel('Yes, Print'),
                    ])
                    ->footer([
//                        Action::make('enteredInBus')
//                            ->color('info')
//                            ->action(function () {
//                                self::markAttendance('enteredInBus');
//                            })->disabled(function ($record) {
//                                return self::checkAttendance('enteredInBus', $record);
//                            })
//                            ->icon(function ($record): ?string {
//                                if (self::checkAttendance('enteredInBus', $record)) {
//                                    return 'heroicon-o-check-circle'; // Icon when true
//                                }
//                                return null;
//                            })->hidden(fn ($record) => !$this->isStudent($record)),

                        Action::make('enteredInCampus')
                            ->color('success')
                            ->action(function () {
                                self::markAttendance('enteredInCampus');
                            })->disabled(function ($record) {
                                return self::checkAttendance('enteredInCampus', $record);
                            })
                            ->icon(function ($record): ?string {
                                if (self::checkAttendance('enteredInCampus', $record)) {
                                    return 'heroicon-o-check-circle'; // Icon when true
                                }
                                return null;
                            })->hidden(fn ($record) => !$this->isStudent($record)),

                        Action::make('leaveFromCampus')
                            ->color('warning')
                            ->action(function () {
                                self::markAttendance('leaveFromCampus');
                            })->disabled(function ($record) {
                                return self::checkAttendance('leaveFromCampus', $record);
                            })
                            ->icon(function ($record): ?string {
                                if (self::checkAttendance('leaveFromCampus', $record)) {
                                    return 'heroicon-o-check-circle'; // Icon when true
                                }
                                return null;
                            })->hidden(fn ($record) => !$this->isStudent($record)),

//                        Action::make('leaveFromBus')
//                            ->color('danger')
//                            ->action(function () {
//                                self::markAttendance('leaveFromBus');
//                            })->disabled(function ($record) {
//                                return self::checkAttendance('leaveFromBus', $record);
//                            })
//                            ->icon(function ($record): ?string {
//                                if (self::checkAttendance('leaveFromBus', $record)) {
//                                    return 'heroicon-o-check-circle'; // Icon when true
//                                }
//                                return null;
//                            })->hidden(fn ($record) => !$this->isStudent($record)),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public function checkAttendance($type, $record): string
    {
        return Attendance::where('user_id', $record->id)
            ->whereDate('created_at', date('Y-m-d', strtotime(now())))
            ->where('type', $type)
            ->exists();
    }

    public function isStudent($record)
    {
        return $record->hasRole('Student');
    }

    public function markAttendance(string $type): void
    {
        if (!$this->record) {
            Notification::make()->title('Error: User not found')->danger()->send();
            return;
        }

        if ($this->record->id === auth()->id()) {
            Notification::make()->title('Error: You can only mark your own attendance')->danger()->send();
            return;
        }

        $attendance = Attendance::create([
            'user_id' => $this->record->id,
            'type' => $type,
        ]);

        // ✅ Refresh attendance records immediately
        $this->updateAttendanceRecords();

        Notification::make()
            ->title(ucwords(str_replace('_', ' ', $type)) . ' marked successfully')
            ->success()
            ->send();

        $template = MessageTemplate::where('name', $type)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            Notification::make()
                ->title('SMS Template Error ' . $type)
                ->body('SMS template not found.')
                ->danger()
                ->send();
            return;
        }

        $message = str_replace(
            ['{{name}}', '{{time}}'],
            [
                $this->record->name,
                $attendance->created_at
            ],
            $template->content
        );

        $provider = SmsProvider::find($template->sms_provider_id);

        if (!$provider || !$provider->is_active) {
            Notification::make()
                ->title('SMS Provider Error')
                ->body('SMS provider not found or inactive.')
                ->danger()
                ->send();
            return;
        }

        $smsService = new SMSService($provider->toArray()); // assuming SMSService accepts provider

        $response = $smsService->sendSms($this->record->primary_contact_number, $message, $template);

        Notification::make()
            ->title('Message: '.$response['state'])
            ->body('Message: '.$response['description'])
            ->send();
    }

    public function updateAttendanceRecords(): void
    {
        $this->attendanceRecords = Attendance::where('user_id', $this->record->id)
            ->whereDate('created_at', now()->toDateString())
            ->pluck('type')
            ->toArray();
    }

    public function sendMessage()
    {
        Notification::make()
            ->title('Message Sent')
            ->body('Message sent successfully');
    }

    public function getQRCode(): ?string
    {
        if (!$this->record) {
            return null;
        }

        $url = route('filament.admin.pages.id-cards.{record}', ['record' => $this->record->id]);
        $svg = QrCode::size(100)->generate($url);

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view Attendance', static::class);
    }


    public static function canMarkAttendance(): bool
    {
        return auth()->user()?->can('create Attendance', static::class);
    }
}
