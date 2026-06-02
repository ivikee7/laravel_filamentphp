<?php

namespace App\Filament\Student\Resources\MyExamResource\Tables;

use App\Filament\Student\Resources\MyExamResource\Pages;
use App\Filament\Student\Resources\MyExamResource\Pages\AttemptViewPage;
use App\Filament\Student\Resources\MyExamResource\Pages\ExamAttemptsPage;
use App\Models\Exam;
use App\Models\ExamSubmission;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MyExamsTable
{
    public static function configure(Table $table): Table
    {
        $student = auth()->user()?->student;

        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Exam')
                    ->weight('bold')
                    ->description(fn (Exam $record): string => collect(array_filter([
                        Exam::formatTypeLabel($record->examType),
                        $record->course?->title,
                    ]))->implode(' · '))
                    ->wrap()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('examType.name')
                    ->label('Type')
                    ->wrap()
                    ->badge()
                    ->color(fn (Exam $record): string => Exam::resolveTypeColor($record->examType))
                    ->placeholder('—'),

                TextColumn::make('exam_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->wrap()
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('duration_minutes')
                    ->label('Duration')
                    ->wrap()
                    ->suffix(' min')
                    ->placeholder('No limit'),

                TextColumn::make('questions_count')
                    ->label('Questions')
                    ->wrap()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('total_marks')
                    ->label('Marks')
                    ->wrap()
                    ->formatStateUsing(fn ($state, Exam $record): string => "{$record->total_marks} (Pass: {$record->passing_marks})"),

                TextColumn::make('attempts_used')
                    ->label('Attempts')
                    ->state(function (Exam $record) use ($student): string {
                        if (! $student) {
                            return '—';
                        }

                        $used = $record->studentAttemptCount($student->id);
                        $max = $record->max_attempts;

                        return $max === null ? "{$used} / ∞" : "{$used} / {$max}";
                    })
                    ->badge()
                    ->color(function (Exam $record) use ($student): string {
                        if (! $student) {
                            return 'gray';
                        }

                        $used = $record->studentAttemptCount($student->id);
                        $max = $record->max_attempts;

                        if ($max === null) {
                            return 'info';
                        }

                        return $used >= $max ? 'danger' : ($used > 0 ? 'warning' : 'gray');
                    }),

                TextColumn::make('my_status')
                    ->label('My Status')
                    ->state(function (Exam $record) use ($student): string {
                        if (! $student) {
                            return 'Not Started';
                        }

                        $sub = static::getStudentSubmission($record, $student->id);

                        if (! $sub) {
                            return 'Not Started';
                        }

                        return match ($sub->status) {
                            'in_progress' => "⏳ In Progress (Attempt {$sub->attempt_number})",
                            'submitted' => "📬 Awaiting Grade (Attempt {$sub->attempt_number})",
                            'graded' => "✅ Graded: {$sub->score}/{$record->total_marks} (A{$sub->attempt_number})",
                            default => 'Not Started',
                        };
                    })
                    ->wrap()
                    ->badge()
                    ->color(function (Exam $record) use ($student): string {
                        if (! $student) {
                            return 'gray';
                        }

                        $sub = static::getStudentSubmission($record, $student->id);

                        if (! $sub) {
                            return 'gray';
                        }

                        return match ($sub->status) {
                            'in_progress' => 'warning',
                            'submitted' => 'info',
                            'graded' => 'success',
                            default => 'gray',
                        };
                    }),
            ])
            ->defaultSort('exam_date')
            ->filters([
                SelectFilter::make('exam_type_id')
                    ->label('Type')
                    ->relationship('examType', 'name')
                    ->preload(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('take')
                        ->label('Take Exam')
                        ->icon(Heroicon::PencilSquare)
                        ->color('primary')
                        ->url(fn (Exam $record): string => Pages\TakeExamPage::getUrl(['record' => $record]))
                        ->visible(function (Exam $record) use ($student): bool {
                            if (! $student) {
                                return false;
                            }

                            $sub = static::getStudentSubmission($record, $student->id);

                            if ($sub && $sub->status === 'in_progress') {
                                return true;
                            }

                            return $record->canStudentAttempt($student->id);
                        }),

                    Action::make('attempt_view')
                        ->label('View Attempt')
                        ->icon(Heroicon::ChartBar)
                        ->color('success')
                        ->url(fn (Exam $record): string => AttemptViewPage::getUrl(['record' => $record]))
                        ->visible(function (Exam $record) use ($student): bool {
                            if (! $student) {
                                return false;
                            }

                            $sub = static::getStudentSubmission($record, $student->id);

                            return $sub && in_array($sub->status, ['submitted', 'graded'], true);
                        }),

                    Action::make('attempts')
                        ->label('All Attempts')
                        ->icon(Heroicon::Clock)
                        ->color('gray')
                        ->url(fn (Exam $record): string => ExamAttemptsPage::getUrl(['record' => $record]))
                        ->visible(function (Exam $record) use ($student): bool {
                            if (! $student) {
                                return false;
                            }

                            return ExamSubmission::query()
                                ->where('exam_id', $record->id)
                                ->where('student_id', $student->id)
                                ->exists();
                        }),
                ]),
            ])
            ->toolbarActions([])
            ->emptyStateHeading('No exams available')
            ->emptyStateDescription('There are no published exams assigned to you at this time.')
            ->emptyStateIcon(Heroicon::ClipboardDocumentList);
    }

    protected static function getStudentSubmission(Exam $record, int $studentId): ?ExamSubmission
    {
        if ($record->relationLoaded('submissions')) {
            return $record->submissions->first();
        }

        return ExamSubmission::query()
            ->where('exam_id', $record->id)
            ->where('student_id', $studentId)
            ->orderByDesc('attempt_number')
            ->orderByDesc('id')
            ->first();
    }
}

