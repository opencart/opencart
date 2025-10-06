<?php namespace Todaymade\Daux\Console;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Terminal;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\Daux;

class Generate extends DauxCommand
{
    protected function configure()
    {
        parent::configure();

        $description = 'Destination folder, relative to the working directory';

        $this
            ->setName('generate')
            ->setDescription('Generate documentation')

            ->addOption('format', 'f', InputOption::VALUE_REQUIRED, 'Output format, html or confluence', 'html')

            // Confluence format only
            ->addOption('delete', null, InputOption::VALUE_NONE, 'Delete pages not linked to a documentation page (confluence)')
            ->addOption('printDiffAndExit', null, InputOption::VALUE_NONE, 'Print the differences between local and remote and exit')

            ->addOption('destination', 'd', InputOption::VALUE_REQUIRED, $description, 'static');
    }

    protected function prepareConfig($mode, InputInterface $input, OutputInterface $output): ConfigBuilder
    {
        $builder = parent::prepareConfig($mode, $input, $output);

        // Set the format if requested
        if ($input->hasOption('format') && $input->getOption('format')) {
            $builder->withFormat($input->getOption('format'));
        }

        if ($input->hasOption('delete') && $input->getOption('delete')) {
            $builder->withConfluenceDelete(true);
        }

        if ($input->hasOption('printDiffAndExit') && $input->getOption('printDiffAndExit')) {
            $builder->withConfluencePrintDiff(true);
        }

        return $builder;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // When used as a default command,
        // Symfony doesn't read the default parameters.
        // This will parse the parameters
        if ($input instanceof ArrayInput) {
            $argv = $_SERVER['argv'];
            $argv[0] = $this->getName();
            array_unshift($argv, 'binary_name');

            $input = new ArgvInput($argv, $this->getDefinition());
        }

        $builder = $this->prepareConfig(Daux::STATIC_MODE, $input, $output);
        $daux = new Daux($builder->build(), $output);

        $width = (new Terminal())->getWidth();

        // Instiantiate the processor if one is defined
        $this->prepareProcessor($daux, $width);

        // Generate the tree
        $daux->generateTree();

        // Generate the documentation
        $daux->getGenerator()->generateAll($input, $output, $width);

        return 0;
    }
}
