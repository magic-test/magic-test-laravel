<?php

namespace MagicTest\MagicTest\Commands;

use Illuminate\Console\Command;

class MagicTestCommand extends Command
{
    public $signature = 'magic {--filter= : Run a single test}';

    public $description = 'Run your Dusk Test Suite using Magic Test.';

    public function handle()
    {
        $this->comment('Your Magic Test session is starting...');
        $filterstring = ($this->option('filter')) ? ' --filter '.$this->option('filter') : '';
        shell_exec('DUSK_HEADLESS_DISABLED=1 MAGIC_TEST=1 php artisan dusk '.$filterstring);
    }
}
