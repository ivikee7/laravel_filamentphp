<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\Store;
use App\Models\StoreCart;
use App\Models\StoreInvoiceItem;
use App\Models\StoreProduct;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class ListStudentProduct extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.stores.pages.list-student-product';

    public $student = null;
    public $academicYearId = null;
    public $classId = null;

    public function mount(int|string $record, int|string $student): void
    {
        $this->record = $this->resolveRecord($record);
        $this->student = User::query()
            ->with(['student.classAssignment'])
            ->findOrFail($student);

        $this->academicYearId = $this->student->student->classAssignment->academic_year_id ?? null;
        $this->classId = $this->student->student->classAssignment->class_id ?? null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('store')->url(StoreResource::getUrl('view', ['record' => $this->record])),
            Action::make('list-students')->url(StoreResource::getUrl('list-students', ['record' => $this->record])),
            Action::make('Cart')
                ->url(StoreResource::getUrl('students-cart', ['record' => $this->record, 'student' => $this->student])),
        ];
    }

    protected function getListeners(): array
    {
        return [
            // When 'refreshTable' event is heard, call the $refresh method (Livewire standard)
            'refreshTable' => '$refresh',
        ];
    }

    public function clearCart(): void
    {
        StoreCart::where('store_product_id', $this->record->id)
            ->where('user_id', $this->student->id)
            ->delete();
    }

    public function getCartItemsQuery(): EloquentBuilder
    {
        return StoreCart::where('store_id', $this->record->id)
            ->where('user_id', $this->student->id);
    }

    public function getInvoiceItemsQuery(): EloquentBuilder
    {
        return StoreInvoiceItem::with('storeInvoice')
            ->withWhereRelation('storeInvoice', 'store_id', $this->record->id)
            ->withWhereRelation('storeInvoice', 'user_id', $this->student->id);
    }

    public function getProductTableQuery(): EloquentBuilder
    {
        return StoreProduct::query()
            ->where(function ($query) {
                $query->where('is_multiple', true)
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('store_id', $this->record->id)
                            ->where('class_id', $this->classId)
                            ->where('academic_year_id', $this->academicYearId)
                            ->whereNotIn('id', $this->getCartItemsQuery()->pluck('store_product_id'))
                            ->whereNotIn('id', $this->getInvoiceItemsQuery()->pluck('store_product_id'));
                    });
            });
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getProductTableQuery())
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('description')->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('price')->money('INR')->sortable(),
            ])
            ->recordActions([
                Action::make('AddToCart')
                    ->action(function (Model $record) {
                        $product = $record;
                        if ($this->getCartItemsQuery()->where('store_product_id', $product->id)->exists()) {
                            Notification::make()
                                ->title('Already Exists!')
                                ->body('Product already exists in Cart')
                                ->warning()
                                ->send();
                            return;
                        }
                        $this->record->carts()->create([
                            'user_id' => $this->student->id,
                            'store_product_id' => $product->id,
                            'quantity' => 1,
                        ]);
                        Notification::make()->title('Added to Cart')->success()->send();

                        $this->dispatch('refreshTable');
//                        $this->redirect(request()->header('referer'), navigate: true);

                    }),
            ]);
    }


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
                Section::make('Student Info')->schema([
                    TextEntry::make('name')->prefix('Name: ')->hiddenLabel(),
                    TextEntry::make('father_name')->prefix('Father Name: ')->hiddenLabel(),
                    TextEntry::make('mother_name')->prefix('Mother Name: ')->hiddenLabel(),
                    TextEntry::make('address')->prefix('Address: ')->hiddenLabel(),
                    TextEntry::make('city')->prefix('City: ')->hiddenLabel(),
                    TextEntry::make('state')->prefix('State: ')->hiddenLabel(),
                    TextEntry::make('pin_code')->prefix('Pin Code: ')->hiddenLabel(),
                    TextEntry::make('student.classAssignment.class.name')->prefix('Class: ')->hiddenLabel(),
                    TextEntry::make('student.classAssignment.section.name')->prefix('Section: ')->hiddenLabel(),
                ])->columns(3)
            ]);
    }
}
