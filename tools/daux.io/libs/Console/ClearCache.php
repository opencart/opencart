<?php namespace Todaymade\Daux\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Cache;

class ClearCache extends SymfonyCommand
{
    protected function configure()
    {
        $this
            ->setName('clear-cache')
            ->setDescription('Clears the cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Clearing cache at '" . Cache::getDirectory() . "'");
        Cache::clear();
        $output->writeln('<info>Cache cleared</info>');

        return 0;
    }
}
