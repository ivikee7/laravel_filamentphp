<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Schemas;

use App\Models\StoreInvoice;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\Livewire;

class StoreInvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('subtotal_amount')
                    ->numeric()->hiddenLabel()->prefix('Subtotal: '),
                TextEntry::make('discount_amount')
                    ->numeric()->hiddenLabel()->prefix('Discount: '),
                TextEntry::make('total_amount')
                    ->numeric()->hiddenLabel()->prefix('Total: '),
                TextEntry::make('totalPaidAmount')
                    ->numeric()->hiddenLabel()->prefix('Paid: ')->color('success'),
                TextEntry::make('totalDueAmount')
                    ->numeric()->hiddenLabel()->prefix('Due: ')->color('danger'),
                TextEntry::make('remarks'),
                TextEntry::make('createdBy.name')->label('Created By')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('updatedBy.name')
                    ->numeric()->label('Updated By')
                    ->placeholder('-'),
                TextEntry::make('deletedBy.name')
                    ->numeric()->label('Deleted By')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()->label('Created At')
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()->label('Updated At')
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()->label('Deleted At')
                    ->visible(fn(StoreInvoice $record): bool => $record->trashed()),
            ])->columns(3);
    }

}
