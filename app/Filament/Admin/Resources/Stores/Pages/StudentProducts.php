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
use Livewire\Component;


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

    #[On('refreshTable')]
    public function refreshTableData(): void
    {
        $this->fillTable();
    }

    public function getStoreProductQuery(): Builder
    {
        // The key is this query: it excludes items already in the cart.
        return StoreProduct::query()
            ->where('store_id', $this->record->id)
            ->where('class_id', $this->academicClass_id)
            ->where('academic_year_id', $this->academicYear_id)
            ->whereNotIn('id', $this->getCartQuery()->pluck('store_product_id'));
    }

    protected function getCartQuery(): Builder
    {
        return StoreCart::query()
            ->where('user_id', $this->targetStudent->id);
    }

    public function getCartItemsProperty(): Builder
    {
        return $this->getCartQuery()->with('storeProduct');
    }

    public function getTotalProperty(): float
    {
        return $this->cartItems->sum(function ($item) {
            return $item->quantity * ($item->storeProduct->price ?? 0);
        });
    }

    public function clearCart(): void
    {
        $this->getCartQuery()->delete();
        Notification::make()->title('Cart Cleared')->success()->send();
        $this->dispatch('cartUpdated');
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
                TextEntry::make('mother_name')->prefix('Mother Name: ')->hiddenLabel(),
                TextEntry::make('address')->prefix('Address: ')->hiddenLabel(),
                TextEntry::make('city')->prefix('City: ')->hiddenLabel(),
                TextEntry::make('state')->prefix('State: ')->hiddenLabel(),
                TextEntry::make('pin_code')->prefix('Pin Code: ')->hiddenLabel(),
            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getStoreProductQuery())
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('description')->searchable(),
                TextColumn::make('price')->money('INR'),
            ])
            ->recordActions([
                Action::make('addToCart')
                    ->action(function (Model $record, Component $livewire): void {
                        // ... (cart item existence checks and creation logic remain the same) ...

                        StoreCart::create([
                            'user_id' => $this->targetStudent->id,
                            'store_product_id' => $record->id,
                            'quantity' => 1,
                        ]);

                        Notification::make()->title('Added to Cart')->success()->send();

                        // --- Change this line ---
                        // $livewire->dispatch('refreshTable');

                        // Use the magic string event for a full component refresh
                        $livewire->dispatch('$refresh');
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
