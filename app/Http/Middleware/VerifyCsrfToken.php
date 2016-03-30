<?php

namespace PartsAt\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    public function handle($request, \Closure $next)
    {
        $csrf = csrf_token();
        \View::share('csrf_token', $csrf);
        \View::share('csrf', '<input type="hidden" name="_token" value="'.$csrf.'" />');

        $response = parent::handle($request, $next);

        return $response;
    }
}
