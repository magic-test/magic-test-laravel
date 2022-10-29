<?php

namespace MagicTest\MagicTest\Middleware;

use Closure;
use Illuminate\Http\Request;

class NullMagicTestMiddleware
{
    public function handle(Request  $request, Closure  $next)
    {
        return $next($request);
    }
}
