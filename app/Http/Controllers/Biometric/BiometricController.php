<?php

namespace App\Http\Controllers\Biometric;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class BiometricController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id_on_device' => 'required|string|max:255',
            'timestamp' => 'required|date',
        ]);

        Attendance::create([
            'user_id' => $validatedData['user_id_on_device'],
            'created_at' => $validatedData['timestamp'],
        ]);

        return response()->json(['message' => 'Biometric data received successfully'], 201);
    }
}
