<?php

use App\Http\Controllers\Api\WebsiteEnquiryController;
use App\Http\Controllers\WhatsApp\WebhookController;
use Illuminate\Support\Facades\Route;

Route::match(array('GET', 'POST'), '/website-enquiries', [WebsiteEnquiryController::class, 'store']);


Route::post('/whatsapp/webhook', [WebhookController::class, 'handle']);
Route::get('/whatsapp/webhook', [WebhookController::class, 'verify']);
