<?php

namespace App\Filament\Admin\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Course Information')
                    ->description('Basic details about this course.')
                    ->icon('heroicon-o-academic-cap')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->placeholder('e.g. Introduction to Mathematics'),

                        TextInput::make('code')
                            ->label('Course Code')
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('e.g. MATH-101'),

                        Select::make('status')
                            ->options([
                                'draft'     => 'Draft',
                                'published' => 'Published',
                                'archived'  => 'Archived',
                            ])
                            ->default('draft')
                            ->required()
                            ->native(false),

                        RichEditor::make('description')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'bulletList', 'orderedList', 'h2', 'h3', 'link',
                            ]),
                    ]),

                Section::make('Academic Settings')
                    ->description('Link this course to academic classifications.')
                    ->icon('heroicon-o-building-library')
                    ->columns(2)
                    ->schema([
                        Select::make('subject_id')
                            ->label('Subject')
                            ->relationship('subject', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->native(false),

                        Select::make('academic_year_id')
                            ->label('Academic Year')
                            ->relationship('academicYear', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->native(false),

                        Select::make('instructor_id')
                            ->label('Instructor / Teacher')
                            ->relationship('instructor', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->native(false),

                        TextInput::make('max_students')
                            ->label('Max Students (leave blank for unlimited)')
                            ->numeric()
                            ->minValue(1)
                            ->nullable()
                            ->suffix('students'),
                    ]),

                Section::make('Course Thumbnail')
                    ->description('Optional cover image displayed on course cards.')
                    ->icon('heroicon-o-photo')
                    ->collapsed()
                    ->schema([
                        FileUpload::make('thumbnail')
                            ->image()
                            ->imageEditor()
                            ->directory('courses/thumbnails')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
