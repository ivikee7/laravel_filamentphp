<?php

namespace App\Filament\Student\Resources\MyCourseResource\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CourseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Course Overview')
                    ->schema([
                        Group::make()
                            ->schema([
                                ImageEntry::make('thumbnail')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->imageSize(200)
                                    ->hiddenLabel()
                                    ->default('https://ui-avatars.com/api/?name=Course&size=200')
                                    ->square(),
                                Group::make()
                                    ->schema([
                                        TextEntry::make('title')
                                            ->label('Course Title')
                                            ->size('lg')
                                            ->weight('bold'),
                                        TextEntry::make('code')
                                            ->label('Course Code')
                                            ->badge()
                                            ->color('info'),
                                        TextEntry::make('subject.name')
                                            ->label('Subject'),
                                    ]),
                            ])
                            ->columns(2),
                        TextEntry::make('description')
                            ->label('Course Description')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Course Information')
                    ->schema([
                        TextEntry::make('instructor.name')
                            ->label('Instructor'),
                        TextEntry::make('academicYear.name')
                            ->label('Academic Year'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'published' => 'success',
                                'draft' => 'warning',
                                'archived' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('Course Content')
                    ->schema([
                        TextEntry::make('lessons_count')
                            ->label('Lessons')
                            ->formatStateUsing(fn($record) => $record->lessons_count ?? 0),
                        TextEntry::make('materials_count')
                            ->label('Course Materials')
                            ->formatStateUsing(fn($record) => $record->materials_count ?? 0),
                        TextEntry::make('exams_count')
                            ->label('Exams')
                            ->formatStateUsing(fn($record) => $record->exams_count ?? 0),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('Capacity Information')
                    ->schema([
                        TextEntry::make('enrolled_count')
                            ->label('Enrolled Students')
                            ->formatStateUsing(fn($record) => $record->getEnrolledCountAttribute() ?? 0),
                        TextEntry::make('max_students')
                            ->label('Total Capacity')
                            ->formatStateUsing(fn($record) => $record->max_students ?? 'Unlimited'),
                        TextEntry::make('capacity_progress')
                            ->label('Capacity Used (%)')
                            ->formatStateUsing(fn($record) => $record->getCapacityProgressAttribute() !== null
                                ? $record->getCapacityProgressAttribute() . '%'
                                : 'N/A'),
                    ])
                    ->columns(3)
                    ->columnSpanFull()
                    ->visible(fn($record) => $record->max_students !== null),

                Section::make('Metadata')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed(),
            ]);
    }
}

