<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::redirect('/', '/admin');
Route::redirect('/admin/login', '/login');
Route::get('/login', \Filament\Auth\Pages\Login::class)->name('login');

//QRCode
Route::get('/qrcode/{id}', function ($id) {

    $data_record = \App\Models\User::role('Student')
        ->where('id', $id)
        ->where('is_active', true)
        ->select('id', 'name', 'father_name')
        ->with([
            'student.classAssignment.class:id,name',
            'student.classAssignment.section:id,name'
        ])
        ->first();

    if ($data_record) {
        $className = data_get($data_record, 'student.classAssignment.class.name', 'N/A');
        $sectionName = data_get($data_record, 'student.classAssignment.section.name', 'N/A');

        $data = "Name: " . $data_record->name . "\n" .
            "Father Name: " . $data_record->father_name . "\n" .
            "Class: " . $className . "\n" .
            "Section: " . $sectionName;

        // Create a short, clean filename using the student's name
        $safeFilename = preg_replace('/[^A-Za-z0-9\-]/', '_', $data_record->name) . '_qrcode.png';
    } else {
        $data = 'Invalid QR Code';
        $safeFilename = 'invalid_qrcode.png';
    }

    // 1. Change format to 'png'
    // 2. SVG used text based formatting, but PNG needs a clean filename.
    $qrCodePng = QrCode::format('png')
        ->size(200)
//        ->merge('/public/img/logo.png', .3) // Optional: If you want a logo inside
        ->generate($data);

    // 3. Update headers for PNG image type
    return response($qrCodePng)
        ->header('Content-Type', 'image/png')
        ->header('Content-Disposition', 'attachment; filename="' . $safeFilename . '"');
})->name('qrcode.generate');


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
    $user = \App\Models\User::whereHas('gSuiteUser', function ($query) use ($googleUser) {
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

// Per-user Google OAuth connect (for Drive/Classroom access)
Route::middleware(['auth'])->group(function () {
    Route::get('/google/connect', [\App\Http\Controllers\GoogleOAuthController::class, 'redirectToGoogle'])->name('google.connect');
    Route::get('/google/connect/callback', [\App\Http\Controllers\GoogleOAuthController::class, 'handleGoogleCallback'])->name('google.connect.callback');
    Route::post('/google/disconnect', [\App\Http\Controllers\GoogleOAuthController::class, 'disconnect'])->name('google.disconnect');
});

Route::get('/admin/invoices/{invoice}/print', [\App\Http\Controllers\Admin\StoreManagementSystem\Invoice\InvoicePrintController::class, 'print'])->name('invoice.print');

Route::get('/print-id-card/{user}', function (\App\Models\User $user) {
    // Eager load any relationships needed for the ID card if they are not directly on User model
    // Example: if class and section are on a 'student' relationship:
    $user->load('student');
    return view('filament.admin.pages.i-d-cards.print-id-card', compact('user'));
})->name('print.user.id_card');


Route::get('/print-student-id-card/{user}', function (\App\Models\User $user) {
    // Check if the user has the 'Student' role
    if (!$user->hasRole('Student')) {
        abort(403, 'User is not a student.');
    }

    // Wrap in a collection to keep your existing Blade view compatible
    $records = collect([$user]);

    return view('filament.admin.pages.i-d-cards.print-student-id-card', compact('records'));
})->name('print.student_id_card');
Route::get('/print-user-id-card/{user}', function (\App\Models\User $user) {
    // Ensure the user is NOT a student, as per your query logic
    if ($user->hasRole('Student')) {
        abort(403, 'Students cannot use this print route.');
    }

    // Wrap the single user in a collection so your Blade @foreach still works
    $records = collect([$user]);

    return view('filament.admin.pages.i-d-cards.print-user-id-card', compact('records'));
})->name('print.user_id_card');


Route::get('/print-student-id-cards', function (\Illuminate\Http\Request $request) {
    $ids = $request->query('ids'); // Get IDs from URL query parameter
    $records = collect();

    if ($ids) {
        $records = App\Models\User::whereIn('id', explode(',', $ids))
            ->whereHas('roles', function (\Illuminate\Database\Eloquent\Builder $query) {
                $query->whereIn('name', ['Student']);
            })
            ->get();
    }

    return view('filament.admin.pages.i-d-cards.print-student-id-cards', compact('records'));
})->name('print.student_id_cards');
Route::get('/print-user-id-cards', function (\Illuminate\Http\Request $request) {
    $ids = $request->query('ids'); // Get IDs from URL query parameter
    $records = collect();

    if ($ids) {
        $records = App\Models\User::whereIn('id', explode(',', $ids))
            ->whereHas('roles', function (\Illuminate\Database\Eloquent\Builder $query) {
                $query->whereNotIn('name', ['Student']);
            })
            ->get();
    }

    return view('filament.admin.pages.i-d-cards.print-user-id-cards', compact('records'));
})->name('print.user_id_cards');

Route::get('/print-user-out-pass', function (\Illuminate\Http\Request $request) {
    $ids = $request->query('ids'); // Get IDs from URL query parameter
    $records = collect();

    if ($ids) {
        $records = App\Models\User::whereIn('id', explode(',', $ids))
            ->whereHas('roles', function (\Illuminate\Database\Eloquent\Builder $query) {
                $query->whereNotIn('name', ['Student']);
            })
            ->get();
    }

    return view('filament.admin.pages.printable.print-user-out-pass', compact('records'));
})->name('print.user_out_pass');


// Route::get('/generate-student-qrs', function () {
//     // Get all student users.
//     $students = User::role('Student')
//         ->where('is_active', true)
//         ->where('id', '>=', 2671)
//         ->get();
//
//     if ($students->isEmpty()) {
//         return response()->json(['error' => 'No students found.'], 404); // Handle the case where no students are found.
//     }
//
//     $results = []; // Array to store the URLs of the generated QR codes.
//
//     foreach ($students as $student) {
//         // Generate the data string for each student.  Customize this as needed.
//         $data = "https://erp.srcspatna.com/admin/id-cards/" . $student->id;
//
//         // Generate the QR code as a PNG
//         $qrCodePng = QrCode::format('png')->size(200)->generate($data);
//
//
//         // Generate a unique filename for each QR code image.
//         $filename = $student->id . '.png';
//
//         // Store the QR code PNG in the storage/qrcode directory
//         Storage::disk('local')->put('qrcode/' . $filename, $qrCodePng);
//         $url = Storage::url('qrcode/' . $filename);
//
//         $results[] = [
//             'student_id' => $student->id,
//             'url' => $url,
//         ];
//     }
//
//     return response()->json($results); // Return an array of URLs, each associated with a student.
// });
