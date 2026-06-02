<?php

namespace App\Filament\Student\Resources\Threads\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ConversationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Conversation Details')
                    ->schema([
                        TextEntry::make('subject')
                            ->label('Subject')
                            ->size('lg')
                            ->weight('bold')
                            ->columnSpanFull(),
                        TextEntry::make('description')
                            ->label('Description')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Participants')
                    ->schema([
                        TextEntry::make('participants_count')
                            ->label('Number of Participants')
                            ->formatStateUsing(fn($record) => $record->participants()->count()),
                        TextEntry::make('created_by')
                            ->label('Started By')
                            ->placeholder('Unknown'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Conversation Activity')
                    ->schema([
                        TextEntry::make('messages_count')
                            ->label('Total Messages')
                            ->formatStateUsing(fn($record) => $record->messages()->count() ?? 0),
                        TextEntry::make('last_message_at')
                            ->label('Last Message')
                            ->dateTime()
                            ->placeholder('No messages yet'),
                        TextEntry::make('is_active')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => $state ? 'success' : 'warning')
                            ->formatStateUsing(fn(string $state): string => $state ? 'Active' : 'Archived'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Conversation Timeline')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Started On')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed(),
            ]);
    }
}

