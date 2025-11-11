<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Stores\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\Stores\StoreResource;
use App\Models\Store;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;

class StudentProducts extends Page
{
    use InteractsWithRecord;
    use InteractsWithInfolists;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.store-management-system.stores.pages.student-products';

    public User $student;

    public function mount(int|string $record, $student): void
    {
        $this->record = $this->resolveRecord($record);
        $this->student = User::role('student')->where('is_active', true)->findOrFail($student->id);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('seller')->url(StoreResource::getUrl('seller', ['record' => $this->record])),
            Action::make('invoices')->url(StoreResource::getUrl('invoices', ['record' => $this->record])),
            Action::make('transactions')->url(StoreResource::getUrl('transactions', ['record' => $this->record])),
        ];
    }


}
