<?php

namespace App\Filament\Admin\Resources\ExamTypes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExamTypeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Type Details')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Exam Type Name')
                            ->size('lg')
                            ->weight(\Filament\Support\Enums\FontWeight::Bold)
                            ->columnSpanFull(),
                        TextEntry::make('code')
                            ->badge()
                            ->color('gray'),
                        TextEntry::make('is_active')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive')
                            ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                        TextEntry::make('color')
                            ->label('Color Token')
                            ->placeholder('—'),
                        TextEntry::make('icon')
                            ->placeholder('—'),
                        TextEntry::make('sort_order')
                            ->label('Display Order'),
                        TextEntry::make('description')
                            ->columnSpanFull()
                            ->placeholder('—'),
                    ]),

                Section::make('Statistics')
                    ->description('Usage statistics for this exam type.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('exam_count')
                                ->label('Total Exams')
                                ->state(fn ($record) => $record->exams()->count())
                                ->badge()
                                ->color('info')
                                ->icon('heroicon-m-list-bullet'),

                            TextEntry::make('published_exam_count')
                                ->label('Published Exams')
                                ->state(fn ($record) => $record->exams()->where('status', 'published')->count())
                                ->badge()
                                ->color('success')
                                ->icon('heroicon-m-check-circle'),
                        ]),
                    ]),

                Section::make('Audit')
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('created_at')->label('Created At')->dateTime(),
                            TextEntry::make('updated_at')->label('Updated At')->dateTime(),
                        ]),
                    ]),
            ]);
    }
}
