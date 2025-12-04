<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\StoreInvoiceTransaction;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListTransactions extends Page implements HasTable, HasForms
{
    use InteractsWithRecord, InteractsWithTable, InteractsWithForms;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.stores.pages.list-transactions';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('store')->url(StoreResource::getUrl('view', ['record' => $this->record])),
            Action::make('list-products')->url(StoreResource::getUrl('list-products', ['record' => $this->record])),
            Action::make('list-invoices')->url(StoreResource::getUrl('list-invoices', ['record' => $this->record])),
            Action::make('list-students')->url(StoreResource::getUrl('list-students', ['record' => $this->record])),
        ];
    }

    protected function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery()) // Define the base query
            ->columns([
                TextColumn::make('id')->label('id')->sortable()->searchable()->wrap(),
                TextColumn::make('storeInvoice.id')->label('InvoiceId')->sortable()->searchable()->wrap(),
                TextColumn::make('storeInvoice.user.id')->label('User Id')->sortable()->searchable()->wrap(),
                TextColumn::make('storeInvoice.user.name')->label('Name')->sortable()->searchable()->wrap(),
                TextColumn::make('amount')->label('Amount')->sortable()->searchable()->wrap(),
                TextColumn::make('createdBy.name')->toggleable(isToggledHiddenByDefault: false)->label('Created By')->wrap(),
                TextColumn::make('updatedBy.name')->toggleable(isToggledHiddenByDefault: true)->label('Updated By')->wrap(),
                TextColumn::make('deletedBy.name')->toggleable(isToggledHiddenByDefault: true)->label('Deleted By')->wrap(),
                TextColumn::make('created_at')->toggleable(isToggledHiddenByDefault: false)->wrap(),
                TextColumn::make('updated_at')->toggleable(isToggledHiddenByDefault: true)->wrap(),
                TextColumn::make('deleted_at')->toggleable(isToggledHiddenByDefault: true)->wrap(),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return StoreInvoiceTransaction::query()
            ->withWhereRelation('storeInvoice', 'store_id', $this->record->id);
    }
}
