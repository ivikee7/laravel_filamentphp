<?php

namespace App\Filament\Admin\Resources\Exams\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'results';

    protected static ?string $title = 'Results / Participants';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                // ── Participant type switcher ──────────────────────────────
                Select::make('participant_type')
                    ->label('Participant Type')
                    ->options([
                        'student'   => '🎓 Enrolled Student',
                        'applicant' => '📋 Applicant (Registration)',
                        'external'  => '🌐 External / Walk-in',
                    ])
                    ->default('student')
                    ->required()
                    ->live()
                    ->columnSpan(2),

                // ── Student (enrolled) ────────────────────────────────────
                Select::make('student_id')
                    ->label('Student')
                    ->relationship('student', 'admission_number')
                    ->getOptionLabelFromRecordUsing(
                        fn ($record) => "{$record->user?->name} ({$record->admission_number})"
                    )
                    ->searchable()
                    ->preload()
                    ->visible(fn ($get) => $get('participant_type') === 'student')
                    ->required(fn ($get) => $get('participant_type') === 'student')
                    ->columnSpan(2),

                // ── Applicant (registration) ──────────────────────────────
                Select::make('registration_id')
                    ->label('Applicant (Registration)')
                    ->relationship('registration', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn ($get) => $get('participant_type') === 'applicant')
                    ->required(fn ($get) => $get('participant_type') === 'applicant')
                    ->columnSpan(2),

                // ── External / Walk-in ────────────────────────────────────
                TextInput::make('participant_name')
                    ->label('Participant Name')
                    ->maxLength(255)
                    ->visible(fn ($get) => $get('participant_type') === 'external')
                    ->required(fn ($get) => $get('participant_type') === 'external'),

                TextInput::make('participant_email')
                    ->label('Participant Email')
                    ->email()
                    ->maxLength(255)
                    ->visible(fn ($get) => $get('participant_type') === 'external')
                    ->nullable(),

                // ── Result fields ─────────────────────────────────────────
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'graded'  => 'Graded',
                        'absent'  => 'Absent',
                    ])
                    ->default('pending')
                    ->required(),

                TextInput::make('score')
                    ->numeric()
                    ->nullable()
                    ->minValue(0),

                TextInput::make('grade')
                    ->maxLength(10)
                    ->nullable(),

                Textarea::make('remarks')
                    ->rows(3)
                    ->columnSpan(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('participant_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'student'   => 'info',
                        'applicant' => 'warning',
                        'external'  => 'gray',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'student'   => 'Student',
                        'applicant' => 'Applicant',
                        'external'  => 'External',
                        default     => $state,
                    }),

                TextColumn::make('participant_label')
                    ->label('Participant')
                    ->getStateUsing(fn ($record): string => $record->participant_label)
                    ->searchable(false)
                    ->sortable(false),

                TextColumn::make('student.admission_number')
                    ->label('Adm. No.')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('score')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('grade')
                    ->placeholder('-'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'graded'  => 'success',
                        'pending' => 'warning',
                        'absent'  => 'danger',
                        default   => 'gray',
                    }),

                TextColumn::make('graded_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('gradedBy.name')
                    ->label('Graded By')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('participant_type')
                    ->label('Participant Type')
                    ->options([
                        'student'   => 'Enrolled Student',
                        'applicant' => 'Applicant',
                        'external'  => 'External / Walk-in',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'graded'  => 'Graded',
                        'absent'  => 'Absent',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()->label('Add Participant Result'),
            ]);
    }
}
