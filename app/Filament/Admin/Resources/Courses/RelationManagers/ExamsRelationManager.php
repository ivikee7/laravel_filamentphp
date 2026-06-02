<?php

namespace App\Filament\Admin\Resources\Courses\RelationManagers;

use App\Filament\Admin\Resources\Exams\ExamResource;
use App\Models\Exam;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ExamsRelationManager extends RelationManager
{
    protected static string $relationship = 'exams';

    protected static ?string $title = 'Exams';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('examType.name')
                    ->label('Type')
                    ->badge()
                    ->color(fn (Exam $record): string => Exam::resolveTypeColor($record->examType))
                    ->placeholder('—'),
                TextColumn::make('total_marks')->label('Total Marks'),
                TextColumn::make('passing_marks')->label('Pass Marks'),
                TextColumn::make('exam_date')->date()->placeholder('-'),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft'     => 'warning',
                        'ongoing'   => 'info',
                        'completed' => 'gray',
                        default     => 'gray',
                    }),
                TextColumn::make('results_count')->counts('results')->label('Results'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft'     => 'Draft',
                        'published' => 'Published',
                        'ongoing'   => 'Ongoing',
                        'completed' => 'Completed',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->url(fn (): string => ExamResource::getUrl('create')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record): string => ExamResource::getUrl('view', ['record' => $record])),
            ]);
    }
}

