<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Stores\Resources\Students\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StudentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('registration.name'),
                TextEntry::make('quota.name'),
                TextEntry::make('admission_number'),
                TextEntry::make('current_status'),
                TextEntry::make('tc_status'),
                TextEntry::make('leaving_date')
                    ->date(),
                TextEntry::make('local_guardian_user_id')
                    ->numeric(),
                TextEntry::make('local_guardian_relationship'),
                TextEntry::make('created_by')
                    ->numeric(),
                TextEntry::make('updated_by')
                    ->numeric(),
                TextEntry::make('deleted_by')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
            ]);
    }
}
