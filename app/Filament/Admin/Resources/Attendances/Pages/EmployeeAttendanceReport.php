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
        // 1. Total base employees (users without student records)
        $allEmployees = User::where('is_active', true)->doesntHave('student')->with(['roles', 'attendances' => function($q) {
            $q->whereDate('created_at', Carbon::today());
        }])->get();

        $this->totalEmployees = $allEmployees->count();

        // 2. Filter who checked in today vs who is absent
        $presentEmployees = $allEmployees->filter(fn($user) => $user->attendances->isNotEmpty());

        $this->presentCount = $presentEmployees->count();
        $this->absentCount = $this->totalEmployees - $this->presentCount;
        $this->lateCount = 0; // If you don't track late separately, keep it 0

        // 3. Role-wise breakdown mapping total staff vs present count per role
        $this->roleBreakdown = $allEmployees->groupBy(function($user) {
            return $user->roles->pluck('name')->implode(', ') ?: 'Staff / No Role';
        })->map(function($group) {
            $total = $group->count();
            $present = $group->filter(fn($user) => $user->attendances->isNotEmpty())->count();
            $absent = $total - $present;

            return [
                'total' => $total,
                'present' => $present,
                'absent' => $absent,
                'percentage' => $total > 0 ? round(($present / $total) * 100) : 0,
            ];
        });

        // 4. Recent activity feed for today
        $this->recentAttendances = Attendance::whereDate('created_at', Carbon::today())
            ->whereHas('user', function ($q) {
                $q->doesntHave('student');
            })
            ->with('user.roles')
            ->latest('created_at')
            ->take(10)
            ->get();
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
