<?php

namespace App\Filament\Admin\Resources\Stores\Widgets;

use App\Models\StoreInvoiceTransaction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DailyCollectionTableWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    // Store date filter state
    protected ?string $filterDate = null;

    // This method sets the base query for the table *before* filters are applied.
    protected function getTableQuery(): Builder
    {
        return StoreInvoiceTransaction::query();
    }


    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $date = $this->filterDate ? Carbon::parse($this->filterDate) : Carbon::today();

                $query
                    ->select(
                        'created_by',
                        DB::raw('created_by as id'), // This satisfies the 'id' requirement
                        'created_by as user_id',     // This is used for the display column name
                        DB::raw('sum(amount) as total_amount')
                    )
                    ->whereDate('created_at', $date)
                    ->groupBy('created_by');
            })
            ->columns([
                // Change the column name reference from 'created_by' to the new alias 'user_id'
                TextColumn::make('user_id')
                    ->label('User ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('createdBy.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total Collection Amount')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('date')
                    ->form([
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
