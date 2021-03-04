<?php

namespace MagicTest\MagicTest\Commands;

use MagicTest\MagicTest\MagicTestManager;
use Psy\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Finish extends Command
{
    protected function configure()
    {
        $this->setName('finish')
            ->setDefinition([]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write(app(MagicTestManager::class)->finish(), true);

        return 0;
    }
}
