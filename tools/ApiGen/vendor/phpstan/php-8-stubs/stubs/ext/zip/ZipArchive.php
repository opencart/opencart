<?php 

class ZipArchive
{
    /**
     * @tentative-return-type
     * @return (bool | int)
     */
    public function open(string $filename, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setPassword(string $password)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function close()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function count()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getStatusString()
    {
    }
    #[\Since('8.2')]
    public function clearError() : void
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function addEmptyDir(string $dirname, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function addFromString(string $name, string $content, int $flags = ZipArchive::FL_OVERWRITE)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function addFile(string $filepath, string $entryname = "", int $start = 0, int $length = 0, int $flags = ZipArchive::FL_OVERWRITE)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function replaceFile(string $filepath, int $index, int $start = 0, int $length = 0, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return (array | false)
     */
    public function addGlob(string $pattern, int $flags = 0, array $options = [])
    {
    }
    /**
     * @tentative-return-type
     * @return (array | false)
     */
    public function addPattern(string $pattern, string $path = ".", array $options = [])
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function renameIndex(int $index, string $new_name)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function renameName(string $name, string $new_name)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setArchiveComment(string $comment)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function getArchiveComment(int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setCommentIndex(int $index, string $comment)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setCommentName(string $name, string $comment)
    {
    }
    #ifdef HAVE_SET_MTIME
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setMtimeIndex(int $index, int $timestamp, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setMtimeName(string $name, int $timestamp, int $flags = 0)
    {
    }
    #endif
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function getCommentIndex(int $index, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function getCommentName(string $name, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function deleteIndex(int $index)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function deleteName(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @return (array | false)
     */
    public function statName(string $name, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return (array | false)
     */
    public function statIndex(int $index, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function locateName(string $name, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function getNameIndex(int $index, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function unchangeArchive()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function unchangeAll()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function unchangeIndex(int $index)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function unchangeName(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function extractTo(string $pathto, array|string|null $files = null)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function getFromName(string $name, int $len = 0, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function getFromIndex(int $index, int $len = 0, int $flags = 0)
    {
    }
    /** @return resource|false */
    #[\Since('8.2')]
    public function getStreamIndex(int $index, int $flags = 0)
    {
    }
    /** @return resource|false */
    #[\Since('8.2')]
    public function getStreamName(string $name, int $flags = 0)
    {
    }
    /** @return resource|false */
    public function getStream(string $name)
    {
    }
    #ifdef ZIP_OPSYS_DEFAULT
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setExternalAttributesName(string $name, int $opsys, int $attr, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setExternalAttributesIndex(int $index, int $opsys, int $attr, int $flags = 0)
    {
    }
    /**
     * @param int $opsys
     * @param int $attr
     * @tentative-return-type
     * @return bool
     */
    public function getExternalAttributesName(string $name, &$opsys, &$attr, int $flags = 0)
    {
    }
    /**
     * @param int $opsys
     * @param int $attr
     * @tentative-return-type
     * @return bool
     */
    public function getExternalAttributesIndex(int $index, &$opsys, &$attr, int $flags = 0)
    {
    }
    #endif
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setCompressionName(string $name, int $method, int $compflags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setCompressionIndex(int $index, int $method, int $compflags = 0)
    {
    }
    #ifdef HAVE_ENCRYPTION
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setEncryptionName(string $name, int $method, ?string $password = null)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setEncryptionIndex(int $index, int $method, ?string $password = null)
    {
    }
    #endif
    #ifdef HAVE_PROGRESS_CALLBACK
    /**
     * @tentative-return-type
     * @return bool
     */
    public function registerProgressCallback(float $rate, callable $callback)
    {
    }
    #endif
    #ifdef HAVE_CANCEL_CALLBACK
    /**
     * @tentative-return-type
     * @return bool
     */
    public function registerCancelCallback(callable $callback)
    {
    }
    #endif
    #ifdef HAVE_METHOD_SUPPORTED
    /** @return bool */
    public static function isCompressionMethodSupported(int $method, bool $enc = true) : bool
    {
    }
    /** @return bool */
    public static function isEncryptionMethodSupported(int $method, bool $enc = true) : bool
    {
    }
}