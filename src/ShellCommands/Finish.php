<?php

namespace MagicTest\MagicTest\ShellCommands;

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
        $actionOutput = (new MagicTestManager)->finish();

        $output->write("<info>{$actionOutput}</info>", true);

        return 0;
    }
}
