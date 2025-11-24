<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\Cart;
use App\Models\StoreCart;
use App\Models\StoreProduct;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class StudentCart extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.stores.pages.student-cart';

    public function mount(int|string $record, int|string $student): void
    {
        $this->record = $this->resolveRecord($record);
        $this->student = User::query()
            ->with(['student.classAssignment'])
            ->findOrFail($student);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('seller')->url(StoreResource::getUrl('seller', ['record' => $this->record])),
            Action::make('student-products')
                ->label('Product\'s')->url(StoreResource::getUrl('students-products', ['record' => $this->record, $this->student])),
        ];
    }

    public function storeCart($student_id, $store_id)
    {
        return dd(StoreCart::query()->get())
//            ->where(['user_id' => $student_id], 'store_product_id', $store_id)
            ;
    }

    public function table(Table $table): Table
    {
        // We need to filter this query based on the current context (store_id, class_id, academic_year_id)
        return $table
            ->query($this->storeCart($this->student->id, $this->record->id))
            ->columns([
                TextColumn::make('store_product.id'),
                TextColumn::make('store_product.name'),
                TextColumn::make('quantity'),
            ]);
    }
}
