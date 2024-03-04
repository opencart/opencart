<?php 

/** @generate-function-entries */
class SplFileInfo
{
    public function __construct(string $filename)
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getPath()
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
     * @return string
     */
    public function getPathname()
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function getPerms()
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function getInode()
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function getSize()
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function getOwner()
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function getGroup()
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function getATime()
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function getMTime()
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function getCTime()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function getType()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isWritable()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isReadable()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isExecutable()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isFile()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isDir()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isLink()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function getLinkTarget()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function getRealPath()
    {
    }
    /**
     * @tentative-return-type
     * @return SplFileInfo
     */
    public function getFileInfo(?string $class = null)
    {
    }
    /**
     * @tentative-return-type
     * @return (SplFileInfo | null)
     */
    public function getPathInfo(?string $class = null)
    {
    }
    /**
     * @param (resource | null) $context
     * @tentative-return-type
     * @return SplFileObject
     */
    public function openFile(string $mode = "r", bool $useIncludePath = false, $context = null)
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function setFileClass(string $class = SplFileObject::class)
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function setInfoClass(string $class = SplFileInfo::class)
    {
    }
    /** @implementation-alias SplFileInfo::getPathname */
    public function __toString() : string
    {
    }
    /**
     * @tentative-return-type
     * @return array
     */
    public function __debugInfo()
    {
    }
    /**
     * @deprecated
     * @tentative-return-type
     * @return void
     */
    public final function _bad_state_ex()
    {
    }
}