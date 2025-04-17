<?php

namespace App\Filament\Admin\Resources\StudentResource\Pages;

use App\Filament\Admin\Resources\RegistrationResource;
use App\Filament\Admin\Resources\StudentResource;
use App\Models\Registration;
use App\Models\Student;
use App\Models\StudentClassAssignment;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
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

        if (!request()->has('registration_id') || !\App\Models\Registration::find(request()->get('registration_id'))) {
            Notification::make()
                ->title('Missing or invalid registration')
                ->danger()
                ->body('Please select a valid registration to create a student.')
                ->send();

            $this->redirect(RegistrationResource::getUrl('index'), navigate: true);
        }

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
