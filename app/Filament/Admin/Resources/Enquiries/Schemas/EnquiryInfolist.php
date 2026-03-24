<?php

namespace App\Filament\Admin\Resources\Enquiries\Schemas;

use App\Models\Enquiry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EnquiryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Student info')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('gender.name')
                            ->placeholder('-'),
                        TextEntry::make('date_of_birth')
                            ->date()
                            ->placeholder('-'),
                    ])->columns(3),
                Section::make('Previous School info')
                    ->schema([
                        TextEntry::make('previous_school')
                            ->placeholder('-'),
                        TextEntry::make('previousClass.name')
                            ->label('Previous class')
                            ->placeholder('-'),
                    ])->columns(2),
                Section::make('Admission info')
                    ->schema([
                        TextEntry::make('class.name')
                            ->label('Class')
                            ->placeholder('-'),
                        TextEntry::make('notes')
                            ->placeholder('-'),
                    ])->columns(2),
                Section::make('Parents info')
                    ->schema([
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
                    ])->columns(3),
                Section::make('Address')
                    ->schema([
                        TextEntry::make('address')
                            ->placeholder('-')->columnSpan(2),
                        TextEntry::make('city')
                            ->placeholder('-'),
                        TextEntry::make('state')
                            ->placeholder('-'),
                        TextEntry::make('pin_code')
                            ->numeric()
                            ->placeholder('-'),
                    ])->columns(5),
                Section::make('Other info')->schema([
                    TextEntry::make('source')
                        ->placeholder('-'),
                ]),
                Section::make('Auth')->schema([
                    TextEntry::make('createdBy.name')
                        ->label('Created by')
                        ->placeholder('-'),
                    TextEntry::make('updatedBy.name')
                        ->label('Updated by')
                        ->placeholder('-'),
                    TextEntry::make('deletedBy.name')
                        ->label('Deleted by')
                        ->placeholder('-')
                        ->visible(fn(Enquiry $record): bool => $record->trashed()),
                    TextEntry::make('created_at')
                        ->dateTime()
                        ->placeholder('-'),
                    TextEntry::make('updated_at')
                        ->dateTime()
                        ->placeholder('-'),
                    TextEntry::make('deleted_at')
                        ->dateTime()
                        ->visible(fn(Enquiry $record): bool => $record->trashed()),
                ])->columns(4),

            ])->columns(1);
    }
}
