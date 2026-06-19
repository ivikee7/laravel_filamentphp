<?php

namespace App\Filament\Admin\Resources\Attendances\Widgets;

use App\Models\Attendance;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class RoleAttendanceOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // 1. Fetch the active date state from the page header filter context
        $selectedFilterDate = $this->pageFilters['filter_date'] ?? null;

        $targetDate = $selectedFilterDate
            ? Carbon::parse($selectedFilterDate)->toDateString()
            : Carbon::today()->toDateString();

        // 2. High-performance Spatie query explicitly ignoring "Super Admin"
        $roleStats = DB::table('roles')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', User::class)
            ->where('roles.name', '!=', 'Super Admin')
            ->leftJoin('attendances', function ($join) use ($targetDate) {
                $join->on('model_has_roles.model_id', '=', 'attendances.user_id')
                    ->whereDate('attendances.created_at', $targetDate);
            })
            ->select(
                'roles.name as role_name',
                DB::raw('COUNT(DISTINCT model_has_roles.model_id) as total_users'),
                DB::raw('COUNT(DISTINCT attendances.user_id) as present_users')
            )
            ->groupBy('roles.id', 'roles.name')
            ->get();

        $stats = [];
        $displayDate = Carbon::parse($targetDate)->format('M d, Y');

        foreach ($roleStats as $row) {
            $roleLabel = ucfirst($row->role_name);
            $absentCount = $row->total_users - $row->present_users;

            // 3. Create exactly ONE widget stat card per role
            $stats[] = Stat::make("{$roleLabel} Attendance", "{$row->present_users} Present")
                ->description("{$absentCount} Absent • (Total: {$row->total_users})")
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($row->present_users === $row->total_users ? 'success' : 'warning');
        }

        return $stats;
    }
}
