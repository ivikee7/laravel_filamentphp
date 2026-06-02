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

<<<<<<< Updated upstream
        WebsiteEnquiry::create($request->all());
=======
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

        // Get the calling source from headers.
        $source = $request->headers->get('origin')
            ?? $request->headers->get('referer')
            ?? 'Direct/Unknown';

        // Extract just the host (for compact storage in notes field).
        $domain = parse_url($source, PHP_URL_HOST) ?? $source;

        $data['notes'] = substr((string) $domain, 0, 150);

        WebsiteEnquiry::create($data);

>>>>>>> Stashed changes
        return response()->json(['success' => true], 201);
    }
}
