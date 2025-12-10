<?php

namespace App\Filament\Admin\Resources\Attendances\Pages;

use App\Filament\Admin\Resources\Attendances\AttendanceResource;
use App\Models\Attendance;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Http;

class AttendanceRTSP extends Page
{
    protected static string $resource = AttendanceResource::class;

    protected string $view = 'filament.admin.resources.attendances.pages.attendance-r-t-s-p';

    // Properties must be public for Livewire/Filament to update the view
    public $message = 'Ready to check attendance from RTSP.';
    public $status = 'info';
    public ?string $userName = null; // <<< ADDED: To display the name in the UI

    // Configuration properties
    protected string $rtspCameraUrl = 'rtsp://rtsp:rtsp@123@12.11.10.186:554/profile1';

    protected string $pythonApiUrl = 'http://127.0.0.1:5000/recognize';

    public function clockIn()
    {
        $this->message = 'Attempting to capture and recognize face. Please wait...';
        $this->status = 'info';
        $this->userName = null; // Reset name on new scan

        try {
            // 1. Send RTSP URL to Python API
            $response = Http::timeout(20)
                ->post($this->pythonApiUrl, [
                    'rtsp_url' => $this->rtspCameraUrl,
                ]);

            if ($response->failed()) {
                $this->message = 'Error communicating with recognition service. Check Python API and network connection.';
                $this->status = 'danger';

                // CORRECTION: Change notification method order and use danger()
                Notification::make()
                    ->title('API Communication Failed')
                    ->body('Could not connect to the recognition service. Please verify Gunicorn status and network.')
                    ->danger() // Use danger() for a critical failure
                    ->send(); // Send must be the last method

                return;
            }

            $data = $response->json();

            // 2. Process Recognition Result
            if ($data['status'] === 'success') {
                $userId = (int)$data['user_id'];
                $confidence = (string)$data['confidence'];

                $user = User::find($userId);

                if ($user) {
                    // 3. Store Data in SQLite (via Eloquent)
                    Attendance::create([
                        'user_id' => $user->id,
                        'clock_in_time' => now(),
                        'recognized_confidence' => $confidence,
                        // Consider adding 'is_late' logic here
                    ]);

                    $this->userName = $user->name;
                    $this->message = "Clocked In Success! Welcome, {$user->name}!";
                    $this->status = 'success';

                    // Filament Success Notification
                    Notification::make()
                        ->title('Clock-In Successful')
                        ->body("Welcome, {$user->name}! Attendance recorded.")
                        ->success()
                        ->send();

                } else {
                    $this->message = "Recognized ID {$userId} not found in the database. Confidence: {$confidence}.";
                    $this->status = 'warning';
                }

            } else {
                $this->message = $data['message'] ?? 'Recognition failed or stream error.';
                $this->status = 'warning';
                Notification::make()->title('Recognition Failed')->body('No confident match found.')->warning()->send();
            }

        } catch (\Exception $e) {
            $this->message = 'An unexpected error occurred: ' . $e->getMessage();
            $this->status = 'danger';
            Notification::make()->title('System Error')->body('An unexpected error occurred.')->danger()->send();
        }
    }

}
