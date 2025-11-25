<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStore extends ViewRecord
{
    protected static string $resource = StoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('seller')->url(StoreResource::getUrl('seller', ['record' => $this->record]))->color('success'),
//            Action::make('invoices')->url(StoreResource::getUrl('invoices', ['record' => $this->record])),
//            Action::make('transactions')->url(StoreResource::getUrl('transactions', ['record' => $this->record])),
        ];
    }
}
