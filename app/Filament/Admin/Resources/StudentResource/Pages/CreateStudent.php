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

        $this->registrationId = request()->query('registration_id');

        // Check if registration_id is missing or invalid
        $registration = Registration::with('student')->find($this->registrationId);

        if (!$registration) {
            Notification::make()
                ->title('Invalid registration')
                ->danger()
                ->body('Please select a valid registration to admit the student.')
                ->send();

            $this->redirect(RegistrationResource::getUrl('index'), navigate: true);
            return;
        }

        // Check if the registration already has a student admitted
        if ($registration->student) {
            Notification::make()
                ->title('Student already admitted')
                ->danger()
                ->body('This registration already has a student record.')
                ->send();

            $this->redirect(RegistrationResource::getUrl('index'), navigate: true);
            return;
        }
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
        $record = $this->record;

        $record->assignRole('Student');

        $record->gSuiteUser()->updateOrCreate(
            [
                'user_id' => $record->id,
            ],
            [
                'email' => $record->id . '@' . env('ORG_DOMAIN'),
                'password' => self::generateGsuitePassword([
                    'name' => $record->name,
                    'primary_contact_number' => $record->primary_contact_number,
                ]),
            ]);


    }

    public static function generateGsuitePassword(array $data): string
    {
        $name = preg_replace('/[^a-zA-Z0-9]/', '', $data['name']);
        $name = ucfirst(strtolower($name));
        $namePart = substr($name, 0, 5);

        if (strlen($namePart) < 5) {
            $namePart = str_pad($namePart, 5, strtoupper(chr(rand(65, 90))));
        }

        $number = preg_replace('/\D/', '', $data['primary_contact_number']);
        $numberPart = substr($number, 0, 5);

        if (strlen($numberPart) < 5) {
            $numberPart = str_pad($numberPart, 5, rand(0, 9));
        }

        $password = $namePart . '@' . $numberPart;

        return substr($password, 0, 11);
    }
}
