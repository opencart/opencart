<?php namespace Todaymade\Daux\Console;

use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Daux;

trait RunAction
{
    protected function getLength($content)
    {
        return function_exists('mb_strlen') ? mb_strlen($content) : strlen($content);
    }

    protected function runAction($title, $width, \Closure $closure)
    {
        $verbose = Daux::getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE;

        Daux::write($title, $verbose);

        // 8 is the length of the label + 2 let it breathe
        $padding = $width - $this->getLength($title) - 10;

        try {
            $response = $closure(function ($content) use (&$padding, $verbose) {
                $padding -= $this->getLength($content);
                Daux::write($content, $verbose);
            });
        } catch (\Exception $e) {
            $this->status($padding, '[ <fg=red>FAIL</fg=red> ]');

            throw $e;
        }
        $this->status($padding, '[  <fg=green>OK</fg=green>  ]');

        return $response;
    }

    protected function status($padding, $content)
    {
        $verbose = Daux::getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE;
        $padding = $verbose ? '' : str_pad(' ', $padding);
        Daux::writeln($padding . $content);
    }
}
