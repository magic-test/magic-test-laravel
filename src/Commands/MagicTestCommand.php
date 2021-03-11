<?php

namespace MagicTest\MagicTest\Commands;

use Illuminate\Console\Command;

class MagicTestCommand extends Command
{
    public $signature = 'magic {--filter= : Filter which tests to run}';

    public $description = 'Run your Dusk Test Suite using Magic Test.';

    public function handle()
    {
        $this->comment('Your Magic Test session is starting...');

        $filter = $this->option('filter') ? (' --filter ' . $this->option('filter')) : '';
        shell_exec('php artisan dusk --browse' . $filter);
    }
}
