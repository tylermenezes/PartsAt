<?php

namespace PartsAt\Http\Middleware;

class ShareConfig
{
    public function handle($request, \Closure $next)
    {
        \View::share('appName', \Config::get('app.name'));
        \View::share('isAuthenticated', \Session::get('isAuthenticated'));
        return $next($request);
    }
}
