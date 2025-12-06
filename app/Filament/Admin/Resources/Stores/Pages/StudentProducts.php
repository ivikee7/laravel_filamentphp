<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\StoreCart;
use App\Models\StoreInvoiceItem;
use App\Models\StoreProduct;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

// Use an alias for clarity
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Table;

class StudentProducts extends Page implements HasInfolists, HasTable, HasActions
{
    use InteractsWithRecord;
    use InteractsWithInfolists;
    use InteractsWithTable;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.stores.pages.student-products';

    public $student = null;
    public $academicYear_id = null;
    public $academicClass_id = null;

    public function mount(int|string $record, int|string $student): void
    {
        $this->record = $this->resolveRecord($record);
        // Ensure student loading is robust
        $this->student = User::query()
            ->with(['student.classAssignment'])
            ->findOrFail($student);

        // Safely set academic context
        $this->academicYear_id = $this->student->student->classAssignment->academic_year_id ?? null;
        $this->academicClass_id = $this->student->student->classAssignment->class_id ?? null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('list-students')->url(StoreResource::getUrl('list-students', ['record' => $this->record])),
            Action::make('Cart')
                ->url(StoreResource::getUrl('students-cart', ['record' => $this->record, 'student' => $this->student])),
        ];
    }


    protected function getTableQuery(): EloquentBuilder
    {

        $cartProductIds = StoreCart::where('user_id', $this->student->id)->pluck('store_product_id');

        $purchasedProductIds = StoreInvoiceItem::query()
            ->whereHas('storeInvoice', function (EloquentBuilder $query) {
                $query->where('store_id', $this->record->id)
                    ->where('user_id', $this->student->id);
            })
            ->pluck('store_product_id');

        $excludedProductIds = $cartProductIds->merge($purchasedProductIds)->unique();

        return StoreProduct::query()
            ->where('store_id', $this->record->id)
            ->where('class_id', $this->academicClass_id)
            ->where('academic_year_id', $this->academicYear_id)
            ->whereNotIn('id', $excludedProductIds);
    }

    // Renamed to getCartItems to follow standard convention
    public function getCartItemsProperty(): EloquentBuilder
    {
        return StoreCart::where('user_id', $this->student->id)
            ->with('storeProduct');
    }

    public function getTotalProperty(): float
    {
        return $this->cartItems->get()->sum(function ($item) { // Added ->get() to resolve the builder
            return $item->quantity * ($item->storeProduct->price ?? 0);
        });
    }

    public function clearCart(): void
    {
        StoreCart::where('user_id', $this->student->id)->delete();
        Notification::make()->title('Cart Cleared')->success()->send();
        // Dispatch the table refresh event after clearing cart
        $this->dispatch('refreshTable');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('description')->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('price')->money('INR')->sortable(),
            ])
            ->recordActions([
                Action::make('addToCart')
                    ->label('Add to Cart')
                    ->action(function ($record, Model $item): void {

                        $user_id = $this->student->id;
                        $product_id = $item->id;

                        // Check existence is mostly redundant due to getTableQuery(), but safe for concurrency.
                        if (StoreCart::where('store_product_id', $product_id)->where('user_id', $user_id)->exists()) {
                            Notification::make()->title('Product Already Exists in Cart')->warning()->send();
                            return;
                        }

                        $this->record->carts()->create([
                            'user_id' => $user_id,
                            'store_product_id' => $product_id,
                            'quantity' => 1,
                        ]);

                        Notification::make()->title('Added to Cart')->success()->send();

                    }),
            ]);
    }

    // Infolist methods remain fine, just minor cleanup
    public function storeInfolist(Schema $infolist): Schema
    {
        return $infolist
            ->record($this->record)
            ->schema([
                TextEntry::make('name')->prefix('Name: ')->hiddenLabel(),
                TextEntry::make('address')->prefix('Address: ')->hiddenLabel(),
            ]);
    }

    public function studentInfolist(Schema $infolist): Schema
    {
        return $infolist
            ->record($this->student)
            ->schema([
                TextEntry::make('name')->prefix('Name: ')->hiddenLabel(),
                TextEntry::make('father_name')->prefix('Father Name: ')->hiddenLabel(),
                TextEntry::make('mother_name')->prefix('Mother Name: ')->hiddenLabel(),
                TextEntry::make('address')->prefix('Address: ')->hiddenLabel(),
                TextEntry::make('city')->prefix('City: ')->hiddenLabel(),
                TextEntry::make('state')->prefix('State: ')->hiddenLabel(),
                TextEntry::make('pin_code')->prefix('Pin Code: ')->hiddenLabel(),
                TextEntry::make('student.classAssignment.class.name')->prefix('Class: ')->hiddenLabel(),
                TextEntry::make('student.classAssignment.section.name')->prefix('Section: ')->hiddenLabel(),
            ])->columns(3);
    }
}
