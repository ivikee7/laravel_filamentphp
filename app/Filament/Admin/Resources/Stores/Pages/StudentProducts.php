<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\AcademicYear;
use App\Models\Product;
use App\Models\StoreCart;
use App\Models\StoreProduct;
use App\Models\StudentClass;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Filament\Tables\Table;


class StudentProducts extends Page implements HasInfolists, HasTable
{
    use InteractsWithRecord;
    use InteractsWithInfolists;
    use InteractsWithTable;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.stores.pages.student-products';

    public ?User $targetStudent = null;
    public $academicYear_id = null;
    public $academicClass_id = null;

    public function mount(int|string $record, int|string $student): void
    {
        $this->record = $this->resolveRecord($record);
        $this->targetStudent = User::query()
            ->with(['student.classAssignment'])
            ->findOrFail($student);

        $this->academicYear_id = $this->targetStudent->student->classAssignment->academic_year_id ?? null;
        $this->academicClass_id = $this->targetStudent->student->classAssignment->class_id ?? null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('seller')
                ->url(StoreResource::getUrl('seller', ['record' => $this->record])),
            Action::make('Cart')
                ->url(StoreResource::getUrl('students-cart', ['record' => $this->record, 'student' => $this->targetStudent]))
        ];
    }


    public function products(): Collection
    {
        if (empty($this->academicClass_id) || empty($this->academicYear_id)) {
            return collect([]);
        }

        return StoreProduct::query()
            ->where('store_id', $this->record->id)
            ->where('class_id', $this->academicClass_id)
            ->where('academic_year_id', $this->academicYear_id)
            ->get();
    }

    // Helper function to get the current cart query scope for this student
    protected function getCartQuery(): Builder
    {
        return StoreCart::query()->where('user_id', $this->targetStudent->id);
    }

    // --- Computed Properties for the View ---

    public function getCartItemsProperty(): Collection
    {
        // This runs every time Livewire updates the DOM (and listens for 'cartUpdated' event)
        return $this->getCartQuery()->with('storeProduct')->get();
    }

    public function getTotalProperty(): float
    {
        return $this->cartItems->sum(function ($item) {
            return $item->quantity * ($item->storeProduct->price ?? 0);
        });
    }

    // --- Cart Actions (Methods called by wire:click) ---

    public function addToCart(int $storeProductId): void
    {
        $cartItem = $this->getCartQuery()
            ->where('store_product_id', $storeProductId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            StoreCart::create([
                'user_id' => $this->targetStudent->id,
                'store_product_id' => $storeProductId,
                'quantity' => 1,
                // Add any other necessary fields
            ]);
        }

        Notification::make()->title('Added to Cart')->success()->send();
        // Dispatch event to force the Blade view's computed properties to refresh
        $this->dispatch('cartUpdated');
    }

    public function decreaseQuantity(int $storeProductId): void
    {
        $cartItem = $this->getCartQuery()
            ->where('store_product_id', $storeProductId)
            ->first();

        if ($cartItem && $cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
        } elseif ($cartItem && $cartItem->quantity <= 1) {
            $this->removeFromCart($storeProductId);
        }

        $this->dispatch('cartUpdated');
    }

    public function removeFromCart(int $storeProductId): void
    {
        $this->getCartQuery()
            ->where('store_product_id', $storeProductId)
            ->delete();

        Notification::make()->title('Removed from Cart')->success()->send();
        $this->dispatch('cartUpdated');
    }

    public function clearCart(): void
    {
        $this->getCartQuery()->delete();
        Notification::make()->title('Cart Cleared')->success()->send();
        $this->dispatch('cartUpdated');
    }

    // Optional: Add a checkout method that processes the cart items into an order
    public function checkout(): void
    {
        // Logic to move items from CartItem table to Orders/OrderItems tables
        Notification::make()->title('Checkout not implemented yet.')->warning()->send();
    }

    // Note: Use the Infolist class for type hinting, not Schema directly
    public function storeInfolist(Schema $infolist): Schema
    {
        return $infolist
            ->record($this->record) // Record is usually set on the main infolist or component
            ->schema([ // Use schema() instead of components() for layout definitions
                TextEntry::make('name')
                    ->prefix('Name: ')
                    ->hiddenLabel(),
                TextEntry::make('address')
                    ->prefix('Address: ')
                    ->hiddenLabel(),
            ]);
    }

    public function studentInfolist(Schema $infolist): Schema
    {
        return $infolist
            ->record($this->targetStudent) // Record is usually set on the main infolist or component
            ->schema([ // Use schema() instead of components()
                TextEntry::make('name')
                    ->prefix('Name: ')
                    ->hiddenLabel(),
                TextEntry::make('father_name')
                    ->prefix('Father Name: ')
                    ->hiddenLabel(),
            ]);
    }

    public function table(Table $table): Table
    {
        // We need to filter this query based on the current context (store_id, class_id, academic_year_id)
        return $table
            ->query(
                StoreProduct::query()
                    ->where('store_id', $this->record->id)
                    ->where('class_id', $this->academicClass_id)
                    ->where('academic_year_id', $this->academicYear_id)
            )
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('description')->searchable(),
                TextColumn::make('price')->money('INR'),
            ])
            ->recordActions([
                Action::make('addToCart')
                    ->action(function (Model $record): void { // Ensure type hint for $record if desired
                        Notification::make()
                            ->title("Added {$record->name} to cart") // Updated notification title for better context
                            ->success()
                            ->send();
                    }),
            ])
            ->filters([
                // ...
            ])
            ->headerActions([
                // ...
            ]);
    }
}
