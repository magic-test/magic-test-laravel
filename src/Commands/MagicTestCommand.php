<?php

namespace Mateusjatenee\MagicTest\Commands;

use Illuminate\Console\Command;

class MagicTestCommand extends Command
{
    public $signature = 'magic-test-laravel';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
