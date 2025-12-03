<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdmissionWidget extends ChartWidget
{
    protected ?string $heading = 'Admission Widget';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $now = Carbon::now();
        $startDate = $now->copy()->subYears(2)->startOfMonth();

        $records = User::query()
            ->has('student')
            ->where('created_at', '>=', $startDate->toDateTimeString())
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

        // ... rest of the method remains the same ...
        $labels = $records->pluck('month_name')->toArray();
        $data = $records->pluck('count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'New Admissions',
                    'data' => $data,
                    'borderColor' => '#0596ad',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
