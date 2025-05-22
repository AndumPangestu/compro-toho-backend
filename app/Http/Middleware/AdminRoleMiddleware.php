<?php

namespace App\Http\Middleware;

use Closure;
use App\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || (Auth::check() && !in_array(Auth::user()->role, ["admin", "superadmin"]))) {
            if ($request->wantsJson()) {
                return $this->sendError(403, null, "You do not have permission to access this route.");
            }
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this route.');
        }
        return $next($request);
    }
}
