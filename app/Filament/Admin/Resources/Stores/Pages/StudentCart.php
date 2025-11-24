<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\Cart;
use App\Models\StoreCart;
use App\Models\StoreProduct;
use App\Models\Student;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentCart extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.stores.pages.student-cart';

    public $student = null;

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

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getCartQuery())
            ->columns([
                TextColumn::make('storeProduct.id')->label('Product ID'),
                TextColumn::make('storeProduct.name')->label('Name'),
                TextColumn::make('storeProduct.description')->label('Description'),
                TextColumn::make('storeProduct.price'),
                TextColumn::make('quantity'),
                TextColumn::make('ProductTotal'),
            ])->recordActions([
//                Action::make("cart-increase")
//                    ->label('+')->button()
//                    ->action(fn() => StoreCart::query()),
//                Action::make("cart-decrease")
//                    ->label('-')
//                    ->button()
//                    ->action(fn() => StoreCart::query())
            ])->headerActions([
                Action::make("cart-remove")
                    ->label('Clear Cart')
                    ->action(fn() => $this->clearCart())
                    ->color('danger'),
            ]);
    }

    public function clearCart(): void
    {
        $this->getCartQuery()->delete();
        Notification::make()
            ->title('Cart Cleared')
            ->success()
            ->send();
        $this->dispatch('cartUpdated');
    }

    protected function getCartQuery(): Builder
    {
        return StoreCart::query()->withWhereRelation('storeProduct','store_id' , $this->record->id)
            ->where('user_id', $this->student->id);
    }
}
