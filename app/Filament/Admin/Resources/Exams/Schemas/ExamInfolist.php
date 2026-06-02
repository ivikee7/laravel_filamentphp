<?php

namespace App\Filament\Admin\Resources\Exams\Schemas;

use App\Models\Exam;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ExamInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Exam details')
                    ->tabs([
                        Tab::make('Details')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        TextEntry::make('title')
                                            ->label('Exam Title')
                                            ->size('lg')
                                            ->weight(\Filament\Support\Enums\FontWeight::Bold),

                                        TextEntry::make('examType.name')
                                            ->label('Exam Type')
                                            ->badge()
                                            ->color(fn(Exam $record): string => Exam::resolveTypeColor($record->examType))
                                            ->placeholder('Unspecified'),

                                        TextEntry::make('status')
                                            ->badge()
                                            ->color(fn(string $state): string => match ($state) {
                                                'published' => 'success',
                                                'draft' => 'warning',
                                                'ongoing' => 'info',
                                                'completed' => 'gray',
                                                default => 'gray',
                                            })
                                            ->formatStateUsing(fn(string $state): string => ucfirst($state)),

                                        TextEntry::make('description')
                                            ->placeholder('No description.'),

                                        TextEntry::make('instructions')
                                            ->label('Student Instructions')
                                            ->placeholder('No instructions provided.')
                                            ->prose(),
                                    ]),
                            ]),
                        Tab::make('Settings')
                            ->columns(4)
                            ->schema([
                                TextEntry::make('course.title')
                                    ->label('Course')
                                    ->badge()
                                    ->color('info')
                                    ->placeholder('Standalone / Entrance'),

                                TextEntry::make('academicYear.name')
                                    ->label('Academic Year')
                                    ->placeholder('—'),

                                TextEntry::make('exam_date')
                                    ->label('Exam Date')
                                    ->date()
                                    ->placeholder('—'),

                                TextEntry::make('start_time')
                                    ->label('Start Time')
                                    ->placeholder('—'),

                                TextEntry::make('duration_minutes')
                                    ->label('Duration')
                                    ->suffix(' minutes')
                                    ->placeholder('No limit'),

                                TextEntry::make('max_attempts')
                                    ->label('Max Attempts')
                                    ->badge()
                                    ->color('info')
                                    ->formatStateUsing(fn($state) => $state === null ? '∞ Unlimited' : $state . ' attempt(s)')
                                    ->placeholder('Unlimited'),

                                TextEntry::make('total_marks')
                                    ->label('Total Marks')
                                    ->numeric(),

                                TextEntry::make('passing_marks')
                                    ->label('Passing Marks')
                                    ->numeric(),
                            ]),
                        Tab::make('Statistics')
                            ->schema([
                                Section::make('Statistics')
                                    ->schema([
                                        Grid::make(4)
                                            ->schema([
                                                TextEntry::make('questions_count')
                                                    ->label('Questions')
                                                    ->state(fn($record) => $record->questions()->count())
                                                    ->badge()->color('info')->icon('heroicon-m-list-bullet'),

                                                TextEntry::make('submissions_count')
                                                    ->label('Submissions')
                                                    ->state(fn($record) => $record->submissions()->count())
                                                    ->badge()->color('primary')->icon('heroicon-m-inbox-arrow-down'),

                                                TextEntry::make('graded_count')
                                                    ->label('Graded')
                                                    ->state(fn($record) => $record->submissions()->where('status', 'graded')->count())
                                                    ->badge()->color('success')->icon('heroicon-m-check-badge'),

                                                TextEntry::make('pass_rate')
                                                    ->label('Pass Rate')
                                                    ->state(function ($record): string {
                                                        $graded = $record->submissions()->where('status', 'graded')->count();
                                                        if (!$graded) {
                                                            return '—';
                                                        }

                                                        $passed = $record->submissions()->where('status', 'graded')
                                                            ->where('score', '>=', $record->passing_marks)
                                                            ->count();

                                                        return round(($passed / $graded) * 100, 1) . '%';
                                                    })
                                                    ->badge()->color('success')->icon('heroicon-m-chart-bar'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Audit')
                            ->schema([
                                Section::make('Audit')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextEntry::make('createdBy.name')->label('Created By')->placeholder('—'),
                                            TextEntry::make('created_at')->label('Created At')->dateTime(),
                                            TextEntry::make('updatedBy.name')->label('Updated By')->placeholder('—'),
                                            TextEntry::make('updated_at')->label('Updated At')->dateTime(),
                                        ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
