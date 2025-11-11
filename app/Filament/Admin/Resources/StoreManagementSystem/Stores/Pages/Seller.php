<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Stores\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\Stores\StoreResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class Seller extends Page
{
    use InteractsWithRecord;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.store-management-system.stores.pages.seller';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('seller')->url(StoreResource::getUrl('seller', ['record' => $this->record])),
            Action::make('invoices')->url(StoreResource::getUrl('invoices', ['record' => $this->record])),
            Action::make('transactions')->url(StoreResource::getUrl('transactions', ['record' => $this->record])),
        ];
    }
}
