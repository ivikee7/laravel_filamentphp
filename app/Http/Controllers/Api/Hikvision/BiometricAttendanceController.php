<?php

namespace App\Http\Controllers\Api\Hikvision;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BiometricAttendanceController extends Controller
{
    public function store(Request $request)
    {
        // Hikvision pushes events in JSON or XML. Laravel's $request->all() handles both.
        $data = $request->all();

        Log::debug($data);

        // 1. Identify the user and timestamp from the device payload
        $employeeId = $data['employeeNoString'] ?? null;
        $timestamp = $data['dateTime'] ?? now();
        $status = $data['attendanceStatus'] ?? 'checkIn'; // e.g., checkIn, checkOut

        if (!$employeeId) {
            return response()->json(['status' => 'error', 'message' => 'No employee ID'], 400);
        }

        // 2. Map to your existing Attendance model
        Attendance::create([
            'user_id' => $employeeId,
            'created_at' => $timestamp,
            'type' => $status,
        ]);

        // 3. IMPORTANT: Hikvision requires a 200 OK response to stop retrying the push
        return response()->json(['status' => 'success'], 200);
    }
}
