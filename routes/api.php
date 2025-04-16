<?php

use App\Http\Controllers\Api\WebsiteEnquiryController;
use Illuminate\Support\Facades\Route;

Route::post('/website-enquiries', [WebsiteEnquiryController::class, 'store']);

