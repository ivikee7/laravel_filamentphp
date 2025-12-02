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
        // Define common base query logic
        $baseQuery = function ($query) {
            return $query
                ->select(
                    DB::raw('count(id) as count'),
                    // We only group by month now
                    DB::raw('MONTH(created_at) as month')
                )
                ->withTrashed()
                ->groupBy('month')
                ->orderBy('month', 'asc');
        };

        // --- 1. Fetch ALL Registrations data (Aggregated by month, all years) ---
        $allRegistrationsAggregated = $baseQuery(Registration::query())->get();

        // --- 2. Fetch Admissions data (Aggregated by month, all years) ---
        $admissionsAggregated = $baseQuery(Registration::whereHas('student'))->get();

        // Colors for the two lines:
        $registrationColor = '#FF6384'; // Red
        $admissionColor = '#36A2EB';    // Blue

        // Helper function to map flat results into a 12-month array
        $mapToMonthlyArray = function ($results) {
            $monthlyDataPoints = array_fill(0, 12, 0); // 0-indexed array for Chart.js
            foreach ($results as $record) {
                // DB month (1-12) maps to array index (0-11)
                $monthlyDataPoints[$record->month - 1] = $record->count;
            }
            return $monthlyDataPoints;
        };

        $registrationData = $mapToMonthlyArray($allRegistrationsAggregated);
        $admissionsData = $mapToMonthlyArray($admissionsAggregated);

        return [
            'datasets' => [
                [
                    'label' => 'Total Registrations (All Time Monthly Avg)',
                    'data' => $registrationData,
                    'backgroundColor' => $registrationColor . '40',
                    'borderColor' => $registrationColor,
                    'tension' => 0.4,
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Admissions (All Time Monthly Avg)',
                    'data' => $admissionsData,
                    'backgroundColor' => $admissionColor . '40',
                    'borderColor' => $admissionColor,
                    'tension' => 0.4,
                    'borderWidth' => 2,
                ],
            ],
            // The labels on the X-axis are fixed Jan-Dec
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
