<?php

namespace App\Filament\Student\Resources\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CourseMaterialInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Material Information')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Material Title')
                            ->size('lg')
                            ->weight('bold'),
                        TextEntry::make('course.title')
                            ->label('Course'),
                        TextEntry::make('lesson.title')
                            ->label('Related Lesson')
                            ->placeholder('Not assigned to any lesson'),
                        TextEntry::make('material_type')
                            ->label('Material Type')
                            ->badge()
                            ->color('info'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Material Description')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Description')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('File Information')
                    ->schema([
                        TextEntry::make('file_path')
                            ->label('File Path')
                            ->copyable(),
                        TextEntry::make('file_size')
                            ->label('File Size')
                            ->formatStateUsing(fn($state) => $state ? round($state / 1024 / 1024, 2) . ' MB' : 'N/A'),
                        TextEntry::make('file_type')
                            ->label('File Type')
                            ->badge()
                            ->color('warning'),
                        TextEntry::make('downloads_count')
                            ->label('Downloads')
                            ->formatStateUsing(fn($state) => $state ?? 0),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->visible(fn($record) => $record->file_path !== null),

                Section::make('External Resource')
                    ->schema([
                        TextEntry::make('resource_url')
                            ->label('Resource URL')
                            ->copyable(),
                        TextEntry::make('resource_platform')
                            ->label('Platform')
                            ->placeholder('Not specified'),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->visible(fn($record) => $record->resource_url !== null),

                Section::make('Material Status')
                    ->schema([
                        TextEntry::make('is_published')
                            ->label('Published')
                            ->badge()
                            ->color(fn(string $state): string => $state ? 'success' : 'warning')
                            ->formatStateUsing(fn(string $state): string => $state ? 'Published' : 'Draft'),
                        TextEntry::make('is_required')
                            ->label('Required Material')
                            ->badge()
                            ->color('info')
                            ->formatStateUsing(fn(string $state): string => $state ? 'Yes' : 'No'),
                        TextEntry::make('sequence_order')
                            ->label('Order'),
                        TextEntry::make('difficulty_level')
                            ->label('Difficulty Level')
                            ->badge()
                            ->color('warning')
                            ->placeholder('Not specified'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Learning Information')
                    ->schema([
                        TextEntry::make('learning_outcomes')
                            ->label('Covered Learning Outcomes')
                            ->html()
                            ->placeholder('Not specified')
                            ->columnSpanFull(),
                        TextEntry::make('estimated_reading_time')
                            ->label('Estimated Learning Time')
                            ->formatStateUsing(fn($state) => $state ? "{$state} minutes" : 'N/A'),
                    ])
                    ->columns(2)
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

