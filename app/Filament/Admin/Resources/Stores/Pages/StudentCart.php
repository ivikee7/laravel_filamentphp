<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\Cart;
use App\Models\Invoice;
use App\Models\StoreCart;
use App\Models\StoreInvoice;
use App\Models\StoreInvoiceItem;
use App\Models\StoreProduct;
use App\Models\Student;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
            Action::make('store')->url(StoreResource::getUrl('view', ['record' => $this->record])),
            Action::make('list-students')->url(StoreResource::getUrl('list-students', ['record' => $this->record])),
            Action::make('list-student-product')
                ->url(StoreResource::getUrl('list-student-product', ['record' => $this->record, $this->student])),
        ];
    }

    public function generateInvoice()
    {
        // 1. Retrieve cart items first to calculate amounts
        $cartItems = $this->getCartQuery()->with('storeProduct')->get();

        // A. Use the same logic as the table's calculation to get the subtotal
        $subtotal = $this->getCartQuery()
            ->getQuery() // This gets the underlying Query Builder without Eloquent's default selects
            ->join('store_products', 'store_carts.store_product_id', '=', 'store_products.id')
            ->sum(DB::raw('store_products.price * store_carts.quantity'));

        // Make sure $subtotal is a number, not null if the cart is empty
        $subtotal = (float) ($subtotal ?? 0);

        // ... (rest of the generateInvoice method continues from step 2)
        // 2. Calculate discount and total (Example logic, adjust as needed)
        $discount = 0; // Implement your actual discount logic here
        $total = $subtotal - $discount;

        // 3. Create the main Invoice record using the calculated values
        $invoice = StoreInvoice::create([
            'user_id' => $this->student->id,
            'store_id' => $this->record->id,
            'class_id' => $this->student->student->classAssignment->class_id,
            'subtotal_amount' => $subtotal,
            'discount_amount' => $discount,
            'total_amount' => $total,
            'created_by' => auth()->id(),
        ]);

        // 4. Prepare invoice items data for bulk insert
        $invoiceItemsData = [];
        $invoiceId = $invoice->id;

        foreach ($cartItems as $item) {
            $invoiceItemsData[] = [
                'store_invoice_id' => $invoiceId,
                'store_product_id' => $item->storeProduct->id,
                'name' => $item->storeProduct->name,
                'quantity' => $item->quantity,
                'price' => $item->storeProduct->price,
                'total' => $item->storeProduct->price * $item->quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 5. Perform bulk insertion of all invoice items in a single query
        StoreInvoiceItem::insert($invoiceItemsData);

        Notification::make()->title('Invoice #' . $invoiceId . ' Generated!')->body('Invoice Generated Successfully!');
        return $invoice;
    }

    protected function getCartQuery(): Builder
    {
        // 1. Get the base query for StoreCart items
        return StoreCart::query()
            // 2. Eager load the storeProduct relationship for display and invoice generation
            ->with(['storeProduct'])
            // 3. Apply necessary filters (store and user)
            ->withWhereRelation('storeProduct', 'store_id', $this->record->id)
            ->where('user_id', $this->student->id)

            // 4. *** IMPLEMENT THE PERFORMANT CALCULATION HERE ***
            // We use addSelect and join to calculate (price * quantity) for each cart item
            ->addSelect([
                // product_total is calculated by joining the price from the store_products table
                // and multiplying it by the quantity in the store_carts table.
                'product_total' => StoreProduct::query()
                    ->select(DB::raw('store_products.price * store_carts.quantity'))
                    ->whereColumn('store_carts.store_product_id', 'store_products.id')
                    ->limit(1)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getCartQuery())
            ->columns([
                TextColumn::make('storeProduct.id')
                    ->wrap()
                    ->label('Product ID'),
                TextColumn::make('storeProduct.name')
                    ->wrap()
                    ->label('Name'),
                TextColumn::make('storeProduct.price')
                    ->wrap()
                    ->money('INR'),
                TextColumn::make('quantity')
                    ->wrap(),
                TextColumn::make('product_total')
                    ->wrap()
                    ->money('INR')
                    ->summarize(Sum::make()->money('INR')->label(''))
                ,
            ])->recordActions([
                Action::make('cart-increase')
                    ->label('+')
                    ->button()
                    ->action(function (Model $record) {
                        $record->increment('quantity');
                    }),
                Action::make("cart-decrease")
                    ->label('-')
                    ->button()
                    ->action(function (Model $record) {
                        if ($record->quantity >= 2) {
                            $record->decrement('quantity');
                            return;
                        }
                        $record->delete();
                    }),
            ])->headerActions([
                Action::make("clear-cart")
                    ->label('Clear Cart')
                    ->action(function () {
                        if (count($this->getCartQuery()->get()) <= 0) {
                            Notification::make()->title('Empty Cart')->body('Cart does not have any item!')->warning()->send();
                            return;
                        }
                        $this->clearCart();
                        Notification::make()->title('Clear Cart')->body('Cart Cleared Successfully!')->success()->send();
                    })
                    ->color('danger'),
                Action::make('generateInvoice')
                    ->label('Generate Invoice')
                    ->color('success')
                    ->visible(function (): bool {
                        return $this->getCartQuery()->exists();
                    })
                    ->action(function () {
                        $invoice = $this->generateInvoice();
                        Notification::make()
                            ->title('Invoice generated successfully.')
                            ->success()
                            ->send();
                        $this->clearCart();
                        return redirect()->to(StoreResource::getUrl('view-invoice', ['record' => $this->record->id, 'invoiceId' => $invoice->id]));
                    }),
            ]);
    }

    public function clearCart(): void
    {
        $this->getCartQuery()->delete();
    }


}
