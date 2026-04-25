<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Filament\Exports\StoreInvoiceTransactionExporter;
use App\Models\StoreInvoiceTransaction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ExportBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Group;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
                TextColumn::make('method')->label('Method')->sortable()->searchable()->wrap(),
                TextColumn::make('amount')->label('Amount')->sortable()->searchable()->wrap(),
                TextColumn::make('remarks')->label('Remarks')->sortable()->searchable()->wrap(),
                TextColumn::make('createdBy.name')->toggleable(isToggledHiddenByDefault: false)->label('Created By')->wrap(),
                TextColumn::make('updatedBy.name')->toggleable(isToggledHiddenByDefault: true)->label('Updated By')->wrap(),
                TextColumn::make('deletedBy.name')->toggleable(isToggledHiddenByDefault: true)->label('Deleted By')->wrap(),
                TextColumn::make('created_at')->toggleable(isToggledHiddenByDefault: false)->wrap(),
                TextColumn::make('updated_at')->toggleable(isToggledHiddenByDefault: true)->wrap(),
                TextColumn::make('deleted_at')->toggleable(isToggledHiddenByDefault: true)->wrap(),
            ])
            ->defaultSort('id', 'desc')
//            ->paginated([5, 10, 25, 50, 100, 500, 1000])
            ->recordActions([
                Action::make('edit-store-invoice-transaction')
                    ->label('Edit')
                    ->modelLabel('Edit StoreInvoiceTransaction')
                    ->authorize(auth()->user()->can('update StoreInvoiceTransaction'))
                    ->model(StoreInvoiceTransaction::class)
                    ->fillForm(fn(StoreInvoiceTransaction $record): array => $record->toArray())
                    ->action(function (array $data, StoreInvoiceTransaction $record): void {
                        $record->update([
                            'amount' => $data['amount'],
                            'remarks' => $data['remarks'],
                            'method' => $data['method'],
                            'updated_by' => auth()->id(),
                        ]);

                        Notification::make()
                            ->title("Payment of {$data['amount']} was successful!")
                            ->success()
                            ->send();
                    })
                    ->schema([
                        Group::make([
                            TextInput::make('amount')
                                ->label('Amount (₹)')
                                ->minValue(1)
                                ->maxValue(function (Model $record) {
                                    return ($record->storeInvoice->subtotal_amount - $record->storeInvoice->discount_amount - $record->storeInvoice->total_paid_amount + $record->amount);
                                })
                                ->numeric()
                                ->required(),
                            Select::make('method')->options([
                                'bank' => 'Bank',
                                'cash' => 'Cash',
                                'upi' => 'UPI',
                            ])->required(),
                            TextInput::make('remarks')
                                ->label('Remarks')
                                ->maxLength(100),
                        ])->columns(2)
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exporter(StoreInvoiceTransactionExporter::class)
                        ->label('Export')
                        ->columnMappingColumns(3),
                ])
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return StoreInvoiceTransaction::query()
            ->withWhereRelation('storeInvoice', 'store_id', $this->record->id);
    }
}
