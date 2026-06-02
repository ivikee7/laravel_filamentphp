<?php

namespace App\Filament\Student\Widgets;

use App\Filament\Student\Resources\AttendanceResource\AttendanceResource;
use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendanceOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        if (! $user) {
            return [];
        }

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $baseQuery = Attendance::query()
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);

        $total = (clone $baseQuery)->count();
        $present = (clone $baseQuery)->whereRaw('LOWER(type) = ?', ['present'])->count();
        $absent = (clone $baseQuery)->whereRaw('LOWER(type) = ?', ['absent'])->count();
        $late = (clone $baseQuery)->whereRaw('LOWER(type) = ?', ['late'])->count();

        $attendanceRate = $total > 0
            ? round((($present + $late) / $total) * 100, 1)
            : null;

        return [
            Stat::make('Present (This Month)', (string) $present)
                ->description('Attendance marked as present')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->url(AttendanceResource::getUrl('index')),

            Stat::make('Absent (This Month)', (string) $absent)
                ->description('Attendance marked as absent')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color($absent > 0 ? 'danger' : 'gray')
                ->url(AttendanceResource::getUrl('index')),

            Stat::make('Late (This Month)', (string) $late)
                ->description('Attendance marked as late')
                ->descriptionIcon('heroicon-m-clock')
                ->color($late > 0 ? 'warning' : 'gray')
                ->url(AttendanceResource::getUrl('index')),

            Stat::make('Attendance Rate', $attendanceRate !== null ? $attendanceRate . '%' : 'N/A')
                ->description('Computed from present + late entries')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($attendanceRate !== null && $attendanceRate >= 75 ? 'success' : 'warning')
                ->url(AttendanceResource::getUrl('index')),
        ];
    }
}

