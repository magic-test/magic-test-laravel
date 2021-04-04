<?php

namespace MagicTest\MagicTest\Middleware;

use Closure;
use Illuminate\Http\Request;
use MagicTest\MagicTest\MagicTest;

class MagicTestMiddleware
{
    /**
     * Adds the Magic Test scripts to the body of the response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (! app()->environment(['local', 'testing'])) {
            return $next($request);
        }

        $response = $next($request);
        $content = $response->getContent();

        if (mb_strpos($content, '</body>') !== false) {
            $scripts = MagicTest::scripts();
            $response->setContent($content . "\n $scripts");
        }

        return $response;
    }
}
