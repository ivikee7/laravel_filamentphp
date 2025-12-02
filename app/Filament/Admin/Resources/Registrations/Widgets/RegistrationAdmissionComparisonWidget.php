<?php

namespace App\Filament\Admin\Resources\Registrations\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RegistrationAdmissionComparisonWidget extends ChartWidget
{
    protected ?string $heading = 'Registration vs Admission Comparison Chart';

    protected int|string|array $columnSpan = 'full';

    protected bool $isCollapsible = true;
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $startDate = Carbon::now()->subYears(1)->startOfYear();
        $colors = ['#FF6384', '#36A2EB']; // Red for Registrations, Blue for Admissions

        // --- 1. Fetch ALL Registrations data (Registrations dataset) ---
        $allRegistrations = Registration::select(
            DB::raw('count(id) as count'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month')
        )
            ->withTrashed()
            ->where('created_at', '>=', $startDate)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // --- 2. Fetch Admissions data (Admissions dataset) ---
        // Assuming your Registration model has a 'student' relationship (hasOne/belongsTo Student model)
        $admissions = Registration::select(
            DB::raw('count(registrations.id) as count'),
            DB::raw('YEAR(registrations.created_at) as year'),
            DB::raw('MONTH(registrations.created_at) as month')
        )
            ->whereHas('student')
            ->withTrashed()
            ->where('registrations.created_at', '>=', $startDate)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();


        // Group data by year for processing
        $groupedRegistrations = $allRegistrations->groupBy('year');
        $groupedAdmissions = $admissions->groupBy('year');

        $datasets = [];

        // Helper to process and add a dataset to the array
        $processDataset = function ($dataCollection, $label, $color, &$datasetsArray) {
            foreach ($dataCollection as $year => $monthlyRecords) {
                // Initialize data points for all 12 months with 0
                $monthlyDataPoints = array_fill(1, 12, 0);

                // Fill in the actual counts where data exists
                foreach ($monthlyRecords as $record) {
                    $monthlyDataPoints[$record->month] = $record->count;
                }

                $datasetsArray[] = [
                    'label' => $label . ' ' . $year, // e.g., 'Registrations 2024'
                    'data' => array_values($monthlyDataPoints),
                    'backgroundColor' => $color . '40',
                    'borderColor' => $color,
                    'tension' => 0.4,
                    'borderWidth' => 2,
                ];
            }
        };

        // Add both datasets to the final array
        $processDataset($groupedRegistrations, 'Total Registrations', $colors[0], $datasets);
        $processDataset($groupedAdmissions, 'Admissions (Students)', $colors[1], $datasets);


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
