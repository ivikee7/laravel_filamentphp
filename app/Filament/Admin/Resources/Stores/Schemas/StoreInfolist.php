<?php

namespace App\Filament\Admin\Resources\Stores\Schemas;

use App\Models\Store;
use App\Models\StoreInvoiceTransaction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use function Laravel\Prompts\form;

class StoreInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextEntry::make('name')->hiddenLabel()->prefix('Name: '),
                    TextEntry::make('address')->hiddenLabel()->prefix('Address: ')
                        ->placeholder('-'),
                    TextEntry::make('city')->hiddenLabel()->prefix('City: ')
                        ->placeholder('-'),
                    TextEntry::make('state')->hiddenLabel()->prefix('State: ')
                        ->placeholder('-'),
                    TextEntry::make('pin_code')->hiddenLabel()->prefix('Pin Code: ')
                        ->placeholder('-'),
                    TextEntry::make('phone')->hiddenLabel()->prefix('Phone: ')
                        ->placeholder('-'),
                    TextEntry::make('email')->hiddenLabel()->prefix('Email Address: ')
                        ->label('Email address')
                        ->placeholder('-'),
                    TextEntry::make('is_active')->hiddenLabel()->prefix('Status: '),
                    TextEntry::make('createdBy.name')->hiddenLabel()->prefix('Created By: ')
                        ->placeholder('-'),
                    TextEntry::make('updatedBy.name')->hiddenLabel()->prefix('Updated By: ')
                        ->placeholder('-'),
                    TextEntry::make('deletedBy.name')->hiddenLabel()->prefix('Deleted By: ')
                        ->placeholder('-'),
                    TextEntry::make('created_at')->hiddenLabel()->prefix('Created At: ')
                        ->dateTime()
                        ->placeholder('-'),
                    TextEntry::make('updated_at')->hiddenLabel()->prefix('Updated At: ')
                        ->dateTime()
                        ->placeholder('-'),
                    TextEntry::make('deleted_at')->hiddenLabel()->prefix('Deleted At: ')
                        ->dateTime()
                        ->visible(fn(Store $record): bool => $record->trashed()),
                ])
                    ->columns(3)
                    ->columnSpanFull()
            ]);
    }
}
