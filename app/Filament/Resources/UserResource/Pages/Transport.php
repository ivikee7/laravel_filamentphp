<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Attendance;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Transport extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.transport';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getActions(): array
    {
        return [
            Actions\Action::make('View')
                ->url(fn(): string => UserResource::getUrl('view', [$this->record->id])),
            Actions\Action::make('Transport')
                ->url(fn(): string => UserResource::getUrl('transport', [$this->record->id])),
            Action::make('entredInBus')
                ->label('Entred in Bus')
                ->action(function (): void {
                    Attendance::create([
                        'user_id' => $this->record->id,
                        'type' => 'entredInBus'
                    ]);
                    Notification::make()
                        ->title('Entred in Bus')
                        ->success()
                        ->send();
                }),
            Action::make('entredInCampus')
                ->label('Entred in Campus')
                ->action(function (): void {
                    Attendance::create([
                        'user_id' => $this->record->id,
                        'type' => 'entredInCampus'
                    ]);
                    Notification::make()
                        ->title('Entred in Campus')
                        ->success()
                        ->send();
                }),
            Action::make('exitFromCampus')
                ->label('Exit from Campus')
                ->action(function (): void {
                    Attendance::create([
                        'user_id' => $this->record->id,
                        'type' => 'exitFromCampus'
                    ]);
                    Notification::make()
                        ->title('Exit from Campus')
                        ->success()
                        ->send();
                }),
            Action::make('exitFromBus')
                ->label('Exit from Bus')
                ->action(function (): void {
                    Attendance::create([
                        'user_id' => $this->record->id,
                        'type' => 'exitFromBus'
                    ]);
                    Notification::make()
                        ->title('Exit from Bus')
                        ->success()
                        ->send();
                })
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
