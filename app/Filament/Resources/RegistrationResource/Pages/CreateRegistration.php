<?php

namespace App\Filament\Resources\RegistrationResource\Pages;

use App\Filament\Resources\RegistrationResource;
use App\Models\Enquiry;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Session;

class CreateRegistration extends CreateRecord
{
    protected static string $resource = RegistrationResource::class;

    public ?int $enquiryId = null;

    public function mount(): void
    {
        parent::mount();
        $this->enquiryId = request()->query('enquiry_id');
    }

    protected function afterCreate(): void
    {
        if ($this->enquiryId) {
            $enquiry = Enquiry::find($this->enquiryId);
            if ($enquiry) {
                $enquiry->delete();
            }
        }
    }
}
