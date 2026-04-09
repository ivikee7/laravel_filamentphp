<?php

namespace App\Filament\Admin\Resources\ActivityLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActivityLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Activity Details')
                    ->schema([
                        Group::make()
                            ->schema([
                                TextEntry::make('id')->label('ID'),
                                TextEntry::make('description')->label('Description'),
                            ])->columns(2),

                        Group::make()
                            ->schema([
                                TextEntry::make('log_name')->label('Log name'),
                                TextEntry::make('created_at')->label('Created At')->dateTime(),
                            ])->columns(2),

                        TextEntry::make('properties')
                            ->label('Properties (JSON)')
                            ->getStateUsing(function ($record) {
                                try {
                                    return json_encode($record->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                                } catch (\Throwable $e) {
                                    return (string) $record->properties;
                                }
                            })
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Causer')
                    ->schema([
                        TextEntry::make('causer.name')->label('Name')->placeholder('-'),
                        TextEntry::make('causer_type')->label('Causer Type'),
                        TextEntry::make('causer_id')->label('Causer ID'),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('Subject')
                    ->schema([
                        TextEntry::make('subject_type')->label('Subject Type'),
                        TextEntry::make('subject_id')->label('Subject ID'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Meta')
                    ->schema([
                        TextEntry::make('properties->ip')->label('IP Address'),
                        TextEntry::make('properties->user_agent')->label('User Agent')->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
