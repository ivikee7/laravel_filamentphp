<?php

use App\Filament\Auth\Login;
use App\Http\Controllers\Api\WebsiteEnquiryController;
use App\Http\Controllers\WhatsApp\WebhookController;
use App\Filament\Admin\Auth\Login as AuthLogin;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::redirect('/', '/admin');
Route::redirect('/admin/login', '/login');
Route::get('/login', AuthLogin::class)->name('login');


