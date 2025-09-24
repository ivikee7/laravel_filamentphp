<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebsiteEnquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebsiteEnquiryController extends Controller
{

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'contact_number' => 'required|digits:10',
            'email' => 'required|email|max:50',
            'message' => 'required|string|max:255',
        ], [
            'name.required' => 'Name required.',
            'contact_number.required' => 'Contact required.',
            'contact_number.digits' => '10 digits only.',
            'email.required' => 'Email required.',
            'email.email' => 'Invalid email.',
            'message.required' => 'Message required.',
            'message.max' => 'Too long.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        WebsiteEnquiry::create($request->all());
        return response()->json(['success' => true], 201);
    }
}
