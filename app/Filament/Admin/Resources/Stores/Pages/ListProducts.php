<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ListProducts extends Page implements HasTable, HasForms
{
    use InteractsWithRecord, InteractsWithTable, InteractsWithForms;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.stores.pages.list-products';


    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('store')->url(StoreResource::getUrl('view', ['record' => $this->record])),
            Action::make('list-products')->url(StoreResource::getUrl('list-products', ['record' => $this->record])),
            Action::make('list-transactions')->url(StoreResource::getUrl('list-transactions', ['record' => $this->record])),
            Action::make('list-students')->url(StoreResource::getUrl('list-students', ['record' => $this->record])),
        ];
    }

    public function table(Table $table): Table
    {
        return $table->query($this->getTableQuery())->columns([
            TextColumn::make('academicYear.name')->label('Academic Year')->searchable()->sortable()->wrap(),
            TextColumn::make('name')->searchable()->sortable()->wrap(),
            TextColumn::make('description')->searchable()->sortable()->wrap(),
            TextColumn::make('price')->searchable()->sortable()->wrap(),
            TextColumn::make('createdBy.name')->label('Created by')
                ->searchable()->sortable()
                ->wrap()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updatedBy.name')->label('Updated by')
                ->searchable()->sortable()
                ->wrap()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('deletedBy.name')->label('Deleted by')
                ->searchable()->sortable()
                ->wrap()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('created_at')->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('deleted_at')->toggleable(isToggledHiddenByDefault: true),
        ]);
    }

    protected function getTableQuery(): Builder
    {
        return $this->record->storeProducts()->getQuery();
    }
}
