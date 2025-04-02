<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\Registration;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    public ?int $registrationId = null;

    public function mount(): void
    {
        parent::mount();
        $this->registrationId = request()->query('registration_id');
    }

    protected function afterCreate(): void
    {
        if ($this->registrationId) {
            $registration = Registration::find($this->registrationId);
            if ($registration) {
                $registration->delete();
            }
        }

        $this->record->assignRole('Student');

        $this->record->update([
            'official_email' => strtolower(str_replace(' ', '', $this->record->id)) . '@' . env("ORG_DOMAIN"),
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['password'])) {
            $data['password'] = Hash::make($data['primary_contact_number']); // Default hashed password
            $data['is_active'] = true;
        }

        return $data;
    }
}
