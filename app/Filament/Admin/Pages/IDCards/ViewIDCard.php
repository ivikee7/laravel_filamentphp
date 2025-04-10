<?php

namespace App\Filament\Admin\Pages\IDCards;

use App\Filament\Admin\Resources\UserResource;
use App\Models\Attendance;
use App\Models\MessageTemplate;
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

        Attendance::create([
            'user_id' => $this->record->id,
            'type' => $type,
        ]);

        // dd($type);

        // ✅ Refresh attendance records immediately
        $this->updateAttendanceRecords();

        Notification::make()
            ->title(ucwords(str_replace('_', ' ', $type)) . ' marked successfully')
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
}
