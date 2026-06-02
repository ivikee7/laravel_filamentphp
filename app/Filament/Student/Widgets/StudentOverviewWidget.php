<?php

namespace App\Filament\Student\Widgets;

use App\Filament\Student\Resources\MyCourseResource\MyCourseResource;
use App\Filament\Student\Resources\MyExamResource\MyExamResource;
use App\Models\Exam;
use App\Models\ExamSubmission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudentOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $student = $user?->student;

        if (! $student) {
            return [
                Stat::make('Welcome', $user?->name ?? 'Student')
                    ->description('No student profile linked to this account')
                    ->color('warning'),
            ];
        }

        $enrolledCourseIds = $student->enrollments()
            ->where('status', 'active')
            ->pluck('course_id');

        $enrolledCount = $enrolledCourseIds->count();

        // Filament/student resources only expose published exams.
        $availableExams = Exam::query()
            ->where('status', 'published')
            ->where(fn ($q) => $q
                ->whereIn('course_id', $enrolledCourseIds)
                ->orWhereNull('course_id')
            )
            ->count();

        $pendingGrading = ExamSubmission::query()
            ->where('student_id', $student->id)
            ->where('status', 'submitted')
            ->count();

        $gradedSubmissions = ExamSubmission::query()
            ->where('student_id', $student->id)
            ->where('status', 'graded')
            ->whereNotNull('score')
            ->latest('submitted_at')
            ->limit(7)
            ->get(['score']);

        $gradedCount = $gradedSubmissions->count();
        $avgScore = $gradedCount > 0
            ? round((float) $gradedSubmissions->avg('score'), 1)
            : null;

        return [
            Stat::make('Enrolled Courses', (string) $enrolledCount)
                ->description('Active course enrollments')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success')
                ->url(MyCourseResource::getUrl('index')),

            Stat::make('Published Exams', (string) $availableExams)
                ->description('Ready for attempt')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary')
                ->url(MyExamResource::getUrl('index')),

            Stat::make('Pending Results', (string) $pendingGrading)
                ->description('Awaiting instructor review')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingGrading > 0 ? 'warning' : 'success')
                ->url(MyExamResource::getUrl('index')),

            Stat::make('7-Exam Average', $avgScore !== null ? (string) $avgScore : '—')
                ->description("Based on {$gradedCount} recent graded exam(s)")
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info')
                ->chart($gradedSubmissions
                    ->pluck('score')
                    ->map(fn ($score) => (float) $score)
                    ->all()),
        ];
    }
}
