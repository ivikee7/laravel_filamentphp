<?php

namespace App\Filament\Student\Resources\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CourseLessonInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lesson Overview')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Lesson Title')
                            ->size('lg')
                            ->weight('bold'),
                        TextEntry::make('course.title')
                            ->label('Course'),
                        TextEntry::make('lesson_number')
                            ->label('Lesson Number')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('sequence_order')
                            ->label('Sequence Order'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Lesson Content')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Description')
                            ->html()
                            ->columnSpanFull(),
                        TextEntry::make('content')
                            ->label('Lesson Content')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Learning Resources')
                    ->schema([
                        TextEntry::make('learning_objectives')
                            ->label('Learning Objectives')
                            ->html()
                            ->columnSpanFull(),
                        TextEntry::make('duration_minutes')
                            ->label('Estimated Duration')
                            ->formatStateUsing(fn($state) => $state ? "{$state} minutes" : 'N/A'),
                        TextEntry::make('skill_level')
                            ->label('Skill Level')
                            ->badge()
                            ->color('info')
                            ->placeholder('Not specified'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Lesson Status')
                    ->schema([
                        TextEntry::make('is_published')
                            ->label('Published')
                            ->badge()
                            ->color(fn(string $state): string => $state ? 'success' : 'warning')
                            ->formatStateUsing(fn(string $state): string => $state ? 'Published' : 'Draft'),
                        TextEntry::make('is_active')
                            ->label('Active')
                            ->badge()
                            ->color(fn(string $state): string => $state ? 'success' : 'danger')
                            ->formatStateUsing(fn(string $state): string => $state ? 'Active' : 'Inactive'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Additional Resources')
                    ->schema([
                        TextEntry::make('materials_count')
                            ->label('Attached Materials')
                            ->formatStateUsing(fn($record) => $record->materials_count ?? 0),
                        TextEntry::make('resource_url')
                            ->label('External Resource URL')
                            ->placeholder('No external resource')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

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

