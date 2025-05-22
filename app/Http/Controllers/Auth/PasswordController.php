<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\PasswordChangeRequest;
use App\Http\Requests\SendResetLinkEmailRequest;


class PasswordController extends Controller
{
    public function sendResetLinkEmail(SendResetLinkEmailRequest $request)
    {

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->SendError(404,  null, "User not found");
        }


        $token = app('auth.password.broker')->createToken($user);

        // Kirimkan notifikasi reset password dengan queue
        $user->sendPasswordResetNotification($token);


        return $this->sendSuccess(200, null, "Password reset link sent successfully.");
    }



    public function getToken($token, Request $request)
    {
        $data['token'] = $token;
        $data['email'] = $request->query('email');

        return $this->sendSuccess(200, $data, "Token sent successfully.");
    }


    public function resetPassword(ResetPasswordRequest $request)
    {

        $data = $request->validated();


        $status = Password::reset(
            [
                'email' => $data['email'],
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
                'token' => $data['token'],
            ],
            function ($user, $password) {

                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();


                event(new PasswordReset($user));
            }
        );


        if ($status !== Password::PASSWORD_RESET) {

            return $this->SendError(500,  null, "Internal server error");
        }


        return $this->sendSuccess(200, null, "Password reset successfully.");
    }



    public function changePassword(PasswordChangeRequest $request)
    {
        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return $this->sendSuccess(200, null, "Password changed successfully.");
    }
}
