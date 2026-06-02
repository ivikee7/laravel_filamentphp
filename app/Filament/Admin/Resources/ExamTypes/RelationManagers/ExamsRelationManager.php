<?php

namespace App\Filament\Admin\Resources\ExamTypes\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExamsRelationManager extends RelationManager
{
    protected static string $relationship = 'exams';
    protected static ?string $title = 'Exams';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Exam')
                    ->weight('bold')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record): string => collect(array_filter([
                        $record->course?->title,
                        $record->academicYear?->name,
                    ]))->implode(' · ')),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        'ongoing' => 'info',
                        'completed' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('exam_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('total_marks')
                    ->label('Marks')
                    ->formatStateUsing(fn ($state, $record): string =>
                        "{$record->total_marks} (Pass: {$record->passing_marks})"
                    )
                    ->sortable(),

                TextColumn::make('questions_count')
                    ->label('Questions')
                    ->counts('questions')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('exam_date', 'desc');
    }
}

