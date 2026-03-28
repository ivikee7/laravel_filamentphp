<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Concerns\HasTabs;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use function Laravel\Prompts\warning;

class ListInvoices extends Page implements HasTable, HasForms
{
    use InteractsWithRecord, InteractsWithTable, InteractsWithForms, HasTabs;

    // Sync the trait's property to the browser URL
    protected $queryString = [
        'activeTab' => ['except' => 'all', 'as' => 'tab'],
    ];

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.stores.pages.list-invoices';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        // Initialize if URL is empty
        $this->activeTab ??= 'all';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('store')->url(StoreResource::getUrl('view', ['record' => $this->record])),
            Action::make('list-products')->url(StoreResource::getUrl('list-products', ['record' => $this->record])),
            Action::make('list-transactions')->url(StoreResource::getUrl('list-transactions', ['record' => $this->record])),
            Action::make('list-students')->url(StoreResource::getUrl('list-students', ['record' => $this->record])),
        ];
    }

    protected function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery()) // Define the base query
            ->columns([
                TextColumn::make('id')->label('#')->sortable()->searchable(),
                TextColumn::make('user.id')->label('User Id')->sortable()->searchable(),
                TextColumn::make('user.name')->label('Name')->sortable()->searchable(),
                TextColumn::make('user.father_name')->label('Father Name')->sortable()->searchable(),
                TextColumn::make('user.mother_name')->label('Mother Name')->sortable()->searchable(),
                TextColumn::make('class.name')->label('Class')->sortable()->searchable(),
                TextColumn::make('subtotal_amount')->label('Subtotal'),
                TextColumn::make('discount_amount')->label('Discount'),
                TextColumn::make('total_amount')->label('Total'),
                TextColumn::make('total_paid_amount')->label('Paid'),
                TextColumn::make('total_due_amount')->label('Due'),
                TextColumn::make('remarks')->label('Remarks')->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->label('Created At')->wrap()->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('createdBy.name')->label('Created By')->wrap()->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->columnManagerColumns(4)
            ->defaultSort('id', 'desc')
            ->filters([
                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('from')->label('From Date'),
                        DatePicker::make('to')->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['to'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators[] = 'From ' . \Carbon\Carbon::parse($data['from'])->toFormattedDateString();
                        }
                        if ($data['to'] ?? null) {
                            $indicators[] = 'Until ' . \Carbon\Carbon::parse($data['to'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
            ])
            ->recordActions([
                Action::make('view-invoice')
                    ->label('View')
                    ->url(fn($record): string => StoreResource::getUrl('view-invoice', [
                        'record' => $this->record->id,
                        'invoiceId' => $record->id,
                    ])),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        $query = $this->record->storeInvoices()->getQuery();

        if ($this->activeTab && $this->activeTab !== 'all') {
            $tabs = $this->getTabs();

            if (isset($tabs[$this->activeTab])) {
                // Using the exact method from your Tab.php source
                $tabs[$this->activeTab]->modifyQuery($query);
            }
        }

        return $query;
    }


    public function updatedActiveTab(): void
    {
        $this->resetTable();
    }

    public function getTabs(): array
    {
        $paidSumSql = '(SELECT COALESCE(SUM(amount), 0) FROM store_invoice_transactions WHERE store_invoice_id = store_invoices.id AND deleted_at IS NULL)';
        $dueCalcSql = "subtotal_amount - {$paidSumSql} - discount_amount";

        return [
            'all' => Tab::make('All'),
            'due' => Tab::make('Has Due')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereRaw("{$dueCalcSql} > 0"))
                ->badge($this->record->storeInvoices()->whereRaw("{$dueCalcSql} > 0")->count())
                ->badgeColor('danger'),
            'paid' => Tab::make('Fully Paid')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereRaw("{$dueCalcSql} <= 0"))
                ->badge($this->record->storeInvoices()->whereRaw("{$dueCalcSql} <= 0")->count())
                ->badgeColor('success'),
            'discounted' => Tab::make('Discounted')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('discount_amount', '>', 0))
                ->badge($this->record->storeInvoices()->where('discount_amount', '>', 0)->count())
                ->badgeColor('warning'),
        ];
    }

}
