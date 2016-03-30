<?php

namespace PartsAt\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (\Session::get('isAuthenticated', false) !== true) {
            return $request->ajax() ? response('Unauthorized', 401) : redirect('/admin/login');
        }

        return $next($request);
    }
}
