<?php

namespace App\Filament\Student\Resources\MyExamResource\Pages;

use App\Filament\Student\Resources\MyExamResource\MyExamResource;
use App\Models\ExamSubmission;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;

class AttemptViewPage extends Page
{
    use InteractsWithRecord;

    protected static string $resource = MyExamResource::class;

    protected string $view = 'filament.student.pages.attempt-view';

    public ?ExamSubmission $submission = null;

    /** @var Collection<int, ExamSubmission> */
    public Collection $submissions;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $student = auth()->user()?->student;

        if (! $student) {
            $this->redirect(MyExamResource::getUrl('index'));
            return;
        }

        $this->submissions = ExamSubmission::with(['answers.question'])
            ->where('exam_id', $this->record->id)
            ->where('student_id', $student->id)
            ->whereIn('status', ['submitted', 'graded'])
            ->orderByDesc('attempt_number')
            ->orderByDesc('id')
            ->get();

        if ($this->submissions->isEmpty()) {
            $this->redirect(MyExamResource::getUrl('index'));
            return;
        }

        $requestedAttempt = request()->integer('attempt');

        $this->submission = $requestedAttempt
            ? $this->submissions->firstWhere('attempt_number', $requestedAttempt)
            : $this->submissions->first();

        $this->submission ??= $this->submissions->first();
    }

    public function switchAttempt(int $attemptNumber): void
    {
        $target = $this->submissions->firstWhere('attempt_number', $attemptNumber);

        if (! $target) {
            return;
        }

        $this->redirect(static::getUrl([
            'record' => $this->record,
            'attempt' => $target->attempt_number,
        ]));
    }

    public function getTitle(): string|Htmlable
    {
        return 'Attempt View: ' . $this->record->title;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('attempts')
                ->label('All Attempts')
                ->icon(Heroicon::Clock)
                ->color('info')
                ->url(fn (): string => ExamAttemptsPage::getUrl(['record' => $this->record])),

            Action::make('back')
                ->label('Back to Exams')
                ->icon(Heroicon::ArrowLeft)
                ->color('gray')
                ->url(MyExamResource::getUrl('index')),
        ];
    }
}
