<?php

namespace MagicTest\MagicTest\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->environment(['local', 'testing'])) {
            return $next($request);
        }

        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        if (! $this->responseContainsClosingHtmlTag($response)) {
            return $response;
        }
        
        return tap($response)->setContent(
            $this->addMagicTestScriptsToResponseContent($response->getContent())
        );
    }

    protected function responseContainsClosingHtmlTag(Response $response): bool
    {
        return mb_strpos($response->getContent(), '</html>') !== false;
    }

    /**
     * @param  string  $responseContent
     * @return string
     */
    protected function addMagicTestScriptsToResponseContent(string $responseContent): string
    {
        $scripts = MagicTest::scripts();

        return Str::replaceLast(
            '</html>',
            "{$scripts} \n </html>",
            $responseContent
        );
    }
}
