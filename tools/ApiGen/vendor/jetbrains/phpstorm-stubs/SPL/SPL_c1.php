<?php

// Start of SPL v.0.2
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;

/**
 * The SplFileInfo class offers a high-level object oriented interface to
 * information for an individual file.
 * @link https://php.net/manual/en/class.splfileinfo.php
 */
class SplFileInfo implements Stringable
{
    /**
     * Construct a new SplFileInfo object
     * @link https://php.net/manual/en/splfileinfo.construct.php
     * @param string $filename
     * @since 5.1.2
     */
    public function __construct(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $filename) {}

    /**
     * Gets the path without filename
     * @link https://php.net/manual/en/splfileinfo.getpath.php
     * @return string the path to the file.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getPath(): string {}

    /**
     * Gets the filename
     * @link https://php.net/manual/en/splfileinfo.getfilename.php
     * @return string The filename.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getFilename(): string {}

    /**
     * Gets the file extension
     * @link https://php.net/manual/en/splfileinfo.getextension.php
     * @return string a string containing the file extension, or an
     * empty string if the file has no extension.
     * @since 5.3.6
     */
    #[TentativeType]
    public function getExtension(): string {}

    /**
     * Gets the base name of the file
     * @link https://php.net/manual/en/splfileinfo.getbasename.php
     * @param string $suffix [optional] <p>
     * Optional suffix to omit from the base name returned.
     * </p>
     * @return string the base name without path information.
     * @since 5.2.2
     */
    #[TentativeType]
    public function getBasename(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $suffix = null): string {}

    /**
     * Gets the path to the file
     * @link https://php.net/manual/en/splfileinfo.getpathname.php
     * @return string The path to the file.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getPathname(): string {}

    /**
     * Gets file permissions
     * @link https://php.net/manual/en/splfileinfo.getperms.php
     * @return int|false The file permissions on success, or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getPerms(): int|false {}

    /**
     * Gets the inode for the file
     * @link https://php.net/manual/en/splfileinfo.getinode.php
     * @return int|false The inode number for the filesystem object on success, or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getInode(): int|false {}

    /**
     * Gets file size
     * @link https://php.net/manual/en/splfileinfo.getsize.php
     * @return int|false The filesize in bytes on success, or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getSize(): int|false {}

    /**
     * Gets the owner of the file
     * @link https://php.net/manual/en/splfileinfo.getowner.php
     * @return int|false The owner id in numerical format on success, or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getOwner(): int|false {}

    /**
     * Gets the file group
     * @link https://php.net/manual/en/splfileinfo.getgroup.php
     * @return int|false The group id in numerical format on success, or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getGroup(): int|false {}

    /**
     * Gets last access time of the file
     * @link https://php.net/manual/en/splfileinfo.getatime.php
     * @return int|false The time the file was last accessed on success, or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getATime(): int|false {}

    /**
     * Gets the last modified time
     * @link https://php.net/manual/en/splfileinfo.getmtime.php
     * @return int|false The last modified time for the file, in a Unix timestamp on success, or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getMTime(): int|false {}

    /**
     * Gets the inode change time
     * @link https://php.net/manual/en/splfileinfo.getctime.php
     * @return int|false The last change time, in a Unix timestamp on success, or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getCTime(): int|false {}

    /**
     * Gets file type
     * @link https://php.net/manual/en/splfileinfo.gettype.php
     * @return string|false A string representing the type of the entry. May be one of file, link, dir, block, fifo, char, socket, or unknown, or <b>FALSE</b> on failure.
     * May be one of file, link,
     * or dir
     * @since 5.1.2
     */
    #[TentativeType]
    public function getType(): string|false {}

    /**
     * Tells if the entry is writable
     * @link https://php.net/manual/en/splfileinfo.iswritable.php
     * @return bool true if writable, false otherwise;
     * @since 5.1.2
     */
    #[TentativeType]
    public function isWritable(): bool {}

    /**
     * Tells if file is readable
     * @link https://php.net/manual/en/splfileinfo.isreadable.php
     * @return bool true if readable, false otherwise.
     * @since 5.1.2
     */
    #[TentativeType]
    public function isReadable(): bool {}

    /**
     * Tells if the file is executable
     * @link https://php.net/manual/en/splfileinfo.isexecutable.php
     * @return bool true if executable, false otherwise.
     * @since 5.1.2
     */
    #[TentativeType]
    public function isExecutable(): bool {}

    /**
     * Tells if the object references a regular file
     * @link https://php.net/manual/en/splfileinfo.isfile.php
     * @return bool true if the file exists and is a regular file (not a link), false otherwise.
     * @since 5.1.2
     */
    #[TentativeType]
    public function isFile(): bool {}

    /**
     * Tells if the file is a directory
     * @link https://php.net/manual/en/splfileinfo.isdir.php
     * @return bool true if a directory, false otherwise.
     * @since 5.1.2
     */
    #[TentativeType]
    public function isDir(): bool {}

    /**
     * Tells if the file is a link
     * @link https://php.net/manual/en/splfileinfo.islink.php
     * @return bool true if the file is a link, false otherwise.
     * @since 5.1.2
     */
    #[TentativeType]
    public function isLink(): bool {}

    /**
     * Gets the target of a link
     * @link https://php.net/manual/en/splfileinfo.getlinktarget.php
     * @return string|false The target of the filesystem link on success, or <b>FALSE</b> on failure.
     * @since 5.2.2
     */
    #[TentativeType]
    public function getLinkTarget(): string|false {}

    /**
     * Gets absolute path to file
     * @link https://php.net/manual/en/splfileinfo.getrealpath.php
     * @return string|false the path to the file, or <b>FALSE</b> if the file does not exist.
     * @since 5.2.2
     */
    #[TentativeType]
    public function getRealPath(): string|false {}

    /**
     * Gets an SplFileInfo object for the file
     * @link https://php.net/manual/en/splfileinfo.getfileinfo.php
     * @param string $class [optional] <p>
     * Name of an <b>SplFileInfo</b> derived class to use.
     * </p>
     * @return SplFileInfo An <b>SplFileInfo</b> object created for the file.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getFileInfo(#[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $class = null): SplFileInfo {}

    /**
     * Gets an SplFileInfo object for the path
     * @link https://php.net/manual/en/splfileinfo.getpathinfo.php
     * @param string $class [optional] <p>
     * Name of an <b>SplFileInfo</b> derived class to use.
     * </p>
     * @return SplFileInfo|null A <b>SplFileInfo</b> object for the parent path of the file on success, or <b>NULL</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getPathInfo(#[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $class = null): ?SplFileInfo {}

    /**
     * Gets an SplFileObject object for the file
     * @link https://php.net/manual/en/splfileinfo.openfile.php
     * @param string $mode [optional] <p>
     * The mode for opening the file. See the <b>fopen</b>
     * documentation for descriptions of possible modes. The default
     * is read only.
     * </p>
     * @param bool $useIncludePath [optional] <p>
     * </p>
     * @param resource $context [optional] <p>
     * </p>
     * @return SplFileObject The opened file as an <b>SplFileObject</b> object.
     * @since 5.1.2
     */
    #[TentativeType]
    public function openFile(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $mode = 'r',
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $useIncludePath = false,
        $context = null
    ): SplFileObject {}

    /**
     * Sets the class name used with <b>SplFileInfo::openFile</b>
     * @link https://php.net/manual/en/splfileinfo.setfileclass.php
     * @param string $class [optional] <p>
     * The class name to use when openFile() is called.
     * </p>
     * @return void
     * @since 5.1.2
     */
    #[TentativeType]
    public function setFileClass(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $class = SplFileObject::class): void {}

    /**
     * Sets the class used with getFileInfo and getPathInfo
     * @link https://php.net/manual/en/splfileinfo.setinfoclass.php
     * @param string $class [optional] <p>
     * The class name to use.
     * </p>
     * @return void
     * @since 5.1.2
     */
    #[TentativeType]
    public function setInfoClass(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $class = SplFileInfo::class): void {}

    /**
     * Returns the path to the file as a string
     * @link https://php.net/manual/en/splfileinfo.tostring.php
     * @return string the path to the file.
     * @since 5.1.2
     */
    #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')]
    public function __toString() {}

    #[TentativeType]
    final public function _bad_state_ex(): void {}

