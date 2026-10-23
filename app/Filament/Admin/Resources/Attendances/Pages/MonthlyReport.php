<?php

namespace App\Filament\Admin\Resources\Attendances\Pages;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Admin\Resources\Attendances\AttendanceResource;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Url;

class MonthlyReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = AttendanceResource::class;

    protected string $view = 'filament.admin.resources.attendances.pages.monthly-report';

    #[Url(as: 'from_date')]
    public ?string $fromDate = null;

    #[Url(as: 'to_date')]
    public ?string $toDate = null;

    public function mount(): void
    {
        if (!$this->fromDate) {
            $this->fromDate = now()->startOfMonth()->toDateString();
        }
        if (!$this->toDate) {
            $this->toDate = now()->endOfMonth()->toDateString();
        }
    }

    public function getHeaderActions(): array{
        return [
            Action::make('students')
                ->icon('heroicon-o-academic-cap')
                ->url(fn() => AttendanceResource::getUrl('students')),
            Action::make('employees')
                ->icon('heroicon-o-briefcase')
                ->url(fn() => AttendanceResource::getUrl('employees')),
            Action::make('Log')
                ->label('All Logs')
                ->icon('heroicon-o-table-cells')
                ->url(fn() => AttendanceResource::getUrl('index')),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->whereDoesntHave('roles', function ($query) {
                        $query->whereIn('name', ['Super Admin']);
                    })
                    ->when($this->fromDate && $this->toDate, function ($query) {
                        $query->whereHas('attendances', function ($q) {
                            $q->whereBetween('created_at', [
                                Carbon::parse($this->fromDate)->startOfDay(),
                                Carbon::parse($this->toDate)->endOfDay()
                            ]);
                        });
                    })
                    ->with([
                        'roles',
                        'student.classAssignment.class',
                        'student.classAssignment.section',
                        'attendances' => function ($q) {
                            if ($this->fromDate && $this->toDate) {
                                $q->whereBetween('created_at', [
                                    Carbon::parse($this->fromDate)->startOfDay(),
                                    Carbon::parse($this->toDate)->endOfDay()
                                ]);
                            }
                        }
                    ])
            )
            ->columns($this->getAttendanceColumns())
            ->filters([
                Filter::make('date_range')
                    ->schema([
                        DatePicker::make('from_date')
                            ->label('From Date')
                            ->default(now()->startOfMonth())
                            ->displayFormat('Y-m-d')
                            ->format('Y-m-d')
                            ->reactive() // Syncs state instantly on change
                            ->afterStateUpdated(fn ($state) => $this->fromDate = $state),
                        DatePicker::make('to_date')
                            ->label('To Date')
                            ->default(now()->endOfMonth())
                            ->displayFormat('Y-m-d')
                            ->format('Y-m-d')
                            ->reactive() // Syncs state instantly on change
                            ->afterStateUpdated(fn ($state) => $this->toDate = $state),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $this->fromDate = $data['from_date'] ?? $this->fromDate;
                        $this->toDate = $data['to_date'] ?? $this->toDate;

                        return $query->whereHas('attendances', function ($q) {
                            $q->whereBetween('created_at', [
                                Carbon::parse($this->fromDate)->startOfDay(),
                                Carbon::parse($this->toDate)->endOfDay()
                            ]);
                        });
                    })
            ])
            ->bulkActions([
                BulkAction::make('print_selected')
                    ->label('Print Selected Report')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->action(function (Collection $records) {
                        $startDate = Carbon::parse($this->fromDate ?? now()->startOfMonth())->startOfDay();
                        $endDate = Carbon::parse($this->toDate ?? now()->endOfMonth())->endOfDay();

                        $dates = [];
                        $dateLabels = [];
                        $cursor = $startDate->copy();

                        while ($cursor <= $endDate) {
                            $dates[] = $cursor->toDateString();
                            $dateLabels[] = $cursor->format('d-m');
                            $cursor->addDay();
                        }

                        $columns = ['ID', 'Name', 'Role', 'Class', 'Section'];
                        $columnKeys = ['id', 'name', 'role', 'class', 'section'];

                        foreach ($dateLabels as $label) {
                            $columns[] = $label;
                        }
                        foreach ($dates as $d) {
                            $columnKeys[] = $d;
                        }

                        $recordsArray = [];
                        foreach ($records as $rec) {
                            $row = [
                                'id' => $rec->id,
                                'name' => $rec->name,
                                'role' => $rec->roles->pluck('name')->join(', '),
                                'class' => data_get($rec, 'student.classAssignment.class.name', '-'),
                                'section' => data_get($rec, 'student.classAssignment.section.name', '-'),
                            ];

                            foreach ($dates as $d) {
                                $attendances = collect();
                                if ($rec->relationLoaded('attendances')) {
                                    $attendances = collect($rec->attendances)->filter(fn($a) => Carbon::parse($a->created_at)->toDateString() === $d)->sortBy('created_at');
                                } else {
                                    $attendances = $rec->attendances()->whereDate('created_at', $d)->get()->sortBy('created_at');
                                }

                                if ($attendances->isEmpty()) {
                                    $row[$d] = '-';
                                } else {
                                    $in = Carbon::parse($attendances->first()->created_at)->format('H:i');
                                    $out = Carbon::parse($attendances->last()->created_at)->format('H:i');
                                    $row[$d] = $in . "\n" . $out;
                                }
                            }

                            $recordsArray[] = $row;
                        }

                        $print_data = [
                            'start_date' => $startDate->format('d-m-Y'),
                            'end_date' => $endDate->format('d-m-Y'),
                            'columns' => $columns,
                            'column_keys' => $columnKeys,
                            'records' => $recordsArray,
                        ];

                        Session::put('print_data', $print_data);

                        $printUrl = url('/admin/attendances/print-monthly-report');
                        $this->js("window.open('{$printUrl}', '_blank');");
                    })
            ])
            ->columnManagerColumns(4)
            ->paginated([5, 10, 25, 50, 100])
            ->selectable()
            ->deselectAllRecordsWhenFiltered();
    }

    protected function getAttendanceColumns(): array
    {
        $columns = [
            TextColumn::make('id')->label('ID')->sortable()->searchable(),
            TextColumn::make('name')->label('Name')->sortable()->searchable()->wrap(),
            TextColumn::make('roles.name')->label('Role')->sortable()->searchable()->wrap()
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('student.classAssignment.class.name')->label('Class')->sortable()->searchable()->wrap()
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('student.classAssignment.section.name')->label('Section')->sortable()->searchable()->wrap()
                ->toggleable(isToggledHiddenByDefault: false),
        ];

        $startDate = Carbon::parse($this->fromDate ?? now()->startOfMonth())->startOfDay();
        $endDate = Carbon::parse($this->toDate ?? now()->endOfMonth())->endOfDay();

        $loopLimit = 31;
        $iteration = 0;

        $cursor = $startDate->copy();
        while ($cursor <= $endDate && $iteration < $loopLimit) {
            $dateString = $cursor->toDateString();
            $formattedDate = $cursor->format('dmy');

            $columns[] = TextColumn::make("attendance_day_" . $cursor->format('Ymd'))
                ->label($formattedDate)
                ->getStateUsing(function ($record) use ($dateString) {
                    $attendance = $record->attendances
                        ->filter(function ($att) use ($dateString) {
                            return Carbon::parse($att->created_at)->toDateString() === $dateString;
                        })
                        ->sortBy('created_at');

                    if ($attendance->isEmpty()) {
                        return '-';
                    }

                    $in = Carbon::parse($attendance->first()->created_at)->format('H:i');
                    $out = Carbon::parse($attendance->last()->created_at)->format('H:i');

                    return "$in\n$out";
                })
                ->toggleable(isToggledHiddenByDefault: false)
                ->wrap();

            $cursor->addDay();
            $iteration++;
        }

        return $columns;
    }
}
