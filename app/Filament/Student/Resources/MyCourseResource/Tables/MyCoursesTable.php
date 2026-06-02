<?php

namespace App\Filament\Student\Resources\MyCourseResource\Tables;

use App\Filament\Student\Resources\MyCourseResource\Pages;
use App\Models\Course;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MyCoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('')
                    ->square()
                    ->defaultImageUrl(fn (Course $record): string =>
                        'https://ui-avatars.com/api/?name=' . urlencode($record->title) . '&background=6366f1&color=ffffff&size=80'
                    )
                    ->size(56)
                    ->extraImgAttributes(['class' => 'rounded-xl']),

                TextColumn::make('title')
                    ->label('Course')
                    ->description(fn (Course $record): string =>
                        collect(array_filter([
                            $record->code,
                            $record->subject?->name,
                        ]))->implode(' · ')
                    )
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('instructor.name')
                    ->label('Instructor')
                    ->wrap()
                    ->icon('heroicon-m-user-circle')
                    ->placeholder('—'),

                TextColumn::make('lessons_count')
                    ->label('Lessons')
                    ->wrap()
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-m-book-open'),

                TextColumn::make('materials_count')
                    ->label('Materials')
                    ->wrap()
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-m-paper-clip'),

                TextColumn::make('exams_count')
                    ->label('Exams')
                    ->wrap()
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-clipboard-document-list'),

                TextColumn::make('academicYear.name')
                    ->label('Year')
                    ->wrap()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('title')
            ->recordUrl(fn (Course $record): string => Pages\ViewMyCourse::getUrl(['record' => $record]))
            ->filters([
                SelectFilter::make('subject')->relationship('subject', 'name')->label('Subject'),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('Open Course')
                    ->icon(Heroicon::ArrowRightCircle)
                    ->color('primary')
                    ->url(fn (Course $record): string => Pages\ViewMyCourse::getUrl(['record' => $record])),
            ])
            ->toolbarActions([])
            ->emptyStateHeading('No courses enrolled')
            ->emptyStateDescription('You are not enrolled in any active courses yet. Contact your administrator for enrollment.')
            ->emptyStateIcon(Heroicon::AcademicCap);
    }
}

