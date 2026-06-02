<?php

namespace App\Filament\Student\Resources\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExamResultInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Exam Details')
                    ->schema([
                        TextEntry::make('exam.title')
                            ->label('Exam Title')
                            ->size('lg')
                            ->weight('bold'),
                        TextEntry::make('exam.course.title')
                            ->label('Course'),
                        TextEntry::make('exam.examType.name')
                            ->label('Exam Type')
                            ->badge()
                            ->color(fn($record) => $record->exam?->examType?->color ?? 'gray'),
                        TextEntry::make('exam.academicYear.name')
                            ->label('Academic Year'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Exam Schedule')
                    ->schema([
                        TextEntry::make('exam.exam_date')
                            ->label('Exam Date')
                            ->date(),
                        TextEntry::make('exam.start_time')
                            ->label('Start Time')
                            ->time(),
                        TextEntry::make('exam.duration_minutes')
                            ->label('Duration')
                            ->formatStateUsing(fn($state) => $state ? "{$state} minutes" : 'N/A'),
                        TextEntry::make('attempt_number')
                            ->label('Attempt Number'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Marks & Performance')
                    ->schema([
                        TextEntry::make('obtained_marks')
                            ->label('Marks Obtained')
                            ->size('lg')
                            ->weight('bold')
                            ->formatStateUsing(fn($state) => round($state, 2)),
                        TextEntry::make('exam.total_marks')
                            ->label('Total Marks')
                            ->formatStateUsing(fn($state) => round($state, 2)),
                        TextEntry::make('exam.passing_marks')
                            ->label('Passing Marks')
                            ->formatStateUsing(fn($state) => round($state, 2)),
                        TextEntry::make('percentage')
                            ->label('Percentage (%)')
                            ->formatStateUsing(fn($record) => $record->exam->total_marks > 0
                                ? round(($record->obtained_marks / $record->exam->total_marks) * 100, 2) . '%'
                                : '0%'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Result Status')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'passed' => 'success',
                                'failed' => 'danger',
                                'pending' => 'warning',
                                'completed' => 'info',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                        TextEntry::make('grade')
                            ->label('Grade')
                            ->badge()
                            ->color('info')
                            ->placeholder('N/A'),
                        TextEntry::make('feedback')
                            ->label('Feedback')
                            ->placeholder('No feedback provided')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Submission Information')
                    ->schema([
                        TextEntry::make('submitted_at')
                            ->label('Submitted On')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('graded_at')
                            ->label('Graded On')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->label('Result Created')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed(),

                Section::make('Question Performance')
                    ->schema([
                        TextEntry::make('total_questions')
                            ->label('Total Questions')
                            ->formatStateUsing(fn($record) => $record->exam->questions_count ?? 0),
                        TextEntry::make('correct_answers')
                            ->label('Correct Answers')
                            ->placeholder('-'),
                        TextEntry::make('incorrect_answers')
                            ->label('Incorrect Answers')
                            ->placeholder('-'),
                        TextEntry::make('unanswered_questions')
                            ->label('Unanswered')
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed(),
            ]);
    }
}

