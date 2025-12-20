<?php

namespace App\Http\Controllers\Api\Hikvision;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $employeeId = $details['employeeNoString'] ?? null;

        if ($employeeId) {
            $punchTime = Carbon::parse($eventData['dateTime']);
            $status = $details['attendanceStatus'] === 'undefined' ? 'marked' : $details['attendanceStatus'];

            // 1. Prevent Exact Duplicates (Same user, same second)
            $exists = Attendance::where('user_id', $employeeId)
                ->where('created_at', $punchTime)
                ->exists();

            if ($exists) {
                return response()->json(['status' => 'duplicate_ignored'], 200);
            }

            // 2. Prevent Mis-punches (e.g., same user within 60 seconds)
            $recentPunch = Attendance::where('user_id', $employeeId)
                ->where('created_at', '>=', $punchTime->copy()->subMinutes(1))
                ->where('created_at', '<=', $punchTime->copy()->addMinutes(1))
                ->exists();

            if ($recentPunch) {
                return response()->json(['status' => 'mis_punch_ignored'], 200);
            }

            Attendance::create([
                'user_id' => $employeeId,
                'created_at' => $punchTime,
                'type' => $status,
            ]);

            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'ignored_event'], 200);
    }
}
