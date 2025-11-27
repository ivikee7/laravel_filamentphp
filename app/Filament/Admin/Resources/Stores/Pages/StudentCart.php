<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\StoreInvoiceResource;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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

    public function generateInvoice()
    {
        // 1. Retrieve cart items first to calculate amounts
        $cartItems = $this->getCartQuery()->with('storeProduct')->get();

        $subtotal = 0;
        // Ensure all items have necessary data structure for calculation
        foreach ($cartItems as $item) {
            // Assuming $item->price and $item->quantity are available
            $subtotal += ($item->storeProduct->price * $item->quantity);
        }

        // 2. Calculate discount and total (Example logic, adjust as needed)
        $discount = 0; // Implement your actual discount logic here
        $total = $subtotal - $discount;

        // 3. Create the main Invoice record using the calculated values
        $invoice = StoreInvoice::create([
            'user_id' => $this->student->id,
            'store_id' => $this->record->id,
            'subtotal_amount' => $subtotal,
            'discount_amount' => $discount,
            'total_amount' => $total,
        ]);

        // 4. Prepare invoice items data for bulk insert
        $invoiceItemsData = [];
        $invoiceId = $invoice->id;

        foreach ($cartItems as $item) {
            $invoiceItemsData[] = [
                'store_invoice_id' => $invoiceId,
                'store_product_id' => $item->storeProduct->id,
                'name' => $item->storeProduct->name,
                'description' => $item->storeProduct->description,
                'quantity' => $item->quantity,
                'price' => $item->storeProduct->price,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 5. Perform bulk insertion of all invoice items in a single query
        StoreInvoiceItem::insert($invoiceItemsData);

        Notification::make()->title('Invoice #' . $invoiceId . ' Generated!')->body('Invoice Generated Successfully!');
        return $invoice;
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
                        return redirect()->to(StoreInvoiceResource::getUrl('view', ['store' => $this->record->id, 'record' => $invoice->id]));
                    }),
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
        return StoreCart::query()->withWhereRelation('storeProduct', 'store_id', $this->record->id)
            ->where('user_id', $this->student->id);
    }
}
