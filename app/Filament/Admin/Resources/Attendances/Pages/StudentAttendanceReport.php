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
    public $recentAttendances;

    public function mount(): void
    {
        $query = Attendance::whereDate('created_at', Carbon::today())
            ->whereHas('user.student');

        $this->totalStudents = (clone $query)->count();
        $this->presentCount = (clone $query)->where('type', 'present')->count();
        $this->absentCount = (clone $query)->where('type', 'absent')->count();
        $this->lateCount = (clone $query)->where('type', 'late')->count();

        $this->recentAttendances = $query->with(['user.student'])->latest('created_at')->take(10)->get();
    }
}
