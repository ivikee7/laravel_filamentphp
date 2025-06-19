<?php

use App\Filament\Auth\Login;
use App\Http\Controllers\Api\WebsiteEnquiryController;
use App\Http\Controllers\WhatsApp\WebhookController;
use App\Filament\Admin\Auth\Login as AuthLogin;
use App\Jobs\GenerateQRCode;
use App\Models\GSuiteUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::redirect('/', '/admin');
Route::redirect('/admin/login', '/login');
Route::get('/login', AuthLogin::class)->name('login');


Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/auth/google/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->user();
    } catch (\Exception $e) {
        // Handle Socialite exceptions (e.g., user denied access, invalid token)
        return redirect('/login')->with('error', 'Failed to authenticate with Google. Please try again.');
    }

    // Check if the user exists in your database via gSuiteUser relationship
    $user = User::whereHas('gSuiteUser', function ($query) use ($googleUser) {
        $query->where('email', $googleUser->email);
    })->first();

    if ($user) {
        // Log the user in
        Auth::login($user);
    } else {
        // If the user doesn't exist, redirect to the login page with a more informative message
        return redirect('/login')->with('error', 'Your Google account is not associated with any account. Please contact support.');
    }

    // Redirect the user to the Filament admin panel
    return redirect('/admin');
});

Route::get('/admin/invoices/{invoice}/print', [\App\Http\Controllers\Admin\StoreManagementSystem\Invoice\InvoicePrintController::class, 'print'])->name('invoice.print');

Route::get('/print-id-card/{user}', function (User $user) {
    // Eager load any relationships needed for the ID card if they are not directly on User model
    // Example: if class and section are on a 'student' relationship:
     $user->load('student');
    return view('filament.admin.pages.i-d-cards.print-id-card', compact('user'));
})->name('print.user.id_card');

Route::get('/print-id-cards', function (\Illuminate\Http\Request $request) {
    $ids = $request->query('ids'); // Get IDs from URL query parameter
    $records = collect();

    if ($ids) {
        $records = App\Models\User::whereIn('id', explode(',', $ids))->get();
    }

    return view('filament.admin.pages.i-d-cards.print-id-cards', compact('records'));
})->name('print.id_cards');


// Route::get('/generate-student-qrs', function () {
//     // Get all student users.
//     $students = User::role('Student')
//         ->where('is_active', true)
//         ->where('id', '>=', 2490)
//         ->get();

//     if ($students->isEmpty()) {
//         return response()->json(['error' => 'No students found.'], 404); // Handle the case where no students are found.
//     }

//     $results = []; // Array to store the URLs of the generated QR codes.


//     foreach ($students as $student) {
//         // Generate the data string for each student.  Customize this as needed.
//         $data = "https://erp.srcspatna.com/admin/id-cards/" . $student->id;

//         // Generate the QR code as a PNG
//         $qrCodePng = QrCode::format('png')->size(200)->generate($data);


//         // Generate a unique filename for each QR code image.
//         $filename = $student->id . '.png';

//         // Store the QR code PNG in the storage/qrcode directory
//         Storage::disk('local')->put('qrcode/' . $filename, $qrCodePng);
//         $url = Storage::url('qrcode/' . $filename);

//         $results[] = [
//             'student_id' => $student->id,
//             'url' => $url,
//         ];
//     }

//     return response()->json($results); // Return an array of URLs, each associated with a student.
// });
