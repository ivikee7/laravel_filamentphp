<?php

namespace App\Filament\Admin\Resources\Courses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn ($record): string =>
                        'https://ui-avatars.com/api/?name='.urlencode($record->title).'&background=6366f1&color=ffffff'
                    )
                    ->size(44),

                TextColumn::make('title')
                    ->searchable()
                    ->wrap()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record): string => $record->code ?? ''),

                TextColumn::make('subject.name')
                    ->label('Subject')
                    ->badge()
                    ->color('info')
                    ->wrap()
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('instructor.name')
                    ->label('Instructor')
                    ->wrap()
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('academicYear.name')
                    ->label('Academic Year')
                    ->wrap()
                    ->wrapHeader()
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft'     => 'warning',
                        'archived'  => 'gray',
                        default     => 'gray',
                    })
                    ->wrap()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('enrollments_count')
                    ->label('Enrolled')
                    ->counts('enrollments')
                    ->wrap()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('capacity_utilization')
                    ->label('Utilization')
                    ->state(fn ($record): string => $record->max_students
                        ? round((($record->enrollments_count ?? $record->enrollments()->count()) / max($record->max_students, 1)) * 100) . '%'
                        : 'Unlimited')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state === 'Unlimited' => 'gray',
                        (int) $state >= 90 => 'danger',
                        (int) $state >= 70 => 'warning',
                        default => 'success',
                    })
                    ->wrap(),

                TextColumn::make('lessons_count')
                    ->label('Lessons')
                    ->counts('lessons')
                    ->wrap()
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('materials_count')
                    ->label('Materials')
                    ->counts('materials')
                    ->wrap()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->toggleable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('max_students')
                    ->label('Capacity')
                    ->wrap()
                    ->placeholder('∞')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft'     => 'Draft',
                        'published' => 'Published',
                        'archived'  => 'Archived',
                    ]),
                SelectFilter::make('subject_id')
                    ->label('Subject')
                    ->relationship('subject', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('academic_year_id')
                    ->label('Academic Year')
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('instructor_id')
                    ->label('Instructor')
                    ->relationship('instructor', 'name')
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
