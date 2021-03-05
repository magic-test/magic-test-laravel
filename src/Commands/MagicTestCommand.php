<?php

namespace MagicTest\MagicTest\Commands;

use Illuminate\Console\Command;

class MagicTestCommand extends Command
{
    public $signature = 'magic';

    public $description = 'Run your Dusk Test Suite using Magic Test.';

    public function handle()
    {
        shell_exec('DUSK_HEADLESS_DISABLED=1 php artisan dusk');
    }
}
