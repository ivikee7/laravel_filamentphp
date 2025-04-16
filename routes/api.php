<?php

use App\Http\Controllers\Api\WebsiteEnquiryController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', fn() => response()->json(['message' => 'API is working']));
Route::get('/website-enquiries', [WebsiteEnquiryController::class, 'store']);
