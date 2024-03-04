<?php 

class SplTempFileObject extends \SplFileObject
{
    public function __construct(int $maxMemory = 2 * 1024 * 1024)
    {
    }
}