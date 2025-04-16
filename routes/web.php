<?php

use App\Filament\Auth\Login;
use App\Http\Controllers\Api\WebsiteEnquiryController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::prefix('/api')->group(function () {
    Route::get('/website-enquiries', [WebsiteEnquiryController::class, 'store']);
});
