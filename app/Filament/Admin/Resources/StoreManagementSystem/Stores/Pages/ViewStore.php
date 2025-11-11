<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Stores\Pages;

use App\Filament\Admin\Resources\Students\StudentResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\StoreManagementSystem\Stores\StoreResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewStore extends ViewRecord
{
    protected static string $resource = StoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            ActionGroup::make([
                Action::make('seller')->url(StoreResource::getUrl('seller', ['record' => $this->record])),
                Action::make('invoices')->url(StoreResource::getUrl('invoices', ['record' => $this->record])),
                Action::make('transactions')->url(StoreResource::getUrl('transactions', ['record' => $this->record])),
            ])->label('More'),
        ];
    }
}
