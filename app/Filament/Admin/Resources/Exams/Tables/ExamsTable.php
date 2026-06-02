<?php

namespace App\Filament\Admin\Resources\Exams\Tables;

use App\Models\Exam;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ExamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->wrap()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Exam $record): string => collect([
                        Exam::formatTypeLabel($record->examType),
                        $record->course?->title ? ' Course: ' . $record->course->title : '— Standalone —',
                    ])->filter()->implode(' | ')),

                TextColumn::make('academicYear.name')
                    ->label('Academic Year')
                    ->wrap()
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('exam_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->wrap()
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('total_marks')
                    ->label('Marks')
                    ->formatStateUsing(fn ($state, $record): string =>
                        "{$record->total_marks} / Pass: {$record->passing_marks}"
                    )
                    ->wrap()
                    ->sortable(),

                TextColumn::make('max_attempts')
                    ->label('Attempts')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => $state === null ? '∞' : $state . 'x')
                    ->tooltip(fn ($state) => $state === null ? 'Unlimited attempts' : "Max {$state} attempt(s)")
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft'     => 'warning',
                        'ongoing'   => 'info',
                        'completed' => 'gray',
                        default     => 'gray',
                    })
                    ->wrap()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('questions_count')
                    ->label('Questions')
                    ->counts('questions')
                    ->wrap()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('submissions_count')
                    ->label('Submissions')
                    ->counts('submissions')
                    ->wrap()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('pending_review_count')
                    ->label('Pending Review')
                    ->wrapHeader()
                    ->state(fn ($record): int => $record->submissions()->where('status', 'submitted')->count())
                    ->wrap()
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'warning' : 'success'),

                TextColumn::make('completion_rate')
                    ->label('Completion')
                    ->state(function ($record): string {
                        $submissions = $record->submissions()->count();
                        if ($submissions === 0) {
                            return '0%';
                        }

                        $completed = $record->submissions()
                            ->whereIn('status', ['submitted', 'graded'])
                            ->count();

                        return round(($completed / $submissions) * 100) . '%';
                    })
                    ->wrap()
                    ->badge()
                    ->color(fn (string $state): string => (int) $state >= 80 ? 'success' : 'gray'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('exam_type_id')
                    ->label('Type')
                    ->relationship('examType', 'name')
                    ->preload(),
                SelectFilter::make('status')
                    ->options([
                        'draft'     => 'Draft',
                        'published' => 'Published',
                        'ongoing'   => 'Ongoing',
                        'completed' => 'Completed',
                    ]),
                SelectFilter::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('academic_year_id')
                    ->label('Academic Year')
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make(),
            ])
            ->filtersLayout(\Filament\Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
