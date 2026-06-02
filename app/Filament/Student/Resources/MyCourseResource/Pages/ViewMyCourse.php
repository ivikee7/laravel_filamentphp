<?php

namespace App\Filament\Student\Resources\MyCourseResource\Pages;

use App\Filament\Student\Resources\MyCourseResource\MyCourseResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ViewMyCourse extends Page
{
    use InteractsWithRecord;

    protected static string $resource = MyCourseResource::class;

    protected string $view = 'filament.student.pages.view-my-course';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        // Ensure only enrolled students can view this course
        $student = auth()->user()?->student;
        if (!$student) {
            $this->redirect(MyCourseResource::getUrl('index'));
            return;
        }

        $isEnrolled = $this->record->enrollments()
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->exists();

        if (!$isEnrolled) {
            $this->redirect(MyCourseResource::getUrl('index'));
        }
    }

    public function getTitle(): string|Htmlable
    {
        return $this->record->title;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Courses')
                ->icon(Heroicon::ArrowLeft)
                ->color('gray')
                ->url(MyCourseResource::getUrl('index')),
        ];
    }
}
