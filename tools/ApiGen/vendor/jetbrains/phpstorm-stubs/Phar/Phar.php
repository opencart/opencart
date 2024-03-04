<?php
// Start of Phar v.2.0.1

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;

/**
 * The PharException class provides a phar-specific exception class
 * for try/catch blocks.
 * @link https://php.net/manual/en/class.pharexception.php
 */
class PharException extends Exception {}

/**
 * The Phar class provides a high-level interface to accessing and creating
 * phar archives.
 * @link https://php.net/manual/en/class.phar.php
 */
class Phar extends RecursiveDirectoryIterator implements RecursiveIterator, SeekableIterator, Countable, ArrayAccess
{
    public const BZ2 = 8192;
    public const GZ = 4096;
    public const NONE = 0;
    public const PHAR = 1;
    public const TAR = 2;
    public const ZIP = 3;
    public const COMPRESSED = 61440;
    public const PHP = 0;
    public const PHPS = 1;
    public const MD5 = 1;
    public const OPENSSL = 16;
    public const SHA1 = 2;
    public const SHA256 = 3;
    public const SHA512 = 4;
    public const OPENSSL_SHA256 = 5;
    public const OPENSSL_SHA512 = 6;

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Construct a Phar archive object
     * @link https://php.net/manual/en/phar.construct.php
     * @param string $filename <p>
     * Path to an existing Phar archive or to-be-created archive. The file name's
     * extension must contain .phar.
     * </p>
     * @param int $flags [optional] <p>
     * Flags to pass to parent class <b>RecursiveDirectoryIterator</b>.
     * </p>
     * @param string $alias [optional] <p>
     * Alias with which this Phar archive should be referred to in calls to stream
     * functionality.
     * </p>
     * @throws BadMethodCallException If called twice.
     * @throws UnexpectedValueException If the phar archive can't be opened.
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $filename,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = FilesystemIterator::KEY_AS_PATHNAME|FilesystemIterator::CURRENT_AS_FILEINFO,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $alias = null,
        #[PhpStormStubsElementAvailable(from: '5.3', to: '5.6')] $fileformat = null
    ) {}

    public function __destruct() {}

    /**
     * (Unknown)<br/>
     * Add an empty directory to the phar archive
     * @link https://php.net/manual/en/phar.addemptydir.php
     * @param string $directory <p>
     * The name of the empty directory to create in the phar archive
     * </p>
     * @return void no return value, exception is thrown on failure.
     */
    #[TentativeType]
    public function addEmptyDir(
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $directory = '',
        #[PhpStormStubsElementAvailable(from: '8.0')] string $directory
    ): void {}

    /**
     * (Unknown)<br/>
     * Add a file from the filesystem to the phar archive
     * @link https://php.net/manual/en/phar.addfile.php
     * @param string $filename <p>
     * Full or relative path to a file on disk to be added
     * to the phar archive.
     * </p>
     * @param string $localName [optional] <p>
     * Path that the file will be stored in the archive.
     * </p>
     * @return void no return value, exception is thrown on failure.
     */
    #[TentativeType]
    public function addFile(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $filename,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $localName = null
    ): void {}

    /**
     * (Unknown)<br/>
     * Add a file from the filesystem to the phar archive
     * @link https://php.net/manual/en/phar.addfromstring.php
     * @param string $localName <p>
     * Path that the file will be stored in the archive.
     * </p>
     * @param string $contents <p>
     * The file contents to store
     * </p>
     * @return void no return value, exception is thrown on failure.
     */
    #[TentativeType]
    public function addFromString(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $localName,
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $contents = '',
        #[PhpStormStubsElementAvailable(from: '8.0')] string $contents
    ): void {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Construct a phar archive from the files within a directory.
     * @link https://php.net/manual/en/phar.buildfromdirectory.php
     * @param string $directory <p>
     * The full or relative path to the directory that contains all files
     * to add to the archive.
     * </p>
     * @param $pattern $regex [optional] <p>
     * An optional pcre regular expression that is used to filter the
     * list of files. Only file paths matching the regular expression
     * will be included in the archive.
     * </p>
     * @return array <b>Phar::buildFromDirectory</b> returns an associative array
     * mapping internal path of file to the full path of the file on the
     * filesystem.
     */
    #[TentativeType]
    public function buildFromDirectory(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $directory,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $pattern = null
    ): array {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Construct a phar archive from an iterator.
     * @link https://php.net/manual/en/phar.buildfromiterator.php
     * @param Traversable $iterator <p>
     * Any iterator that either associatively maps phar file to location or
     * returns SplFileInfo objects
     * </p>
     * @param string $baseDirectory [optional] <p>
     * For iterators that return SplFileInfo objects, the portion of each
     * file's full path to remove when adding to the phar archive
     * </p>
     * @return array <b>Phar::buildFromIterator</b> returns an associative array
     * mapping internal path of file to the full path of the file on the
     * filesystem.
     */
    #[TentativeType]
    public function buildFromIterator(
        Traversable $iterator,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $baseDirectory = null
    ): array {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Compresses all files in the current Phar archive
     * @link https://php.net/manual/en/phar.compressfiles.php
     * @param int $compression <p>
     * Compression must be one of Phar::GZ,
     * Phar::BZ2 to add compression, or Phar::NONE
     * to remove compression.
     * </p>
     * @return void No value is returned.
     */
    #[TentativeType]
    public function compressFiles(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $compression): void {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Decompresses all files in the current Phar archive
     * @link https://php.net/manual/en/phar.decompressfiles.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function decompressFiles() {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Compresses the entire Phar archive using Gzip or Bzip2 compression
     * @link https://php.net/manual/en/phar.compress.php
     * @param int $compression <p>
     * Compression must be one of Phar::GZ,
     * Phar::BZ2 to add compression, or Phar::NONE
     * to remove compression.
     * </p>
     * @param string $extension [optional] <p>
     * By default, the extension is .phar.gz
     * or .phar.bz2 for compressing phar archives, and
     * .phar.tar.gz or .phar.tar.bz2 for
     * compressing tar archives. For decompressing, the default file extensions
     * are .phar and .phar.tar.
     * </p>
     * @return static|null a <b>Phar</b> object.
     */
    #[TentativeType]
    public function compress(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $compression,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $extension = null
    ): ?Phar {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Decompresses the entire Phar archive
     * @link https://php.net/manual/en/phar.decompress.php
     * @param string $extension [optional] <p>
     * For decompressing, the default file extensions
     * are .phar and .phar.tar.
     * Use this parameter to specify another file extension. Be aware
     * that all executable phar archives must contain .phar
     * in their filename.
     * </p>
     * @return static|null A <b>Phar</b> object is returned.
     */
    #[TentativeType]
    public function decompress(#[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $extension = null): ?Phar {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Convert a phar archive to another executable phar archive file format
     * @link https://php.net/manual/en/phar.converttoexecutable.php
     * @param int $format [optional] <p>
     * This should be one of Phar::PHAR, Phar::TAR,
     * or Phar::ZIP. If set to <b>NULL</b>, the existing file format
     * will be preserved.
     * </p>
     * @param int $compression [optional] <p>
     * This should be one of Phar::NONE for no whole-archive
     * compression, Phar::GZ for zlib-based compression, and
     * Phar::BZ2 for bzip-based compression.
     * </p>
     * @param string $extension [optional] <p>
     * This parameter is used to override the default file extension for a
     * converted archive. Note that all zip- and tar-based phar archives must contain
     * .phar in their file extension in order to be processed as a
     * phar archive.
     * </p>
     * <p>
     * If converting to a phar-based archive, the default extensions are
     * .phar, .phar.gz, or .phar.bz2
     * depending on the specified compression. For tar-based phar archives, the
     * default extensions are .phar.tar, .phar.tar.gz,
     * and .phar.tar.bz2. For zip-based phar archives, the
     * default extension is .phar.zip.
     * </p>
     * @return Phar|null The method returns a <b>Phar</b> object on success and throws an
     * exception on failure.
     */
    #[TentativeType]
    public function convertToExecutable(
        #[LanguageLevelTypeAware(['8.0' => 'int|null'], default: '')] $format = 9021976,
        #[LanguageLevelTypeAware(['8.0' => 'int|null'], default: '')] $compression = 9021976,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $extension = null
    ): ?Phar {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Convert a phar archive to a non-executable tar or zip file
     * @link https://php.net/manual/en/phar.converttodata.php
     * @param int $format [optional] <p>
     * This should be one of Phar::TAR
     * or Phar::ZIP. If set to <b>NULL</b>, the existing file format
     * will be preserved.
     * </p>
     * @param int $compression [optional] <p>
     * This should be one of Phar::NONE for no whole-archive
     * compression, Phar::GZ for zlib-based compression, and
     * Phar::BZ2 for bzip-based compression.
     * </p>
     * @param string $extension [optional] <p>
     * This parameter is used to override the default file extension for a
     * converted archive. Note that .phar cannot be used
     * anywhere in the filename for a non-executable tar or zip archive.
     * </p>
     * <p>
     * If converting to a tar-based phar archive, the
     * default extensions are .tar, .tar.gz,
     * and .tar.bz2 depending on specified compression.
     * For zip-based archives, the
     * default extension is .zip.
     * </p>
     * @return PharData|null The method returns a <b>PharData</b> object on success and throws an
     * exception on failure.
     */
    #[TentativeType]
    public function convertToData(
        #[LanguageLevelTypeAware(['8.0' => 'int|null'], default: '')] $format = 9021976,
        #[LanguageLevelTypeAware(['8.0' => 'int|null'], default: '')] $compression = 9021976,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $extension = null
    ): ?PharData {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Copy a file internal to the phar archive to another new file within the phar
     * @link https://php.net/manual/en/phar.copy.php
     * @param string $to
     * @param string $from
     * @return bool returns <b>TRUE</b> on success, but it is safer to encase method call in a
     * try/catch block and assume success if no exception is thrown.
     */
    public function copy(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $to,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $from
    ) {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Returns the number of entries (files) in the Phar archive
     * @link https://php.net/manual/en/phar.count.php
     * @param int $mode [optional]
     * @return int<0,max> The number of files contained within this phar, or 0 (the number zero)
     * if none.
     */
    #[TentativeType]
    public function count(#[PhpStormStubsElementAvailable(from: '8.0')] int $mode = COUNT_NORMAL): int {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Delete a file within a phar archive
     * @link https://php.net/manual/en/phar.delete.php
     * @param string $localName <p>
     * Path within an archive to the file to delete.
     * </p>
     * @return bool returns <b>TRUE</b> on success, but it is better to check for thrown exception,
     * and assume success if none is thrown.
     */
    public function delete(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $localName) {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.2.0)<br/>
     * Deletes the global metadata of the phar
     * @link https://php.net/manual/en/phar.delmetadata.php
     * @return bool returns <b>TRUE</b> on success, but it is better to check for thrown exception,
     * and assume success if none is thrown.
     */
    public function delMetadata() {}

    /**
     * (Unknown)<br/>
     * Extract the contents of a phar archive to a directory
     * @link https://php.net/manual/en/phar.extractto.php
     * @param string $directory <p>
     * Path within an archive to the file to delete.
     * </p>
     * @param string|array|null $files [optional] <p>
     * The name of a file or directory to extract, or an array of files/directories to extract
     * </p>
     * @param bool $overwrite [optional] <p>
     * Set to <b>TRUE</b> to enable overwriting existing files
     * </p>
     * @return bool returns <b>TRUE</b> on success, but it is better to check for thrown exception,
     * and assume success if none is thrown.
     */
    #[TentativeType]
    public function extractTo(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $directory,
        #[LanguageLevelTypeAware(['8.0' => 'array|string|null'], default: '')] $files = null,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $overwrite = false
    ): bool {}

    /**
     * @return string|null
     * @see setAlias
     */
    #[TentativeType]
    public function getAlias(): ?string {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Returns phar archive meta-data
     * @link https://php.net/manual/en/phar.getmetadata.php
     * @param array $unserializeOptions [optional] if is set to anything other than the default,
     * the resulting metadata won't be cached and this won't return the value from the cache
     * @return mixed any PHP variable that can be serialized and is stored as meta-data for the Phar archive,
     * or <b>NULL</b> if no meta-data is stored.
     */
    #[TentativeType]
    public function getMetadata(#[PhpStormStubsElementAvailable(from: '8.0')] array $unserializeOptions = []): mixed {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Return whether phar was modified
     * @link https://php.net/manual/en/phar.getmodified.php
     * @return bool <b>TRUE</b> if the phar has been modified since opened, <b>FALSE</b> if not.
     */
    #[TentativeType]
    public function getModified(): bool {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Return MD5/SHA1/SHA256/SHA512/OpenSSL signature of a Phar archive
     * @link https://php.net/manual/en/phar.getsignature.php
     * @return array Array with the opened archive's signature in hash key and MD5,
     * SHA-1,
     * SHA-256, SHA-512, or OpenSSL
     * in hash_type. This signature is a hash calculated on the
     * entire phar's contents, and may be used to verify the integrity of the archive.
     * A valid signature is absolutely required of all executable phar archives if the
     * phar.require_hash INI variable
     * is set to true.
     */
    #[ArrayShape(["hash" => "string", "hash_type" => "string"])]
    #[TentativeType]
    public function getSignature(): array|false {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Return the PHP loader or bootstrap stub of a Phar archive
     * @link https://php.net/manual/en/phar.getstub.php
     * @return string a string containing the contents of the bootstrap loader (stub) of
     * the current Phar archive.
     */
    #[TentativeType]
    public function getStub(): string {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Return version info of Phar archive
     * @link https://php.net/manual/en/phar.getversion.php
     * @return string The opened archive's API version. This is not to be confused with
     * the API version that the loaded phar extension will use to create
     * new phars. Each Phar archive has the API version hard-coded into
     * its manifest. See Phar file format
     * documentation for more information.
     */
    #[TentativeType]
    public function getVersion(): string {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.2.0)<br/>
     * Returns whether phar has global meta-data
     * @link https://php.net/manual/en/phar.hasmetadata.php
     * @return bool <b>TRUE</b> if meta-data has been set, and <b>FALSE</b> if not.
     */
    #[TentativeType]
    public function hasMetadata(): bool {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Used to determine whether Phar write operations are being buffered, or are flushing directly to disk
     * @link https://php.net/manual/en/phar.isbuffering.php
     * @return bool <b>TRUE</b> if the write operations are being buffer, <b>FALSE</b> otherwise.
     */
    #[TentativeType]
    public function isBuffering(): bool {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Returns Phar::GZ or PHAR::BZ2 if the entire phar archive is compressed (.tar.gz/tar.bz and so on)
     * @link https://php.net/manual/en/phar.iscompressed.php
     * @return mixed Phar::GZ, Phar::BZ2 or <b>FALSE</b>
     */
    #[TentativeType]
    public function isCompressed(): int|false {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Returns true if the phar archive is based on the tar/phar/zip file format depending on the parameter
     * @link https://php.net/manual/en/phar.isfileformat.php
     * @param int $format <p>
     * Either Phar::PHAR, Phar::TAR, or
     * Phar::ZIP to test for the format of the archive.
     * </p>
     * @return bool <b>TRUE</b> if the phar archive matches the file format requested by the parameter
     */
    #[TentativeType]
    public function isFileFormat(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $format): bool {}

    /**
     * (Unknown)<br/>
     * Returns true if the phar archive can be modified
     * @link https://php.net/manual/en/phar.iswritable.php
     * @return bool <b>TRUE</b> if the phar archive can be modified
     */
    #[TentativeType]
    public function isWritable(): bool {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * determines whether a file exists in the phar
     * @link https://php.net/manual/en/phar.offsetexists.php
     * @param string $localName <p>
     * The filename (relative path) to look for in a Phar.
     * </p>
     * @return bool <b>TRUE</b> if the file exists within the phar, or <b>FALSE</b> if not.
     */
    #[TentativeType]
    public function offsetExists($localName): bool {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Gets a <b>PharFileInfo</b> object for a specific file
     * @link https://php.net/manual/en/phar.offsetget.php
     * @param string $localName <p>
     * The filename (relative path) to look for in a Phar.
     * </p>
     * @return PharFileInfo A <b>PharFileInfo</b> object is returned that can be used to
     * iterate over a file's contents or to retrieve information about the current file.
     */
    #[TentativeType]
    public function offsetGet($localName): SplFileInfo {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * set the contents of an internal file to those of an external file
     * @link https://php.net/manual/en/phar.offsetset.php
     * @param string $localName <p>
     * The filename (relative path) to modify in a Phar.
     * </p>
     * @param string $value <p>
     * Content of the file.
     * </p>
     * @return void No return values.
     */
    #[TentativeType]
    public function offsetSet($localName, $value): void {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * remove a file from a phar
     * @link https://php.net/manual/en/phar.offsetunset.php
     * @param string $localName <p>
     * The filename (relative path) to modify in a Phar.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function offsetUnset($localName): void {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.2.1)<br/>
     * Set the alias for the Phar archive
     * @link https://php.net/manual/en/phar.setalias.php
     * @param string $alias <p>
     * A shorthand string that this archive can be referred to in phar
     * stream wrapper access.
     * </p>
     * @return bool
     */
    #[TentativeType]
    public function setAlias(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $alias): bool {}

    /**
     * (Unknown)<br/>
     * Used to set the PHP loader or bootstrap stub of a Phar archive to the default loader
     * @link https://php.net/manual/en/phar.setdefaultstub.php
     * @param string $index [optional] <p>
     * Relative path within the phar archive to run if accessed on the command-line
     * </p>
     * @param string $webIndex [optional] <p>
     * Relative path within the phar archive to run if accessed through a web browser
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function setDefaultStub(
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $index = null,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $webIndex = null
    ): bool {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Sets phar archive meta-data
     * @link https://php.net/manual/en/phar.setmetadata.php
     * @param mixed $metadata <p>
     * Any PHP variable containing information to store that describes the phar archive
     * </p>
     * @return void No value is returned.
     */
    #[TentativeType]
    public function setMetadata(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $metadata): void {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.1.0)<br/>
     * set the signature algorithm for a phar and apply it.
     * @link https://php.net/manual/en/phar.setsignaturealgorithm.php
     * @param int $algo <p>
     * One of Phar::MD5,
     * Phar::SHA1, Phar::SHA256,
     * Phar::SHA512, or Phar::OPENSSL
     * </p>
     * @param string $privateKey [optional] <p>
     * The contents of an OpenSSL private key, as extracted from a certificate or
     * OpenSSL key file:
     * <code>
     * $private = openssl_get_privatekey(file_get_contents('private.pem'));
     * $pkey = '';
     * openssl_pkey_export($private, $pkey);
     * $p->setSignatureAlgorithm(Phar::OPENSSL, $pkey);
     * </code>
     * See phar introduction for instructions on
     * naming and placement of the public key file.
     * </p>
     * @return void No value is returned.
     */
    #[TentativeType]
    public function setSignatureAlgorithm(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $algo,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $privateKey = null
    ): void {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Used to set the PHP loader or bootstrap stub of a Phar archive
     * @link https://php.net/manual/en/phar.setstub.php
     * @param string $stub <p>
     * A string or an open stream handle to use as the executable stub for this
     * phar archive.
     * </p>
     * @param int $length [optional] <p>
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function setStub(
        $stub,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $length = -1
    ) {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Start buffering Phar write operations, do not modify the Phar object on disk
     * @link https://php.net/manual/en/phar.startbuffering.php
     * @return void No value is returned.
     */
    #[TentativeType]
    public function startBuffering(): void {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Stop buffering write requests to the Phar archive, and save changes to disk
     * @link https://php.net/manual/en/phar.stopbuffering.php
     * @return void No value is returned.
     */
    #[TentativeType]
    public function stopBuffering(): void {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Returns the api version
     * @link https://php.net/manual/en/phar.apiversion.php
     * @return string The API version string as in &#x00022;1.0.0&#x00022;.
     */
    final public static function apiVersion(): string {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Returns whether phar extension supports compression using either zlib or bzip2
     * @link https://php.net/manual/en/phar.cancompress.php
     * @param int $compression [optional] <p>
     * Either Phar::GZ or Phar::BZ2 can be
     * used to test whether compression is possible with a specific compression
     * algorithm (zlib or bzip2).
     * </p>
     * @return bool <b>TRUE</b> if compression/decompression is available, <b>FALSE</b> if not.
     */
    final public static function canCompress(int $compression = 0): bool {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Returns whether phar extension supports writing and creating phars
     * @link https://php.net/manual/en/phar.canwrite.php
     * @return bool <b>TRUE</b> if write access is enabled, <b>FALSE</b> if it is disabled.
     */
    final public static function canWrite(): bool {}

    /**
     * (Unknown)<br/>
     * Create a phar-file format specific stub
     * @link https://php.net/manual/en/phar.createdefaultstub.php
     * @param string|null $index [optional]
     * @param string|null $webIndex [optional]
     * @return string a string containing the contents of a customized bootstrap loader (stub)
     * that allows the created Phar archive to work with or without the Phar extension
     * enabled.
     */
    final public static function createDefaultStub(?string $index = null, ?string $webIndex = null): string {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.2.0)<br/>
     * Return array of supported compression algorithms
     * @link https://php.net/manual/en/phar.getsupportedcompression.php
     * @return string[] an array containing any of "GZ" or
     * "BZ2", depending on the availability of
     * the zlib extension or the
     * bz2 extension.
     */
    final public static function getSupportedCompression(): array {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.1.0)<br/>
     * Return array of supported signature types
     * @link https://php.net/manual/en/phar.getsupportedsignatures.php
     * @return string[] an array containing any of "MD5", "SHA-1",
     * "SHA-256", "SHA-512", or "OpenSSL".
     */
    final public static function getSupportedSignatures(): array {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * instructs phar to intercept fopen, file_get_contents, opendir, and all of the stat-related functions
     * @link https://php.net/manual/en/phar.interceptfilefuncs.php
     * @return void
     */
    final public static function interceptFileFuncs(): void {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.2.0)<br/>
     * Returns whether the given filename is a valid phar filename
     * @link https://php.net/manual/en/phar.isvalidpharfilename.php
     * @param string $filename <p>
     * The name or full path to a phar archive not yet created
     * </p>
     * @param bool $executable [optional] <p>
     * This parameter determines whether the filename should be treated as
     * a phar executable archive, or a data non-executable archive
     * </p>
     * @return bool <b>TRUE</b> if the filename is valid, <b>FALSE</b> if not.
     */
    final public static function isValidPharFilename(string $filename, bool $executable = true): bool {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Loads any phar archive with an alias
     * @link https://php.net/manual/en/phar.loadphar.php
     * @param string $filename <p>
     * the full or relative path to the phar archive to open
     * </p>
     * @param string|null $alias [optional] <p>
     * The alias that may be used to refer to the phar archive. Note
     * that many phar archives specify an explicit alias inside the
     * phar archive, and a <b>PharException</b> will be thrown if
     * a new alias is specified in this case.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    final public static function loadPhar(string $filename, ?string $alias = null): bool {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Reads the currently executed file (a phar) and registers its manifest
     * @link https://php.net/manual/en/phar.mapphar.php
     * @param string|null $alias [optional] <p>
     * The alias that can be used in phar:// URLs to
     * refer to this archive, rather than its full path.
     * </p>
     * @param int $offset [optional] <p>
     * Unused variable, here for compatibility with PEAR's PHP_Archive.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    final public static function mapPhar(?string $alias = null, int $offset = 0): bool {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Returns the full path on disk or full phar URL to the currently executing Phar archive
     * @link https://php.net/manual/en/phar.running.php
     * @param bool $returnPhar <p>
     * If <b>FALSE</b>, the full path on disk to the phar
     * archive is returned. If <b>TRUE</b>, a full phar URL is returned.
     * </p>
     * @return string the filename if valid, empty string otherwise.
     */
    final public static function running(
        #[PhpStormStubsElementAvailable(from: '5.3', to: '5.6')] $returnPhar,
        #[PhpStormStubsElementAvailable(from: '7.0')] bool $returnPhar = true
    ): string {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Mount an external path or file to a virtual location within the phar archive
     * @link https://php.net/manual/en/phar.mount.php
     * @param string $pharPath <p>
     * The internal path within the phar archive to use as the mounted path location.
     * This must be a relative path within the phar archive, and must not already exist.
     * </p>
     * @param string $externalPath <p>
     * A path or URL to an external file or directory to mount within the phar archive
     * </p>
     * @return void No return. <b>PharException</b> is thrown on failure.
     */
    final public static function mount(string $pharPath, string $externalPath): void {}

    /**
     * (Unknown)<br/>
     * Defines a list of up to 4 $_SERVER variables that should be modified for execution
     * @link https://php.net/manual/en/phar.mungserver.php
     * @param array $variables <p>
     * an array containing as string indices any of
     * REQUEST_URI, PHP_SELF,
     * SCRIPT_NAME and SCRIPT_FILENAME.
     * Other values trigger an exception, and <b>Phar::mungServer</b>
     * is case-sensitive.
     * </p>
     * @return void No return.
     */
    final public static function mungServer(array $variables): void {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Completely remove a phar archive from disk and from memory
     * @link https://php.net/manual/en/phar.unlinkarchive.php
     * @param string $filename <p>
     * The path on disk to the phar archive.
     * </p>
     * @throws PharException
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    final public static function unlinkArchive(string $filename): bool {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * mapPhar for web-based phars. front controller for web applications
     * @link https://php.net/manual/en/phar.webphar.php
     * @param null|string $alias [optional] <p>
     * The alias that can be used in phar:// URLs to
     * refer to this archive, rather than its full path.
     * </p>
     * @param string|null $index [optional] <p>
     * The location within the phar of the directory index.
     * </p>
     * @param null|string $fileNotFoundScript [optional] <p>
     * The location of the script to run when a file is not found. This
     * script should output the proper HTTP 404 headers.
     * </p>
     * @param null|array $mimeTypes [optional] <p>
     * An array mapping additional file extensions to MIME type.
     * If the default mapping is sufficient, pass an empty array.
     * By default, these extensions are mapped to these MIME types:
     * <code>
     * $mimes = array(
     * 'phps' => Phar::PHPS, // pass to highlight_file()
     * 'c' => 'text/plain',
     * 'cc' => 'text/plain',
     * 'cpp' => 'text/plain',
     * 'c++' => 'text/plain',
     * 'dtd' => 'text/plain',
     * 'h' => 'text/plain',
     * 'log' => 'text/plain',
     * 'rng' => 'text/plain',
     * 'txt' => 'text/plain',
     * 'xsd' => 'text/plain',
     * 'php' => Phar::PHP, // parse as PHP
     * 'inc' => Phar::PHP, // parse as PHP
     * 'avi' => 'video/avi',
     * 'bmp' => 'image/bmp',
     * 'css' => 'text/css',
     * 'gif' => 'image/gif',
     * 'htm' => 'text/html',
     * 'html' => 'text/html',
     * 'htmls' => 'text/html',
     * 'ico' => 'image/x-ico',
     * 'jpe' => 'image/jpeg',
     * 'jpg' => 'image/jpeg',
     * 'jpeg' => 'image/jpeg',
     * 'js' => 'application/x-javascript',
     * 'midi' => 'audio/midi',
     * 'mid' => 'audio/midi',
     * 'mod' => 'audio/mod',
     * 'mov' => 'movie/quicktime',
     * 'mp3' => 'audio/mp3',
     * 'mpg' => 'video/mpeg',
     * 'mpeg' => 'video/mpeg',
     * 'pdf' => 'application/pdf',
     * 'png' => 'image/png',
     * 'swf' => 'application/shockwave-flash',
     * 'tif' => 'image/tiff',
     * 'tiff' => 'image/tiff',
     * 'wav' => 'audio/wav',
     * 'xbm' => 'image/xbm',
     * 'xml' => 'text/xml',
     * );
     * </code>
     * </p>
     * @param null|callable $rewrite [optional] <p>
     * The rewrites function is passed a string as its only parameter and must return a string or <b>FALSE</b>.
     * </p>
     * <p>
     * If you are using fast-cgi or cgi then the parameter passed to the function is the value of the
     * $_SERVER['PATH_INFO'] variable. Otherwise, the parameter passed to the function is the value
     * of the $_SERVER['REQUEST_URI'] variable.
     * </p>
     * <p>
     * If a string is returned it is used as the internal file path. If <b>FALSE</b> is returned then webPhar() will
     * send a HTTP 403 Denied Code.
     * </p>
     * @return void No value is returned.
     */
    final public static function webPhar(
        ?string $alias = null,
        ?string $index = "index.php",
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: 'string')] $fileNotFoundScript = null,
        array $mimeTypes = null,
        ?callable $rewrite = null
    ): void {}

    /**
     * Returns whether current entry is a directory and not '.' or '..'
     * @link https://php.net/manual/en/recursivedirectoryiterator.haschildren.php
     * @param bool $allow_links [optional] <p>
     * </p>
     * @return bool whether the current entry is a directory, but not '.' or '..'
     */
    public function hasChildren($allow_links = false) {}

    /**
     * Returns an iterator for the current entry if it is a directory
     * @link https://php.net/manual/en/recursivedirectoryiterator.getchildren.php
     * @return mixed The filename, file information, or $this depending on the set flags.
     * See the FilesystemIterator
     * constants.
     */
    public function getChildren() {}

    /**
     * Rewinds back to the beginning
     * @link https://php.net/manual/en/filesystemiterator.rewind.php
     * @return void No value is returned.
     */
    public function rewind() {}

    /**
     * Move to the next file
     * @link https://php.net/manual/en/filesystemiterator.next.php
     * @return void No value is returned.
     */
    public function next() {}

    /**
     * Retrieve the key for the current file
     * @link https://php.net/manual/en/filesystemiterator.key.php
     * @return string the pathname or filename depending on the set flags.
     * See the FilesystemIterator constants.
     */
    public function key() {}

    /**
     * The current file
     * @link https://php.net/manual/en/filesystemiterator.current.php
     * @return mixed The filename, file information, or $this depending on the set flags.
     * See the FilesystemIterator constants.
     */
    public function current() {}

    /**
     * Check whether current DirectoryIterator position is a valid file
     * @link https://php.net/manual/en/directoryiterator.valid.php
     * @return bool <b>TRUE</b> if the position is valid, otherwise <b>FALSE</b>
     */
    public function valid() {}

    /**
     * Seek to a DirectoryIterator item
     * @link https://php.net/manual/en/directoryiterator.seek.php
     * @param int $position <p>
     * The zero-based numeric position to seek to.
     * </p>
     * @return void No value is returned.
     */
    public function seek($position) {}

    public function _bad_state_ex() {}
}

/**
 * The PharData class provides a high-level interface to accessing and creating
 * non-executable tar and zip archives. Because these archives do not contain
 * a stub and cannot be executed by the phar extension, it is possible to create
 * and manipulate regular zip and tar files using the PharData class even if
 * phar.readonly php.ini setting is 1.
 * @link https://php.net/manual/en/class.phardata.php
 */
class PharData extends Phar
{
    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Construct a non-executable tar or zip archive object
     * @link https://php.net/manual/en/phardata.construct.php
     * @param string $filename <p>
     * Path to an existing tar/zip archive or to-be-created archive
     * </p>
     * @param int $flags [optional] <p>
     * Flags to pass to <b>Phar</b> parent class
     * <b>RecursiveDirectoryIterator</b>.
     * </p>
     * @param string $alias [optional] <p>
     * Alias with which this Phar archive should be referred to in calls to stream
     * functionality.
     * </p>
     * @param int $format [optional] <p>
     * One of the
     * file format constants
     * available within the <b>Phar</b> class.
     * </p>
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $filename,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = FilesystemIterator::KEY_AS_PATHNAME|FilesystemIterator::CURRENT_AS_FILEINFO,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $alias = null,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $format = Phar::TAR
    ) {}

    /**
     * @param string $localName
     * @return bool
     */
    #[TentativeType]
    public function offsetExists($localName): bool {}

    /**
     * @param string $localName
     * @return SplFileInfo
     */
    #[TentativeType]
    public function offsetGet($localName): SplFileInfo {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * set the contents of a file within the tar/zip to those of an external file or string
     * @link https://php.net/manual/en/phardata.offsetset.php
     * @param string $localName <p>
     * The filename (relative path) to modify in a tar or zip archive.
     * </p>
     * @param string $value <p>
     * Content of the file.
     * </p>
     * @return void No return values.
     */
    #[TentativeType]
    public function offsetSet($localName, $value): void {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * remove a file from a tar/zip archive
     * @link https://php.net/manual/en/phardata.offsetunset.php
     * @param string $localName <p>
     * The filename (relative path) to modify in the tar/zip archive.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function offsetUnset($localName): void {}

    /**
     * Returns whether current entry is a directory and not '.' or '..'
     * @link https://php.net/manual/en/recursivedirectoryiterator.haschildren.php
     * @param bool $allow_links [optional] <p>
     * </p>
     * @return bool whether the current entry is a directory, but not '.' or '..'
     */
    public function hasChildren($allow_links = false) {}

    /**
     * Returns an iterator for the current entry if it is a directory
     * @link https://php.net/manual/en/recursivedirectoryiterator.getchildren.php
     * @return mixed The filename, file information, or $this depending on the set flags.
     * See the FilesystemIterator
     * constants.
     */
    public function getChildren() {}

    /**
     * Rewinds back to the beginning
     * @link https://php.net/manual/en/filesystemiterator.rewind.php
     * @return void No value is returned.
     */
    public function rewind() {}

    /**
     * Move to the next file
     * @link https://php.net/manual/en/filesystemiterator.next.php
     * @return void No value is returned.
     */
    public function next() {}

    /**
     * Retrieve the key for the current file
     * @link https://php.net/manual/en/filesystemiterator.key.php
     * @return string the pathname or filename depending on the set flags.
     * See the FilesystemIterator constants.
     */
    public function key() {}

    /**
     * The current file
     * @link https://php.net/manual/en/filesystemiterator.current.php
     * @return mixed The filename, file information, or $this depending on the set flags.
     * See the FilesystemIterator constants.
     */
    public function current() {}

    /**
     * Check whether current DirectoryIterator position is a valid file
     * @link https://php.net/manual/en/directoryiterator.valid.php
     * @return bool <b>TRUE</b> if the position is valid, otherwise <b>FALSE</b>
     */
    public function valid() {}

    /**
     * Seek to a DirectoryIterator item
     * @link https://php.net/manual/en/directoryiterator.seek.php
     * @param int $position <p>
     * The zero-based numeric position to seek to.
     * </p>
     * @return void No value is returned.
     */
    public function seek($position) {}
}

/**
 * The PharFileInfo class provides a high-level interface to the contents
 * and attributes of a single file within a phar archive.
 * @link https://php.net/manual/en/class.pharfileinfo.php
 */
class PharFileInfo extends SplFileInfo
{
    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Construct a Phar entry object
     * @link https://php.net/manual/en/pharfileinfo.construct.php
     * @param string $filename <p>
     * The full url to retrieve a file. If you wish to retrieve the information
     * for the file my/file.php from the phar boo.phar,
     * the entry should be phar://boo.phar/my/file.php.
     * </p>
     */
    public function __construct(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $filename) {}

    public function __destruct() {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Sets file-specific permission bits
     * @link https://php.net/manual/en/pharfileinfo.chmod.php
     * @param int $perms <p>
     * permissions (see <b>chmod</b>)
     * </p>
     * @return void No value is returned.
     */
    #[TentativeType]
    public function chmod(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $perms): void {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Compresses the current Phar entry with either zlib or bzip2 compression
     * @link https://php.net/manual/en/pharfileinfo.compress.php
     * @param int $compression
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function compress(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $compression) {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 2.0.0)<br/>
     * Decompresses the current Phar entry within the phar
     * @link https://php.net/manual/en/pharfileinfo.decompress.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function decompress() {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.2.0)<br/>
     * Deletes the metadata of the entry
     * @link https://php.net/manual/en/pharfileinfo.delmetadata.php
     * @return bool <b>TRUE</b> if successful, <b>FALSE</b> if the entry had no metadata.
     * As with all functionality that modifies the contents of
     * a phar, the phar.readonly INI variable
     * must be off in order to succeed if the file is within a <b>Phar</b>
     * archive. Files within <b>PharData</b> archives do not have
     * this restriction.
     */
    public function delMetadata() {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Returns the actual size of the file (with compression) inside the Phar archive
     * @link https://php.net/manual/en/pharfileinfo.getcompressedsize.php
     * @return int<0, max> The size in bytes of the file within the Phar archive on disk.
     */
    #[TentativeType]
    public function getCompressedSize(): int {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Returns CRC32 code or throws an exception if CRC has not been verified
     * @link https://php.net/manual/en/pharfileinfo.getcrc32.php
     * @return int The <b>crc32</b> checksum of the file within the Phar archive.
     */
    #[TentativeType]
    public function getCRC32(): int {}

    #[TentativeType]
    public function getContent(): string {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Returns file-specific meta-data saved with a file
     * @link https://php.net/manual/en/pharfileinfo.getmetadata.php
     * @param array $unserializeOptions [optional] if is set to anything other than the default,
     * the resulting metadata won't be cached and this won't return the value from the cache
     * @return mixed any PHP variable that can be serialized and is stored as meta-data for the file,
     * or <b>NULL</b> if no meta-data is stored.
     */
    #[TentativeType]
    public function getMetadata(#[PhpStormStubsElementAvailable(from: '8.0')] array $unserializeOptions = []): mixed {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Returns the Phar file entry flags
     * @link https://php.net/manual/en/pharfileinfo.getpharflags.php
     * @return int The Phar flags (always 0 in the current implementation)
     */
    #[TentativeType]
    public function getPharFlags(): int {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.2.0)<br/>
     * Returns the metadata of the entry
     * @link https://php.net/manual/en/pharfileinfo.hasmetadata.php
     * @return bool <b>FALSE</b> if no metadata is set or is <b>NULL</b>, <b>TRUE</b> if metadata is not <b>NULL</b>
     */
    #[TentativeType]
    public function hasMetadata(): bool {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Returns whether the entry is compressed
     * @link https://php.net/manual/en/pharfileinfo.iscompressed.php
     * @param int $compression [optional] <p>
     * One of <b>Phar::GZ</b> or <b>Phar::BZ2</b>,
     * defaults to any compression.
     * </p>
     * @return bool <b>TRUE</b> if the file is compressed within the Phar archive, <b>FALSE</b> if not.
     */
    #[TentativeType]
    public function isCompressed(#[LanguageLevelTypeAware(['8.0' => 'int|null'], default: '')] $compression = 9021976): bool {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Returns whether file entry has had its CRC verified
     * @link https://php.net/manual/en/pharfileinfo.iscrcchecked.php
     * @return bool <b>TRUE</b> if the file has had its CRC verified, <b>FALSE</b> if not.
     */
    #[TentativeType]
    public function isCRCChecked(): bool {}

    /**
     * (PHP &gt;= 5.3.0, PECL phar &gt;= 1.0.0)<br/>
     * Sets file-specific meta-data saved with a file
     * @link https://php.net/manual/en/pharfileinfo.setmetadata.php
     * @param mixed $metadata <p>
     * Any PHP variable containing information to store alongside a file
     * </p>
     * @return void No value is returned.
     */
    #[TentativeType]
    public function setMetadata(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $metadata): void {}
}
// End of Phar v.2.0.1
