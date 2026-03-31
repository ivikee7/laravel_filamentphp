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
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if an exact match already exists
        $exists = WebsiteEnquiry::where('name', $request->name)
            ->where('contact_number', $request->contact_number)
            ->where('email', $request->email)
            ->where('message', $request->message)
            ->exists();

        if ($exists) {
            return response()->json([
                'errors' => ['duplicate' => ['This exact enquiry has already been submitted.']]
            ], 422);
        }

        $data = $request->all();

        // 1. Get the calling source from headers
        // 'Origin' is best for AJAX/CORS; 'Referer' is a backup for standard links
        $source = $request->headers->get('origin')
            ?? $request->headers->get('referer')
            ?? 'Direct/Unknown';

        // 2. Extract just the host (e.g., "school-website.com") to save space
        $domain = parse_url($source, PHP_URL_HOST) ?? $source;

        // 3. Store in the 150-char notes field
        $data = $request->all();
        $data['notes'] = substr("Source: " . $domain, 0, 150);

        WebsiteEnquiry::create($data);

        return response()->json(['success' => true], 201);
    }
}
