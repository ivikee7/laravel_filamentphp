<?php

namespace App\Filament\Admin\Resources\Enquiries\Schemas;

use App\Models\Enquiry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EnquiryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('gender.name')
                    ->placeholder('-'),
                TextEntry::make('date_of_birth')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('father_name')
                    ->placeholder('-'),
                TextEntry::make('mother_name')
                    ->placeholder('-'),
                TextEntry::make('primary_contact_number'),
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
                TextEntry::make('source')
                    ->placeholder('-'),
                TextEntry::make('previous_school')
                    ->placeholder('-'),
                TextEntry::make('previousClass.name')
                    ->label('Previous class')
                    ->placeholder('-'),
                TextEntry::make('class.name')
                    ->label('Class')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-'),
                TextEntry::make('createdBy.name')
                    ->label('Created by')
                    ->placeholder('-'),
                TextEntry::make('updatedBy.name')
                    ->label('Updated by')
                    ->placeholder('-'),
                TextEntry::make('deletedBy.name')
                    ->label('Deleted by')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Enquiry $record): bool => $record->trashed()),
            ]);
    }
}
