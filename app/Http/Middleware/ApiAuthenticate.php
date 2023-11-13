<?php

namespace App\Http\Middleware;

use App\Enums\Channel;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class ApiAuthenticate extends Middleware
{
//    public function handle($request, Closure $next, ...$guards)
//    {
//        $host = $request->httpHost();
//
//        $channel = Channel::fromHost($host);
//
//        $request->merge(['channel_id' => $channel->projectId()]);
//
//        return $next($request);
//    }
}
