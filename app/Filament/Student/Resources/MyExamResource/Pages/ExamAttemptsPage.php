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

class ExamAttemptsPage extends Page
{
    use InteractsWithRecord;

    protected static string $resource = MyExamResource::class;

    protected string $view = 'filament.student.pages.exam-attempts';

    /** @var Collection<int, ExamSubmission> */
    public Collection $attempts;

    public ?float $averageScore = null;

    public ?ExamSubmission $bestAttempt = null;

    public ?ExamSubmission $latestAttempt = null;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $student = auth()->user()?->student;

        if (! $student) {
            $this->redirect(MyExamResource::getUrl('index'));
            return;
        }

        $this->attempts = ExamSubmission::query()
            ->with('answers')
            ->where('exam_id', $this->record->id)
            ->where('student_id', $student->id)
            ->orderByDesc('attempt_number')
            ->orderByDesc('id')
            ->get();

        if ($this->attempts->isEmpty()) {
            $this->redirect(MyExamResource::getUrl('index'));
            return;
        }

        $gradedAttempts = $this->attempts
            ->where('status', 'graded')
            ->filter(fn (ExamSubmission $attempt) => $attempt->score !== null);

        $this->averageScore = $gradedAttempts->isNotEmpty()
            ? round((float) $gradedAttempts->avg('score'), 2)
            : null;

        $this->bestAttempt = $gradedAttempts
            ->sortByDesc('score')
            ->first();

        $this->latestAttempt = $this->attempts->first();
    }

    public function getTitle(): string|Htmlable
    {
        return 'All Attempts: ' . $this->record->title;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Exams')
                ->icon(Heroicon::ArrowLeft)
                ->color('gray')
                ->url(MyExamResource::getUrl('index')),
        ];
    }
}
