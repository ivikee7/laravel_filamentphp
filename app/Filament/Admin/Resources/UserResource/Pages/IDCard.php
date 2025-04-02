<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\Attendance;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;

class IDCard extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.admin.resources.user-resource.pages.i-d-card';

    public function mount(int | string $record): void
    {
        $this->record = User::findOrFail($record);
    }

    protected function getActions(): array
    {
        return [
            Actions\Action::make('View')
                ->url(fn(): string => UserResource::getUrl('view', [$this->record->id])),
            Actions\Action::make('Transport')
                ->url(fn(): string => UserResource::getUrl('transport', [$this->record->id])),

        ];
    }

    public function getModel(): string
    {
        return User::class;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Attendance::query()->where('user_id', $this->record->id))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->wrap(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            null => 'Empty',
                            "entredInCampus" => 'Entred in Campus',
                            "exitFromCampus" => 'Exit from Campus',
                            "entredInBus" => 'Entred in Bus',
                            "exitFromBus" => 'Exit from Bus',
                            default => 'OK'
                        };
                    })
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()
                    ->wrap(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                // Add actions if needed
            ])
            ->bulkActions([
                // Add bulk actions if needed
            ]);
    }
}
