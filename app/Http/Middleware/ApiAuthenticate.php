<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class ApiAuthenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        return $next($request);
    }
}
