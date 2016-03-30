<?php

namespace PartsAt\Http\Middleware;

class ShareExtensions
{
    public function handle($request, \Closure $next)
    {
        $ua = strtolower($request->header('User-Agent'));

        if (strpos($ua, 'firefox') !== false) {
            \View::share('extension', config('extension.firefox_url'));
        } elseif (strpos($ua, 'chrome') !== false) {
            \View::share('extension', config('extension.chrome_url'));
        }

        return $next($request);
    }
}
