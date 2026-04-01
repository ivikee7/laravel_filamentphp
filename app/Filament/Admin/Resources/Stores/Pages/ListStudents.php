<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ListStudents extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.stores.pages.list-students';

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
            Action::make('list-transactions')->url(StoreResource::getUrl('list-transactions', ['record' => $this->record])),
        ];
    }

    public function table(Table $table): Table
    {
        return $table->query(User::Role('Student'))
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('name')->searchable()->label('Name')->wrap(),
                TextColumn::make('student.classAssignment.class.name')->searchable()->label('Class')->wrap(),
                TextColumn::make('student.classAssignment.section.name')->searchable()->label('Section')->wrap(),
                TextColumn::make('father_name')->searchable()->label('Father Name')->wrap(),
                TextColumn::make('mother_name')->searchable()->label('Mother Name')->wrap(),
                TextColumn::make('student.quota.name')->searchable()->label('Quota')->wrap()->badge(),
            ])
            ->recordUrl((fn($record): string => StoreResource::getUrl('list-student-product', [$this->record->id, $record->id])))
            ->defaultSort('id', 'desc')
            ->recordActions([
                Action::make('list-student-product')
                    ->url((fn($record): string => StoreResource::getUrl('list-student-product', [$this->record->id, $record->id])))
                    ->label('')
                    ->icon('heroicon-s-arrow-uturn-right'),
            ]);
    }
}
