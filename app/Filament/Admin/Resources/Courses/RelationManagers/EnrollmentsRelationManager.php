<?php

namespace App\Filament\Admin\Resources\Courses\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    protected static ?string $title = 'Enrollments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('student_id')
                    ->label('Student')
                    ->relationship('student', 'admission_number')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->user?->name} ({$record->admission_number})")
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('status')
                    ->options([
                        'active'    => 'Active',
                        'completed' => 'Completed',
                        'dropped'   => 'Dropped',
                    ])
                    ->default('active')
                    ->required(),

                Textarea::make('remarks')
                    ->rows(3)
                    ->columnSpan(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.admission_number')->label('ID')->searchable(),
                TextColumn::make('student.user.name')->label('Student')->searchable()->sortable(),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active'    => 'success',
                        'completed' => 'info',
                        'dropped'   => 'danger',
                        default     => 'gray',
                    }),
                TextColumn::make('enrolled_at')->dateTime()->sortable(),
                TextColumn::make('remarks')->limit(40)->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active'    => 'Active',
                        'completed' => 'Completed',
                        'dropped'   => 'Dropped',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}

