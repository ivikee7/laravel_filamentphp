<?php

namespace App\Filament\Admin\Resources\Attendances\Pages;

use App\Filament\Admin\Resources\Attendances\AttendanceResource;
use App\Models\Attendance;
use App\Models\User;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

class EmployeeAttendanceReport extends Page
{
    protected static string $resource = AttendanceResource::class;

    protected string $view = 'filament.admin.resources.attendances.pages.employee-attendance-report';

    protected static string | BackedEnum | null $activeNavigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Employee Attendance Dashboard';

    public int $totalEmployees = 0;
    public int $presentCount = 0;
    public int $absentCount = 0;
    public int $lateCount = 0;
    public $roleBreakdown = [];
    public $recentAttendances;

    public function mount(): void
    {
        // Total base employees (users without student records)
        $this->totalEmployees = User::doesntHave('student')->count();

        // Today's attendance query
        $attendanceQuery = Attendance::whereDate('created_at', Carbon::today())
            ->whereHas('user', function ($q) {
                $q->doesntHave('student');
            });

        $this->presentCount = (clone $attendanceQuery)->where('type', 'present')->count();
        $this->absentCount = (clone $attendanceQuery)->where('type', 'absent')->count();
        $this->lateCount = (clone $attendanceQuery)->where('type', 'late')->count();

        // Role-wise breakdown mapping total staff vs today's status per role
        $allEmployees = User::doesntHave('student')->with(['roles', 'attendances' => function($q) {
            $q->whereDate('created_at', Carbon::today());
        }])->get();

        $this->roleBreakdown = $allEmployees->groupBy(function($user) {
            return $user->roles->pluck('name')->implode(', ') ?: 'Staff / No Role';
        })->map(function($group) {
            $total = $group->count();
            $present = $group->filter(fn($user) => $user->attendances->where('type', 'present')->isNotEmpty())->count();
            $absent = $group->filter(fn($user) => $user->attendances->where('type', 'absent')->isNotEmpty())->count();
            $late = $group->filter(fn($user) => $user->attendances->where('type', 'late')->isNotEmpty())->count();

            return [
                'total' => $total,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'percentage' => $total > 0 ? round(($present / $total) * 100) : 0,
            ];
        });

        $this->recentAttendances = $attendanceQuery->with('user.roles')->latest('created_at')->take(10)->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('students')
                ->label('Student Dashboard')
                ->icon('heroicon-o-academic-cap')
                ->url(fn() => AttendanceResource::getUrl('students')),
            Action::make('monthlyReport')
                ->label('Monthly Report')
                ->icon('heroicon-o-chart-bar')
                ->url(fn() => AttendanceResource::getUrl('monthly-report')),
            Action::make('Log')
                ->label('All Logs')
                ->icon('heroicon-o-table-cells')
                ->url(fn() => AttendanceResource::getUrl('index')),
        ];
    }
}
