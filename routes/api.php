<?php

use App\Http\Controllers\Api\WebsiteEnquiryController;
use Illuminate\Support\Facades\Route;

Route::match(array('GET', 'POST'), '/website-enquiries', [WebsiteEnquiryController::class, 'store']);
