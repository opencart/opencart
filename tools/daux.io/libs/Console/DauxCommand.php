<?php namespace Todaymade\Daux\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\Daux;

class DauxCommand extends SymfonyCommand
{
    protected function configure()
    {
        $this
            ->addOption('configuration', 'c', InputOption::VALUE_REQUIRED, 'Configuration file')
            ->addOption('source', 's', InputOption::VALUE_REQUIRED, 'Where to take the documentation from')
            ->addOption('processor', 'p', InputOption::VALUE_REQUIRED, 'Manipulations on the tree')
            ->addOption('no-cache', null, InputOption::VALUE_NONE, 'Disable Cache')
            ->addOption('themes', 't', InputOption::VALUE_REQUIRED, 'Set a different themes directory (Used by HTML format only)')
            ->addOption('value', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Set different configuration values');
    }

    protected function prepareConfig($mode, InputInterface $input, OutputInterface $output): ConfigBuilder
    {
        $builder = ConfigBuilder::withMode($mode);

        if ($input->getOption('configuration')) {
            $builder->withConfigurationOverride($input->getOption('configuration'));
        }

        if ($input->getOption('source')) {
            $builder->withDocumentationDirectory($input->getOption('source'));
        }

        if ($input->getOption('processor')) {
            $builder->withProcessor($input->getOption('processor'));
        }

        if ($input->getOption('no-cache')) {
            $builder->withCache(false);
        }

        if ($input->getOption('themes')) {
            $builder->withThemesDirectory($input->getOption('themes'));
        }

        if ($input->hasOption('value')) {
            $values = array_map(
                function ($value) {
                    return array_map('trim', explode('=', $value));
                },
                $input->getOption('value')
            );
            $builder->withValues($values);
        }

        return $builder;
    }

    protected function prepareProcessor(Daux $daux, $width)
    {
        $class = $daux->getProcessorClass();
        if (!empty($class)) {
            $daux->setProcessor(new $class($daux, $daux->getOutput(), $width));
        }
    }
}
