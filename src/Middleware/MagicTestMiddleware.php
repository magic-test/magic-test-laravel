<?php

namespace Mateusjatenee\MagicTest\Middleware;

use Illuminate\Http\Request;
use Closure;
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
        //    $response->setContent(
        //        mb_substr($content, 0, $head) . "\n ao" . mb_substr($content, $head)
        //    ); 
        }

        return $response;
    }
}