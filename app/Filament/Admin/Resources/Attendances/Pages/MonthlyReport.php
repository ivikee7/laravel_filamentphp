<?php

namespace App\Filament\Admin\Resources\Attendances\Pages;

//use App\Filament\Admin\Resources\Attendances\Pages\MonthlyReport;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Admin\Resources\Attendances\AttendanceResource;
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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Url;

class MonthlyReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = AttendanceResource::class;

    protected string $view = 'filament.admin.resources.attendance-resource.pages.monthly-report';

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
                User::query()->whereDoesntHave('roles', function ($query) {
                    $query->whereIn('name', ['Super Admin']);
                })->with(['attendances' => function ($q) {
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
                    ->schema([
                        DatePicker::make('from_date')
                            ->label('From Date')
                            ->displayFormat('Y-m-d')
                            ->format('Y-m-d')
                            ->reactive(),
                        DatePicker::make('to_date')
                            ->label('To Date')
                            ->displayFormat('Y-m-d')
                            ->format('Y-m-d')
                            ->after('from_date')
                            ->reactive(),
                    ])
                    ->query(function (Builder $query, array $data, MonthlyReport $livewire): Builder {
                        $livewire->fromDate = $data['from_date'] ?? null;
                        $livewire->toDate = $data['to_date'] ?? null;

                        $query->when($livewire->fromDate, fn($q) => $q->whereHas('attendances', fn($sq) => $sq->whereDate('created_at', '>=', $livewire->fromDate)));
                        $query->when($livewire->toDate, fn($q) => $q->whereHas('attendances', fn($sq) => $sq->whereDate('created_at', '<=', $livewire->toDate)));

                        return $query;
                    })
            ])
            ->bulkActions([
                BulkAction::make('print_selected')
                    ->label('Print Selected')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->action(function (Collection $records) {
                        // Prepare a serializable print payload (simple arrays / strings only)
                        $columns = [];
                        $columnKeys = [];
                        foreach ($this->getTable()->getColumns() as $col) {
                            // Try to extract label & name defensively
                            $label = null;
                            $name = null;
                            if (method_exists($col, 'getLabel')) {
                                $label = $col->getLabel();
                            } elseif (property_exists($col, 'label')) {
                                $label = $col->label;
                            }
                            if (method_exists($col, 'getName')) {
                                $name = $col->getName();
                            } elseif (property_exists($col, 'name')) {
                                $name = $col->name;
                            }
                            $columns[] = $label ?? $name ?? '';
                            $columnKeys[] = $name ?? null;
                        }

                        $recordsArray = array_map(function ($r) use ($columnKeys) {
                            // $r may be an array or model; normalize to array
                            $row = is_array($r) ? $r : (is_object($r) && method_exists($r, 'toArray') ? $r->toArray() : (array)$r);
                            // Keep whole row; view will access nested fields safely using data_get
                            return $row;
                        }, $records->toArray());

                        $print_data = [
                            'start_date' => $this->fromDate,
                            'end_date' => $this->toDate,
                            'columns' => $columns,
                            'column_keys' => $columnKeys,
                            'records' => $recordsArray,
                        ];

                        // Store the data in the session (store array, not JSON)
                        Session::put('print_data', $print_data);

                        // Open the new page
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
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('student.classAssignment.section.name')->label('Section')->sortable()->searchable()->wrap()
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

                $columns[] = TextColumn::make("attendance_day_" . $startDate->format('Ymd'))
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
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->wrap();

                $startDate->addDay();
            }
        }

        return $columns;
    }

    public function printRecords(array $recordIds): void
    {
        // Generate the URL for the raw print page (adjust URL path if necessary)
        $printUrl = url('/admin/attendances/print-monthly-report?' . http_build_query([
                'user_ids' => $recordIds,
                'from_date' => $this->fromDate,
                'to_date' => $this->toDate,
            ]));

        // Open the new page in a new window/tab
        $this->js("window.open('{$printUrl}', '_blank');");
    }
}