    public function __wakeup() {}

    /**
     * @return array
     * @since 7.4
     */
    #[TentativeType]
    public function __debugInfo(): array {}
}

/**
 * The DirectoryIterator class provides a simple interface for viewing
 * the contents of filesystem directories.
 * @link https://php.net/manual/en/class.directoryiterator.php
 */
class DirectoryIterator extends SplFileInfo implements SeekableIterator
{
    /**
     * Constructs a new directory iterator from a path
     * @link https://php.net/manual/en/directoryiterator.construct.php
     * @param string $directory
     * @throws UnexpectedValueException if the path cannot be opened.
     * @throws RuntimeException if the path is an empty string.
     */
    public function __construct(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $directory) {}

    /**
     * Determine if current DirectoryIterator item is '.' or '..'
     * @link https://php.net/manual/en/directoryiterator.isdot.php
     * @return bool true if the entry is . or ..,
     * otherwise false
     */
    #[TentativeType]
    public function isDot(): bool {}

    /**
     * Rewind the DirectoryIterator back to the start
     * @link https://php.net/manual/en/directoryiterator.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Check whether current DirectoryIterator position is a valid file
     * @link https://php.net/manual/en/directoryiterator.valid.php
     * @return bool true if the position is valid, otherwise false
     */
    #[TentativeType]
    public function valid(): bool {}

    /**
     * Return the key for the current DirectoryIterator item
     * @link https://php.net/manual/en/directoryiterator.key.php
     * @return string The key for the current <b>DirectoryIterator</b> item.
     */
    #[TentativeType]
    public function key(): mixed {}

    /**
     * Return the current DirectoryIterator item.
     * @link https://php.net/manual/en/directoryiterator.current.php
     * @return DirectoryIterator The current <b>DirectoryIterator</b> item.
     */
    #[TentativeType]
    public function current(): mixed {}

    /**
     * Move forward to next DirectoryIterator item
     * @link https://php.net/manual/en/directoryiterator.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * Seek to a DirectoryIterator item
     * @link https://php.net/manual/en/directoryiterator.seek.php
     * @param int $offset <p>
     * The zero-based numeric position to seek to.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function seek(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $offset): void {}
}

/**
 * The Filesystem iterator
 * @link https://php.net/manual/en/class.filesystemiterator.php
 */
class FilesystemIterator extends DirectoryIterator
{
    public const CURRENT_MODE_MASK = 240;
    public const CURRENT_AS_PATHNAME = 32;
    public const CURRENT_AS_FILEINFO = 0;
    public const CURRENT_AS_SELF = 16;
    public const KEY_MODE_MASK = 3840;
    public const KEY_AS_PATHNAME = 0;
    public const FOLLOW_SYMLINKS = 16384;
    public const KEY_AS_FILENAME = 256;
    public const NEW_CURRENT_AND_KEY = 256;
    public const SKIP_DOTS = 4096;
    public const UNIX_PATHS = 8192;
    public const OTHER_MODE_MASK = 28672;

    /**
     * Constructs a new filesystem iterator
     * @link https://php.net/manual/en/filesystemiterator.construct.php
     * @param string $directory
     * @param int $flags [optional]
     * @throws UnexpectedValueException if the path cannot be found.
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $directory,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = FilesystemIterator::KEY_AS_PATHNAME|FilesystemIterator::CURRENT_AS_FILEINFO|FilesystemIterator::SKIP_DOTS
    ) {}

    /**
     * Rewinds back to the beginning
     * @link https://php.net/manual/en/filesystemiterator.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Move to the next file
     * @link https://php.net/manual/en/filesystemiterator.next.php
     * @return void
     */
    public function next() {}

    /**
     * Retrieve the key for the current file
     * @link https://php.net/manual/en/filesystemiterator.key.php
     * @return string the pathname or filename depending on the set flags.
     * See the FilesystemIterator constants.
     */
    #[TentativeType]
    public function key(): string {}

    /**
     * The current file
     * @link https://php.net/manual/en/filesystemiterator.current.php
     * @return string|SplFileInfo|self The filename, file information, or $this depending on the set flags.
     * See the FilesystemIterator constants.
     */
    #[TentativeType]
    public function current(): SplFileInfo|FilesystemIterator|string {}

    /**
     * Get the handling flags
     * @link https://php.net/manual/en/filesystemiterator.getflags.php
     * @return int The integer value of the set flags.
     */
    #[TentativeType]
    public function getFlags(): int {}

    /**
     * Sets handling flags
     * @link https://php.net/manual/en/filesystemiterator.setflags.php
     * @param int $flags <p>
     * The handling flags to set.
     * See the FilesystemIterator constants.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setFlags(
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $flags = null,
        #[PhpStormStubsElementAvailable(from: '8.0')] int $flags
    ): void {}
}

/**
 * The <b>RecursiveDirectoryIterator</b> provides
 * an interface for iterating recursively over filesystem directories.
 * @link https://php.net/manual/en/class.recursivedirectoryiterator.php
 */
class RecursiveDirectoryIterator extends FilesystemIterator implements RecursiveIterator
{
    /**
     * Constructs a RecursiveDirectoryIterator
     * @link https://php.net/manual/en/recursivedirectoryiterator.construct.php
     * @param string $directory
     * @param int $flags [optional]
     * @throws UnexpectedValueException if the path cannot be found or is not a directory.
     * @since 5.1.2
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $directory,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = FilesystemIterator::KEY_AS_PATHNAME|FilesystemIterator::CURRENT_AS_FILEINFO
    ) {}

    /**
     * Returns whether current entry is a directory and not '.' or '..'
     * @link https://php.net/manual/en/recursivedirectoryiterator.haschildren.php
     * @param bool $allowLinks [optional] <p>
     * </p>
     * @return bool whether the current entry is a directory, but not '.' or '..'
     */
    #[TentativeType]
    public function hasChildren(#[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $allowLinks = false): bool {}

    /**
     * Returns an iterator for the current entry if it is a directory
     * @link https://php.net/manual/en/recursivedirectoryiterator.getchildren.php
     * @return RecursiveDirectoryIterator An iterator for the current entry, if it is a directory.
     */
    #[TentativeType]
    public function getChildren(): RecursiveDirectoryIterator {}

    /**
     * Get sub path
     * @link https://php.net/manual/en/recursivedirectoryiterator.getsubpath.php
     * @return string The sub path (sub directory).
     */
    #[TentativeType]
    public function getSubPath(): string {}

    /**
     * Get sub path and name
     * @link https://php.net/manual/en/recursivedirectoryiterator.getsubpathname.php
     * @return string The sub path (sub directory) and filename.
     */
    #[TentativeType]
    public function getSubPathname(): string {}

    /**
     * Rewinds back to the beginning
     * @link https://php.net/manual/en/filesystemiterator.rewind.php
     * @return void
     */
    public function rewind() {}

    /**
     * Move to the next file
     * @link https://php.net/manual/en/filesystemiterator.next.php
     * @return void
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
     * @return string|SplFileInfo|self The filename, file information, or $this depending on the set flags.
     * See the FilesystemIterator constants.
     */
    public function current() {}
}

/**
 * Iterates through a file system in a similar fashion to
 * <b>glob</b>.
 * @link https://php.net/manual/en/class.globiterator.php
 */
class GlobIterator extends FilesystemIterator implements Countable
{
    /**
     * Construct a directory using glob
     * @link https://php.net/manual/en/globiterator.construct.php
     * @param $pattern
     * @param int $flags [optional]
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $pattern,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = FilesystemIterator::KEY_AS_PATHNAME|FilesystemIterator::CURRENT_AS_FILEINFO
    ) {}

    /**
     * Get the number of directories and files
     * @link https://php.net/manual/en/globiterator.count.php
     * @return int<0,max> The number of returned directories and files, as an
     * integer.
     */
    #[TentativeType]
    public function count(): int {}
}

/**
 * The SplFileObject class offers an object oriented interface for a file.
 * @link https://php.net/manual/en/class.splfileobject.php
 */
class SplFileObject extends SplFileInfo implements RecursiveIterator, SeekableIterator
{
    /**
     * Drop newlines at the end of a line.
     */
    public const DROP_NEW_LINE = 1;

    /**
     * Read on rewind/next.
     */
    public const READ_AHEAD = 2;

