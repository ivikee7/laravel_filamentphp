<?php

namespace App\Filament\Admin\Resources\Courses\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MaterialsRelationManager extends RelationManager
{
    protected static string $relationship = 'materials';
    protected static ?string $title = 'Course Materials';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->placeholder('e.g. Chapter 1 Notes'),

                Select::make('type')
                    ->options([
                        'document'   => '📄 Document / PDF',
                        'video'      => '🎬 Video',
                        'link'       => '🔗 External Link',
                        'assignment' => '📝 Assignment',
                        'image'      => '🖼️ Image',
                    ])
                    ->default('document')
                    ->required()
                    ->live()
                    ->native(false),

                Toggle::make('is_published')
                    ->label('Published — visible to students')
                    ->default(false)
                    ->inline(false),

                TextInput::make('url')
                    ->label('External URL')
                    ->url()
                    ->nullable()
                    ->columnSpanFull()
                    ->visible(fn ($get) => in_array($get('type'), ['link', 'video']))
                    ->placeholder('https://...'),

                Textarea::make('description')
                    ->rows(2)
                    ->columnSpanFull()
                    ->placeholder('Brief description of this material...'),

                FileUpload::make('file_path')
                    ->label('Upload File')
                    ->disk('public')
                    ->visibility('public')
                    ->directory('courses/materials')
                    ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(20480)
                    ->columnSpanFull()
                    ->visible(fn ($get) => in_array($get('type'), ['document', 'assignment', 'image'])),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')->label('#')->sortable()->width(50),

                TextColumn::make('title')
                    ->searchable()->wrap()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record): string => $record->description ?? ''),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'document'   => 'info',
                        'video'      => 'danger',
                        'link'       => 'success',
                        'assignment' => 'warning',
                        'image'      => 'purple',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'document'   => '📄 Document',
                        'video'      => '🎬 Video',
                        'link'       => '🔗 Link',
                        'assignment' => '📝 Assignment',
                        'image'      => '🖼️ Image',
                        default      => ucfirst($state),
                    }),

                TextColumn::make('url')
                    ->label('URL')
                    ->placeholder('—')
                    ->limit(30)
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab(),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'document'   => 'Document',
                        'video'      => 'Video',
                        'link'       => 'Link',
                        'assignment' => 'Assignment',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()->label('Add Material'),
            ]);
    }
}
