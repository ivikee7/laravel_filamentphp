<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\GSuiteUser;

class GoogleOAuthController
{
    /**
     * Redirect the user to Google's OAuth consent screen to connect their account.
     */
    public function redirectToGoogle(Request $request)
    {
        // Request access to Drive and Classroom in addition to profile/email
        $scopes = [
            'https://www.googleapis.com/auth/drive.file',
            'https://www.googleapis.com/auth/classroom.courses',
            'https://www.googleapis.com/auth/classroom.rosters',
            'https://www.googleapis.com/auth/classroom.coursework.me',
        ];

        return Socialite::driver('google')
            ->scopes($scopes)
            ->with(['access_type' => 'offline', 'prompt' => 'consent'])
            ->redirect();
    }

    /**
     * Handle callback from Google and persist tokens to g_suite_users.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to connect Google: ' . $e->getMessage());
        }

        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to connect Google.');
        }

        $user = Auth::user();

        $g = GSuiteUser::firstOrNew(['user_id' => $user->id]);
        $g->email = $googleUser->getEmail();
        $g->google_id = $googleUser->getId();
        // Socialite provides token and refreshToken, expiresIn
        $g->google_access_token = [
            'access_token' => $googleUser->token,
            'refresh_token' => $googleUser->refreshToken ?? null,
            'expires_in' => $googleUser->expiresIn ?? null,
        ];
        $g->google_token_expires_at = $googleUser->expiresIn ? now()->addSeconds($googleUser->expiresIn) : null;
        $g->google_token_scopes = implode(' ', $googleUser->user['scope'] ?? []);
        $g->google_last_synced_at = now();
        $g->save();

        return redirect()->back()->with('success', 'Google account connected successfully.');
    }

    /**
     * Disconnect Google for authenticated user (remove tokens).
     */
    public function disconnect(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $g = GSuiteUser::where('user_id', Auth::id())->first();
        if ($g) {
            $g->google_access_token = null;
            $g->google_id = null;
            $g->google_token_expires_at = null;
            $g->google_token_scopes = null;
            $g->save();
        }

        return redirect()->back()->with('success', 'Google account disconnected.');
    }
}

