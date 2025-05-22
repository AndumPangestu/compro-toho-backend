<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendResetLinkEmailRequest;

class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->id);

        // Pastikan hash sesuai dengan email pengguna
        if (!hash_equals(sha1($user->getEmailForVerification()), $request->hash)) {
            return $this->sendError(400, null, "Invalid verification link.");
        }

        // Jika email sudah diverifikasi
        if ($user->hasVerifiedEmail()) {
            return $this->sendSuccess(200, null, "Email already verified.");
        }

        // Tandai email sebagai terverifikasi
        $user->markEmailAsVerified();

        return $this->sendSuccess(200, null, "Email verified successfully.");
    }


    public function resendVerificationEmail(SendResetLinkEmailRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->sendError(400, null, "User not found.");
        }

        if ($user->hasVerifiedEmail()) {
            return $this->sendSuccess(200, null, "Email already verified.");
        }

        $user->sendEmailVerificationNotification();

        return $this->sendSuccess(200, null, "Verification email sent successfully.");
    }
}
