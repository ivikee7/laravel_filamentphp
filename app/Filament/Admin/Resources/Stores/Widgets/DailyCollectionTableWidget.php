<?php

namespace App\Filament\Admin\Resources\Stores\Widgets;

use App\Models\StoreInvoiceTransaction;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DailyCollectionTableWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected ?string $filterDate = null;

    protected function getTableQuery(): Builder
    {
        return StoreInvoiceTransaction::query();
    }

    public function table(Table $table): Table
    {
        // 1. Get all unique payment methods to build columns dynamically
        // Note: We use distinct() to ensure we know what columns to create.
        $paymentMethods = StoreInvoiceTransaction::query()
            ->distinct()
            ->pluck('method')
            ->filter() // Remove nulls if any
            ->values()
            ->toArray();

        return $table
            ->modifyQueryUsing(function (Builder $query) use ($paymentMethods) {
                $date = $this->filterDate ? Carbon::parse($this->filterDate) : Carbon::today();

                // 2. Build the Select Statement
                $selects = [
                    'created_by',
                    DB::raw('created_by as id'),
                    'created_by as user_id',
                    DB::raw('sum(amount) as total_amount')
                ];

                // Dynamically add a SUM(CASE...) for each method found
                foreach ($paymentMethods as $method) {
                    // Sanitize method name for alias safely
                    $safeMethod = preg_replace('/[^a-zA-Z0-9_]/', '', $method);
                    $selects[] = DB::raw("sum(case when method = '{$method}' then amount else 0 end) as amount_{$safeMethod}");
                }

                $query
                    ->select($selects)
                    ->whereDate('created_at', $date)
                    ->groupBy('created_by');
            })
            ->columns(array_merge(
                [
                    TextColumn::make('user_id')
                        ->label('User ID')
                        ->sortable(),
                    TextColumn::make('createdBy.name')
                        ->label('Name')
                        ->searchable()
                        ->sortable(),
                ],
                // 3. Dynamically generate columns for each method
                array_map(function ($method) {
                    $safeMethod = preg_replace('/[^a-zA-Z0-9_]/', '', $method);
                    return TextColumn::make("amount_{$safeMethod}")
                        ->label(Str::headline($method)) // Converts 'credit_card' to 'Credit Card'
                        ->money('INR') // Optional: Change currency as needed
                        ->sortable();
                }, $paymentMethods),
                [
                    TextColumn::make('total_amount')
                        ->label('Total Collection')
                        ->money('INR') // Optional: Change currency as needed
                        ->weight('bold')
                        ->sortable(),
                ]
            ))
            ->filters([
                Filter::make('date')
                    ->schema([
                        DatePicker::make('filter_date')
                            ->default(now())
                            ->reactive(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $this->filterDate = $data['filter_date'] ?? null;
                        return $query;
                    }),
            ]);
    }
}
