<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Enquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TodayEnquiryWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 1;
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        $yesterdayStart = Carbon::yesterday()->startOfDay();
        $yesterdayEnd = Carbon::yesterday()->endOfDay();

        $todayCount = Enquiry::query()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->count();

        $yesterdayCount = Enquiry::query()
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->count();

        $difference = $todayCount - $yesterdayCount;
        $percentageChange = $yesterdayCount > 0 ? round(($difference / $yesterdayCount) * 100, 1) : ($todayCount > 0 ? 100 : 0);

        $isPositive = $percentageChange >= 0;
        $trendIcon = $isPositive ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $trendDescription = ($isPositive ? '+' : '') . $percentageChange . '% vs yesterday';
        $color = $todayCount > 0 ? 'success' : 'warning';

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartData[] = Enquiry::query()
                ->whereDate('created_at', $date)
                ->count();
        }

        return [
            Stat::make("Today's Enquiries", $todayCount)
                ->description($trendDescription)
                ->descriptionIcon($trendIcon)
                ->color($color)
                ->chart($chartData),
        ];
    }
}
