<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TodayRegistrationWidget extends StatsOverviewWidget
{

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 1;

    protected function getStats(): array
    {
        // Get today's date range (start of day to end of day)
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        // Query the User model for today's count with the 'student' relationship
        $todayRegistrationsCount = User::query()
            ->has('student') // Ensure the user is a student
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->count();

        $color = ($todayRegistrationsCount > 0) ? 'success' : 'warning';

        // Format the count into a Filament Stat card
        return [
            Stat::make('Today\'s Registrations', $todayRegistrationsCount)
                ->description('New students signed up today')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($color)
                ->columnSpan(1),
        ];
    }
}
