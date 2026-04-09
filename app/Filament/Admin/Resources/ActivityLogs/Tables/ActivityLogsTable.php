<?php

namespace App\Filament\Admin\Resources\ActivityLogs\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity as ActivityModel;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('description')
                    ->searchable()
                    ->wrap()
                    ->limit(80),

                TextColumn::make('log_name')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('causer.name')
                    ->label('Causer')
                    ->getStateUsing(function ($record) {
                        return $record->causer?->name ?? $record->causer_type ?? null;
                    })
                    ->searchable(),

                TextColumn::make('subject_type')
                    ->label('Subject Type')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('properties')
                    ->label('Properties')
                    ->getStateUsing(function ($record) {
                        try {
                            return json_encode($record->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        } catch (\Throwable $e) {
                            return (string) $record->properties;
                        }
                    })
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->options(fn() => ActivityModel::query()->distinct()->pluck('log_name', 'log_name')->toArray()),
            ])
            ->defaultSort('id', 'desc')
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}

