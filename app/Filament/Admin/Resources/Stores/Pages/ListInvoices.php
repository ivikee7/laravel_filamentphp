<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListInvoices extends Page implements HasTable, HasForms
{
    use InteractsWithRecord, InteractsWithTable, InteractsWithForms;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.stores.pages.list-invoices';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
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
                TextColumn::make('class.name')->label('Class')->sortable()->searchable(),
                TextColumn::make('subtotal_amount')->label('Subtotal'),
                TextColumn::make('discount_amount')->label('Discount'),
                TextColumn::make('total_amount')->label('Total'),
                TextColumn::make('total_paid_amount')->label('Paid'),
                TextColumn::make('total_due_amount')->label('Due'),
                TextColumn::make('created_at')->label('Created At')->wrap()->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('createdBy.name')->label('Created By')->wrap()->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
            ])
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
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
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
        return $this->record->storeInvoices()->getQuery();
    }

}
