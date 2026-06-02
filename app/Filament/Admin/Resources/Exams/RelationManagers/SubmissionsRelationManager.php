<?php

namespace App\Filament\Admin\Resources\Exams\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';
    protected static ?string $title = 'Student Submissions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('score')
                    ->label('Score')
                    ->numeric()
                    ->minValue(0)
                    ->nullable()
                    ->suffix(fn ($livewire) => ' / ' . $livewire->getOwnerRecord()->total_marks),

                Select::make('status')
                    ->options([
                        'in_progress' => 'In Progress',
                        'submitted'   => 'Submitted',
                        'graded'      => 'Graded',
                    ])
                    ->required()
                    ->native(false),

                TextInput::make('grade')
                    ->label('Grade')
                    ->maxLength(10)
                    ->nullable()
                    ->placeholder('e.g. A, B+'),

                Textarea::make('remarks')
                    ->label('Instructor Remarks')
                    ->rows(3)
                    ->columnSpanFull()
                    ->placeholder('Feedback for the student...'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.user.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record): string => $record->student?->admission_number ?? ''),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in_progress' => 'warning',
                        'submitted'   => 'info',
                        'graded'      => 'success',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'in_progress' => '⏳ In Progress',
                        'submitted'   => '📬 Submitted',
                        'graded'      => '✅ Graded',
                        default       => ucfirst($state),
                    }),

                TextColumn::make('score')
                    ->label('Score')
                    ->placeholder('—')
                    ->formatStateUsing(fn ($state, $record): string => $state !== null
                        ? "{$state} / {$record->exam->total_marks}"
                        : '—'
                    )
                    ->sortable(),

                TextColumn::make('grade')
                    ->label('Grade')
                    ->placeholder('—')
                    ->badge()
                    ->color('success'),

                TextColumn::make('time_taken_minutes')
                    ->label('Time Taken')
                    ->suffix(' min')
                    ->placeholder('—'),

                TextColumn::make('started_at')
                    ->label('Started')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('submitted_at')
                    ->label('Submitted')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('—'),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'in_progress' => 'In Progress',
                        'submitted'   => 'Submitted',
                        'graded'      => 'Graded',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Grade')
                    ->icon('heroicon-m-pencil-square')
                    ->modalHeading('Grade Submission')
                    ->successNotificationTitle('Submission graded successfully'),

                Action::make('view_answers')
                    ->label('Answers')
                    ->icon('heroicon-m-eye')
                    ->color('gray')
                    ->modalHeading(fn ($record): string => "Answers — {$record->student?->user?->name}")
                    ->modalContent(fn ($record) => view('filament.admin.modals.submission-answers', ['submission' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
            ])
            ->headerActions([])
            ->emptyStateHeading('No submissions yet')
            ->emptyStateDescription('Students will appear here once they start the exam.')
            ->emptyStateIcon('heroicon-o-inbox');
    }
}

