<?php

namespace App\Filament\Admin\Resources\Attendances\Pages;

use App\Filament\Admin\Resources\Attendances\AttendanceResource;
use App\Models\Attendance;
use App\Models\User;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

class StudentAttendanceReport extends Page
{
    protected static string $resource = AttendanceResource::class;

    protected string $view = 'filament.admin.resources.attendances.pages.student-attendance-report';

    protected static string | BackedEnum | null $activeNavigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Student Attendance Dashboard';

    public int $totalStudents = 0;
    public int $presentCount = 0;
    public int $absentCount = 0;
    public int $lateCount = 0;
    public $classSectionBreakdown = [];
    public $recentAttendances;

    public function mount(): void
    {
        // 1. Fetch active students with class assignments, classes, sections, and today's attendance
        $allStudents = User::where('is_active', true)
            ->has('student')
            ->with([
                'student.classAssignment.class',
                'student.classAssignment.section',
                'attendances' => function ($q) {
                    $q->whereDate('created_at', Carbon::today());
                }
            ])
            ->get();

        $this->totalStudents = $allStudents->count();

        // 2. Compute present vs absent counts for today
        $presentStudents = $allStudents->filter(fn($user) => $user->attendances->isNotEmpty());
        $this->presentCount = $presentStudents->count();
        $this->absentCount = $this->totalStudents - $this->presentCount;
        $this->lateCount = 0;

        // 3. Class & Section breakdown mapping total students vs present count per class/section
        $this->classSectionBreakdown = $allStudents->groupBy(function ($user) {
            $assignment = $user->student?->classAssignment;
            $className = $assignment?->class?->name ?? 'Unassigned Class';
            $sectionName = $assignment?->section?->name ?? 'General';

            return $className . ' - ' . $sectionName;
        })->map(function ($group) {
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

        // 4. Recent activity feed for today's student check-ins
        $this->recentAttendances = Attendance::whereDate('created_at', Carbon::today())
            ->whereHas('user.student')
            ->with(['user.student.classAssignment.class', 'user.student.classAssignment.section', 'user.student'])
            ->latest('created_at')
            ->take(10)
            ->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('employees')
                ->label('Employee Dashboard')
                ->icon('heroicon-o-briefcase')
                ->url(fn() => AttendanceResource::getUrl('employees')),
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
