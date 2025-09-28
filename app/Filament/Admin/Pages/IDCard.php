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

    protected static ?string $slug = 'id-card/{record}';

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
            ]);
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
                                        ->disk('public')
                                        ->square()
                                        ->overlap(2)
                                        ->extraAttributes(['style' => 'text-align: center;background-color:white;padding:1rem;border-radius:5px;display:inline-block;width:auto;box-sizing:border-box;'])
                                        ->hiddenLabel(),
                                    ImageEntry::make('qrcode')
                                        ->state(self::getQRCode())
                                        ->square()
                                        ->overlap(2)
                                        ->extraAttributes(['style' => 'text-align: center;background-color:white;padding:1rem;border-radius:5px;display:inline-block;width:auto;box-sizing:border-box;'])
                                        ->hiddenLabel(),
                                ])
                                ->columns(['xs' => 2, 'sm' => 2, 'md' => 2])
                                ->extraAttributes([
                                    'class' => 'place-items-center',
                                ]),
                            TextEntry::make('name')
                                ->extraAttributes(['style' => 'text-align: center;font-size:2rem;'])
                                ->hiddenLabel()->wrap(),
                            TextEntry::make('roles.name')
                                ->extraAttributes(['style' => 'text-align: center;font-size:1rem;'])
                                ->color('primary')
                                ->hiddenLabel()->wrap(),
                        ]),
                        Group::make([
                            TextEntry::make('name')->inlineLabel()->wrap(),
                            TextEntry::make('email')->inlineLabel()->wrap(),
                            TextEntry::make('primary_contact_number')
                                ->label('Primary Contact')->inlineLabel()->wrap(),
                            TextEntry::make('secondary_contact_number')
                                ->label('Secondary Contact')->inlineLabel()->wrap(),
                            TextEntry::make('father_name')->inlineLabel()->wrap(),
                            TextEntry::make('mother_name')->inlineLabel()->wrap(),
                            TextEntry::make('full_address')
                                ->label('Full Address')
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
                                ->inlineLabel()
                                ->wrap(),
                        ])
                    ])
                    ->columns(2)
                    ->footer([
                        Action::make('enteredInBus')
                            ->color('info')
                            ->action(function () {
                                self::markAttendance('enteredInBus');
                            })->disabled(function ($record) {
                                return self::checkAttendanceType('enteredInBus', $record);
                            })
                            ->icon(function ($record): ?string {
                                if (self::checkAttendanceType('enteredInBus', $record)) {
                                    return 'heroicon-o-check-circle'; // Icon when true
                                }
                                return null;
                            }),

                        Action::make('enteredInCampus')
                            ->color('success')
                            ->action(function () {
                                self::markAttendance('enteredInCampus');
                            })->disabled(function ($record) {
                                return self::checkAttendanceType('enteredInCampus', $record);
                            })
                            ->icon(function ($record): ?string {
                                if (self::checkAttendanceType('enteredInCampus', $record)) {
                                    return 'heroicon-o-check-circle'; // Icon when true
                                }
                                return null;
                            }),

                        Action::make('leaveFromCampus')
                            ->color('warning')
                            ->action(function () {
                                self::markAttendance('leaveFromCampus');
                            })->disabled(function ($record) {
                                return self::checkAttendanceType('leaveFromCampus', $record);
                            })
                            ->icon(function ($record): ?string {
                                if (self::checkAttendanceType('leaveFromCampus', $record)) {
                                    return 'heroicon-o-check-circle'; // Icon when true
                                }
                                return null;
                            }),

                        Action::make('leaveFromBus')
                            ->color('danger')
                            ->action(function () {
                                self::markAttendance('leaveFromBus');
                            })->disabled(function ($record) {
                                return self::checkAttendanceType('leaveFromBus', $record);
                            })
                            ->icon(function ($record): ?string {
                                if (self::checkAttendanceType('leaveFromBus', $record)) {
                                    return 'heroicon-o-check-circle'; // Icon when true
                                }
                                return null;
                            }),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public function checkAttendanceType($type, $record): string
    {
        return Attendance::where('user_id', $record->id)->where('type', $type)->exists();
    }

    public function markAttendance(string $type): void
    {
        if (!$this->record) {
            Notification::make()->title('Error: User not found')->danger()->send();
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

        $provider = SmsProvider::find(env('MESSAGE_PROVIDER_ID'));

        if (!$provider || !$provider->is_active) {
            Notification::make()
                ->title('SMS Provider Error')
                ->body('SMS provider not found or inactive.')
                ->danger()
                ->send();
            return;
        }

        $template = MessageTemplate::where('name', $type)->first();

        if (!$template) {
            Notification::make()
                ->title('SMS Template Error ' . $type)
                ->body('SMS template not found.')
                ->danger()
                ->send();
            return;
        }

        $smsService = new SMSService($provider->toArray()); // assuming SMSService accepts provider

        $message = str_replace(
            ['{{name}}', '{{time}}'],
            [
                $this->record->name,
                $attendance->created_at
            ],
            $template->content
        );

        $smsService->sendSms($this->record->primary_contact_number, $message, $template);


        Notification::make()
            ->title('Message successfully sent')
            ->success()
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
