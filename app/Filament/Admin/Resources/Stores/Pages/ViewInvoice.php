<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\StoreInvoice;
use App\Models\StoreInvoiceItem;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ViewInvoice extends Page implements HasTable, HasForms, HasInfolists
{
    use InteractsWithRecord, InteractsWithTable, InteractsWithForms, InteractsWithInfolists;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.stores.pages.view-invoice';

    public $invoiceId = null;
    public $invoice = null;
    public $items = null;

    public function mount(int|string $record, int|string $invoiceId): void
    {
        $this->record = $this->resolveRecord($record);
        $this->invoice = StoreInvoice::query()->find($invoiceId);
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('store')->url(StoreResource::getUrl('view', ['record' => $this->record])),
            Action::make('list-products')->url(StoreResource::getUrl('list-products', ['record' => $this->record])),
            Action::make('list-invoices')->url(StoreResource::getUrl('list-invoices', ['record' => $this->record])),
            Action::make('list-transactions')->url(StoreResource::getUrl('list-transactions', ['record' => $this->record])),
            Action::make('list-students')->url(StoreResource::getUrl('list-students', ['record' => $this->record])),
        ];
    }

    public function invoiceInfolist(Schema $infolist): Schema
    {
        return $infolist
            ->record($this->invoice)
            ->schema([
                Section::make('Invoice')->schema([
                    Group::make()->schema([
                        TextEntry::make('id')
                            ->prefix('Invoice #: ')
                            ->hiddenLabel(),
                        TextEntry::make('user.name')
                            ->label('name')->prefix('Name: ')->hiddenLabel(),
                        TextEntry::make('user.address')
                            ->label('Address')
                            ->getStateUsing(function ($record): string {
                                $user = $record->user;

                                if (!$user) {
                                    return 'N/A';
                                }

                                return $user->address . ', ' .
                                    $user->city . ', ' .
                                    $user->state . ', ' .
                                    $user->pin_code;
                            })
                            ->prefix('Address: ')
                            ->hiddenLabel(),
                        TextEntry::make('user.primary_contact_number')->prefix('Contact number: ')->hiddenLabel(),
                        TextEntry::make('user.email')->prefix('Email: ')->hiddenLabel(),
                    ]),
                    Group::make()->schema([
                        TextEntry::make('created_at')
                            ->prefix('Date: ')
                            ->hiddenLabel(),
                        TextEntry::make('store.name')
                            ->prefix('Name: ')
                            ->hiddenLabel(),
                        TextEntry::make('store.address')
                            ->label('Address')
                            ->getStateUsing(function ($record): string {
                                $store = $record->store;

                                if (!$store) {
                                    return 'N/A';
                                }

                                return $store->address . ', ' .
                                    $store->city . ', ' .
                                    $store->state . ', ' .
                                    $store->pin_code;
                            })
                            ->prefix('Address: ')
                            ->hiddenLabel(),
                        TextEntry::make('store.phone')->prefix('Contact number: ')->hiddenLabel(),
                        TextEntry::make('store.email')->prefix('Email: ')->hiddenLabel(),
                    ])
                ])->columns(2),
            ]);
    }


    protected function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery()) // Define the base query
            ->columns([
                TextColumn::make('id')->label('#')->sortable()->searchable()->wrap(),
                TextColumn::make('storeInvoice.id')->label('InvoiceId')->sortable()->searchable()->wrap(),
                TextColumn::make('storeProduct.id')->label('PID')->sortable()->searchable()->wrap(),
                TextColumn::make('name')->label('Name')->sortable()->searchable()->wrap(),
                TextColumn::make('description')->label('Description')->sortable()->searchable()->wrap(),
                TextColumn::make('price')->label('Price')->sortable()->searchable()->wrap(),
                TextColumn::make('createdBy.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Created By')->wrap(),
                TextColumn::make('updatedBy.name')
                    ->toggleable(isToggledHiddenByDefault: true)->label('Updated By')->wrap(),
                TextColumn::make('deletedBy.name')
                    ->toggleable(isToggledHiddenByDefault: true)->label('Deleted By')->wrap(),
                TextColumn::make('created_at')->toggleable(isToggledHiddenByDefault: true)->wrap(),
                TextColumn::make('updated_at')->toggleable(isToggledHiddenByDefault: true)->wrap(),
                TextColumn::make('deleted_at')->toggleable(isToggledHiddenByDefault: true)->wrap(),
            ])->heading('Items');
    }

    protected function getTableQuery(): Builder
    {
        return StoreInvoiceItem::query()
            ->withWhereRelation('storeInvoice', 'store_id', $this->record->id);
    }
}
