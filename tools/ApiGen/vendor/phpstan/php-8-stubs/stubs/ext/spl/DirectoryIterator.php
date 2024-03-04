<?php 

class DirectoryIterator extends \SplFileInfo implements \SeekableIterator
{
    public function __construct(string $directory)
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getFilename()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getExtension()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getBasename(string $suffix = "")
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isDot()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function rewind()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function valid()
    {
    }
    /** @return int|false */
    public function key()
    {
    }
    /** @return DirectoryIterator */
    public function current()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function next()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function seek(int $offset)
    {
    }
    /** @implementation-alias DirectoryIterator::getFilename */
    public function __toString() : string
    {
    }
}