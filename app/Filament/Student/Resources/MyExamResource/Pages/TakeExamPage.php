<?php
namespace App\Filament\Student\Resources\MyExamResource\Pages;
use App\Filament\Student\Resources\MyExamResource\MyExamResource;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamSubmission;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class TakeExamPage extends Page
{
    use InteractsWithRecord;

    protected static string $resource = MyExamResource::class;

    protected string $view = 'filament.student.pages.take-exam';

    /** @var array<int, string|null> answers keyed by question id */
    public array $answers = [];

    public ?ExamSubmission $submission = null;

    /** How many completed attempts the student already has */
    public int $attemptCount = 0;

    /** Whether the student has exhausted their attempts */
    public bool $attemptsExhausted = false;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $student = auth()->user()?->student;

        if (! $student) {
            $this->redirect(MyExamResource::getUrl('index'));
            return;
        }

        $this->attemptCount = $this->record->studentAttemptCount($student->id);

        $this->submission = ExamSubmission::where('exam_id', $this->record->id)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->latest()
            ->first();

        if (! $this->submission) {
            $latest = ExamSubmission::where('exam_id', $this->record->id)
                ->where('student_id', $student->id)
                ->latest()
                ->first();

            if ($latest && $latest->isSubmitted()) {
                if (! $this->record->canStudentAttempt($student->id)) {
                    $this->attemptsExhausted = true;
                    return;
                }

                if ($this->record->max_attempts === 1) {
                    $this->redirect(AttemptViewPage::getUrl([
                        'record' => $this->record,
                        'attempt' => $latest->attempt_number,
                    ]));
                    return;
                }
            }
        }

        if (! $this->submission) {
            if (! $this->record->canStudentAttempt($student->id)) {
                $this->attemptsExhausted = true;
                return;
            }

            $attemptNumber = $this->record->nextAttemptNumber($student->id);

            $this->submission = ExamSubmission::create([
                'exam_id'        => $this->record->id,
                'student_id'     => $student->id,
                'attempt_number' => $attemptNumber,
                'started_at'     => now(),
                'status'         => 'in_progress',
            ]);
        }

        foreach ($this->record->questions as $question) {
            $existing = $this->submission->answers()->where('question_id', $question->id)->first();
            $this->answers[$question->id] = $existing?->answer;
        }
    }

    public function saveAnswer(int $questionId, ?string $answer): void
    {
        $this->answers[$questionId] = $answer;

        if ($this->submission) {
            ExamAnswer::updateOrCreate(
                ['submission_id' => $this->submission->id, 'question_id' => $questionId],
                ['answer' => $answer]
            );
        }
    }

    public function submitExam(): void
    {
        $student = auth()->user()?->student;
        if (! $student || ! $this->submission) return;

        $totalScore = 0;
        $questions = $this->record->questions;

        foreach ($questions as $question) {
            $answer = $this->answers[$question->id] ?? null;
            if ($answer === null) continue;

            $isCorrect = null;
            $marksAwarded = null;

            if (in_array($question->type, ['multiple_choice', 'true_false'], true)) {
                $isCorrect = strtolower(trim((string) $answer)) === strtolower(trim((string) $question->correct_answer));
                $marksAwarded = $isCorrect ? (float) $question->marks : 0;
                $totalScore += (float) $marksAwarded;
            }

            ExamAnswer::updateOrCreate(
                ['submission_id' => $this->submission->id, 'question_id' => $question->id],
                ['answer' => $answer, 'is_correct' => $isCorrect, 'marks_awarded' => $marksAwarded]
            );
        }

        $hasManualQuestions = $questions->whereIn('type', ['short_answer', 'essay'])->isNotEmpty();

        $this->submission->update([
            'submitted_at' => now(),
            'status' => $hasManualQuestions ? 'submitted' : 'graded',
            'score' => $hasManualQuestions ? null : $totalScore,
            'time_taken_minutes' => now()->diffInMinutes($this->submission->started_at),
        ]);

        Notification::make()
            ->title('Exam Submitted!')
            ->body($hasManualQuestions
                ? 'Your answers have been submitted and are awaiting grading.'
                : "Auto-graded! Your score: {$totalScore} / {$this->record->total_marks}")
            ->success()
            ->send();

        $this->redirect(AttemptViewPage::getUrl([
            'record' => $this->record,
            'attempt' => $this->submission->attempt_number,
        ]));
    }

    public function getTitle(): string|Htmlable
    {
        return '📝 ' . $this->record->title;
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
