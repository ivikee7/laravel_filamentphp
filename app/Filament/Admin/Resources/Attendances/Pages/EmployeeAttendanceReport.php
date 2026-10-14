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
        $this->loadAttendanceData();
    }

    // Called automatically by wire:poll to refresh data live
    public function loadAttendanceData(): void
    {
        $allEmployees = User::where('is_active', true)
            ->doesntHave('student')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Super Admin');
            })
            ->with(['roles', 'attendances' => function($q) {
                $q->whereDate('created_at', Carbon::today());
            }])
            ->get();

        $this->totalEmployees = $allEmployees->count();

        $presentEmployees = $allEmployees->filter(fn($user) => $user->attendances->isNotEmpty());

        $this->presentCount = $presentEmployees->count();
        $this->absentCount = $this->totalEmployees - $this->presentCount;
        $this->lateCount = 0;

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

        $this->recentAttendances = Attendance::whereDate('created_at', Carbon::today())
            ->whereHas('user', function ($q) {
                $q->where('is_active', true)
                    ->doesntHave('student')
                    ->whereDoesntHave('roles', function ($subQ) {
                        $subQ->where('name', 'Super Admin');
                    });
            })
            ->with('user.roles')
            ->latest('created_at')
            ->take(10)
            ->get();
    }

    public function viewRoleEmployeesAction(): Action
    {
        return Action::make('viewRoleEmployees')
            ->modalHeading(fn (array $arguments) => ucwords($arguments['type'] ?? 'total') . ' Employees: ' . ($arguments['roleKey'] ?? ''))
            ->modalContent(function (array $arguments) {
                $roleKey = $arguments['roleKey'] ?? '';
                $type = $arguments['type'] ?? 'total';

                $employees = $this->getEmployeesForRole($roleKey, $type)->sortBy('name');

                return view('filament.admin.resources.attendances.pages.partials.role-employees-modal', [
                    'employees' => $employees,
                    'type' => $type,
                ]);
            })
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Close');
    }

    public function getEmployeesForRole(string $roleKey, string $type)
    {
        $allEmployees = User::where('is_active', true)
            ->doesntHave('student')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Super Admin');
            })
            ->with(['roles', 'attendances' => function($q) {
                $q->whereDate('created_at', Carbon::today());
            }])
            ->get();

        $group = $allEmployees->filter(function ($user) use ($roleKey) {
            $userRole = $user->roles->pluck('name')->implode(', ') ?: 'Staff / No Role';
            return $userRole === $roleKey;
        });

        if ($type === 'present') {
            return $group->filter(fn($user) => $user->attendances->isNotEmpty());
        } elseif ($type === 'absent') {
            return $group->filter(fn($user) => $user->attendances->isEmpty());
        }

        return $group;
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
