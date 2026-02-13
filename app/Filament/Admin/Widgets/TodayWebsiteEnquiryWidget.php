<?php

namespace App\Filament\Admin\Widgets;

use App\Models\WebsiteEnquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TodayWebsiteEnquiryWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    protected function getStats(): array
    {
        // Get today's date range (start of day to end of day)
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        // Query the User model for today's count with the 'student' relationship
        $todayWebsiteEnquiriesCount = WebsiteEnquiry::query()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->count();

        $color = ($todayWebsiteEnquiriesCount > 0) ? 'success' : 'warning';

        // Format the count into a Filament Stat card
        return [
            Stat::make('Today\'s Website Enquiries', $todayWebsiteEnquiriesCount)
                ->description('New users signed up today')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($color)->columnSpanFull(),
        ];
    }
}
