<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RegistrationAdmissionWidget extends ChartWidget
{
    protected ?string $heading = 'Registration Admission Widget';

    protected function getData(): array
    {
        $startDate = Carbon::now()->subMonths(12)->startOfYear();

        // 1. Fetch data grouped by year and month
        $data = Registration::select(
            DB::raw('count(id) as count'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month')
        )
            ->whereHas('student')
            ->withTrashed()
            ->where('created_at', '>=', $startDate)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->groupBy('year'); // Group the results by year for easier processing

        // 2. Prepare datasets for Chart.js
        $datasets = [];
        $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']; // Example colors

        foreach ($data as $year => $monthlyRecords) {
            // Initialize data points for all 12 months with 0
            $monthlyDataPoints = array_fill(1, 12, 0);

            // Fill in the actual counts where data exists
            foreach ($monthlyRecords as $record) {
                $monthlyDataPoints[$record->month] = $record->count;
            }

            // Get a color from our array, cycle through them using Arr::get
            $color = Arr::get($colors, count($datasets) % count($colors));

            $datasets[] = [
                'label' => $year, // Label the line with the year
                'data' => array_values($monthlyDataPoints), // Use just the counts
                'backgroundColor' => $color . '40', // light background fill
                'borderColor' => $color,
                'tension' => 0.4,
                'borderWidth' => 2,
            ];
        }

        return [
            'datasets' => $datasets,
            // The labels on the X-axis are fixed Jan-Dec
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
