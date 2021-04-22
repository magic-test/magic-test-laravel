<?php

namespace MagicTest\MagicTest\Commands;

use Illuminate\Console\Command;

class MagicTestCommand extends Command
{
    public $signature = 'magic {--filter= : Filter which tests to run}';

    public $description = 'Run your Dusk Test Suite using Magic Test.';

    public function handle()
    {
        $this->line('<info>***</info> Starting a 🧙 <fg=yellow>Magic Test</> session...');

        $filter = $this->option('filter') ? (' --filter ' . $this->option('filter')) : '';
        shell_exec('php artisan dusk --browse' . $filter);
    }
}
