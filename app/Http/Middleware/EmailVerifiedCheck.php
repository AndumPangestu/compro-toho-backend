<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmailVerifiedCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Periksa apakah email pengguna telah diverifikasi
        if (!$request->user()->hasVerifiedEmail()) {
            throw new HttpResponseException(response(['code' => 200, 'success' => false, 'message' => 'Email is not verified. Please verify your email first.', 'data' => ['isNotVerified' => true]], 200));
        }

        return $next($request);
    }
}
