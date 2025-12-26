<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\StoreInvoice;
use App\Models\StoreInvoiceItem;
use App\Models\StoreInvoiceTransaction;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

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
                Section::make('Invoice')
                    ->headerActions([
                        Action::make('print-invoice')
                            ->url(function (Model $record): string {
                                return StoreResource::getUrl('print-invoice', [
                                    'record' => $this->record->id,
                                    'invoiceId' => $record->id
                                ]);
                            })
                            ->openUrlInNewTab(),
                    ])
                    ->schema([
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
                            TextEntry::make('class.name')->prefix('Class: ')->hiddenLabel(),
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
                        ]),
                        RepeatableEntry::make('storeInvoiceItems')
                            ->hiddenLabel()
                            ->table([
                                RepeatableEntry\TableColumn::make('Name'),
                                RepeatableEntry\TableColumn::make('Price'),
                                RepeatableEntry\TableColumn::make('Quantity'),
                                RepeatableEntry\TableColumn::make('Total'),
                            ])
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Product'),
                                TextEntry::make('price')
                                    ->label('Unit Price')
                                    ->money('INR'),
                                TextEntry::make('quantity')
                                    ->label('Qty'),
                                TextEntry::make('total')
                                    ->label('Total')
                                    ->state(fn($record) => $record->quantity * $record->price)
                                    ->money('INR'),
                            ])
                            ->columnSpanFull(),
                    ])->columns(['sm' => 2]),
                Section::make('Payment')
                    ->components([
                        TextEntry::make('subtotal_amount')->numeric()->money('INR')
                            ->numeric()->hiddenLabel()->prefix('Subtotal: '),
                        TextEntry::make('discount_amount')->numeric()->money('INR')
                            ->numeric()->hiddenLabel()->prefix('Discount: ')->color('warning'),
                        TextEntry::make('total_amount')->numeric()->money('INR')
                            ->numeric()->hiddenLabel()->prefix('Total: '),
                        TextEntry::make('total_paid_amount')->numeric()->money('INR')
                            ->numeric()->hiddenLabel()->prefix('Paid: ')->color('success'),
                        TextEntry::make('total_due_amount')->numeric()->money('INR')
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
                            ->placeholder('-')
                            ->visible(fn(StoreInvoice $record): bool => $record->trashed()),
                        TextEntry::make('created_at')
                            ->dateTime()->label('Created At')
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()->label('Updated At')
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->dateTime()->label('Deleted At')
                            ->visible(fn(StoreInvoice $record): bool => $record->trashed()),
                    ])
                    ->columns(['sm' => 2, 'md' => 4, 'lg' => 5])
                    ->headerActions([
                        Action::make('make-payment')
                            ->label('Payment')
                            ->modalHeading('Invoice payment')
                            ->authorize(auth()->user()->can('create StoreInvoiceTransaction'))
                            ->schema([
                                Group::make([
                                    TextInput::make('amount')
                                        ->label('Amount (₹)')
                                        ->minValue(1)
                                        ->maxValue(function (Model $record) {
                                            return ($record->subtotal_amount - $record->discount_amount - $record->total_paid_amount);
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
                            ])
                            ->action(function (array $data, Model $record): void {
                                $record->storeInvoiceTransactions()->create([
                                    'amount' => $data['amount'],
                                    'remarks' => $data['remarks'],
                                    'method' => $data['method'],
                                    'created_by' => auth()->id(),
                                ]);

                                Notification::make()
                                    ->title("Payment of {$data['amount']} was successful!")
                                    ->success()
                                    ->send();

                            }),
                        Action::make('make-discount')
                            ->label('Discount')
                            ->modalHeading('Discount')
                            ->authorize(auth()->user()->can('update StoreInvoice'))
                            ->schema([
                                Group::make([
                                    TextInput::make('discount_amount')
                                        ->label('Amount (₹)')
                                        ->default(function (Model $record) {
                                            return $record->discount_amount;
                                        })
                                        ->minValue(0)
                                        ->maxValue(function (Model $record) {
                                            return ($record->subtotal_amount - $record->total_paid_amount);
                                        })
                                        ->numeric()
                                        ->required(),
                                    TextInput::make('remarks')
                                        ->label('Remarks')
                                        ->default(function (Model $record) {
                                            return $record->remarks;
                                        })
                                        ->maxLength(100),
                                ])
                            ])
                            ->action(function (array $data, Model $record): void {
                                $record->update([
                                    'discount_amount' => $data['discount_amount'],
                                    'total_amount' => $record->subtotal_amount - $data['discount_amount'],
                                    'remarks' => $data['remarks'],
                                    'updated_by' => auth()->id(),
                                ]);
                                Notification::make()
                                    ->title("{$data['discount_amount']} discount applied successfully!")
                                    ->success()
                                    ->send();
                            }),
                    ]),
            ]);
    }


    protected function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('id')->label('#')->sortable()->searchable()->wrap(),
                TextColumn::make('storeInvoice.id')->label('InvoiceId')->sortable()->searchable()->wrap(),
                TextColumn::make('method')->sortable()->searchable()->wrap(),
                TextColumn::make('amount')->sortable()->searchable()->wrap(),
                TextColumn::make('remarks')->searchable()->wrap(),
                TextColumn::make('createdBy.name')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Created By')->wrap(),
                TextColumn::make('updatedBy.name')
                    ->toggleable(isToggledHiddenByDefault: true)->label('Updated By')->wrap(),
                TextColumn::make('deletedBy.name')
                    ->toggleable(isToggledHiddenByDefault: true)->label('Deleted By')->wrap(),
                TextColumn::make('created_at')->toggleable(isToggledHiddenByDefault: false)->wrap(),
                TextColumn::make('updated_at')->toggleable(isToggledHiddenByDefault: true)->wrap(),
                TextColumn::make('deleted_at')->toggleable(isToggledHiddenByDefault: true)->wrap(),
            ])
            ->defaultSort('id', 'desc')
            ->heading('Payment History');
    }

    protected function getTableQuery(): Builder
    {
        return $this->invoice->storeInvoiceTransactions()->getQuery();
    }
}
