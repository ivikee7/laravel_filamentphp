<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;

class IDCard extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.admin.resources.user-resource.pages.i-d-card';

    public array $attendanceRecords = [];

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $today = Carbon::today()->toDateString();

        // Fetch today's attendance types
        $this->attendanceRecords = Attendance::where('user_id', $this->record->id)
            ->whereDate('created_at', $today)
            ->pluck('type')
            ->toArray();
    }

    // ✅ Place "View" & "Transport" buttons in the header
    protected function getHeaderActions(): array
    {
        return [
            Action::make('View')
                ->url(fn(): string => UserResource::getUrl('view', [$this->record?->id])) // Use `?->` to avoid null errors
                ->icon('heroicon-o-eye'),

            Action::make('Transport')
                ->url(fn(): string => UserResource::getUrl('transport', [$this->record?->id])) // Use `?->`
                ->icon('heroicon-o-truck'),
        ];
    }

    protected function getActions(): array
    {
        $today = Carbon::today()->toDateString(); // Get today's date

        $attendanceRecords = Attendance::where('user_id', $this->record->id)
            ->whereDate('created_at', $today) // ✅ Check if attendance exists for today
            ->pluck('type')
            ->toArray();

        return [
            // Show "Entered in Bus" only if NOT marked today
            !in_array('entredInBus', $attendanceRecords)
                ? Action::make('entredInBus')
                ->label('Entered in Bus')
                ->action(fn() => $this->markAttendance('entredInBus'))
                : null,

            // Show "Entered in Campus" only if NOT marked today
            !in_array('entredInCampus', $attendanceRecords)
                ? Action::make('entredInCampus')
                ->label('Entered in Campus')
                ->action(fn() => $this->markAttendance('entredInCampus'))
                : null,

            // Show "Exit from Campus" only if NOT marked today
            !in_array('exitFromCampus', $attendanceRecords)
                ? Action::make('exitFromCampus')
                ->label('Exit from Campus')
                ->action(fn() => $this->markAttendance('exitFromCampus'))
                : null,

            // Show "Exit from Bus" only if NOT marked today
            !in_array('exitFromBus', $attendanceRecords)
                ? Action::make('exitFromBus')
                ->label('Exit from Bus')
                ->action(fn() => $this->markAttendance('exitFromBus'))
                : null,
        ];
    }

    public function getModel(): string
    {
        return User::class;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Attendance::query()->where('user_id', $this->record->id))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->wrap(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            null => 'Empty',
                            "entredInCampus" => 'Entred in Campus',
                            "exitFromCampus" => 'Exit from Campus',
                            "entredInBus" => 'Entred in Bus',
                            "exitFromBus" => 'Exit from Bus',
                            default => 'OK'
                        };
                    })
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()
                    ->wrap(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                // Add actions if needed
            ])
            ->bulkActions([
                // Add bulk actions if needed
            ]);
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
