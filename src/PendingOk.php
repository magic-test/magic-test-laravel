<?php

namespace MagicTest\MagicTest;

class PendingOk
{
    public function __invoke(): void
    {
        app(MagicTestManager::class)->runScripts();
    }
}
