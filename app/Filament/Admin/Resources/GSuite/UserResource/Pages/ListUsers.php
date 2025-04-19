<?php

namespace App\Filament\Admin\Resources\GSuite\UserResource\Pages;

use App\Filament\Admin\Resources\GSuite\UserResource;
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

    //             // Remove spaces from the user's name and extract the first 5 digits from the primary contact number
    //             $cleanedName = str_replace(' ', '', $user->name);  // Remove spaces from name
    //             $primaryContactDigits = substr(str_replace(' ', '', $user->primary_contact_number), 0, 5);  // Remove spaces from the primary contact number

    //             // If primary contact number is less than 5 digits, generate a random 6-digit number
    //             if (strlen($primaryContactDigits) < 5) {
    //                 $primaryContactDigits = rand(10000, 99999);  // Random 5-digit number
    //             }

    //             // Create a new password using cleaned name + first 5 digits of the contact number
    //             $newPassword = substr($cleanedName, 0, 5) . '@' . $primaryContactDigits;  // Only take the first 5 characters of the cleaned name

    //             // Ensure password length is at least 11 characters (if less, append a random 6-digit number)
    //             if (strlen($newPassword) < 11) {
    //                 $randomNumber = rand(100000, 999999);  // Random 6-digit number
    //                 $newPassword .= $randomNumber;  // Append random number to meet 11 characters
    //             }

    //             $passwordChanged = $gsuiteUser->password !== $newPassword;

    //             // Only update if there is a change in email or password
    //             if ($emailChanged || $passwordChanged) {
    //                 $gsuiteUser->email = $user->official_email;
    //                 $gsuiteUser->password = $newPassword;  // Set the new password
    //                 $gsuiteUser->saveQuietly();  // Avoid triggering any events during saving
    //             }
    //         });

    //     return User::query();
    // }
}
