<?php

namespace App\Filament\Admin\Pages\IDCards;

use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;

class ListIDCards extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.i-d-cards.list-i-d-cards';

    protected static ?string $slug = 'id-cards';
    protected static ?string $navigationGroup = 'IDCard';
    protected static ?string $navigationLabel = 'ID Cards';

    public function table(Table $table): Table
    {
        return $table
            ->query(User::with(['student', 'student.classAssignments'])->role('Student')) // eager load relationships
            ->columns([

                ImageColumn::make('avatar')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(url('default-avatar.png'))
                    ->size(50),

                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.current_status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'danger' => 'left',
                        'warning' => 'graduated',
                    ]),

                TextColumn::make('student.classAssignments.class.name')
                    ->label('Class')
                    ->sortable()
                    ->formatStateUsing(fn($state, $record) => $record->student->classAssignments->last()?->class?->name),

                TextColumn::make('student.classAssignments.section.name')
                    ->label('Section')
                    ->sortable()
                    ->formatStateUsing(fn($state, $record) => $record->student->classAssignments->last()?->section?->name),
                ViewColumn::make('qr_code')
                    ->label('QR Code')
                    ->view('components.qr-code-column')
                    ->state(fn($record) => route('filament.admin.pages.id-cards.{record}', ['record' => $record->id]))
                    ->alignCenter(),
            ])
            ->filters([
                // Add filters here if needed (class, section, etc.)
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => route('filament.admin.pages.id-cards.{record}', ['record' => $record->id])),

            ]);
    }
}
