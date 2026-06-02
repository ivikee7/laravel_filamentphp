<?php

namespace App\Filament\Student\Resources\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EnrollmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Enrollment Details')
                    ->schema([
                        Group::make()
                            ->schema([
                                ImageEntry::make('course.thumbnail')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->imageSize(200)
                                    ->hiddenLabel()
                                    ->default('https://ui-avatars.com/api/?name=Course')
                                    ->square(),
                                Group::make()
                                    ->schema([
                                        TextEntry::make('course.title')
                                            ->label('Course Title')
                                            ->size('lg')
                                            ->weight('bold'),
                                        TextEntry::make('course.code')
                                            ->label('Course Code')
                                            ->badge()
                                            ->color('info'),
                                        TextEntry::make('status')
                                            ->label('Enrollment Status')
                                            ->badge()
                                            ->color(fn(string $state): string => match ($state) {
                                                'active' => 'success',
                                                'completed' => 'info',
                                                'suspended' => 'warning',
                                                'withdrawn' => 'danger',
                                                default => 'gray',
                                            })
                                            ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                                    ]),
                            ])
                            ->columns(2),
                        Group::make()
                            ->schema([
                                TextEntry::make('course.instructor.name')
                                    ->label('Instructor'),
                                TextEntry::make('course.subject.name')
                                    ->label('Subject'),
                                TextEntry::make('course.academicYear.name')
                                    ->label('Academic Year'),
                            ])
                            ->columns(3),
                    ])
                    ->columnSpanFull(),

                Section::make('Course Progress')
                    ->schema([
                        TextEntry::make('progress_percentage')
                            ->label('Progress')
                            ->formatStateUsing(fn($record) => $record->progress_percentage ? round($record->progress_percentage, 2) . '%' : '0%'),
                        TextEntry::make('completion_date')
                            ->label('Completion Date')
                            ->date()
                            ->placeholder('Not completed'),
                        TextEntry::make('course.lessons_count')
                            ->label('Total Lessons'),
                        TextEntry::make('course.materials_count')
                            ->label('Study Materials'),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),

                Section::make('Enrollment Timeline')
                    ->schema([
                        TextEntry::make('enrolled_at')
                            ->label('Enrolled On')
                            ->dateTime(),
                        TextEntry::make('started_at')
                            ->label('Started On')
                            ->dateTime()
                            ->placeholder('Not started'),
                        TextEntry::make('completed_at')
                            ->label('Completed On')
                            ->dateTime()
                            ->placeholder('Not completed'),
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed(),

                Section::make('Course Information')
                    ->schema([
                        TextEntry::make('course.description')
                            ->label('Description')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->collapsed(),
            ]);
    }
}

