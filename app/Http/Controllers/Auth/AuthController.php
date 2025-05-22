<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmailSubscriber;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\AdminLoginRequest;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Requests\SocialLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'password' => Hash::make($request->get('password')),
        ]);

        $user->sendEmailVerificationNotification();


        EmailSubscriber::create([
            'email' => $user->email,
        ]);

        return $this->sendSuccess(201, $user, "User registered successfully. Please check your email for verification.");
    }

    // User login
    public function userLogin(UserLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {

            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->sendError(401, ["Invalid email or password"], "Unauthorized");
            }

            // Get the authenticated user.
            $user = auth()->user();

            // (optional) Attach the role to the token.
            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            return $this->sendSuccess(200, $token, "User logged in successfully.");
        } catch (JWTException $e) {
            return $this->sendError(500, null, "Internal server error");
        }
    }

    public function adminLogin(AdminLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');


        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();


            if (!in_array($user->role, ['admin', 'superadmin'])) {
                Auth::logout();
                return back()->withInput()->with('error', 'You do not have access to this section.');
            }

            return redirect()->intended('/dashboard')->with('success', 'Login successful');
        }

        return back()->withInput()->with('error', 'Invalid email or password');
    }


    public function logout(Request $request)
    {
        if ($request->wantsJson()) {
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->sendSuccess(200, null, "User logged out successfully.");
        }

        Auth::logout();
        return redirect()->route('login');
    }

    public function socialLogin(SocialLoginRequest $request, $provider)
    {
        try {


            $token = $request->token;
            $socialUser = Socialite::driver($provider)->userFromToken($token);

            $user = User::where('provider_id', $socialUser->getId())
                ->where('provider', $provider)
                ->first();

            if (!$user) {
                // Jika email sudah digunakan oleh akun lain, update provider_id
                $existingUser = User::where('email', $socialUser->getEmail())->first();

                if ($existingUser) {
                    return $this->sendError(400, null, "Email already in use.");
                }
                // Buat pengguna baru jika belum ada
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'provider_id' => $socialUser->getId(),
                    'provider' => $provider,
                    'password' => bcrypt(Str::random(16)), // Generate password acak
                ]);

                $user->markEmailAsVerified();
            }

            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            return $this->sendSuccess(200, $token, "User logged in successfully.");
        } catch (\Exception $e) {
            return $this->SendError(400, null, "Invalid token or provider.");
        }
    }
}
