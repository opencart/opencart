<?php 

#ifdef HAVE_GLOB
class GlobIterator extends \FilesystemIterator implements \Countable
{
    public function __construct(string $pattern, int $flags = FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO)
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function count()
    {
    }
}