    /**
     * Skip empty lines in the file. This requires the {@see READ_AHEAD} flag to work as expected.
     */
    public const SKIP_EMPTY = 4;

    /**
     * Read lines as CSV rows.
     */
    public const READ_CSV = 8;

    /**
     * Construct a new file object.
     *
     * @link  https://php.net/manual/en/splfileobject.construct.php
     *
     * @param string $filename The file to open
     * @param string $mode [optional] The mode in which to open the file. See {@see fopen} for a list of allowed modes.
     * @param bool $useIncludePath [optional] Whether to search in the include_path for filename
     * @param resource $context [optional] A valid context resource created with {@see stream_context_create}
     *
     * @throws RuntimeException When the filename cannot be opened
     * @throws LogicException When the filename is a directory
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $filename,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $mode = 'r',
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $useIncludePath = false,
        $context = null
    ) {}

    /**
     * Rewind the file to the first line
     * @link https://php.net/manual/en/splfileobject.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Reached end of file
     * @link https://php.net/manual/en/splfileobject.eof.php
     * @return bool true if file is at EOF, false otherwise.
     */
    #[TentativeType]
    public function eof(): bool {}

    /**
     * Not at EOF
     * @link https://php.net/manual/en/splfileobject.valid.php
     * @return bool true if not reached EOF, false otherwise.
     */
    #[TentativeType]
    public function valid(): bool {}

    /**
     * Gets line from file
     * @link https://php.net/manual/en/splfileobject.fgets.php
     * @return string a string containing the next line from the file.
     */
    #[TentativeType]
    public function fgets(): string {}

    /**
     * Read from file
     * @link https://php.net/manual/en/splfileobject.fread.php
     * @param int $length <p>
     * The number of bytes to read.
     * </p>
     * @return string|false returns the string read from the file or FALSE on failure.
     * @since 5.5.11
     */
    #[TentativeType]
    public function fread(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $length): string|false {}

    /**
     * Gets line from file and parse as CSV fields
     * @link https://php.net/manual/en/splfileobject.fgetcsv.php
     * @param string $separator [optional] <p>
     * The field delimiter (one character only). Defaults as a comma or the value set using <b>SplFileObject::setCsvControl</b>.
     * </p>
     * @param string $enclosure [optional] <p>
     * The field enclosure character (one character only). Defaults as a double quotation mark or the value set using <b>SplFileObject::setCsvControl</b>.
     * </p>
     * @param string $escape [optional] <p>
     * The escape character (one character only). Defaults as a backslash (\) or the value set using <b>SplFileObject::setCsvControl</b>.
     * </p>
     * @return array|false|null an indexed array containing the fields read, or false on error.
     * </p>
     * <p>
     * A blank line in a CSV file will be returned as an array
     * comprising a single null field unless using <b>SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE</b>,
     * in which case empty lines are skipped.
     */
    #[TentativeType]
    #[LanguageLevelTypeAware(['8.1' => 'array|false'], default: 'array|false|null')]
    public function fgetcsv(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $separator = ",",
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $enclosure = "\"",
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $escape = "\\"
    ) {}

    /**
     * Write a field array as a CSV line
     * @link https://php.net/manual/en/splfileobject.fputcsv.php
     * @param array $fields An array of values
     * @param string $separator [optional] <p>
     * The field delimiter (one character only). Defaults as a comma or the value set using <b>SplFileObject::setCsvControl</b>.
     * </p>
     * @param string $enclosure [optional] <p>
     * The field enclosure character (one character only). Defaults as a double quotation mark or the value set using <b>SplFileObject::setCsvControl</b>.
     * </p>
     * @param string $escape The optional escape parameter sets the escape character (one character only).
     * @return int|false Returns the length of the written string or FALSE on failure.
     * @since 5.4
     */
    #[TentativeType]
    public function fputcsv(
        array $fields,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $separator = ',',
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $enclosure = '"',
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $escape = "\\",
        #[PhpStormStubsElementAvailable('8.1')] string $eol = PHP_EOL
    ): int|false {}

    /**
     * Set the delimiter and enclosure character for CSV
     * @link https://php.net/manual/en/splfileobject.setcsvcontrol.php
     * @param string $separator [optional] <p>
     * The field delimiter (one character only).
     * </p>
     * @param string $enclosure [optional] <p>
     * The field enclosure character (one character only).
     * </p>
     * @param string $escape [optional] <p>
     * The field escape character (one character only).
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setCsvControl(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $separator = ",",
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $enclosure = "\"",
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $escape = "\\"
    ): void {}

    /**
     * Get the delimiter and enclosure character for CSV
     * @link https://php.net/manual/en/splfileobject.getcsvcontrol.php
     * @return array an indexed array containing the delimiter and enclosure character.
     */
    #[TentativeType]
    public function getCsvControl(): array {}

    /**
     * Portable file locking
     * @link https://php.net/manual/en/splfileobject.flock.php
     * @param int $operation <p>
     * <i>operation</i> is one of the following:
     * <b>LOCK_SH</b> to acquire a shared lock (reader).
     * </p>
     * @param int &$wouldBlock [optional] <p>
     * Set to 1 if the lock would block (EWOULDBLOCK errno condition).
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function flock(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $operation, &$wouldBlock = null): bool {}

    /**
     * Flushes the output to the file
     * @link https://php.net/manual/en/splfileobject.fflush.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function fflush(): bool {}

    /**
     * Return current file position
     * @link https://php.net/manual/en/splfileobject.ftell.php
     * @return int|false the position of the file pointer as an integer, or false on error.
     */
    #[TentativeType]
    public function ftell(): int|false {}

    /**
     * Seek to a position
     * @link https://php.net/manual/en/splfileobject.fseek.php
     * @param int $offset <p>
     * The offset. A negative value can be used to move backwards through the file which
     * is useful when SEEK_END is used as the <i>whence</i> value.
     * </p>
     * @param int $whence [optional] <p>
     * <i>whence</i> values are:
     * <b>SEEK_SET</b> - Set position equal to <i>offset</i> bytes.
     * <b>SEEK_CUR</b> - Set position to current location plus <i>offset</i>.
     * <b>SEEK_END</b> - Set position to end-of-file plus <i>offset</i>.
     * </p>
     * <p>
     * If <i>whence</i> is not specified, it is assumed to be <b>SEEK_SET</b>.
     * </p>
     * @return int 0 if the seek was successful, -1 otherwise. Note that seeking
     * past EOF is not considered an error.
     */
    #[TentativeType]
    public function fseek(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $offset,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $whence = SEEK_SET
    ): int {}

    /**
     * Gets character from file
     * @link https://php.net/manual/en/splfileobject.fgetc.php
     * @return string|false a string containing a single character read from the file or false on EOF.
     */
    #[TentativeType]
    public function fgetc(): string|false {}

    /**
     * Output all remaining data on a file pointer
     * @link https://php.net/manual/en/splfileobject.fpassthru.php
     * @return int the number of characters read from <i>handle</i>
     * and passed through to the output.
     */
    #[TentativeType]
    public function fpassthru(): int {}

    /**
     * Gets line from file and strip HTML tags
     * @link https://php.net/manual/en/splfileobject.fgetss.php
     * @param string $allowable_tags [optional] <p>
     * You can use the optional third parameter to specify tags which should
     * not be stripped.
     * </p>
     * @return string|false a string containing the next line of the file with HTML and PHP
     * code stripped, or false on error.
     * @removed 8.0
     */
    #[Deprecated(since: '7.3')]
    public function fgetss($allowable_tags = null) {}

    /**
     * Parses input from file according to a format
     * @link https://php.net/manual/en/splfileobject.fscanf.php
     * @param string $format <p>
     * The specified format as described in the <b>sprintf</b> documentation.
     * </p>
     * @param mixed &...$vars [optional] <p>
     * The optional assigned values.
     * </p>
     * @return array|int If only one parameter is passed to this method, the values parsed will be
     * returned as an array. Otherwise, if optional parameters are passed, the
     * function will return the number of assigned values. The optional
     * parameters must be passed by reference.
     */
    #[TentativeType]
    public function fscanf(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $format,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] &...$vars
    ): array|int|null {}

