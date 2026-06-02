<?php

use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

// Dynamic website routes from database
Route::get('/', [WebsiteController::class, 'home'])->name('website.home');
Route::get('/home', [WebsiteController::class, 'home'])->name('website.home.alias');
Route::post('/enquiry', [WebsiteController::class, 'submitEnquiry'])->name('website.enquiry.store');
Route::get('/{slug}', [WebsiteController::class, 'show'])->name('website.page');
