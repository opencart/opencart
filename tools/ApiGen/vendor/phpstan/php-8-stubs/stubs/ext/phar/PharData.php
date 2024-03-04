<?php 

class PharData extends \RecursiveDirectoryIterator implements \Countable, \ArrayAccess
{
    /** @implementation-alias Phar::__construct */
    public function __construct(string $filename, int $flags = FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS, ?string $alias = null, int $format = 0)
    {
    }
    /** @implementation-alias Phar::__destruct */
    public function __destruct()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::addEmptyDir
     * @return void
     */
    public function addEmptyDir(string $directory)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::addFile
     * @return void
     */
    public function addFile(string $filename, ?string $localName = null)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::addFromString
     * @return void
     */
    public function addFromString(string $localName, string $contents)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::buildFromDirectory
     * @return (array | false)
     */
    public function buildFromDirectory(string $directory, string $pattern = "")
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::buildFromIterator
     * @return (array | false)
     */
    public function buildFromIterator(Traversable $iterator, ?string $baseDirectory = null)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::compressFiles
     * @return void
     */
    public function compressFiles(int $compression)
    {
    }
    /**
     * @return bool
     * @implementation-alias Phar::decompressFiles
     */
    public function decompressFiles()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::compress
     * @no-verify
     * @return (PharData | null)
     */
    public function compress(int $compression, ?string $extension = null)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::decompress
     * @no-verify
     * @return (PharData | null)
     */
    public function decompress(?string $extension = null)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::convertToExecutable
     * @return (Phar | null)
     */
    public function convertToExecutable(?int $format = null, ?int $compression = null, ?string $extension = null)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::convertToData
     * @return (PharData | null)
     */
    public function convertToData(?int $format = null, ?int $compression = null, ?string $extension = null)
    {
    }
    /**
     * @return bool
     * @implementation-alias Phar::copy
     */
    public function copy(string $from, string $to)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::count
     * @return int
     */
    public function count(int $mode = COUNT_NORMAL)
    {
    }
    /**
     * @return bool
     * @implementation-alias Phar::delete
     */
    public function delete(string $localName)
    {
    }
    /**
     * @return bool
     * @implementation-alias Phar::delMetadata
     */
    public function delMetadata()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::extractTo
     * @return bool
     */
    public function extractTo(string $directory, array|string|null $files = null, bool $overwrite = false)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::getAlias
     * @return (string | null)
     */
    public function getAlias()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::getPath
     * @return string
     */
    public function getPath()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::getMetadata
     * @return mixed
     */
    public function getMetadata(array $unserializeOptions = [])
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::getModified
     * @return bool
     */
    public function getModified()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::getSignature
     * @return (array | false)
     */
    public function getSignature()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::getStub
     * @return string
     */
    public function getStub()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::getVersion
     * @return string
     */
    public function getVersion()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::hasMetadata
     * @return bool
     */
    public function hasMetadata()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::isBuffering
     * @return bool
     */
    public function isBuffering()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::isCompressed
     * @return (int | false)
     */
    public function isCompressed()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::isFileFormat
     * @return bool
     */
    public function isFileFormat(int $format)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::isWritable
     * @return bool
     */
    public function isWritable()
    {
    }
    /**
     * @param string $localName
     * @tentative-return-type
     * @implementation-alias Phar::offsetExists
     * @return bool
     */
    public function offsetExists($localName)
    {
    }
    /**
     * @param string $localName
     * @tentative-return-type
     * @implementation-alias Phar::offsetGet
     * @return PharFileInfo
     */
    public function offsetGet($localName)
    {
    }
    /**
     * @param string $localName
     * @param (resource | string) $value
     * @tentative-return-type
     * @implementation-alias Phar::offsetSet
     * @return void
     */
    public function offsetSet($localName, $value)
    {
    }
    /**
     * @param string $localName
     * @tentative-return-type
     * @implementation-alias Phar::offsetUnset
     * @return bool
     */
    public function offsetUnset($localName)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::setAlias
     * @return bool
     */
    public function setAlias(string $alias)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::setDefaultStub
     * @return bool
     */
    public function setDefaultStub(?string $index = null, ?string $webIndex = null)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::setMetadata
     * @return void
     */
    public function setMetadata(mixed $metadata)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::setSignatureAlgorithm
     * @return void
     */
    public function setSignatureAlgorithm(int $algo, ?string $privateKey = null)
    {
    }
    /**
     * @param resource|string $stub
     * @return bool
     * @implementation-alias Phar::setStub
     */
    public function setStub($stub, int $length = UNKNOWN)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::startBuffering
     * @return void
     */
    public function startBuffering()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias Phar::stopBuffering
     * @return void
     */
    public function stopBuffering()
    {
    }
    /** @implementation-alias Phar::apiVersion */
    public static final function apiVersion() : string
    {
    }
    /** @implementation-alias Phar::canCompress */
    public static final function canCompress(int $compression = 0) : bool
    {
    }
    /** @implementation-alias Phar::canWrite */
    public static final function canWrite() : bool
    {
    }
    /** @implementation-alias Phar::createDefaultStub */
    public static final function createDefaultStub(?string $index = null, ?string $webIndex = null) : string
    {
    }
    /** @implementation-alias Phar::getSupportedCompression */
    public static final function getSupportedCompression() : array
    {
    }
    /** @implementation-alias Phar::getSupportedSignatures */
    public static final function getSupportedSignatures() : array
    {
    }
    /** @implementation-alias Phar::interceptFileFuncs */
    public static final function interceptFileFuncs() : void
    {
    }
    /** @implementation-alias Phar::isValidPharFilename */
    public static final function isValidPharFilename(string $filename, bool $executable = true) : bool
    {
    }
    /** @implementation-alias Phar::loadPhar */
    public static final function loadPhar(string $filename, ?string $alias = null) : bool
    {
    }
    /** @implementation-alias Phar::mapPhar */
    public static final function mapPhar(?string $alias = null, int $offset = 0) : bool
    {
    }
    /** @implementation-alias Phar::running */
    public static final function running(bool $returnPhar = true) : string
    {
    }
    /** @implementation-alias Phar::mount */
    public static final function mount(string $pharPath, string $externalPath) : void
    {
    }
    /** @implementation-alias Phar::mungServer */
    public static final function mungServer(array $variables) : void
    {
    }
    /** @implementation-alias Phar::unlinkArchive */
    public static final function unlinkArchive(string $filename) : bool
    {
    }
    /** @implementation-alias Phar::webPhar */
    public static final function webPhar(?string $alias = null, ?string $index = null, ?string $fileNotFoundScript = null, array $mimeTypes = [], ?callable $rewrite = null) : void
    {
    }
}