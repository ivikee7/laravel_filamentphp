<?php

namespace App\Filament\Admin\Resources\Attendances\Pages;

use App\Filament\Admin\Resources\Attendances\AttendanceResource;
use App\Models\Attendance;
use BackedEnum;
use Carbon\Carbon;
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
    public $classSectionBreakdown;
    public $recentAttendances;

    public function mount(): void
    {
        $query = Attendance::whereDate('created_at', Carbon::today())
            ->whereHas('user.student');

        $this->totalStudents = (clone $query)->count();
        $this->presentCount = (clone $query)->where('type', 'present')->count();
        $this->absentCount = (clone $query)->where('type', 'absent')->count();
        $this->lateCount = (clone $query)->where('type', 'late')->count();

        // Fetch recent attendances using the available classAssignment relationship
        $this->recentAttendances = $query->with([
            'user.student.classAssignment.class',
            'user.student.classAssignment.section'
        ])->latest('created_at')->take(10)->get();

        // Group dynamically by class and section from relationships
        $this->classSectionBreakdown = $query->with([
            'user.student.classAssignment.class',
            'user.student.classAssignment.section'
        ])->get()->groupBy(function ($attendance) {
            $assignment = $attendance->user?->student?->classAssignment;
            $className = $assignment?->class?->name ?? 'Unassigned Class';
            $sectionName = $assignment?->section?->name ?? 'General';

            return $className . ' - ' . $sectionName;
        })->map(function ($group) {
            return [
                'total' => $group->count(),
                'present' => $group->where('type', 'present')->count(),
            ];
        });
    }
}
