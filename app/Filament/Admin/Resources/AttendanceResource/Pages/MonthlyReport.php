<?php

namespace App\Filament\Admin\Resources\AttendanceResource\Pages;

use App\Filament\Admin\Resources\AttendanceResource;
use App\Models\User;
use Carbon\Carbon;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MonthlyReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = AttendanceResource::class;

    protected static string $view = 'filament.admin.resources.attendance-resource.pages.monthly-report';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $month = $this->getTable()->getFilters()['month']->getState() ?? now()->format('m');
                $year = $this->getTable()->getFilters()['year']->getState() ?? now()->format('Y');

                return User::query()
                    ->role('Student')
                    ->with(['attendances' => function ($q) use ($month, $year) {
                        $q->whereMonth('created_at', $month)
                            ->whereYear('created_at', $year);
                    }]);
            })
            ->columns($this->getAttendanceColumns())
            ->filters([
                SelectFilter::make('month')
                    ->options([
                        '01' => 'January',
                        '02' => 'February',
                        '03' => 'March',
                        '04' => 'April',
                        '05' => 'May',
                        '06' => 'June',
                        '07' => 'July',
                        '08' => 'August',
                        '09' => 'September',
                        '10' => 'October',
                        '11' => 'November',
                        '12' => 'December',
                    ])
                    ->default(now()->format('m'))
                    ->query(fn($query) => $query), // 🔐 Prevent Filament from auto-applying this filter


                SelectFilter::make('year')
                    ->options(
                        collect(range(now()->year, now()->year - 5))
                            ->mapWithKeys(fn($y) => [$y => $y])
                            ->toArray()
                    )
                    ->default(now()->format('Y'))
                    ->query(fn($query) => $query), // 🔐 Prevent Filament from auto-applying this filter
            ])
            // ->paginated([5, 10, 25, 50, 100])
            ;
    }

    protected function getAttendanceColumns(): array
    {
        $daysInMonth = now()->daysInMonth;

        $staticColumns = [
            Tables\Columns\TextColumn::make('id')->label('ID')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('name')->label('Name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('roles.name')->label('Role')->sortable()->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('currentStudent.currentClassAssignment.class.name')->label('Class')->sortable()->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('currentStudent.currentClassAssignment.section.name')->label('Section')->sortable()->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];

        $dayColumns = array_map(function ($day) {
            $date = now()->startOfMonth()->addDays($day - 1)->toDateString();

            return Tables\Columns\TextColumn::make("attendance_day_{$day}")
                ->label((string) $day)
                ->getStateUsing(function ($record) use ($date) {
                    $attendance = $record->attendances
                        ->filter(fn($att) => \Carbon\Carbon::parse($att->created_at)->toDateString() === $date);

                    if ($attendance->isEmpty()) return '-';

                    $in = \Carbon\Carbon::parse($attendance->first()->created_at)->format('H:i');
                    $out = \Carbon\Carbon::parse($attendance->last()->created_at)->format('H:i');

                    return "$in\n$out";
                })
                ->wrap();
        }, range(1, $daysInMonth));

        return array_merge($staticColumns, $dayColumns);
    }
}
