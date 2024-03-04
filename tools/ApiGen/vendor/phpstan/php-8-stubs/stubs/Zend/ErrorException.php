<?php 

class ErrorException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, int $severity = E_ERROR, ?string $filename = null, ?int $line = null, ?Throwable $previous = null)
    {
    }
    public final function getSeverity() : int
    {
    }
}