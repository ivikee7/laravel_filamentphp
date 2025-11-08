<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Stores\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\Stores\StoreResource;
use App\Filament\Admin\Resources\Students\StudentResource;
use App\Models\Student;
use App\Models\User;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;

class Students extends Page
{
    use InteractsWithRecord;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.store-management-system.stores.pages.students';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getStudents(): array
    {
        return Student::all()->toArray();
    }

    public function productsInfolist(Schema $schema): Schema
    {
        return $schema
            ->record(self::getStudents())
            ->components([
                RepeatableEntry::make('Products')
                    ->table([
                        TableColumn::make('Product'),
                        TableColumn::make('Price'),
                    ])
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('price'),
                    ])
            ]);
    }

}
