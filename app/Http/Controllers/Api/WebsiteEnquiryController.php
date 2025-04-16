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
            'contact_number' => 'nullable|digits:10',
            'email' => 'nullable|email|max:50',
            'message' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = WebsiteEnquiry::create($request->all());
        return response()->json(['data' => $product], 201);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'contact_number' => 'nullable|digits:10',
            'email' => 'nullable|email|max:50',
            'message' => 'nullable|string|max:255',
        ]);

        $enquiry = WebsiteEnquiry::create($validated);

        return response()->json([
            'success' => true,
            'data' => $enquiry,
        ]);
    }
}
