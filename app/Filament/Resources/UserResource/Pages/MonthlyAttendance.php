<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class MonthlyAttendance extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = UserResource::class;
    protected static string $view = 'filament.resources.user-resource.pages.monthly-attendance';

    public int $year = 2025;
    public int $month = 3;

    public function mount(): void
    {
        $this->updateAttendanceData();
    }

    public function updateAttendanceData(): void
    {
        // Refresh table when month or year changes
        $this->dispatch('refreshTable');
    }

    public function table(Table $table): Table
    {
        $daysInMonth = Carbon::now()->daysInMonth;

        $columns = [
            Tables\Columns\TextColumn::make('id')->label('ID')->searchable(),
            Tables\Columns\TextColumn::make('name')->label('Name')->searchable(),
        ];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::now()->format("Y-m-{$day}");
            $columns[] = Tables\Columns\TextColumn::make("formattedAttendance.{$date}")
                ->label($day)
                ->html();
        }

        return $table
            ->query(User::query()->with('attendances')) // Ensure attendance is loaded
            ->columns($columns);
    }

    protected function getUserQuery(): Builder
    {
        return User::query()
            ->with('attendances')
            ->select('id', 'name');
    }

    protected function formatAttendance(User $user): array
    {
        $formattedAttendance = [];

        for ($day = 1; $day <= Carbon::create($this->year, $this->month)->daysInMonth; $day++) {
            $date = Carbon::create($this->year, $this->month, $day)->toDateString();
            $formattedAttendance[$date] = '';

            foreach ($user->attendances as $record) {
                if (Carbon::parse($record->created_at)->toDateString() === $date) {
                    $time = Carbon::parse($record->created_at)->format('H:i');
                    $formattedAttendance[$date] .= ($formattedAttendance[$date] ? '<br>' : '') .
                        ($record->type === 'in' ? 'In: ' : 'Out: ') . $time;
                }
            }
        }

        return $formattedAttendance;
    }
}
