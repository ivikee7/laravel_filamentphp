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
        // Define how far back you want the timeline to start (e.g., 2 years ago)
        $startDate = Carbon::now()->subYears(2)->startOfMonth();

        // Common query parts
        $querySelect = [
            DB::raw('count(id) as count'),
            // Use CONCAT or similar for a clean YYYY-MM label for sorting and display
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period"),
        ];

        // --- 1. Fetch ALL Registrations chronologically ---
        $allRegistrations = Registration::select($querySelect)
            ->withTrashed()
            ->where('created_at', '>=', $startDate)
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        // --- 2. Fetch Admissions chronologically using whereHas('student') ---
        $admissions = Registration::select($querySelect)
            ->whereHas('student') // Use your existing relationship
            ->withTrashed()
            ->where('created_at', '>=', $startDate)
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        // We need a complete list of all periods (e.g., 2023-01, 2023-02, ..., 2025-11)
        $allPeriods = $allRegistrations->pluck('period')->merge($admissions->pluck('period'))->unique()->sort()->values();

        // Helper to map results to the full timeline, filling gaps with zeros
        $mapToTimelineData = function ($results, $allPeriods) {
            $dataMap = $results->pluck('count', 'period');
            return $allPeriods->map(function ($period) use ($dataMap) {
                return $dataMap->get($period, 0);
            })->toArray();
        };

        $registrationData = $mapToTimelineData($allRegistrations, $allPeriods);
        $admissionsData = $mapToTimelineData($admissions, $allPeriods);

        // Map the YYYY-MM labels to a cleaner format for the chart's footer (e.g., "Jan 23", "Feb 23")
        $chartLabels = $allPeriods->map(function ($period) {
            return Carbon::createFromFormat('Y-m', $period)->format('M y');
        })->toArray();

        // Colors
        $registrationColor = '#FF6384'; // Red
        $admissionColor = '#36A2EB';    // Blue

        return [
            'datasets' => [
                [
                    'label' => 'Total Registrations',
                    'data' => $registrationData,
                    'backgroundColor' => $registrationColor . '40',
                    'borderColor' => $registrationColor,
                    'tension' => 0.4,
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Total Admissions',
                    'data' => $admissionsData,
                    'backgroundColor' => $admissionColor . '40',
                    'borderColor' => $admissionColor,
                    'tension' => 0.4,
                    'borderWidth' => 2,
                ],
            ],
            // These labels now represent the full chronological timeline
            'labels' => $chartLabels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