    /**
     * Write to file
     * @link https://php.net/manual/en/splfileobject.fwrite.php
     * @param string $data <p>
     * The string to be written to the file.
     * </p>
     * @param int $length [optional] <p>
     * If the <i>length</i> argument is given, writing will
     * stop after <i>length</i> bytes have been written or
     * the end of <i>string</i> is reached, whichever comes
     * first.
     * </p>
     * @return int|false the number of bytes written, or 0 (false since 7.4) on error.
     */
    #[LanguageLevelTypeAware(['7.4' => 'int|false'], default: 'int')]
    #[TentativeType]
    public function fwrite(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $length = null
    ): int|false {}

    /**
     * Gets information about the file
     * @link https://php.net/manual/en/splfileobject.fstat.php
     * @return array an array with the statistics of the file; the format of the array
     * is described in detail on the <b>stat</b> manual page.
     */
    #[TentativeType]
    public function fstat(): array {}

    /**
     * Truncates the file to a given length
     * @link https://php.net/manual/en/splfileobject.ftruncate.php
     * @param int $size <p>
     * The size to truncate to.
     * </p>
     * <p>
     * If <i>size</i> is larger than the file it is extended with null bytes.
     * </p>
     * <p>
     * If <i>size</i> is smaller than the file, the extra data will be lost.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function ftruncate(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $size): bool {}

    /**
     * Retrieve current line of file
     * @link https://php.net/manual/en/splfileobject.current.php
     * @return string|array|false Retrieves the current line of the file. If the <b>SplFileObject::READ_CSV</b> flag is set, this method returns an array containing the current line parsed as CSV data.
     */
    #[TentativeType]
    public function current(): string|array|false {}

    /**
     * Get line number
     * @link https://php.net/manual/en/splfileobject.key.php
     * @return int the current line number.
     */
    #[TentativeType]
    public function key(): int {}

    /**
     * Read next line
     * @link https://php.net/manual/en/splfileobject.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * Sets flags for the SplFileObject
     * @link https://php.net/manual/en/splfileobject.setflags.php
     * @param int $flags <p>
     * Bit mask of the flags to set. See
     * SplFileObject constants
     * for the available flags.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setFlags(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags): void {}

    /**
     * Gets flags for the SplFileObject
     * @link https://php.net/manual/en/splfileobject.getflags.php
     * @return int an integer representing the flags.
     */
    #[TentativeType]
    public function getFlags(): int {}

    /**
     * Set maximum line length
     * @link https://php.net/manual/en/splfileobject.setmaxlinelen.php
     * @param int $maxLength <p>
     * The maximum length of a line.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setMaxLineLen(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $maxLength): void {}

    /**
     * Get maximum line length
     * @link https://php.net/manual/en/splfileobject.getmaxlinelen.php
     * @return int<0, max> the maximum line length if one has been set with
     * <b>SplFileObject::setMaxLineLen</b>, default is 0.
     */
    #[TentativeType]
    public function getMaxLineLen(): int {}

    /**
     * SplFileObject does not have children
     * @link https://php.net/manual/en/splfileobject.haschildren.php
     * @return bool false
     * @since 5.1.2
     */
    #[TentativeType]
    #[LanguageLevelTypeAware(['8.2' => 'false'], default: 'bool')]
    public function hasChildren() {}

    /**
     * No purpose
     * @link https://php.net/manual/en/splfileobject.getchildren.php
     * @return null|RecursiveIterator An SplFileObject does not have children so this method returns NULL.
     */
    #[TentativeType]
    #[LanguageLevelTypeAware(['8.2' => 'null'], default: 'null|RecursiveIterator')]
    public function getChildren() {}

    /**
     * Seek to specified line
     * @link https://php.net/manual/en/splfileobject.seek.php
     * @param int $line <p>
     * The zero-based line number to seek to.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function seek(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $line): void {}

    /**
     * Alias of <b>SplFileObject::fgets</b>
     * @link https://php.net/manual/en/splfileobject.getcurrentline.php
     * @return string Returns a string containing the next line from the file.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getCurrentLine(): string {}

    /**
     * Alias of <b>SplFileObject::current</b>
     * @link https://php.net/manual/en/splfileobject.tostring.php
     */
    #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')]
    public function __toString() {}
}

/**
 * The SplTempFileObject class offers an object oriented interface for a temporary file.
 * @link https://php.net/manual/en/class.spltempfileobject.php
 */
class SplTempFileObject extends SplFileObject
{
    /**
     * Construct a new temporary file object
     * @link https://php.net/manual/en/spltempfileobject.construct.php
     * @param int $maxMemory [optional]
     * @throws RuntimeException if an error occurs.
     * @since 5.1.2
     */
    public function __construct(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $maxMemory = 2097152) {}
}

/**
 * @template TValue
 * The SplDoublyLinkedList class provides the main functionalities of a doubly linked list.
 * @link https://php.net/manual/en/class.spldoublylinkedlist.php
 * @template-implements Iterator<int, TValue>
 * @template-implements ArrayAccess<int, TValue>
 */
class SplDoublyLinkedList implements Iterator, Countable, ArrayAccess, Serializable
{
    public const IT_MODE_LIFO = 2;
    public const IT_MODE_FIFO = 0;
    public const IT_MODE_DELETE = 1;
    public const IT_MODE_KEEP = 0;

    /**
     * Add/insert a new value at the specified index
     * @param mixed $index The index where the new value is to be inserted.
     * @param mixed $value The new value for the index.
     * @return void
     * @link https://php.net/spldoublylinkedlist.add
     * @since 5.5
     */
    #[TentativeType]
    public function add(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $index,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value
    ): void {}

    /**
     * Pops a node from the end of the doubly linked list
     * @link https://php.net/manual/en/spldoublylinkedlist.pop.php
     * @return mixed The value of the popped node.
     */
    #[TentativeType]
    public function pop(): mixed {}

    /**
     * Shifts a node from the beginning of the doubly linked list
     * @link https://php.net/manual/en/spldoublylinkedlist.shift.php
     * @return mixed The value of the shifted node.
     */
    #[TentativeType]
    public function shift(): mixed {}

    /**
     * Pushes an element at the end of the doubly linked list
     * @link https://php.net/manual/en/spldoublylinkedlist.push.php
     * @param mixed $value <p>
     * The value to push.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function push(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value): void {}

    /**
     * Prepends the doubly linked list with an element
     * @link https://php.net/manual/en/spldoublylinkedlist.unshift.php
     * @param mixed $value <p>
     * The value to unshift.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function unshift(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value): void {}

    /**
     * Peeks at the node from the end of the doubly linked list
     * @link https://php.net/manual/en/spldoublylinkedlist.top.php
     * @return mixed The value of the last node.
     */
    #[TentativeType]
    public function top(): mixed {}

    /**
     * Peeks at the node from the beginning of the doubly linked list
     * @link https://php.net/manual/en/spldoublylinkedlist.bottom.php
     * @return mixed The value of the first node.
     */
    #[TentativeType]
    public function bottom(): mixed {}

    /**
     * Counts the number of elements in the doubly linked list.
     * @link https://php.net/manual/en/spldoublylinkedlist.count.php
     * @return int the number of elements in the doubly linked list.
     */
    #[TentativeType]
    public function count(): int {}

    /**
     * Checks whether the doubly linked list is empty.
     * @link https://php.net/manual/en/spldoublylinkedlist.isempty.php
     * @return bool whether the doubly linked list is empty.
     */
    #[TentativeType]
    public function isEmpty(): bool {}

    /**
     * Sets the mode of iteration
     * @link https://php.net/manual/en/spldoublylinkedlist.setiteratormode.php
     * @param int $mode <p>
     * There are two orthogonal sets of modes that can be set:
     * </p>
     * The direction of the iteration (either one or the other):
     * <b>SplDoublyLinkedList::IT_MODE_LIFO</b> (Stack style)
     * @return int
     */
    #[TentativeType]
    public function setIteratorMode(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $mode): int {}

    /**
     * Returns the mode of iteration
     * @link https://php.net/manual/en/spldoublylinkedlist.getiteratormode.php
     * @return int the different modes and flags that affect the iteration.
     */
    #[TentativeType]
    public function getIteratorMode(): int {}

    /**
     * Returns whether the requested $index exists
     * @link https://php.net/manual/en/spldoublylinkedlist.offsetexists.php
     * @param mixed $index <p>
     * The index being checked.
     * </p>
     * @return bool true if the requested <i>index</i> exists, otherwise false
     */
    #[TentativeType]
    public function offsetExists($index): bool {}

