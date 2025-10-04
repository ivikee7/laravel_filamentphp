<?php

namespace App\Filament\Admin\Resources\Registrations\Schemas;

use App\Models\Registration;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RegistrationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('date_of_birth')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('gender.name')
                    ->label('Gender')
                    ->placeholder('-'),
                TextEntry::make('father_name')
                    ->placeholder('-'),
                TextEntry::make('father_qualification')
                    ->placeholder('-'),
                TextEntry::make('father_occupation')
                    ->placeholder('-'),
                TextEntry::make('primary_contact_number')
                    ->placeholder('-'),
                TextEntry::make('mother_name')
                    ->placeholder('-'),
                TextEntry::make('mother_qualification')
                    ->placeholder('-'),
                TextEntry::make('mother_occupation')
                    ->placeholder('-'),
                TextEntry::make('secondary_contact_number')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->placeholder('-'),
                TextEntry::make('city')
                    ->placeholder('-'),
                TextEntry::make('state')
                    ->placeholder('-'),
                TextEntry::make('pin_code')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('previous_school')
                    ->placeholder('-'),
                TextEntry::make('payment_amount')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('payment_mode')
                    ->placeholder('-'),
                TextEntry::make('payment_notes')
                    ->placeholder('-'),
                TextEntry::make('previousClass.name')
                    ->label('Previous class')
                    ->placeholder('-'),
                TextEntry::make('academicYear.name')
                    ->label('Academic year')
                    ->placeholder('-'),
                TextEntry::make('class.name')
                    ->label('Class')
                    ->placeholder('-'),
                TextEntry::make('enquiry_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('placement_test_date')
                    ->date()
                    ->placeholder('-'),
                IconEntry::make('placement_test_status')
                    ->boolean()
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
                    ->visible(fn (Registration $record): bool => $record->trashed()),
            ]);
    }
}
