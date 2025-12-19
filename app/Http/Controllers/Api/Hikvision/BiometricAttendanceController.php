<?php

namespace App\Http\Controllers\Api\Hikvision;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BiometricAttendanceController extends Controller
{
    public function store(Request $request)
    {
        $outerData = $request->all();

        if (!isset($outerData['event_log'])) {
            return response()->json(['status' => 'error'], 400);
        }

        $eventData = json_decode($outerData['event_log'], true);
        $details = $eventData['AccessControllerEvent'] ?? [];

        // Check for Employee ID (1436 in your log)
        $employeeId = $details['employeeNoString'] ?? null;

        if ($employeeId) {
            // Logic to determine status if device sends 'undefined'
            $status = $details['attendanceStatus'];
            if ($status === 'undefined') {
                // Example: If before 12 PM Check-in, else Check-out
                // $status = now()->hour < 12 ? 'checkIn' : 'checkOut';
                $status = 'marked';
            }

            Attendance::create([
                'user_id' => $employeeId,
                'created_at' => Carbon::parse($eventData['dateTime']),
                'type' => $status,
            ]);

            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'ignored_event'], 200);
    }
}
