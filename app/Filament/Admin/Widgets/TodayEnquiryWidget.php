<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Enquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TodayEnquiryWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    protected function getStats(): array
    {
        // Get today's date range (start of day to end of day)
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        // Query the User model for today's count with the 'student' relationship
        $todayEnquiriesCount = Enquiry::query()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->count();

        $color = ($todayEnquiriesCount > 0) ? 'success' : 'warning';

        // Format the count into a Filament Stat card
        return [
            Stat::make('Today\'s Enquiries', $todayEnquiriesCount)
                ->columnSpan(3)
                ->description('New students signed up today')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($color),
        ];
    }
}
