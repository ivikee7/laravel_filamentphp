<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Livewire\Attributes\On;

class MonthlyAttendance extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = UserResource::class;
    protected static string $view = 'filament.resources.user-resource.pages.monthly-attendance';

    public int $year;
    public int $month;

    public function mount(): void
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('')
                ->schema([
                    Select::make('month')
                        ->label('')
                        ->options([
                            '1' => 'January',
                            '2' => 'February',
                            '3' => 'March',
                            '4' => 'April',
                            '5' => 'May',
                            '6' => 'June',
                            '7' => 'July',
                            '8' => 'August',
                            '9' => 'September',
                            '10' => 'October',
                            '11' => 'November',
                            '12' => 'December',
                        ])
                        ->default($this->month)
                        ->reactive()
                        ->afterStateUpdated(fn($state) => $this->updateTable()),

                    Select::make('year')
                        ->label('')
                        ->options(
                            collect(range(Carbon::now()->year - 10, Carbon::now()->year + 1))
                                ->mapWithKeys(fn($year) => [$year => $year])
                        )
                        ->default($this->year)
                        ->reactive()
                        ->afterStateUpdated(fn($state) => $this->updateTable()),
                ])->columns(4)
        ];
    }

    #[On('refreshTable')]
    public function updateTable(): void
    {
        $this->dispatch('refreshTable');
    }

    public function table(Table $table): Table
    {
        // Correctly get days in the selected month
        $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;

        $columns = [
            Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
            Tables\Columns\TextColumn::make('name')->label('Name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('roles.name')->label('Role')->badge()
                ->searchable()->sortable(),
        ];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($this->year, $this->month, $day)->toDateString();
            $columns[] = Tables\Columns\TextColumn::make("formattedAttendance.{$date}")
                ->label($day)
                ->html();
        }

        return $table
            ->query(
                User::query()
                    ->with(['attendances' => function ($query) {
                        $query->whereMonth('created_at', $this->month)
                            ->whereYear('created_at', $this->year);
                    }])
            )
            ->columns($columns);
    }
}
