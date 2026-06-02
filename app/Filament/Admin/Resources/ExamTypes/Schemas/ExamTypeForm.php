<?php

namespace App\Filament\Admin\Resources\ExamTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExamTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Exam Type Details')
                    ->description('Define different types of exams (quiz, midterm, final, etc.)')
                    ->icon('heroicon-o-flag')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Type Name')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g. Quiz, Mid-Term, Final, Assignment, Practical')
                            ->helperText('Choose a descriptive name for this exam type.')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set): void {
                                $set('code', str($state)->slug('_')->upper()->toString());
                            }),

                        TextInput::make('code')
                            ->label('System Code')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g. MIDTERM')
                            ->helperText('Used for automation, reporting, and API integrations.'),

                        TextInput::make('color')
                            ->required()
                            ->maxLength(20)
                            ->default('gray')
                            ->placeholder('e.g. info, warning, success'),

                        TextInput::make('icon')
                            ->maxLength(100)
                            ->placeholder('e.g. heroicon-m-academic-cap'),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Optional details about when this exam type should be used.'),

                    ]),
            ]);
    }
}
