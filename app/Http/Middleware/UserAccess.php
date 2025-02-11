<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (in_array(auth()->user()->type, $roles)) {
            if (Auth::user()->active_status == 0) {
                return $next($request);
            }
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is inactive. Please contact the administrator.');
        }

        return abort(403, 'Access denied');
    }
}
