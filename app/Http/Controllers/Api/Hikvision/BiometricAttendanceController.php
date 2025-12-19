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
        // 1. Get the outer array
        $outerData = $request->all();

        if (!isset($outerData['event_log'])) {
            return response()->json(['error' => 'Invalid Format'], 400);
        }

        Log::debug($outerData);

        // 2. Decode the inner JSON string provided in your debug log
        $eventData = json_decode($outerData['event_log'], true);

        // 3. Extract data specifically from 'AccessControllerEvent'
        $details = $eventData['AccessControllerEvent'] ?? [];

        // Only log to database if it's a valid scan (subEventType 1 is usually a success)
        // subEventType 1024 is often just a heartbeat or door status
        if (isset($details['subEventType']) && $details['subEventType'] == 1) {

            Attendance::create([
                'user_id' => $details['employeeNoString'],
                'created_at'  => $eventData['dateTime'] ?? now(),
                'type'        => $details['attendanceStatus'] ?? 'checkIn',
            ]);

            return response()->json(['status' => 'Success'], 200);
        }

        // Return 200 even for heartbeats so the device doesn't keep retrying
        return response()->json(['status' => 'Heartbeat Received'], 200);
    }
}
