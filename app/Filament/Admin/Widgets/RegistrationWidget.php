<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RegistrationWidget extends ChartWidget
{
    protected ?string $heading = 'Registrations Trend';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 1;
    protected ?string $pollingInterval = '60s';

    protected function getFilters(): ?array
    {
        return [
            '1' => 'Past 1 Year',
            '2' => 'Past 2 Years',
            '3' => 'Past 3 Years',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter ?? '2';
        $startDate = Carbon::now()->subYears((int)$activeFilter)->startOfYear();

        $data = Registration::query()
            ->select(
                DB::raw('count(id) as count'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month')
            )
            ->withTrashed()
            ->where('created_at', '>=', $startDate)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->groupBy('year');

        $datasets = [];
        $colors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'];

        foreach ($data as $year => $monthlyRecords) {
            $monthlyDataPoints = array_fill(1, 12, 0);

            foreach ($monthlyRecords as $record) {
                $monthlyDataPoints[$record->month] = $record->count;
            }

            $color = Arr::get($colors, count($datasets) % count($colors));

            $datasets[] = [
                'label' => (string) $year,
                'data' => array_values($monthlyDataPoints),
                'backgroundColor' => $color . '15',
                'borderColor' => $color,
                'fill' => true,
                'tension' => 0.35,
                'borderWidth' => 2.5,
                'pointRadius' => 3,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
