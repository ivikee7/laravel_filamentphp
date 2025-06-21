<?php

namespace App\Filament\Admin\Resources\RegistrationResource\Pages;

use App\Filament\Admin\Resources\RegistrationResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class PrintRegistration extends Page
{
    use InteractsWithRecord;

    protected static string $resource = RegistrationResource::class;

    protected static string $view = 'filament.admin.resources.registration-resource.pages.print-registration';

    public function mount(int | string $record): void
    {
        // The InteractsWithRecord trait already sets the $record internally.
        // You can access it via $this->getRecord() or directly $this->record
        // if you want to perform additional actions here.
        $this->record = $this->resolveRecord($record);
    }

    // You can access the record in your view using $this->record or $this->getRecord()
    // For example, in your Blade: {{ $this->record->name }}

    // You might want to hide the Filament header/sidebar for a clean print page
//    protected function getHeader(): ?\Illuminate\Contracts\View\View
//    {
//        return null;
//    }
//
//    protected function getFooter(): ?\Illuminate\Contracts\View\View
//    {
//        return null;
//    }

    // Optionally, you can also hide the navigation if it's a sub-page
    // protected static bool $shouldRegisterNavigation = false;
}
