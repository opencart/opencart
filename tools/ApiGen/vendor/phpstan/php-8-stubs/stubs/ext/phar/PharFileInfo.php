<?php 

class PharFileInfo extends \SplFileInfo
{
    public function __construct(string $filename)
    {
    }
    public function __destruct()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function chmod(int $perms)
    {
    }
    /** @return bool */
    public function compress(int $compression)
    {
    }
    /** @return bool */
    public function decompress()
    {
    }
    /** @return bool */
    public function delMetadata()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function getCompressedSize()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function getCRC32()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getContent()
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function getMetadata(array $unserializeOptions = [])
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function getPharFlags()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function hasMetadata()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isCompressed(?int $compression = null)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isCRCChecked()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function setMetadata(mixed $metadata)
    {
    }
}