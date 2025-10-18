<?php

namespace App\Filament\Admin\Resources\Students\Schemas;

use App\Models\Student;
use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StudentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('registration.name')
                    ->label('Registration')
                    ->placeholder('-'),
                TextEntry::make('quota.name')
                    ->label('Quota'),
                TextEntry::make('admission_number')
                    ->placeholder('-'),
                TextEntry::make('current_status')
                    ->badge(),
                TextEntry::make('tc_status')
                    ->badge(),
                TextEntry::make('leaving_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('exit_reason')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('local_guardian_user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('local_guardian_relationship')
                    ->placeholder('-'),
                TextEntry::make('created_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('updated_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('deleted_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (User $record): bool => $record->trashed()),
            ]);
    }
}
