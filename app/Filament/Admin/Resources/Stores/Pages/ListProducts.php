<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\StoreProduct;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;

class ListProducts extends Page implements HasTable, HasForms
{
    use InteractsWithRecord, InteractsWithTable, InteractsWithForms;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.stores.pages.list-products';


    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('store')->url(StoreResource::getUrl('view', ['record' => $this->record])),
            Action::make('list-invoices')->url(StoreResource::getUrl('list-invoices', ['record' => $this->record])),
            Action::make('list-transactions')->url(StoreResource::getUrl('list-transactions', ['record' => $this->record])),
            Action::make('list-students')->url(StoreResource::getUrl('list-students', ['record' => $this->record])),
            Action::make('create')
                ->modelLabel('Create Product')
                ->authorize(auth()->user()->can('create StoreProduct'))
                ->model(StoreProduct::class)
                ->action(function (array $data, Model $record): void {
                    $data['store_id'] = $record->id;
                    $product = $record->storeProducts()->create($data);

                    Notification::make()
                        ->title("Product {$product->name} at {$product->price} successfully created!")
                        ->success()
                        ->send();
                })
                ->schema([
                    Select::make('academic_year_id')
                        ->label('Academic Year')
                        ->relationship('storeProducts.academicYear', 'name')
                        ->required()
                        ->reactive()
                        // CORRECTED: Clearing 'class_id' instead of 'student_class_id'
                        ->afterStateUpdated(function (Set $set) {
                            $set('class_id', null);
                        }),
                    Select::make('class_id')
                        ->label('Class')
                        ->relationship('storeProducts.studentClass', 'name', modifyQueryUsing: function (Builder $query, Get $get): Builder {
                            // Ensure the query is scoped by the selected academic year ID
                            return $query->when($get('academic_year_id'), fn(Builder $q) => $q->where('academic_year_id', $get('academic_year_id')));
                        })
                        ->reactive() // Make this field reactive to trigger dependent updates (though none are strictly needed here)
                        ->required()
                        // Hide until an academic year is selected
                        ->visible(fn(Get $get) => filled($get('academic_year_id'))),
                    TextInput::make('name')
                        ->required()
                        ->maxLength(100),
                    TextInput::make('price')
                        ->required()
                        ->numeric()
                        ->prefix('₹'),
                ]),
        ];
    }

    public function table(Table $table): Table
    {
        return $table->query($this->getTableQuery())->columns([
            TextColumn::make('id'),
            TextColumn::make('academicYear.name')->label('Academic Year')->searchable()->sortable()->wrap(),
            TextColumn::make('class.name')->label('Class')->searchable()->sortable()->wrap(),
            TextColumn::make('name')->searchable()->sortable()->wrap(),
            TextColumn::make('price')->searchable()->sortable()->wrap(),
            TextColumn::make('createdBy.name')->label('Created by')
                ->searchable()->sortable()
                ->wrap()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updatedBy.name')->label('Updated by')
                ->searchable()->sortable()
                ->wrap()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('deletedBy.name')->label('Deleted by')
                ->searchable()->sortable()
                ->wrap()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('created_at')->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('deleted_at')->toggleable(isToggledHiddenByDefault: true),
        ])
            ->defaultSort('id', 'desc')
            ->recordActions([
                Action::make('edit-product')
                    ->label('Edit')
                    ->modelLabel('Edit Product')
                    ->authorize(auth()->user()->can('update StoreProduct'))
                    ->model(StoreProduct::class)
                    ->fillForm(fn(StoreProduct $record): array => $record->toArray()) // <--- ADD THIS LINE
                    ->action(function (array $data, StoreProduct $record): void {
                        $record->update($data);

                        Notification::make()
                            ->title("Product {$record->name} successfully updated!")
                            ->success()
                            ->send();
                    })
                    ->schema([
                        Select::make('academic_year_id')
                            ->label('Academic Year')
                            ->relationship('academicYear', 'name') // Corrected to 'academicYear'
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (Set $set) {
                                $set('class_id', null);
                            }),
                        Select::make('class_id')
                            ->label('Class')
                            ->relationship('studentClass', 'name', modifyQueryUsing: function (Builder $query, Get $get): Builder {
                                return $query->when($get('academic_year_id'), fn(Builder $q) => $q->where('academic_year_id', $get('academic_year_id')));
                            })
                            ->reactive()
                            ->required()
                            ->visible(fn(Get $get) => filled($get('academic_year_id'))),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('₹'),
                    ]),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return $this->record->storeProducts()->getQuery();
    }
}
