<?php

namespace MagicTest\MagicTest\ShellCommands;

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
        $scriptOutput = (new MagicTestManager)->runScripts();

        $output->write("<info>{$scriptOutput}</info>", true);

        return 0;
    }
}
