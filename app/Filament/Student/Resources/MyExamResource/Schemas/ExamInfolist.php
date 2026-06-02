<?php

namespace App\Filament\Student\Resources\MyExamResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExamInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Exam Overview')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Exam Title')
                            ->size('lg')
                            ->weight('bold'),
                        TextEntry::make('description')
                            ->label('Exam Description')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Exam Details')
                    ->schema([
                        TextEntry::make('course.title')
                            ->label('Course'),
                        TextEntry::make('examType.name')
                            ->label('Exam Type')
                            ->badge()
                            ->color(fn($record) => $record->examType?->color ?? 'gray'),
                        TextEntry::make('academicYear.name')
                            ->label('Academic Year'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'published' => 'success',
                                'draft' => 'warning',
                                'archived' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Exam Schedule')
                    ->schema([
                        TextEntry::make('exam_date')
                            ->label('Exam Date')
                            ->date(),
                        TextEntry::make('start_time')
                            ->label('Start Time')
                            ->time(),
                        TextEntry::make('duration_minutes')
                            ->label('Duration')
                            ->formatStateUsing(fn($state) => $state ? "{$state} minutes" : 'Not specified'),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('Marking & Performance')
                    ->schema([
                        TextEntry::make('total_marks')
                            ->label('Total Marks')
                            ->formatStateUsing(fn($state) => $state ? round($state, 2) : '0'),
                        TextEntry::make('passing_marks')
                            ->label('Passing Marks')
                            ->formatStateUsing(fn($state) => $state ? round($state, 2) : '0'),
                        TextEntry::make('questions_count')
                            ->label('Total Questions')
                            ->formatStateUsing(fn($record) => $record->questions_count ?? 0),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('Exam Rules & Attempts')
                    ->schema([
                        TextEntry::make('max_attempts')
                            ->label('Maximum Attempts')
                            ->formatStateUsing(fn($state) => $state ?? 'Unlimited'),
                        TextEntry::make('instructions')
                            ->label('Instructions')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Metadata')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed(),
            ]);
    }
}

