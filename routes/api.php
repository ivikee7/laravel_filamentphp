<?php

use App\Http\Controllers\Api\WebsiteEnquiryController;
use Illuminate\Support\Facades\Route;

Route::prefix('/api')->group(function () {
    Route::get('/website-enquiries', [WebsiteEnquiryController::class, 'store']);
});