    /**
     * Returns the value at the specified $index
     * @link https://php.net/manual/en/spldoublylinkedlist.offsetget.php
     * @param mixed $index <p>
     * The index with the value.
     * </p>
     * @return mixed The value at the specified <i>index</i>.
     */
    #[TentativeType]
    public function offsetGet($index): mixed {}

    /**
     * Sets the value at the specified $index to $newval
     * @link https://php.net/manual/en/spldoublylinkedlist.offsetset.php
     * @param mixed $index <p>
     * The index being set.
     * </p>
     * @param mixed $value <p>
     * The new value for the <i>index</i>.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function offsetSet($index, #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value): void {}

    /**
     * Unsets the value at the specified $index
     * @link https://php.net/manual/en/spldoublylinkedlist.offsetunset.php
     * @param mixed $index <p>
     * The index being unset.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function offsetUnset($index): void {}

    /**
     * Rewind iterator back to the start
     * @link https://php.net/manual/en/spldoublylinkedlist.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Return current array entry
     * @link https://php.net/manual/en/spldoublylinkedlist.current.php
     * @return mixed The current node value.
     */
    #[TentativeType]
    public function current(): mixed {}

    /**
     * Return current node index
     * @link https://php.net/manual/en/spldoublylinkedlist.key.php
     * @return string|float|int|bool|null The current node index.
     */
    #[TentativeType]
    public function key(): int {}

    /**
     * Move to next entry
     * @link https://php.net/manual/en/spldoublylinkedlist.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * Move to previous entry
     * @link https://php.net/manual/en/spldoublylinkedlist.prev.php
     * @return void
     */
    #[TentativeType]
    public function prev(): void {}

    /**
     * Check whether the doubly linked list contains more nodes
     * @link https://php.net/manual/en/spldoublylinkedlist.valid.php
     * @return bool true if the doubly linked list contains any more nodes, false otherwise.
     */
    #[TentativeType]
    public function valid(): bool {}

    /**
     * Unserializes the storage
     * @link https://php.net/manual/en/spldoublylinkedlist.serialize.php
     * @param string $data The serialized string.
     * @return void
     * @since 5.4
     */
    #[TentativeType]
    public function unserialize(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data): void {}

    /**
     * Serializes the storage
     * @link https://php.net/manual/en/spldoublylinkedlist.unserialize.php
     * @return string The serialized string.
     * @since 5.4
     */
    #[TentativeType]
    public function serialize(): string {}

    /**
     * @return array
     * @since 7.4
     */
    #[TentativeType]
    public function __debugInfo(): array {}

    /**
     * @return array
     * @since 7.4
     */
    #[TentativeType]
    public function __serialize(): array {}

    /**
     * @param array $data
     * @since 7.4
     */
    #[TentativeType]
    public function __unserialize(array $data): void {}
}

/**
 * @template TValue
 * The SplQueue class provides the main functionalities of a queue implemented using a doubly linked list.
 * @link https://php.net/manual/en/class.splqueue.php
 */
class SplQueue extends SplDoublyLinkedList
{
    /**
     * Adds an element to the queue.
     * @link https://php.net/manual/en/splqueue.enqueue.php
     * @param TValue $value <p>
     * The value to enqueue.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function enqueue(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value): void {}

    /**
     * Dequeues a node from the queue
     * @link https://php.net/manual/en/splqueue.dequeue.php
     * @return TValue The value of the dequeued node.
     */
    #[TentativeType]
    public function dequeue(): mixed {}

    /**
     * Sets the mode of iteration
     * @link https://php.net/manual/en/spldoublylinkedlist.setiteratormode.php
     * @param int $mode <p>
     * There are two orthogonal sets of modes that can be set:
     * </p>
     * The direction of the iteration (either one or the other):
     * <b>SplDoublyLinkedList::IT_MODE_LIFO</b> (Stack style)
     * @return void
     */
    public function setIteratorMode($mode) {}
}

/**
 * @template TValue
 * The SplStack class provides the main functionalities of a stack implemented using a doubly linked list.
 * @link https://php.net/manual/en/class.splstack.php
 * @template-extends SplDoublyLinkedList<TValue>
 */
class SplStack extends SplDoublyLinkedList
{
    /**
     * Sets the mode of iteration
     * @link https://php.net/manual/en/spldoublylinkedlist.setiteratormode.php
     * @param int $mode <p>
     * There are two orthogonal sets of modes that can be set:
     * </p>
     * The direction of the iteration (either one or the other):
     * <b>SplDoublyLinkedList::IT_MODE_LIFO</b> (Stack style)
     * @return void
     */
    public function setIteratorMode($mode) {}
}

/**
 * @template TValue
 * The SplHeap class provides the main functionalities of an Heap.
 * @link https://php.net/manual/en/class.splheap.php
 * @template-implements Iterator<int, TValue>
 */
abstract class SplHeap implements Iterator, Countable
{
    /**
     * Extracts a node from top of the heap and sift up.
     * @link https://php.net/manual/en/splheap.extract.php
     * @return mixed The value of the extracted node.
     */
    #[TentativeType]
    public function extract(): mixed {}

    /**
     * Inserts an element in the heap by sifting it up.
     * @link https://php.net/manual/en/splheap.insert.php
     * @param TValue $value <p>
     * The value to insert.
     * </p>
     * @return bool
     */
    #[TentativeType]
    public function insert(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value): bool {}

    /**
     * Peeks at the node from the top of the heap
     * @link https://php.net/manual/en/splheap.top.php
     * @return TValue The value of the node on the top.
     */
    #[TentativeType]
    public function top(): mixed {}

    /**
     * Counts the number of elements in the heap.
     * @link https://php.net/manual/en/splheap.count.php
     * @return int the number of elements in the heap.
     */
    #[TentativeType]
    public function count(): int {}

    /**
     * Checks whether the heap is empty.
     * @link https://php.net/manual/en/splheap.isempty.php
     * @return bool whether the heap is empty.
     */
    #[TentativeType]
    public function isEmpty(): bool {}

    /**
     * Rewind iterator back to the start (no-op)
     * @link https://php.net/manual/en/splheap.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Return current node pointed by the iterator
     * @link https://php.net/manual/en/splheap.current.php
     * @return TValue The current node value.
     */
    #[TentativeType]
    public function current(): mixed {}

    /**
     * Return current node index
     * @link https://php.net/manual/en/splheap.key.php
     * @return int The current node index.
     */
    #[TentativeType]
    public function key(): int {}

    /**
     * Move to the next node
     * @link https://php.net/manual/en/splheap.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * Check whether the heap contains more nodes
     * @link https://php.net/manual/en/splheap.valid.php
     * @return bool true if the heap contains any more nodes, false otherwise.
     */
    #[TentativeType]
    public function valid(): bool {}

    /**
     * Recover from the corrupted state and allow further actions on the heap.
     * @link https://php.net/manual/en/splheap.recoverfromcorruption.php
     * @return bool
     */
    #[TentativeType]
    public function recoverFromCorruption(): bool {}

    /**
     * Compare elements in order to place them correctly in the heap while sifting up.
     * @link https://php.net/manual/en/splheap.compare.php
     * @param mixed $value1 <p>
     * The value of the first node being compared.
     * </p>
     * @param mixed $value2 <p>
     * The value of the second node being compared.
     * </p>
     * @return int Result of the comparison, positive integer if <i>value1</i> is greater than <i>value2</i>, 0 if they are equal, negative integer otherwise.
     * </p>
     * <p>
     * Having multiple elements with the same value in a Heap is not recommended. They will end up in an arbitrary relative position.
     */
    abstract protected function compare($value1, $value2);

    /**
     * @return bool
     */
    #[TentativeType]
    public function isCorrupted(): bool {}

