<?php

namespace MagicTest\MagicTest\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        if (mb_strpos($response->getContent(), '</body>') !== false) {
            $scripts = MagicTest::scripts();

            $responseContent = Str::replaceLast('</html>',
                "{$scripts} \n </html>",
                $response->getContent()
            );

            $response->setContent($responseContent);
        }

        return $response;
    }
}
