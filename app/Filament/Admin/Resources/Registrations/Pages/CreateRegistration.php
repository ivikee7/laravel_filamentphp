<?php

namespace App\Filament\Admin\Resources\Registrations\Pages;

use App\Filament\Admin\Resources\Registrations\RegistrationResource;
use App\Models\Enquiry;
use Filament\Resources\Pages\CreateRecord;

class CreateRegistration extends CreateRecord
{
    protected static string $resource = RegistrationResource::class;

    public ?int $enquiryId = null;

    public function mount(): void
    {
        parent::mount();
        $this->enquiryId = request()->query('enquiry_id');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['enquiry_id'] = $this->enquiryId;

        return $data;
    }

//    protected function afterCreate(): void
//    {
//        if ($this->enquiryId) {
//            $enquiry = Enquiry::find($this->enquiryId);
//            if ($enquiry) {
//                $enquiry->delete();
//            }
//        }
//    }
}
