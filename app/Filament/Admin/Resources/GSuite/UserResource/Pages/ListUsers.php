<?php

namespace App\Filament\Admin\Resources\GSuite\UserResource\Pages;

use App\Filament\Admin\Resources\GSuite\UserResource;
use App\Filament\Admin\Resources\StudentResource\Pages\UpdateClassSection;
use App\Models\GSuiteUser;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // protected function getTableQuery(): ?Builder
    // {
    //     User::whereNotNull('official_email')
    //         ->where('official_email', '!=', '')
    //         ->get()
    //         ->each(function ($user) {
    //             // Fetch the related GSuiteUser, or create a new one if not exists
    //             $gsuiteUser = $user->gSuiteUser ?? new GSuiteUser(['user_id' => $user->id]);

    //             $emailChanged = $gsuiteUser->email !== $user->official_email;

    //             // Remove special characters and spaces from name, and capitalize
    //             $cleanedName = preg_replace('/[^a-zA-Z0-9]/', '', $user->name); // Remove non-alphanumeric
    //             $cleanedName = ucfirst(strtolower($cleanedName)); // Capitalize first letter

    //             // Remove spaces from the contact number and take the first 5 digits
    //             $primaryContactDigits = substr(preg_replace('/\D/', '', $user->primary_contact_number), 0, 5);

    //             // If primary contact number is less than 5 digits, generate a random 5-digit number
    //             if (strlen($primaryContactDigits) < 5) {
    //                 $primaryContactDigits = rand(10000, 99999);
    //             }

    //             // Create a new password: first 5 chars of name + '@' + digits
    //             $newPassword = substr($cleanedName, 0, 5) . '@' . $primaryContactDigits;

    //             // Ensure password length is at least 11 characters
    //             if (strlen($newPassword) < 11) {
    //                 $newPassword .= rand(100000, 999999);
    //             }

    //             $passwordChanged = $gsuiteUser->password !== $newPassword;

    //             // Only update if email or password changed
    //             if ($emailChanged || $passwordChanged) {
    //                 $gsuiteUser->email = $user->official_email;
    //                 $gsuiteUser->password = $newPassword;
    //                 $gsuiteUser->saveQuietly();
    //             }
    //         });

    //     return User::query();
    // }
}
