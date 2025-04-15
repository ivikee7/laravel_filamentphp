<?php

namespace App\Filament\Admin\Resources\StudentResource\Pages;

use App\Filament\Admin\Resources\StudentResource;
use App\Models\Registration;
use App\Models\Student;
use App\Models\StudentClassAssignment;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make($data['primary_contact_number']);
        $data['is_active'] = true;
        $data['student']['registration_id'] = $this->registrationId;

        return $data;
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
    }
}