    /**
     * @return array
     * @since 7.4
     */
    #[TentativeType]
    public function __debugInfo(): array {}
}

/**
 * @template TValue
 * The SplMinHeap class provides the main functionalities of a heap, keeping the minimum on the top.
 * @link https://php.net/manual/en/class.splminheap.php
 * @template-extends SplHeap<TValue>
 */
class SplMinHeap extends SplHeap
{
    /**
     * Compare elements in order to place them correctly in the heap while sifting up.
     * @link https://php.net/manual/en/splminheap.compare.php
     * @param TValue $value1 <p>
     * The value of the first node being compared.
     * </p>
     * @param TValue $value2 <p>
     * The value of the second node being compared.
     * </p>
     * @return int Result of the comparison, positive integer if <i>value1</i> is lower than <i>value2</i>, 0 if they are equal, negative integer otherwise.
     * </p>
     * <p>
     * Having multiple elements with the same value in a Heap is not recommended. They will end up in an arbitrary relative position.
     */
    #[TentativeType]
    protected function compare(
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value1,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value2
    ): int {}

    /**
     * Extracts a node from top of the heap and sift up.
     * @link https://php.net/manual/en/splheap.extract.php
     * @return TValue The value of the extracted node.
     */
    public function extract() {}

    /**
     * Inserts an element in the heap by sifting it up.
     * @link https://php.net/manual/en/splheap.insert.php
     * @param TValue $value <p>
     * The value to insert.
     * </p>
     * @return void
     */
    public function insert($value) {}

    /**
     * Peeks at the node from the top of the heap
     * @link https://php.net/manual/en/splheap.top.php
     * @return TValue The value of the node on the top.
     */
    public function top() {}

    /**
     * Counts the number of elements in the heap.
     * @link https://php.net/manual/en/splheap.count.php
     * @return int the number of elements in the heap.
     */
    public function count() {}

    /**
     * Checks whether the heap is empty.
     * @link https://php.net/manual/en/splheap.isempty.php
     * @return bool whether the heap is empty.
     */
    public function isEmpty() {}

    /**
     * Rewind iterator back to the start (no-op)
     * @link https://php.net/manual/en/splheap.rewind.php
     * @return void
     */
    public function rewind() {}

    /**
     * Return current node pointed by the iterator
     * @link https://php.net/manual/en/splheap.current.php
     * @return TValue The current node value.
     */
    public function current() {}

    /**
     * Return current node index
     * @link https://php.net/manual/en/splheap.key.php
     * @return int The current node index.
     */
    public function key() {}

    /**
     * Move to the next node
     * @link https://php.net/manual/en/splheap.next.php
     * @return void
     */
    public function next() {}

    /**
     * Check whether the heap contains more nodes
     * @link https://php.net/manual/en/splheap.valid.php
     * @return bool true if the heap contains any more nodes, false otherwise.
     */
    public function valid() {}

    /**
     * Recover from the corrupted state and allow further actions on the heap.
     * @link https://php.net/manual/en/splheap.recoverfromcorruption.php
     * @return void
     */
    public function recoverFromCorruption() {}
}

/**
 * @template TValue
 * The SplMaxHeap class provides the main functionalities of a heap, keeping the maximum on the top.
 * @link https://php.net/manual/en/class.splmaxheap.php
 * @template-extends SplHeap<TValue>
 */
class SplMaxHeap extends SplHeap
{
    /**
     * Compare elements in order to place them correctly in the heap while sifting up.
     * @link https://php.net/manual/en/splmaxheap.compare.php
     * @param TValue $value1 <p>
     * The value of the first node being compared.
     * </p>
     * @param TValue $value2 <p>
     * The value of the second node being compared.
     * </p>
     * @return int Result of the comparison, positive integer if <i>value1</i> is greater than <i>value2</i>, 0 if they are equal, negative integer otherwise.
     * </p>
     * <p>
     * Having multiple elements with the same value in a Heap is not recommended. They will end up in an arbitrary relative position.
     */
    #[TentativeType]
    protected function compare(
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value1,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value2
    ): int {}
}

/**
 * @template TPriority
 * @template TValue
 * The SplPriorityQueue class provides the main functionalities of an
 * prioritized queue, implemented using a heap.
 * @link https://php.net/manual/en/class.splpriorityqueue.php
 * @template-implements Iterator<int, TValue>
 */
class SplPriorityQueue implements Iterator, Countable
{
    public const EXTR_BOTH = 3;
    public const EXTR_PRIORITY = 2;
    public const EXTR_DATA = 1;

    /**
     * Compare priorities in order to place elements correctly in the heap while sifting up.
     * @link https://php.net/manual/en/splpriorityqueue.compare.php
     * @param TPriority $priority1 <p>
     * The priority of the first node being compared.
     * </p>
     * @param TPriority $priority2 <p>
     * The priority of the second node being compared.
     * </p>
     * @return int Result of the comparison, positive integer if <i>priority1</i> is greater than <i>priority2</i>, 0 if they are equal, negative integer otherwise.
     * </p>
     * <p>
     * Multiple elements with the same priority will get dequeued in no particular order.
     */
    #[TentativeType]
    public function compare(
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $priority1,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $priority2
    ): int {}

    /**
     * Inserts an element in the queue by sifting it up.
     * @link https://php.net/manual/en/splpriorityqueue.insert.php
     * @param TValue $value <p>
     * The value to insert.
     * </p>
     * @param TPriority $priority <p>
     * The associated priority.
     * </p>
     * @return true
     */
    public function insert(
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $priority
    ) {}

    /**
     * Sets the mode of extraction
     * @link https://php.net/manual/en/splpriorityqueue.setextractflags.php
     * @param int $flags <p>
     * Defines what is extracted by <b>SplPriorityQueue::current</b>,
     * <b>SplPriorityQueue::top</b> and
     * <b>SplPriorityQueue::extract</b>.
     * </p>
     * <b>SplPriorityQueue::EXTR_DATA</b> (0x00000001): Extract the data
     * @return int
     */
    #[TentativeType]
    public function setExtractFlags(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags): int {}

    /**
     * Peeks at the node from the top of the queue
     * @link https://php.net/manual/en/splpriorityqueue.top.php
     * @return TValue The value or priority (or both) of the top node, depending on the extract flag.
     */
    #[TentativeType]
    public function top(): mixed {}

    /**
     * Extracts a node from top of the heap and sift up.
     * @link https://php.net/manual/en/splpriorityqueue.extract.php
     * @return TValue The value or priority (or both) of the extracted node, depending on the extract flag.
     */
    #[TentativeType]
    public function extract(): mixed {}

    /**
     * Counts the number of elements in the queue.
     * @link https://php.net/manual/en/splpriorityqueue.count.php
     * @return int the number of elements in the queue.
     */
    #[TentativeType]
    public function count(): int {}

    /**
     * Checks whether the queue is empty.
     * @link https://php.net/manual/en/splpriorityqueue.isempty.php
     * @return bool whether the queue is empty.
     */
    #[TentativeType]
    public function isEmpty(): bool {}

    /**
     * Rewind iterator back to the start (no-op)
     * @link https://php.net/manual/en/splpriorityqueue.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Return current node pointed by the iterator
     * @link https://php.net/manual/en/splpriorityqueue.current.php
     * @return TValue The value or priority (or both) of the current node, depending on the extract flag.
     */
    #[TentativeType]
    public function current(): mixed {}

    /**
     * Return current node index
     * @link https://php.net/manual/en/splpriorityqueue.key.php
     * @return int The current node index.
     */
    #[TentativeType]
    public function key(): int {}

    /**
     * Move to the next node
     * @link https://php.net/manual/en/splpriorityqueue.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * Check whether the queue contains more nodes
     * @link https://php.net/manual/en/splpriorityqueue.valid.php
     * @return bool true if the queue contains any more nodes, false otherwise.
     */
    #[TentativeType]
    public function valid(): bool {}

    /**
     * Recover from the corrupted state and allow further actions on the queue.
     * @link https://php.net/manual/en/splpriorityqueue.recoverfromcorruption.php
     * @return void
     */
    public function recoverFromCorruption() {}

    /**
     * @return bool
     */
    #[TentativeType]
    public function isCorrupted(): bool {}

    /**
     * @return int
     */
    #[TentativeType]
    public function getExtractFlags(): int {}

