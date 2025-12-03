<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\AcademicYear;
use App\Models\Product;
use App\Models\StoreCart;
use App\Models\StoreInvoiceItem;
use App\Models\StoreProduct;
use App\Models\StudentClass;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
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


class StudentProducts extends Page implements HasInfolists, HasTable, HasActions
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
        return StoreProduct::query()
            ->where('store_id', $this->record->id)
            ->where('class_id', $this->academicClass_id)
            ->where('academic_year_id', $this->academicYear_id)
            ->whereNotIn('id', $this->getCartQuery()->pluck('store_product_id'))
            ->whereNotIn('id', $this->getStoreInvoiceItemsQuery()->pluck('store_product_id'));
    }

    // check already purchased items
    protected function getStoreInvoiceItemsQuery(): Builder
    {
        return StoreInvoiceItem::query()
            ->withWhereRelation('storeInvoice', 'store_id', $this->record->id);
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
                TextEntry::make('student.classAssignment.class.name')->prefix('Class: ')->hiddenLabel(),
                TextEntry::make('student.classAssignment.section.name')->prefix('Section: ')->hiddenLabel(),
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
                        $user_id = $this->targetStudent->id;
                        $product_id = $record->id;

                        if (StoreCart::query()->where('store_product_id', $product_id)->where('user_id', $user_id)->exists()) {
                            Notification::make()->title('Product Already Exists in Cart')->warning()->send();
                            return;
                        }

                        StoreCart::create([
                            'user_id' => $user_id,
                            'store_product_id' => $product_id,
                            'quantity' => 1,
                        ]);

                        Notification::make()->title('Added to Cart')->success()->send();

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
