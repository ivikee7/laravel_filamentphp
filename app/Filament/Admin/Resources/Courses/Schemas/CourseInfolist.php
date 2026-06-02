<?php

namespace App\Filament\Admin\Resources\Courses\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class CourseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Course details')
                    ->tabs([
                        Tab::make('Details')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        // ── Left: Main Info ──────────────────────────────────────────
                                        Section::make('Course Details')
                                            ->columns(2)
                                            ->schema([
                                                TextEntry::make('title')
                                                    ->label('Course Title')
                                                    ->size('lg')
                                                    ->weight(\Filament\Support\Enums\FontWeight::Bold)
                                                    ->columnSpanFull(),

                                                TextEntry::make('code')
                                                    ->label('Course Code')
                                                    ->badge()
                                                    ->color('gray')
                                                    ->placeholder('—'),

                                                TextEntry::make('status')
                                                    ->badge()
                                                    ->color(fn (string $state): string => match ($state) {
                                                        'published' => 'success',
                                                        'draft'     => 'warning',
                                                        'archived'  => 'gray',
                                                        default     => 'gray',
                                                    })
                                                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                                                TextEntry::make('description')
                                                    ->html()
                                                    ->columnSpanFull()
                                                    ->placeholder('No description provided.')
                                                    ->prose(),
                                            ]),

                                        // ── Right: Meta ──────────────────────────────────────────────
                                        Section::make('Info')
                                            ->columns(2)
                                            ->schema([
                                                ImageEntry::make('thumbnail')
                                                    ->label('')
                                                    ->height(120)
                                                    ->defaultImageUrl('https://ui-avatars.com/api/?name=Course&background=6366f1&color=ffffff&size=200')
                                                    ->extraImgAttributes(['class' => 'rounded-xl w-full object-cover'])
                                                ->columnSpanFull(),

                                                TextEntry::make('subject.name')
                                                    ->label('Subject')
                                                    ->badge()
                                                    ->color('info')
                                                    ->placeholder('—'),

                                                TextEntry::make('instructor.name')
                                                    ->label('Instructor')
                                                    ->icon('heroicon-m-user')
                                                    ->placeholder('—'),

                                                TextEntry::make('academicYear.name')
                                                    ->label('Academic Year')
                                                    ->placeholder('—'),

                                                TextEntry::make('max_students')
                                                    ->label('Capacity')
                                                    ->suffix(' students')
                                                    ->placeholder('Unlimited'),
                                            ]),
                                    ]),
                            ]),
                        Tab::make('Statistics')
                            ->schema([
                                Section::make('Statistics')
                                    ->schema([
                                        Grid::make(4)
                                            ->schema([
                                                TextEntry::make('lessons_count')
                                                    ->label('Total Lessons')
                                                    ->state(fn ($record) => $record->lessons()->count())
                                                    ->badge()
                                                    ->color('success')
                                                    ->icon('heroicon-m-book-open'),

                                                TextEntry::make('published_lessons_count')
                                                    ->label('Published Lessons')
                                                    ->state(fn ($record) => $record->lessons()->where('is_published', true)->count())
                                                    ->badge()
                                                    ->color('info')
                                                    ->icon('heroicon-m-check-circle'),

                                                TextEntry::make('materials_count')
                                                    ->label('Materials')
                                                    ->state(fn ($record) => $record->materials()->count())
                                                    ->badge()
                                                    ->color('warning')
                                                    ->icon('heroicon-m-paper-clip'),

                                                TextEntry::make('active_enrollments')
                                                    ->label('Active Enrollments')
                                                    ->state(fn ($record) => $record->enrollments()->where('status', 'active')->count())
                                                    ->badge()
                                                    ->color('primary')
                                                    ->icon('heroicon-m-user-group'),
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
                                            TextEntry::make('updatedBy.name')->label('Last Updated By')->placeholder('—'),
                                            TextEntry::make('updated_at')->label('Last Updated')->dateTime(),
                                        ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
