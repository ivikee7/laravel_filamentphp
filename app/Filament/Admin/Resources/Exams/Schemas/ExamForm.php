<?php

namespace App\Filament\Admin\Resources\Exams\Schemas;

use App\Models\Exam;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Exam Details')
                    ->description('Core information about the exam.')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->placeholder('e.g. Mid-Term Mathematics Exam'),

                        Select::make('exam_type_id')
                            ->label('Exam Type')
                            ->relationship('examType', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->native(false),

                        Select::make('status')
                            ->options([
                                'draft'     => 'Draft',
                                'published' => 'Published',
                                'ongoing'   => 'Ongoing',
                                'completed' => 'Completed',
                            ])
                            ->default('draft')
                            ->required()
                            ->native(false),

                        Select::make('course_id')
                            ->label('Course (optional)')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->native(false)
                            ->helperText('Leave blank for standalone/entrance exams'),

                        Select::make('academic_year_id')
                            ->label('Academic Year')
                            ->relationship('academicYear', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->native(false),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Brief overview of this exam...'),
                    ]),

                Section::make('Scoring & Duration')
                    ->description('Configure marks and time limits.')
                    ->icon('heroicon-o-calculator')
                    ->columns(3)
                    ->schema([
                        TextInput::make('total_marks')
                            ->label('Total Marks')
                            ->numeric()
                            ->default(100)
                            ->required()
                            ->minValue(1),

                        TextInput::make('passing_marks')
                            ->label('Passing Marks')
                            ->numeric()
                            ->default(40)
                            ->required()
                            ->minValue(0)
                            ->lte('total_marks'),

                        TextInput::make('duration_minutes')
                            ->label('Duration')
                            ->numeric()
                            ->nullable()
                            ->suffix('minutes')
                            ->placeholder('No limit'),
                        TextInput::make('max_attempts')
                            ->label('Max Attempts')
                            ->numeric()
                            ->nullable()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(1)
                            ->placeholder('Leave blank for unlimited')
                            ->helperText('How many times a student may attempt this exam. Leave blank = unlimited.')
                            ->suffix('attempt(s)'),
                    ]),

                Section::make('Schedule')
                    ->description('When is this exam scheduled?')
                    ->icon('heroicon-o-calendar-days')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('exam_date')
                            ->label('Exam Date')
                            ->nullable()
                            ->native(false),

                        TimePicker::make('start_time')
                            ->label('Start Time')
                            ->seconds(false)
                            ->nullable(),
                    ]),

                Section::make('Instructions')
                    ->description('Instructions shown to students before they start.')
                    ->icon('heroicon-o-information-circle')
                    ->collapsed()
                    ->schema([
                        Textarea::make('instructions')
                            ->label('Student Instructions')
                            ->rows(5)
                            ->columnSpanFull()
                            ->placeholder("e.g.\n- Read each question carefully.\n- All answers are final once submitted.\n- Mobile devices are not allowed."),
                    ]),
            ]);
    }
}
