<?php
namespace GuzzleHttp\Subscriber\Log;

use Psr\Log\LoggerTrait;
use Psr\Log\LoggerInterface;

/**
 * Simple logger implementation that can write to a function, resource, or
 * uses echo() if nothing is provided.
 */
class SimpleLogger implements LoggerInterface
{
    use LoggerTrait;

    private $writeTo;

    public function __construct($writeTo = null)
    {
        $this->writeTo = $writeTo;
    }

    public function log($level, $message, array $context = array())
    {
        if (is_resource($this->writeTo)) {
            fwrite($this->writeTo, "[{$level}] {$message}\n");
        } elseif (is_callable($this->writeTo)) {
            call_user_func($this->writeTo, "[{$level}] {$message}\n");
        } else {
            echo "[{$level}] {$message}\n";
        }
    }
}
