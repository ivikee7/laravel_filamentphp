<?php

namespace App\Filament\Admin\Resources\Stores\Widgets;

use App\Models\StoreInvoice;
use App\Models\StoreInvoiceTransaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CollectionStatusChart extends ChartWidget
{
    protected ?string $heading = 'Collection Status Chart';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    // Define colors for the segments (Collected, Discounted, Due)
    protected static array $colors = [
        'rgb(75, 192, 192)', // Green for Collected
        'rgb(54, 162, 235)', // Blue for Discounted
        'rgb(255, 99, 132)', // Red for Due
    ];

    protected function getData(): array
    {
        $startDate = Carbon::now()->subYear()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Manually aggregate data using DB facade or Eloquent Builder
        $dailyCollections = StoreInvoiceTransaction::query()
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total_collected'),
            ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Initialize arrays for chart data (values) and labels (dates)
        $data = [];
        $labels = [];

        // Loop through the results and format them for Chart.js
        foreach ($dailyCollections as $collection) {
            // Push the aggregate value
            $data[] = $collection->total_collected;
            // Push the formatted date label (e.g., "Jan 01")
            $labels[] = Carbon::parse($collection->date)->format('M d');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Amount Collected',
                    'data' => $data,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.1)',
                    'tension' => 0.4, // Smooth the line
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
