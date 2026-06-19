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
    public ?string $selectedDate = null;

    // 1. MANDATORY: Tell Livewire to execute updates when this event name is triggered
    protected $listeners = [
        'refreshAttendanceWidget' => 'updateWidgetDate'
    ];

    /**
     * Intercept the custom date payload and trigger a sub-component reactive cycle
     */
    public function updateWidgetDate(string $selectedDate): void
    {
        $this->selectedDate = $selectedDate;

        // This forces Livewire to clear old stats and re-run getStats() immediately
        $this->render();
    }

    protected function getStats(): array
    {
        $targetDate = $this->selectedDate ? Carbon::parse($this->selectedDate)->toDateString() : Carbon::today()->toDateString();

        // High-performance query for active users, excluding Super Admin
        $roleStats = DB::table('roles')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('model_has_roles.model_type', User::class)
            ->whereNotIn('roles.name', ['Super Admin', 'Principal', 'Director', 'Owner'])
            ->leftJoin('attendances', function ($join) use ($targetDate) {
                $join->on('users.id', '=', 'attendances.user_id')
                    ->where('is_active', true)
                    ->whereDate('attendances.created_at', $targetDate);
            })
            ->select(
                'roles.name as role_name',
                DB::raw('COUNT(DISTINCT users.id) as total_users'),
                DB::raw('COUNT(DISTINCT attendances.user_id) as present_users')
            )
            ->groupBy('roles.id', 'roles.name')
            ->get();

        $stats = [];

        foreach ($roleStats as $row) {
            $roleLabel = ucfirst($row->role_name);
            $absentCount = $row->total_users - $row->present_users;

            $stats[] = Stat::make("{$roleLabel}", "{$row->present_users} Present")
                ->description("{$absentCount} Absent (Total : {$row->total_users})")
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($row->present_users === $row->total_users ? 'success' : 'warning');
        }

        return $stats;
    }
}
