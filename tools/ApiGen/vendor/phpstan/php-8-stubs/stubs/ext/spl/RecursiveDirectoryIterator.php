<?php 

class RecursiveDirectoryIterator extends \FilesystemIterator implements \RecursiveIterator
{
    public function __construct(string $directory, int $flags = FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function hasChildren(bool $allowLinks = false)
    {
    }
    /**
     * @tentative-return-type
     * @return RecursiveDirectoryIterator
     */
    public function getChildren()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getSubPath()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getSubPathname()
    {
    }
}