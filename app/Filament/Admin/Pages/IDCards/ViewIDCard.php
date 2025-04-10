<?php

namespace App\Filament\Admin\Pages\IDCards;

use App\Filament\Admin\Resources\UserResource;
use App\Models\Attendance;
use App\Models\MessageTemplate;
use App\Models\SmsProvider;
use App\Models\User;
use App\Services\SMSService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ViewIDCard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.i-d-cards.view-i-d-card';

    protected static ?string $slug = 'id-cards/{record}';
    protected static ?string $navigationGroup = 'IDCard';
    protected static ?string $navigationLabel = 'View ID Card';

    public $record = null;
    public array $attendanceRecords = [];

    public function mount(int $record): void
    {
        $this->record = User::findOrFail($record);

        $today = Carbon::today()->toDateString();

        // Fetch today's attendance types
        $this->attendanceRecords = Attendance::where('user_id', $this->record->id)
            ->whereDate('created_at', $today)
            ->pluck('type')
            ->toArray();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getRouteName(?string $panel = null): string
    {
        return parent::generateRouteName('id-cards.view', $panel);
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

        // dd($attendance);

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

    // ✅ Function to refresh attendance records without reloading the page
    public function updateAttendanceRecords(): void
    {
        $this->attendanceRecords = Attendance::where('user_id', $this->record->id)
            ->whereDate('created_at', now()->toDateString())
            ->pluck('type')
            ->toArray();
    }

    public static function canAccess(): bool
    {
        // return auth()->user()?->can('view IDCard', static::class);
        return true;
    }

    public static function canMarkAttendance(): bool
    {
        return auth()->user()?->can('create IDCard', static::class);
    }
}
