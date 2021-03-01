<?php

namespace Mateusjatenee\MagicTest\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mateusjatenee\MagicTest\MagicTest;

class MagicTestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $content = $response->getContent();

        if ($head = mb_strpos($content, '</body>') !== false) {
            $scripts = MagicTest::scripts();
            $response->setContent($content . "\n $scripts");
        }

        return $response;
    }
}
