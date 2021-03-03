<?php

namespace MagicTest\MagicTest\Commands;

use MagicTest\MagicTest\MagicTestManager;
use Psy\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Ok extends Command
{
    protected function configure()
    {
        $this->setName('ok')
            ->setDefinition([]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write(app(MagicTestManager::class)->runScripts(), true);

        return 0;
    }
}
