<?php namespace Todaymade\Daux\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Terminal;
use Symfony\Component\Process\PhpExecutableFinder;
use Todaymade\Daux\Daux;

class Serve extends DauxCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('serve')
            ->setDescription('Serve documentation')

            ->addOption('host', null, InputOption::VALUE_REQUIRED, 'The host to serve on', 'localhost')
            ->addOption('port', null, InputOption::VALUE_REQUIRED, 'The port to serve on', 8085);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $input->getOption('host');
        $port = $input->getOption('port');

        $builder = $this->prepareConfig(Daux::LIVE_MODE, $input, $output);

        // Daux can only serve HTML
        $builder->withFormat('html');

        $daux = new Daux($builder->build(), $output);

        $width = (new Terminal())->getWidth();

        // Instiantiate the processor if one is defined
        $this->prepareProcessor($daux, $width);

        // Write the current configuration to a file to read it from the other serving side
        $file = tmpfile();

        if ($file === false) {
            $output->writeln('<fg=red>Failed to create temporary file for configuration</fg=red>');

            return 1;
        }

        $path = stream_get_meta_data($file)['uri'];
        fwrite($file, serialize($daux->getConfig()));

        chdir(__DIR__ . '/../../');

        putenv('DAUX_CONFIG=' . $path);
        putenv('DAUX_VERBOSITY=' . $output->getVerbosity());
        putenv('DAUX_EXTENSION=' . DAUX_EXTENSION);

        $base = escapeshellarg(__DIR__ . '/../../');
        $binary = escapeshellarg((new PhpExecutableFinder())->find(false));

        echo "Daux development server started on http://{$host}:{$port}/\n";

        passthru("{$binary} -S {$host}:{$port} {$base}/index.php");
        fclose($file);

        return 0;
    }
}
