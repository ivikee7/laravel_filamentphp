<?php
namespace App\Filament\Admin\Resources\Exams\RelationManagers;
use App\Models\ExamQuestion;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';
    protected static ?string $title = 'Questions';
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Question builder')
                    ->tabs([
                        Tab::make('Core')
                            ->icon('heroicon-m-pencil-square')
                            ->schema([
                                Grid::make(2)->schema([
                                    Textarea::make('question')
                                        ->label('Question Text')
                                        ->required()
                                        ->rows(4)
                                        ->columnSpanFull()
                                        ->placeholder('Write a clear, focused question...'),
                                    Select::make('type')
                                        ->label('Question Type')
                                        ->options(ExamQuestion::typeOptions())
                                        ->default('multiple_choice')
                                        ->required()
                                        ->live()
                                        ->native(false)
                                        ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                                            if ($state === 'true_false') {
                                                $set('options', [
                                                    'true' => '✅ True',
                                                    'false' => '❌ False',
                                                ]);
                                                $set('correct_answer', $get('correct_answer') ?: 'true');
                                            }
                                            if ($state === 'multiple_choice' && blank($get('options'))) {
                                                $set('options', [
                                                    'A' => 'Option A',
                                                    'B' => 'Option B',
                                                    'C' => 'Option C',
                                                    'D' => 'Option D',
                                                ]);
                                            }
                                            if (in_array($state, ['short_answer', 'essay'], true)) {
                                                $set('shuffle_options', false);
                                            }
                                        }),
                                    TextInput::make('marks')
                                        ->label('Marks')
                                        ->numeric()
                                        ->default(1)
                                        ->minValue(0.5)
                                        ->step(0.5)
                                        ->required()
                                        ->suffix('marks'),
                                    TextInput::make('order')
                                        ->label('Order')
                                        ->numeric()
                                        ->default(fn () => (int) ($this->getOwnerRecord()->questions()->max('order') ?? 0) + 1)
                                        ->helperText('Display position in the exam.'),
                                ]),
                            ]),
                        Tab::make('Answer Key')
                            ->icon('heroicon-m-key')
                            ->schema([
                                Section::make('Objective Questions')
                                    ->description('Use answer keys and options for auto-graded questions.')
                                    ->schema([
                                        KeyValue::make('options')
                                            ->label('Answer Options')
                                            ->keyLabel('Key (A, B, C, D)')
                                            ->valueLabel('Option Text')
                                            ->columnSpanFull()
                                            ->addButtonLabel('Add Option')
                                            ->reorderable()
                                            ->default([])
                                            ->helperText('Use short keys like A/B/C or 1/2/3. The student sees the option text.')
                                            ->visible(fn (Get $get): bool => $get('type') === 'multiple_choice'),
                                        Placeholder::make('true_false_note')
                                            ->content('True / False options are generated automatically. You only need to pick the correct answer.')
                                            ->columnSpanFull()
                                            ->visible(fn (Get $get): bool => $get('type') === 'true_false'),
                                        Select::make('correct_answer')
                                            ->label('Correct Answer')
                                            ->options(function (Get $get): array {
                                                return match ($get('type')) {
                                                    'multiple_choice' => collect((array) $get('options'))
                                                        ->mapWithKeys(fn ($label, $key) => [$key => strtoupper((string) $key) . ' — ' . $label])
                                                        ->all(),
                                                    'true_false' => [
                                                        'true' => '✅ True',
                                                        'false' => '❌ False',
                                                    ],
                                                    default => [],
                                                };
                                            })
                                            ->searchable()
                                            ->native(false)
                                            ->required(fn (Get $get): bool => in_array($get('type'), ['multiple_choice', 'true_false'], true))
                                            ->helperText('Pick the exact key/value students should be marked against.')
                                            ->visible(fn (Get $get): bool => in_array($get('type'), ['multiple_choice', 'true_false'], true)),
                                        Textarea::make('correct_answer')
                                            ->label('Model Answer')
                                            ->rows(4)
                                            ->columnSpanFull()
                                            ->helperText(fn (Get $get): string => match ($get('type')) {
                                                'short_answer' => 'Enter the expected short response for grading.',
                                                'essay' => 'Optional model answer or rubric notes for instructor reference.',
                                                default => '',
                                            })
                                            ->placeholder(fn (Get $get): string => match ($get('type')) {
                                                'short_answer' => 'Expected answer...',
                                                'essay' => 'Model answer / rubric notes...',
                                                default => 'Model answer...',
                                            })
                                            ->visible(fn (Get $get): bool => in_array($get('type'), ['short_answer', 'essay'], true)),
                                    ]),
                            ]),
                        Tab::make('Advanced')
                            ->icon('heroicon-m-sparkles')
                            ->schema([
                                Section::make('Advanced Options')
                                    ->description('Fine-tune how the question behaves for students and instructors.')
                                    ->schema([
                                        Toggle::make('shuffle_options')
                                            ->label('Shuffle answer choices')
                                            ->helperText('Randomizes the MCQ order for each student.')
                                            ->default(false)
                                            ->visible(fn (Get $get): bool => $get('type') === 'multiple_choice'),
                                        Textarea::make('explanation')
                                            ->label('Explanation / Instructor Notes')
                                            ->rows(5)
                                            ->columnSpanFull()
                                            ->placeholder('Explain the reasoning, grading rubric, or reference notes...')
                                            ->helperText('Shown to instructors and can be surfaced in student review screens.'),
                                        Placeholder::make('builder_hint')
                                            ->content('Tip: MCQ and True/False questions can auto-grade instantly. Short answer and essay questions are great for manual review.')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->width(50),
                TextColumn::make('question')
                    ->label('Question')
                    ->limit(70)
                    ->searchable()
                    ->description(fn (ExamQuestion $record): string => collect([
                        ExamQuestion::typeLabel($record->type),
                        $record->answer_mode_label,
                        $record->shuffle_options ? 'Shuffled' : null,
                    ])->filter()->implode(' · '))
                    ->tooltip(fn (ExamQuestion $record) => $record->question),
                TextColumn::make('type')
                    ->badge()
                    ->icon(fn (string $state): string => ExamQuestion::typeIcon($state))
                    ->color(fn (string $state): string => ExamQuestion::typeColor($state))
                    ->formatStateUsing(fn (string $state): string => ExamQuestion::typeLabel($state)),
                TextColumn::make('choices')
                    ->label('Choices')
                    ->state(fn (ExamQuestion $record): string => match ($record->type) {
                        'multiple_choice' => (string) count($record->display_options),
                        'true_false' => '2',
                        default => '—',
                    })
                    ->badge()
                    ->color('gray'),
                TextColumn::make('shuffle_options')
                    ->label('Shuffle')
                    ->state(fn (ExamQuestion $record): string => $record->shuffle_options ? 'On' : 'Off')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'On' ? 'warning' : 'gray')
                    ->toggleable(),
                TextColumn::make('marks')
                    ->label('Marks')
                    ->suffix(' pts')
                    ->sortable(),
                TextColumn::make('correct_answer')
                    ->label('Answer')
                    ->limit(24)
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('explanation')
                    ->label('Explanation')
                    ->limit(30)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                SelectFilter::make('type')
                    ->options(collect(ExamQuestion::typeOptions())
                        ->mapWithKeys(fn (string $label, string $key) => [$key => strip_tags($label)])
                        ->all()),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Question')
                    ->icon('heroicon-m-plus')
                    ->color('primary'),
            ]);
    }
}
