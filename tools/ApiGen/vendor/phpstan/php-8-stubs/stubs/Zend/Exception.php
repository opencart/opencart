<?php 

class Exception implements \Throwable
{
    #[\Until('8.1')]
    private final function __clone() : void
    {
    }
    #[\Since('8.1')]
    private function __clone() : void
    {
    }
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function __wakeup()
    {
    }
    public final function getMessage() : string
    {
    }
    public final function getCode()
    {
    }
    public final function getFile() : string
    {
    }
    public final function getLine() : int
    {
    }
    public final function getTrace() : array
    {
    }
    public final function getPrevious() : ?Throwable
    {
    }
    public final function getTraceAsString() : string
    {
    }
    public function __toString() : string
    {
    }
}