    /**
     * @return array
     * @since 7.4
     */
    #[TentativeType]
    public function __debugInfo(): array {}
}

/**
 * @template TValue
 * The SplFixedArray class provides the main functionalities of array. The
 * main differences between a SplFixedArray and a normal PHP array is that
 * the SplFixedArray is of fixed length and allows only integers within
 * the range as indexes. The advantage is that it allows a faster array
 * implementation.
 * @link https://php.net/manual/en/class.splfixedarray.php
 * @template-implements Iterator<int, TValue>
 * @template-implements ArrayAccess<int, TValue>
 * @template-implements IteratorAggregate<int, TValue>
 */
class SplFixedArray implements Iterator, ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /**
     * Constructs a new fixed array
     * @link https://php.net/manual/en/splfixedarray.construct.php
     * @param int $size [optional]
     */
    public function __construct(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $size = 0) {}

    /**
     * Returns the size of the array
     * @link https://php.net/manual/en/splfixedarray.count.php
     * @return int the size of the array.
     */
    #[TentativeType]
    public function count(): int {}

    /**
     * Returns a PHP array from the fixed array
     * @link https://php.net/manual/en/splfixedarray.toarray.php
     * @return TValue[] a PHP array, similar to the fixed array.
     */
    #[TentativeType]
    public function toArray(): array {}

    /**
     * Import a PHP array in a <b>SplFixedArray</b> instance
     * @link https://php.net/manual/en/splfixedarray.fromarray.php
     * @param array $array <p>
     * The array to import.
     * </p>
     * @param bool $preserveKeys [optional] <p>
     * Try to save the numeric indexes used in the original array.
     * </p>
     * @return SplFixedArray an instance of <b>SplFixedArray</b>
     * containing the array content.
     */
    #[TentativeType]
    public static function fromArray(
        #[LanguageLevelTypeAware(['8.0' => 'array'], default: '')] $array,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $preserveKeys = true
    ): SplFixedArray {}

    /**
     * Gets the size of the array
     * @link https://php.net/manual/en/splfixedarray.getsize.php
     * @return int the size of the array, as an integer.
     */
    #[TentativeType]
    public function getSize(): int {}

    /**
     * Change the size of an array
     * @link https://php.net/manual/en/splfixedarray.setsize.php
     * @param int $size <p>
     * The new array size.
     * </p>
     * @return bool
     */
    public function setSize(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $size) {}

    /**
     * Returns whether the requested index exists
     * @link https://php.net/manual/en/splfixedarray.offsetexists.php
     * @param int $index <p>
     * The index being checked.
     * </p>
     * @return bool true if the requested <i>index</i> exists, otherwise false
     */
    #[TentativeType]
    public function offsetExists($index): bool {}

    /**
     * Returns the value at the specified index
     * @link https://php.net/manual/en/splfixedarray.offsetget.php
     * @param int $index <p>
     * The index with the value.
     * </p>
     * @return TValue The value at the specified <i>index</i>.
     */
    #[TentativeType]
    public function offsetGet($index): mixed {}

    /**
     * Sets a new value at a specified index
     * @link https://php.net/manual/en/splfixedarray.offsetset.php
     * @param int $index <p>
     * The index being set.
     * </p>
     * @param TValue $value <p>
     * The new value for the <i>index</i>.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function offsetSet($index, #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value): void {}

    /**
     * Unsets the value at the specified $index
     * @link https://php.net/manual/en/splfixedarray.offsetunset.php
     * @param int $index <p>
     * The index being unset.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function offsetUnset($index): void {}

    /**
     * Rewind iterator back to the start
     * @link https://php.net/manual/en/splfixedarray.rewind.php
     * @return void
     */
    public function rewind() {}

    /**
     * Return current array entry
     * @link https://php.net/manual/en/splfixedarray.current.php
     * @return TValue The current element value.
     */
    public function current() {}

    /**
     * Return current array index
     * @link https://php.net/manual/en/splfixedarray.key.php
     * @return int The current array index.
     */
    public function key() {}

    /**
     * Move to next entry
     * @link https://php.net/manual/en/splfixedarray.next.php
     * @return void
     */
    public function next() {}

    /**
     * Check whether the array contains more elements
     * @link https://php.net/manual/en/splfixedarray.valid.php
     * @return bool true if the array contains any more elements, false otherwise.
     */
    #[TentativeType]
    public function valid(): bool {}

    #[TentativeType]
    public function __wakeup(): void {}

    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function __serialize(): array {}

    /**
     * @param array $data
     */
    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function __unserialize(array $data): void {}

    /**
     * @return Traversable<int, TValue>
     */
    public function getIterator(): Iterator {}

