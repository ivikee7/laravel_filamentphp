<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\InvoiceResource\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\EditAction::make(),
            Actions\Action::make('edit')
                ->label('Edit')
                ->url(fn () => static::getResource()::getUrl('edit', ['record' => $this->record])),

            Actions\ActionGroup::make([
                Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->url(fn () => route('invoice.print', ['invoice' => $this->record->id]))
                    ->openUrlInNewTab(true),
            ])
                ->label('More')
                ->icon('heroicon-o-cog-6-tooth'),
        ];
    }
}
