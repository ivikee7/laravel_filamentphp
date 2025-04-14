<?php

namespace App\Filament\Admin\Resources\AttendanceResource\Pages;

use App\Filament\Admin\Resources\AttendanceResource;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class MonthlyReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = AttendanceResource::class;

    protected static string $view = 'filament.admin.resources.attendance-resource.pages.monthly-report';

    #[Url(as: 'from_date')]
    public ?string $fromDate = null;

    #[Url(as: 'to_date')]
    public ?string $toDate = null;

    public function mount(): void
    {
        $this->fromDate = null;
        $this->toDate = null;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                $user = User::query()->with(['attendances' => function ($q) {
                    if ($this->fromDate) {
                        $q->whereDate('created_at', '>=', $this->fromDate);
                    }
                    if ($this->toDate) {
                        $q->whereDate('created_at', '<=', $this->toDate);
                    }
                }])
            )
            ->columns($this->getAttendanceColumns())
            ->filters([
                Filter::make('date_range')
                    ->form([
                        DatePicker::make('from_date')
                            ->label('From Date')
                            ->displayFormat('Y-m-d')
                            ->format('Y-m-d'),
                        DatePicker::make('to_date')
                            ->label('To Date')
                            ->displayFormat('Y-m-d')
                            ->format('Y-m-d')
                            ->after('from_date'),
                    ])
                    ->query(function (Builder $query, array $data, MonthlyReport $livewire): Builder {
                        $livewire->fromDate = $data['from_date'] ?? null;
                        $livewire->toDate = $data['to_date'] ?? null;

                        if ($livewire->fromDate) {
                            $query->whereHas('attendances', function (Builder $q) use ($livewire) {
                                $q->whereDate('created_at', '>=', $livewire->fromDate);
                            });
                        }
                        if ($livewire->toDate) {
                            $query->whereHas('attendances', function (Builder $q) use ($livewire) {
                                $q->whereDate('created_at', '<=', $livewire->toDate);
                            });
                        }

                        return $query;
                    })
                    ->label('Date Range'),
            ]);
    }

    protected function getAttendanceColumns(): array
    {
        $columns = [
            Tables\Columns\TextColumn::make('id')->label('ID')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('name')->label('Name')->sortable()->searchable()->wrap(),
            Tables\Columns\TextColumn::make('roles.name')->label('Role')->sortable()->searchable()->wrap()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('currentStudent.currentClassAssignment.class.name')->label('Class')->sortable()->searchable()->wrap()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('currentStudent.currentClassAssignment.section.name')->label('Section')->sortable()->searchable()->wrap()
                ->toggleable(isToggledHiddenByDefault: true),
        ];

        $startDate = null;
        $endDate = null;

        if ($this->fromDate && $this->toDate) {
            $startDate = Carbon::parse($this->fromDate)->startOfDay();
            $endDate = Carbon::parse($this->toDate)->endOfDay();
        } else {
            $now = now();
            $startDate = Carbon::create($now->year, $now->month, 1)->startOfDay();
            $endDate = Carbon::create($now->year, $now->month, $now->daysInMonth)->endOfDay();
        }

        if ($startDate && $endDate) {
            while ($startDate <= $endDate) {
                $dateString = $startDate->toDateString();
                $formattedDate = $startDate->format('dmy');

                $columns[] = Tables\Columns\TextColumn::make("attendance_day_" . $startDate->format('Ymd'))
                    ->label($formattedDate)
                    ->getStateUsing(function ($record) use ($dateString) {
                        $attendance = $record->attendances
                            ->filter(function ($att) use ($dateString) {
                                return Carbon::parse($att->created_at)->toDateString() === $dateString;
                            })
                            ->sortBy('created_at'); // Sort attendances by time

                        if ($attendance->isEmpty()) {
                            return '-';
                        }

                        $in = $attendance->first()->created_at->format('H:i');
                        $out = $attendance->last()->created_at->format('H:i');

                        return "$in\n$out"; // Display in and out on separate lines
                    })
                    ->wrap();

                $startDate->addDay();
            }
        }

        return $columns;
    }
}