    public function jsonSerialize(): array {}
}

/**
 * The <b>SplObserver</b> interface is used alongside
 * <b>SplSubject</b> to implement the Observer Design Pattern.
 * @link https://php.net/manual/en/class.splobserver.php
 */
interface SplObserver
{
    /**
     * Receive update from subject
     * @link https://php.net/manual/en/splobserver.update.php
     * @param SplSubject $subject <p>
     * The <b>SplSubject</b> notifying the observer of an update.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function update(SplSubject $subject): void;
}

/**
 * The <b>SplSubject</b> interface is used alongside
 * <b>SplObserver</b> to implement the Observer Design Pattern.
 * @link https://php.net/manual/en/class.splsubject.php
 */
interface SplSubject
{
    /**
     * Attach an SplObserver
     * @link https://php.net/manual/en/splsubject.attach.php
     * @param SplObserver $observer <p>
     * The <b>SplObserver</b> to attach.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function attach(SplObserver $observer): void;

    /**
     * Detach an observer
     * @link https://php.net/manual/en/splsubject.detach.php
     * @param SplObserver $observer <p>
     * The <b>SplObserver</b> to detach.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function detach(SplObserver $observer): void;

    /**
     * Notify an observer
     * @link https://php.net/manual/en/splsubject.notify.php
     * @return void
     */
    #[TentativeType]
    public function notify(): void;
}

/**
 * @template TObject of object
 * @template TValue
 * The SplObjectStorage class provides a map from objects to data or, by
 * ignoring data, an object set. This dual purpose can be useful in many
 * cases involving the need to uniquely identify objects.
 * @link https://php.net/manual/en/class.splobjectstorage.php
 * @template-implements Iterator<int, TObject>
 * @template-implements ArrayAccess<TObject, TValue>
 */
class SplObjectStorage implements Countable, Iterator, Serializable, ArrayAccess
{
    /**
     * Adds an object in the storage
     * @link https://php.net/manual/en/splobjectstorage.attach.php
     * @param TObject $object <p>
     * The object to add.
     * </p>
     * @param TValue $info [optional] <p>
     * The data to associate with the object.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function attach(
        #[LanguageLevelTypeAware(['8.0' => 'object'], default: '')] $object,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $info = null
    ): void {}

    /**
     * Removes an object from the storage
     * @link https://php.net/manual/en/splobjectstorage.detach.php
     * @param TObject $object <p>
     * The object to remove.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function detach(#[LanguageLevelTypeAware(['8.0' => 'object'], default: '')] $object): void {}

    /**
     * Checks if the storage contains a specific object
     * @link https://php.net/manual/en/splobjectstorage.contains.php
     * @param TObject $object <p>
     * The object to look for.
     * </p>
     * @return bool true if the object is in the storage, false otherwise.
     */
    #[TentativeType]
    public function contains(#[LanguageLevelTypeAware(['8.0' => 'object'], default: '')] $object): bool {}

    /**
     * Adds all objects from another storage
     * @link https://php.net/manual/en/splobjectstorage.addall.php
     * @param SplObjectStorage<TObject, TValue> $storage <p>
     * The storage you want to import.
     * </p>
     * @return int
     */
    #[TentativeType]
    public function addAll(#[LanguageLevelTypeAware(['8.0' => 'SplObjectStorage'], default: '')] $storage): int {}

    /**
     * Removes objects contained in another storage from the current storage
     * @link https://php.net/manual/en/splobjectstorage.removeall.php
     * @param SplObjectStorage<TObject, TValue> $storage <p>
     * The storage containing the elements to remove.
     * </p>
     * @return int
     */
    #[TentativeType]
    public function removeAll(#[LanguageLevelTypeAware(['8.0' => 'SplObjectStorage'], default: '')] $storage): int {}

    /**
     * Removes all objects except for those contained in another storage from the current storage
     * @link https://php.net/manual/en/splobjectstorage.removeallexcept.php
     * @param SplObjectStorage<TObject, TValue> $storage <p>
     * The storage containing the elements to retain in the current storage.
     * </p>
     * @return int
     * @since 5.3.6
     */
    #[TentativeType]
    public function removeAllExcept(#[LanguageLevelTypeAware(['8.0' => 'SplObjectStorage'], default: '')] $storage): int {}

    /**
     * Returns the data associated with the current iterator entry
     * @link https://php.net/manual/en/splobjectstorage.getinfo.php
     * @return TValue The data associated with the current iterator position.
     */
    #[TentativeType]
    public function getInfo(): mixed {}

    /**
     * Sets the data associated with the current iterator entry
     * @link https://php.net/manual/en/splobjectstorage.setinfo.php
     * @param TValue $info <p>
     * The data to associate with the current iterator entry.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setInfo(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $info): void {}

    /**
     * Returns the number of objects in the storage
     * @link https://php.net/manual/en/splobjectstorage.count.php
     * @param int $mode [optional]
     * @return int The number of objects in the storage.
     */
    #[TentativeType]
    public function count(#[PhpStormStubsElementAvailable(from: '8.0')] int $mode = COUNT_NORMAL): int {}

    /**
     * Rewind the iterator to the first storage element
     * @link https://php.net/manual/en/splobjectstorage.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Returns if the current iterator entry is valid
     * @link https://php.net/manual/en/splobjectstorage.valid.php
     * @return bool true if the iterator entry is valid, false otherwise.
     */
    #[TentativeType]
    public function valid(): bool {}

    /**
     * Returns the index at which the iterator currently is
     * @link https://php.net/manual/en/splobjectstorage.key.php
     * @return int The index corresponding to the position of the iterator.
     */
    #[TentativeType]
    public function key(): int {}

    /**
     * Returns the current storage entry
     * @link https://php.net/manual/en/splobjectstorage.current.php
     * @return TObject The object at the current iterator position.
     */
    #[TentativeType]
    public function current(): object {}

    /**
     * Move to the next entry
     * @link https://php.net/manual/en/splobjectstorage.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * Unserializes a storage from its string representation
     * @link https://php.net/manual/en/splobjectstorage.unserialize.php
     * @param string $data <p>
     * The serialized representation of a storage.
     * </p>
     * @return void
     * @since 5.2.2
     */
    #[TentativeType]
    public function unserialize(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data): void {}

    /**
     * Serializes the storage
     * @link https://php.net/manual/en/splobjectstorage.serialize.php
     * @return string A string representing the storage.
     * @since 5.2.2
     */
    #[TentativeType]
    public function serialize(): string {}

    /**
     * Checks whether an object exists in the storage
     * @link https://php.net/manual/en/splobjectstorage.offsetexists.php
     * @param TObject $object <p>
     * The object to look for.
     * </p>
     * @return bool true if the object exists in the storage,
     * and false otherwise.
     */
    #[TentativeType]
    public function offsetExists($object): bool {}

    /**
     * Associates data to an object in the storage
     * @link https://php.net/manual/en/splobjectstorage.offsetset.php
     * @param TObject $object <p>
     * The object to associate data with.
     * </p>
     * @param TValue $info [optional] <p>
     * The data to associate with the object.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function offsetSet(
        #[LanguageLevelTypeAware(['8.1' => 'mixed'], default: '')] $object,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $info = null
    ): void {}

    /**
     * Removes an object from the storage
     * @link https://php.net/manual/en/splobjectstorage.offsetunset.php
     * @param TObject $object <p>
     * The object to remove.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function offsetUnset($object): void {}

    /**
     * Returns the data associated with an <type>object</type>
     * @link https://php.net/manual/en/splobjectstorage.offsetget.php
     * @param TObject $object <p>
     * The object to look for.
     * </p>
     * @return TValue The data previously associated with the object in the storage.
     */
    #[TentativeType]
    public function offsetGet($object): mixed {}

    /**
     * Calculate a unique identifier for the contained objects
     * @link https://php.net/manual/en/splobjectstorage.gethash.php
     * @param TObject $object <p>
     * object whose identifier is to be calculated.
     * </p>
     * @return string A string with the calculated identifier.
     * @since 5.4
     */
    #[TentativeType]
    public function getHash(#[LanguageLevelTypeAware(['8.0' => 'object'], default: '')] $object): string {}

    /**
     * @return array
     * @since 7.4
     */
    #[TentativeType]
    public function __serialize(): array {}

    /**
     * @param array $data
     * @since 7.4
     */
    #[TentativeType]
    public function __unserialize(array $data): void {}

    /**
     * @return array
     * @since 7.4
     */
    #[TentativeType]
    public function __debugInfo(): array {}
}

/**
 * An Iterator that sequentially iterates over all attached iterators
 * @link https://php.net/manual/en/class.multipleiterator.php
 */
class MultipleIterator implements Iterator
{
    public const MIT_NEED_ANY = 0;
    public const MIT_NEED_ALL = 1;
    public const MIT_KEYS_NUMERIC = 0;
    public const MIT_KEYS_ASSOC = 2;

    /**
     * Constructs a new MultipleIterator
     * @link https://php.net/manual/en/multipleiterator.construct.php
     * @param int $flags Defaults to MultipleIterator::MIT_NEED_ALL | MultipleIterator::MIT_KEYS_NUMERIC
     */
    public function __construct(
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $flags,
        #[PhpStormStubsElementAvailable(from: '8.0')] int $flags = MultipleIterator::MIT_NEED_ALL|MultipleIterator::MIT_KEYS_NUMERIC
    ) {}

    /**
     * Gets the flag information
     * @link https://php.net/manual/en/multipleiterator.getflags.php
     * @return int Information about the flags, as an integer.
     */
    #[TentativeType]
    public function getFlags(): int {}

    /**
     * Sets flags
     * @link https://php.net/manual/en/multipleiterator.setflags.php
     * @param int $flags <p>
     * The flags to set, according to the
     * Flag Constants
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setFlags(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags): void {}

    /**
     * Attaches iterator information
     * @link https://php.net/manual/en/multipleiterator.attachiterator.php
     * @param Iterator $iterator <p>
     * The new iterator to attach.
     * </p>
     * @param int|string|null $info [optional] <p>
     * The associative information for the Iterator, which must be an
     * integer, a string, or null.
     * </p>
     * @return void Description...
     */
    #[TentativeType]
    public function attachIterator(Iterator $iterator, #[LanguageLevelTypeAware(['8.0' => 'int|string|null'], default: '')] $info = null): void {}

    /**
     * Detaches an iterator
     * @link https://php.net/manual/en/multipleiterator.detachiterator.php
     * @param Iterator $iterator <p>
     * The iterator to detach.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function detachIterator(Iterator $iterator): void {}

    /**
     * Checks if an iterator is attached
     * @link https://php.net/manual/en/multipleiterator.containsiterator.php
     * @param Iterator $iterator <p>
     * The iterator to check.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function containsIterator(Iterator $iterator): bool {}

    /**
     * Gets the number of attached iterator instances
     * @link https://php.net/manual/en/multipleiterator.countiterators.php
     * @return int The number of attached iterator instances (as an integer).
     */
    #[TentativeType]
    public function countIterators(): int {}

    /**
     * Rewinds all attached iterator instances
     * @link https://php.net/manual/en/multipleiterator.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Checks the validity of sub iterators
     * @link https://php.net/manual/en/multipleiterator.valid.php
     * @return bool true if one or all sub iterators are valid depending on flags,
     * otherwise false
     */
    #[TentativeType]
    public function valid(): bool {}

    /**
     * Gets the registered iterator instances
     * @link https://php.net/manual/en/multipleiterator.key.php
     * @return array An array of all registered iterator instances,
     * or false if no sub iterator is attached.
     */
    #[TentativeType]
    public function key(): array {}

    /**
     * Gets the registered iterator instances
     * @link https://php.net/manual/en/multipleiterator.current.php
     * @return array An array containing the current values of each attached iterator,
     * or false if no iterators are attached.
     * @throws RuntimeException if mode MIT_NEED_ALL is set and at least one attached iterator is not valid.
     * @throws InvalidArgumentException if a key is NULL and MIT_KEYS_ASSOC is set.
     */
    #[TentativeType]
    public function current(): array {}

    /**
     * Moves all attached iterator instances forward
     * @link https://php.net/manual/en/multipleiterator.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * @return array
     * @since 7.4
     */
    #[TentativeType]
    public function __debugInfo(): array {}
}
