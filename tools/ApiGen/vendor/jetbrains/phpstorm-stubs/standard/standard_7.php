<?php

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Pure;

/**
 * Open Internet or Unix domain socket connection
 * @link https://php.net/manual/en/function.fsockopen.php
 * @param string $hostname <p>
 * If you have compiled in OpenSSL support, you may prefix the
 * hostname with either ssl://
 * or tls:// to use an SSL or TLS client connection
 * over TCP/IP to connect to the remote host.
 * </p>
 * @param int $port <p>
 * The port number.
 * </p>
 * @param int &$error_code [optional] <p>
 * If provided, holds the system level error number that occurred in the
 * system-level connect() call.
 * </p>
 * <p>
 * If the value returned in errno is
 * 0 and the function returned false, it is an
 * indication that the error occurred before the
 * connect() call. This is most likely due to a
 * problem initializing the socket.
 * </p>
 * @param string &$error_message [optional] <p>
 * The error message as a string.
 * </p>
 * @param float|null $timeout [optional] <p>
 * The connection timeout, in seconds.
 * </p>
 * <p>
 * If you need to set a timeout for reading/writing data over the
 * socket, use stream_set_timeout, as the
 * timeout parameter to
 * fsockopen only applies while connecting the
 * socket.
 * </p>
 * @return resource|false fsockopen returns a file pointer which may be used
 * together with the other file functions (such as
 * fgets, fgetss,
 * fwrite, fclose, and
 * feof). If the call fails, it will return false
 */
function fsockopen(
    string $hostname,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] int $port,
    #[PhpStormStubsElementAvailable(from: '7.1')] int $port = -1,
    &$error_code,
    &$error_message,
    ?float $timeout
) {}

/**
 * Open persistent Internet or Unix domain socket connection
 * @link https://php.net/manual/en/function.pfsockopen.php
 * @see fsockopen
 * @param string $hostname
 * @param int $port
 * @param int &$error_code [optional]
 * @param string &$error_message [optional]
 * @param float|null $timeout [optional]
 * @return resource|false
 */
function pfsockopen(
    string $hostname,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] int $port,
    #[PhpStormStubsElementAvailable(from: '7.1')] int $port = -1,
    &$error_code,
    &$error_message,
    ?float $timeout
) {}

/**
 * Pack data into binary string
 * @link https://php.net/manual/en/function.pack.php
 * @param string $format <p>
 * The format string consists of format codes
 * followed by an optional repeater argument. The repeater argument can
 * be either an integer value or * for repeating to
 * the end of the input data. For a, A, h, H the repeat count specifies
 * how many characters of one data argument are taken, for @ it is the
 * absolute position where to put the next data, for everything else the
 * repeat count specifies how many data arguments are consumed and packed
 * into the resulting binary string.
 * </p>
 * <p>
 * Currently implemented formats are:
 * <table>
 * pack format characters
 * <tr valign="top">
 * <td>Code</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>a</td>
 * <td>NUL-padded string</td>
 * </tr>
 * <tr valign="top">
 * <td>A</td>
 * <td>SPACE-padded string</td></tr>
 * <tr valign="top">
 * <td>h</td>
 * <td>Hex string, low nibble first</td></tr>
 * <tr valign="top">
 * <td>H</td>
 * <td>Hex string, high nibble first</td></tr>
 * <tr valign="top"><td>c</td><td>signed char</td></tr>
 * <tr valign="top">
 * <td>C</td>
 * <td>unsigned char</td></tr>
 * <tr valign="top">
 * <td>s</td>
 * <td>signed short (always 16 bit, machine byte order)</td>
 * </tr>
 * <tr valign="top">
 * <td>S</td>
 * <td>unsigned short (always 16 bit, machine byte order)</td>
 * </tr>
 * <tr valign="top">
 * <td>n</td>
 * <td>unsigned short (always 16 bit, big endian byte order)</td>
 * </tr>
 * <tr valign="top">
 * <td>v</td>
 * <td>unsigned short (always 16 bit, little endian byte order)</td>
 * </tr>
 * <tr valign="top">
 * <td>i</td>
 * <td>signed integer (machine dependent size and byte order)</td>
 * </tr>
 * <tr valign="top">
 * <td>I</td>
 * <td>unsigned integer (machine dependent size and byte order)</td>
 * </tr>
 * <tr valign="top">
 * <td>l</td>
 * <td>signed long (always 32 bit, machine byte order)</td>
 * </tr>
 * <tr valign="top">
 * <td>L</td>
 * <td>unsigned long (always 32 bit, machine byte order)</td>
 * </tr>
 * <tr valign="top">
 * <td>N</td>
 * <td>unsigned long (always 32 bit, big endian byte order)</td>
 * </tr>
 * <tr valign="top">
 * <td>V</td>
 * <td>unsigned long (always 32 bit, little endian byte order)</td>
 * </tr>
 * <tr valign="top">
 * <td>f</td>
 * <td>float (machine dependent size and representation, both little and big endian)</td>
 * </tr>
 * <tr valign="top">
 * <td>d</td>
 * <td>double (machine dependent size and representation, both little and big endian)</td>
 * </tr>
 * <tr valign="top">
 * <td>x</td>
 * <td>NUL byte</td>
 * </tr>
 * <tr valign="top">
 * <td>X</td>
 * <td>Back up one byte</td>
 * </tr>
 * <tr valign="top">
 * <td>@</td>
 * <td>NUL-fill to absolute position</td>
 * </tr>
 * </table>
 * </p>
 * @param mixed ...$values <p>
 * </p>
 * @return string|false a binary string containing data or false if the format string contains errors
 */
#[Pure]
#[LanguageLevelTypeAware(["8.0" => "string"], default: "string|false")]
function pack(
    string $format,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.3')] $values,
    mixed ...$values
) {}

/**
 * Unpack data from binary string
 * @link https://php.net/manual/en/function.unpack.php
 * @param string $format <p>
 * See pack for an explanation of the format codes.
 * </p>
 * @param string $string <p>
 * The packed data.
 * </p>
 * @param int $offset [optional]
 * @return array|false an associative array containing unpacked elements of binary
 * string or false if the format string contains errors
 */
#[Pure]
function unpack(
    string $format,
    string $string,
    #[PhpStormStubsElementAvailable(from: '7.1')] int $offset = 0
): array|false {}

/**
 * Tells what the user's browser is capable of
 * @link https://php.net/manual/en/function.get-browser.php
 * @param string|null $user_agent [optional] <p>
 * The User Agent to be analyzed. By default, the value of HTTP
 * User-Agent header is used; however, you can alter this (i.e., look up
 * another browser's info) by passing this parameter.
 * </p>
 * <p>
 * You can bypass this parameter with a null value.
 * </p>
 * @param bool $return_array [optional] <p>
 * If set to true, this function will return an array
 * instead of an object.
 * </p>
 * @return array|object|false Returns false if browscap.ini can't be loaded or the user agent can't be found, otherwise the information is returned in an object or an array which will contain
 * various data elements representing, for instance, the browser's major and
 * minor version numbers and ID string; true/false values for features
 * such as frames, JavaScript, and cookies; and so forth.
 * </p>
 * <p>
 * The cookies value simply means that the browser
 * itself is capable of accepting cookies and does not mean the user has
 * enabled the browser to accept cookies or not. The only way to test if
 * cookies are accepted is to set one with setcookie,
 * reload, and check for the value.
 */
#[Pure(true)]
function get_browser(?string $user_agent, bool $return_array = false): object|array|false {}

/**
 * One-way string encryption (hashing)
 * @link https://php.net/manual/en/function.crypt.php
 * @param string $string <p>
 * The string to be encrypted.
 * </p>
 * @param string $salt <p>
 * An optional salt string to base the encryption on. If not provided,
 * one will be randomly generated by PHP each time you call this function.
 * PHP 5.6 or later raise E_NOTICE error if this parameter is omitted
 * </p>
 * <p>
 * If you are using the supplied salt, you should be aware that the salt
 * is generated once. If you are calling this function repeatedly, this
 * may impact both appearance and security.
 * </p>
 * @return string|null the encrypted string or <b>NULL</b> if an error occurs
 */
#[Pure]
#[PhpStormStubsElementAvailable(to: '7.4')]
function crypt($string, $salt): ?string {}

/**
 * One-way string encryption (hashing)
 * @link https://php.net/manual/en/function.crypt.php
 * @param string $string <p>
 * The string to be encrypted.
 * </p>
 * @param string $salt <p>
 * An optional salt string to base the encryption on. If not provided,
 * one will be randomly generated by PHP each time you call this function.
 * PHP 5.6 or later raise E_NOTICE error if this parameter is omitted
 * </p>
 * <p>
 * If you are using the supplied salt, you should be aware that the salt
 * is generated once. If you are calling this function repeatedly, this
 * may impact both appearance and security.
 * </p>
 * @return string the encrypted string or <b>NULL</b> if an error occurs
 */
#[Pure]
#[PhpStormStubsElementAvailable('8.0')]
function crypt(string $string, string $salt): string {}

/**
 * Open directory handle
 * @link https://php.net/manual/en/function.opendir.php
 * @param string $directory <p>
 * The directory path that is to be opened
 * </p>
 * @param resource $context [optional] <p>
 * For a description of the context parameter,
 * refer to the streams section of
 * the manual.
 * </p>
 * @return resource|false a directory handle resource on success, or
 * false on failure.
 * </p>
 * <p>
 * If path is not a valid directory or the
 * directory can not be opened due to permission restrictions or
 * filesystem errors, opendir returns false and
 * generates a PHP error of level
 * E_WARNING. You can suppress the error output of
 * opendir by prepending
 * '@' to the
 * front of the function name.
 */
function opendir(string $directory, $context) {}

/**
 * Close directory handle
 * @link https://php.net/manual/en/function.closedir.php
 * @param resource $dir_handle [optional] <p>
 * The directory handle resource previously opened
 * with opendir. If the directory handle is
 * not specified, the last link opened by opendir
 * is assumed.
 * </p>
 * @return void
 */
function closedir($dir_handle): void {}

/**
 * Change directory
 * @link https://php.net/manual/en/function.chdir.php
 * @param string $directory <p>
 * The new current directory
 * </p>
 * @return bool true on success or false on failure.
 */
function chdir(string $directory): bool {}

/**
 * Change the root directory
 * @link https://php.net/manual/en/function.chroot.php
 * @param string $directory <p>
 * The new directory
 * </p>
 * @return bool true on success or false on failure.
 */
function chroot(string $directory): bool {}

/**
 * Gets the current working directory
 * @link https://php.net/manual/en/function.getcwd.php
 * @return string|false <p>
 * the current working directory on success, or false on
 * failure. <br>
 * <br>
 * On some Unix variants, getcwd will return
 * false if any one of the parent directories does not have the
 * readable or search mode set, even if the current directory
 * does. See chmod for more information on
 * modes and permissions.
 * </p>
 */
#[Pure(true)]
function getcwd(): string|false {}

/**
 * Rewind directory handle
 * @link https://php.net/manual/en/function.rewinddir.php
 * @param resource $dir_handle [optional] <p>
 * The directory handle resource previously opened
 * with opendir. If the directory handle is
 * not specified, the last link opened by opendir
 * is assumed.
 * </p>
 * @return null|false
 * @see https://bugs.php.net/bug.php?id=75485
 */
function rewinddir($dir_handle): null|false {}

/**
 * Read entry from directory handle
 * @link https://php.net/manual/en/function.readdir.php
 * @param resource $dir_handle [optional] <p>
 * The directory handle resource previously opened
 * with opendir. If the directory handle is
 * not specified, the last link opened by opendir
 * is assumed.
 * </p>
 * @return string|false the filename on success or false on failure.
 */
function readdir($dir_handle): string|false {}

/**
 * Return an instance of the Directory class
 * @link https://php.net/manual/en/function.dir.php
 * @param string $directory <p>
 * Directory to open
 * </p>
 * @param resource $context [optional]
 * @return Directory|false an instance of Directory, or <b>NULL</b> with wrong
 * parameters, or <b>FALSE</b> in case of another error
 */
function dir(string $directory, $context): Directory|false {}

/**
 * Alias of dir()
 * @param string $directory
 * @param resource $context
 * @since 8.0
 * @return Directory|false
 * @see dir()
 */
function getdir(string $directory, $context = null): Directory|false {}

/**
 * List files and directories inside the specified path
 * @link https://php.net/manual/en/function.scandir.php
 * @param string $directory <p>
 * The directory that will be scanned.
 * </p>
 * @param int $sorting_order <p>
 * By default, the sorted order is alphabetical in ascending order. If
 * the optional sorting_order is set to non-zero,
 * then the sort order is alphabetical in descending order.
 * </p>
 * @param resource $context [optional] <p>
 * For a description of the context parameter,
 * refer to the streams section of
 * the manual.
 * </p>
 * @return array|false an array of filenames on success, or false on
 * failure. If directory is not a directory, then
 * boolean false is returned, and an error of level
 * E_WARNING is generated.
 */
function scandir(string $directory, int $sorting_order = 0, $context): array|false {}

/**
 * Find pathnames matching a pattern
 * @link https://php.net/manual/en/function.glob.php
 * @param string $pattern <p>
 * The pattern. No tilde expansion or parameter substitution is done.
 * </p>
 * @param int $flags <p>
 * Valid flags:
 * GLOB_MARK - Adds a slash to each directory returned
 * GLOB_NOSORT - Return files as they appear in the directory (no sorting). When this flag is not used, the pathnames are sorted alphabetically
 * GLOB_NOCHECK - Return the search pattern if no files matching it were found
 * GLOB_NOESCAPE - Backslashes do not quote metacharacters
 * GLOB_BRACE - Expands {a,b,c} to match 'a', 'b', or 'c'
 * GLOB_ONLYDIR - Return only directory entries which match the pattern
 * GLOB_ERR - Stop on read errors (like unreadable directories), by default errors are ignored.
 * @return array|false an array containing the matched files/directories, an empty array
 * if no file matched or false on error.
 * </p>
 * <p>
 * On some systems it is impossible to distinguish between empty match and an
 * error.</p>
 */
#[Pure(true)]
function glob(string $pattern, int $flags = 0): array|false {}

/**
 * Gets last access time of file
 * @link https://php.net/manual/en/function.fileatime.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @return int|false the time the file was last accessed, or false on failure.
 * The time is returned as a Unix timestamp.
 */
#[Pure(true)]
function fileatime(string $filename): int|false {}

/**
 * Gets inode change time of file
 * @link https://php.net/manual/en/function.filectime.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @return int|false the time the file was last changed, or false on failure.
 * The time is returned as a Unix timestamp.
 */
#[Pure(true)]
function filectime(string $filename): int|false {}

/**
 * Gets file group
 * @link https://php.net/manual/en/function.filegroup.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @return int|false the group ID of the file, or false in case
 * of an error. The group ID is returned in numerical format, use
 * posix_getgrgid to resolve it to a group name.
 * Upon failure, false is returned.
 */
#[Pure(true)]
function filegroup(string $filename): int|false {}

/**
 * Gets file inode
 * @link https://php.net/manual/en/function.fileinode.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @return int|false the inode number of the file, or false on failure.
 */
#[Pure(true)]
function fileinode(string $filename): int|false {}

/**
 * Gets file modification time
 * @link https://php.net/manual/en/function.filemtime.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @return int|false the time the file was last modified, or false on failure.
 * The time is returned as a Unix timestamp, which is
 * suitable for the date function.
 */
#[Pure(true)]
function filemtime(string $filename): int|false {}

/**
 * Gets file owner
 * @link https://php.net/manual/en/function.fileowner.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @return int|false the user ID of the owner of the file, or false on failure.
 * The user ID is returned in numerical format, use
 * posix_getpwuid to resolve it to a username.
 */
#[Pure(true)]
function fileowner(string $filename): int|false {}

/**
 * Gets file permissions
 * @link https://php.net/manual/en/function.fileperms.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @return int|false the permissions on the file, or false on failure.
 */
#[Pure(true)]
function fileperms(string $filename): int|false {}

/**
 * Gets file size
 * @link https://php.net/manual/en/function.filesize.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @return int|false the size of the file in bytes, or false (and generates an error
 * of level E_WARNING) in case of an error.
 */
#[Pure(true)]
function filesize(string $filename): int|false {}

/**
 * Gets file type
 * @link https://php.net/manual/en/function.filetype.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @return string|false the type of the file. Possible values are fifo, char,
 * dir, block, link, file, socket and unknown.
 * </p>
 * <p>
 * Returns false if an error occurs. filetype will also
 * produce an E_NOTICE message if the stat call fails
 * or if the file type is unknown.
 */
#[Pure(true)]
function filetype(string $filename): string|false {}

/**
 * Checks whether a file or directory exists
 * @link https://php.net/manual/en/function.file-exists.php
 * @param string $filename <p>
 * Path to the file or directory.
 * </p>
 * <p>
 * On windows, use //computername/share/filename or
 * \\computername\share\filename to check files on
 * network shares.
 * </p>
 * @return bool true if the file or directory specified by
 * filename exists; false otherwise.
 * </p>
 * <p>
 * This function will return false for symlinks pointing to non-existing
 * files.
 * </p>
 * <p>
 * This function returns false for files inaccessible due to safe mode restrictions. However these
 * files still can be included if
 * they are located in safe_mode_include_dir.
 * </p>
 * <p>
 * The check is done using the real UID/GID instead of the effective one.
 */
#[Pure(true)]
function file_exists(string $filename): bool {}

/**
 * Tells whether the filename is writable
 * @link https://php.net/manual/en/function.is-writable.php
 * @param string $filename <p>
 * The filename being checked.
 * </p>
 * @return bool true if the filename exists and is
 * writable.
 */
#[Pure(true)]
function is_writable(string $filename): bool {}

/**
 * Alias:
 * {@see is_writable}
 * @link https://php.net/manual/en/function.is-writeable.php
 * @param string $filename <p>
 * The filename being checked.
 * </p>
 * @return bool true if the filename exists and is
 * writable.
 */
#[Pure(true)]
function is_writeable(string $filename): bool {}

/**
 * Tells whether a file or a directory exists and is readable
 * @link https://php.net/manual/en/function.is-readable.php
 * @param string $filename <p>
 * Path to the file or directory.
 * </p>
 * @return bool true if the file or directory specified by
 * filename exists and is readable, false otherwise.
 */
#[Pure(true)]
function is_readable(string $filename): bool {}

/**
 * Tells whether the filename is executable
 * @link https://php.net/manual/en/function.is-executable.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @return bool true if the filename exists and is executable, or false on
 * error.
 */
#[Pure(true)]
function is_executable(string $filename): bool {}

/**
 * Tells whether the filename is a regular file
 * @link https://php.net/manual/en/function.is-file.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @return bool true if the filename exists and is a regular file, false
 * otherwise.
 */
#[Pure(true)]
function is_file(string $filename): bool {}

/**
 * Tells whether the filename is a directory
 * @link https://php.net/manual/en/function.is-dir.php
 * @param string $filename <p>
 * Path to the file. If filename is a relative
 * filename, it will be checked relative to the current working
 * directory. If filename is a symbolic or hard link
 * then the link will be resolved and checked.
 * </p>
 * @return bool true if the filename exists and is a directory, false
 * otherwise.
 */
#[Pure(true)]
function is_dir(string $filename): bool {}

/**
 * Tells whether the filename is a symbolic link
 * @link https://php.net/manual/en/function.is-link.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @return bool true if the filename exists and is a symbolic link, false
 * otherwise.
 */
#[Pure(true)]
function is_link(string $filename): bool {}

/**
 * Gives information about a file
 * @link https://php.net/manual/en/function.stat.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @return array|false <table>
 * stat and fstat result
 * format
 * <tr valign="top">
 * <td>Numeric</td>
 * <td>Associative (since PHP 4.0.6)</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>0</td>
 * <td>dev</td>
 * <td>device number</td>
 * </tr>
 * <tr valign="top">
 * <td>1</td>
 * <td>ino</td>
 * <td>inode number *</td>
 * </tr>
 * <tr valign="top">
 * <td>2</td>
 * <td>mode</td>
 * <td>inode protection mode</td>
 * </tr>
 * <tr valign="top">
 * <td>3</td>
 * <td>nlink</td>
 * <td>number of links</td>
 * </tr>
 * <tr valign="top">
 * <td>4</td>
 * <td>uid</td>
 * <td>userid of owner *</td>
 * </tr>
 * <tr valign="top">
 * <td>5</td>
 * <td>gid</td>
 * <td>groupid of owner *</td>
 * </tr>
 * <tr valign="top">
 * <td>6</td>
 * <td>rdev</td>
 * <td>device type, if inode device</td>
 * </tr>
 * <tr valign="top">
 * <td>7</td>
 * <td>size</td>
 * <td>size in bytes</td>
 * </tr>
 * <tr valign="top">
 * <td>8</td>
 * <td>atime</td>
 * <td>time of last access (Unix timestamp)</td>
 * </tr>
 * <tr valign="top">
 * <td>9</td>
 * <td>mtime</td>
 * <td>time of last modification (Unix timestamp)</td>
 * </tr>
 * <tr valign="top">
 * <td>10</td>
 * <td>ctime</td>
 * <td>time of last inode change (Unix timestamp)</td>
 * </tr>
 * <tr valign="top">
 * <td>11</td>
 * <td>blksize</td>
 * <td>blocksize of filesystem IO **</td>
 * </tr>
 * <tr valign="top">
 * <td>12</td>
 * <td>blocks</td>
 * <td>number of 512-byte blocks allocated **</td>
 * </tr>
 * </table>
 * * On Windows this will always be 0.
 * </p>
 * <p>
 * ** Only valid on systems supporting the st_blksize type - other
 * systems (e.g. Windows) return -1.
 * </p>
 * <p>
 * In case of error, stat returns false.
 */
#[Pure(true)]
#[ArrayShape([
    "dev" => "int",
    "ino" => "int",
    "mode" => "int",
    "nlink" => "int",
    "uid" => "int",
    "gid" => "int",
    "rdev" => "int",
    "size" => "int",
    "atime" => "int",
    "mtime" => "int",
    "ctime" => "int",
    "blksize" => "int",
    "blocks" => "int"
])]
function stat(string $filename): array|false {}

/**
 * Gives information about a file or symbolic link
 * @link https://php.net/manual/en/function.lstat.php
 * @see stat
 * @param string $filename <p>
 * Path to a file or a symbolic link.
 * </p>
 * @return array|false See the manual page for stat for information on
 * the structure of the array that lstat returns.
 * This function is identical to the stat function
 * except that if the filename parameter is a symbolic
 * link, the status of the symbolic link is returned, not the status of the
 * file pointed to by the symbolic link.
 */
#[Pure(true)]
function lstat(string $filename): array|false {}

/**
 * Changes file owner
 * @link https://php.net/manual/en/function.chown.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @param string|int $user <p>
 * A user name or number.
 * </p>
 * @return bool true on success or false on failure.
 */
function chown(string $filename, string|int $user): bool {}

/**
 * Changes file group
 * @link https://php.net/manual/en/function.chgrp.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @param string|int $group <p>
 * A group name or number.
 * </p>
 * @return bool true on success or false on failure.
 */
function chgrp(string $filename, string|int $group): bool {}

/**
 * Changes user ownership of symlink
 * @link https://php.net/manual/en/function.lchown.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @param string|int $user <p>
 * User name or number.
 * </p>
 * @return bool true on success or false on failure.
 * @since 5.1.2
 */
function lchown(string $filename, string|int $user): bool {}

/**
 * Changes group ownership of symlink
 * @link https://php.net/manual/en/function.lchgrp.php
 * @param string $filename <p>
 * Path to the symlink.
 * </p>
 * @param string|int $group <p>
 * The group specified by name or number.
 * </p>
 * @return bool true on success or false on failure.
 * @since 5.1.2
 */
function lchgrp(string $filename, string|int $group): bool {}

/**
 * Changes file mode
 * @link https://php.net/manual/en/function.chmod.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @param int $permissions <p>
 * Note that mode is not automatically
 * assumed to be an octal value, so strings (such as "g+w") will
 * not work properly. To ensure the expected operation,
 * you need to prefix mode with a zero (0):
 * </p>
 * <pre>
 * <?php
 * chmod("/somedir/somefile", 755);   // decimal; probably incorrect
 * chmod("/somedir/somefile", "u+rwx,go+rx"); // string; incorrect
 * chmod("/somedir/somefile", 0755);  // octal; correct value of mode
 * ?>
 * </pre>
 * <p>
 * The mode parameter consists of three octal
 * number components specifying access restrictions for the owner,
 * the user group in which the owner is in, and to everybody else in
 * this order. One component can be computed by adding up the needed
 * permissions for that target user base. Number 1 means that you
 * grant execute rights, number 2 means that you make the file
 * writeable, number 4 means that you make the file readable. Add
 * up these numbers to specify needed rights. You can also read more
 * about modes on Unix systems with 'man 1 chmod'
 * and 'man 2 chmod'.
 * </p>
 * @return bool true on success or false on failure.
 */
function chmod(string $filename, int $permissions): bool {}

/**
 * Sets access and modification time of file
 * @link https://php.net/manual/en/function.touch.php
 * @param string $filename <p>
 * The name of the file being touched.
 * </p>
 * @param int|null $mtime [optional] <p>
 * The touch time. If time is not supplied,
 * the current system time is used.
 * </p>
 * @param int|null $atime [optional] <p>
 * If present, the access time of the given filename is set to
 * the value of atime. Otherwise, it is set to
 * time.
 * </p>
 * @return bool true on success or false on failure.
 */
function touch(string $filename, ?int $mtime, ?int $atime): bool {}

/**
 * Clears file status cache
 * @link https://php.net/manual/en/function.clearstatcache.php
 * @param bool $clear_realpath_cache [optional] <p>
 * Whenever to clear realpath cache or not.
 * </p>
 * @param string $filename <p>
 * Clear realpath cache on a specific filename, only used if
 * clear_realpath_cache is true.
 * </p>
 * @return void
 */
function clearstatcache(bool $clear_realpath_cache = false, string $filename = ''): void {}

/**
 * Returns the total size of a filesystem or disk partition
 * @link https://php.net/manual/en/function.disk-total-space.php
 * @param string $directory <p>
 * A directory of the filesystem or disk partition.
 * </p>
 * @return float|false the total number of bytes as a float
 * or false on failure.
 */
#[Pure(true)]
function disk_total_space(string $directory): float|false {}

/**
 * Returns available space in directory
 * @link https://php.net/manual/en/function.disk-free-space.php
 * @param string $directory <p>
 * A directory of the filesystem or disk partition.
 * </p>
 * <p>
 * Given a file name instead of a directory, the behaviour of the
 * function is unspecified and may differ between operating systems and
 * PHP versions.
 * </p>
 * @return float|false the number of available bytes as a float
 * or false on failure.
 */
#[Pure(true)]
function disk_free_space(string $directory): float|false {}

/**
 * Alias of {@see disk_free_space}
 * @link https://php.net/manual/en/function.diskfreespace.php
 * @see disk_free_space
 * @param string $directory
 * @return float|false
 */
#[Pure(true)]
function diskfreespace(string $directory): float|false {}

/**
 * Send mail
 * @link https://php.net/manual/en/function.mail.php
 * @param string $to <p>
 * Receiver, or receivers of the mail.
 * </p>
 * <p>
 * The formatting of this string must comply with
 * RFC 2822. Some examples are:
 * user@example.com
 * user@example.com, anotheruser@example.com
 * User &lt;user@example.com&gt;
 * User &lt;user@example.com&gt;, Another User &lt;anotheruser@example.com&gt;
 * </p>
 * @param string $subject <p>
 * Subject of the email to be sent.
 * </p>
 * <p>
 * Subject must satisfy RFC 2047.
 * </p>
 * @param string $message <p>
 * Message to be sent.
 * </p>
 * <p>
 * Each line should be separated with a LF (\n). Lines should not be larger
 * than 70 characters.
 * </p>
 * <p>
 * <strong>Caution</strong>
 * (Windows only) When PHP is talking to a SMTP server directly, if a full
 * stop is found on the start of a line, it is removed. To counter-act this,
 * replace these occurrences with a double dot.
 * </p>
 * <pre>
 * <?php
 * $text = str_replace("\n.", "\n..", $text);
 * ?>
 * </pre>
 * @param string|array $additional_headers <p>
 * String or array to be inserted at the end of the email header.<br/>
 * Since 7.2.0 accepts an array. Its keys are the header names and its values are the respective header values.
 * </p>
 * <p>
 * This is typically used to add extra headers (From, Cc, and Bcc).
 * Multiple extra headers should be separated with a CRLF (\r\n).
 * </p>
 * <p>
 * When sending mail, the mail must contain
 * a From header. This can be set with the
 * additional_headers parameter, or a default
 * can be set in "php.ini".
 * </p>
 * <p>
 * Failing to do this will result in an error
 * message similar to Warning: mail(): "sendmail_from" not
 * set in php.ini or custom "From:" header missing.
 * The From header sets also
 * Return-Path under Windows.
 * </p>
 * <p>
 * If messages are not received, try using a LF (\n) only.
 * Some poor quality Unix mail transfer agents replace LF by CRLF
 * automatically (which leads to doubling CR if CRLF is used).
 * This should be a last resort, as it does not comply with
 * RFC 2822.
 * </p>
 * @param string $additional_params <p>
 * The additional_parameters parameter
 * can be used to pass additional flags as command line options to the
 * program configured to be used when sending mail, as defined by the
 * sendmail_path configuration setting. For example,
 * this can be used to set the envelope sender address when using
 * sendmail with the -f sendmail option.
 * </p>
 * <p>
 * The user that the webserver runs as should be added as a trusted user to the
 * sendmail configuration to prevent a 'X-Warning' header from being added
 * to the message when the envelope sender (-f) is set using this method.
 * For sendmail users, this file is /etc/mail/trusted-users.
 * </p>
 * @return bool true if the mail was successfully accepted for delivery, false otherwise.
 * <p>
 * It is important to note that just because the mail was accepted for delivery,
 * it does NOT mean the mail will actually reach the intended destination.
 * </p>
 */
function mail(string $to, string $subject, string $message, array|string $additional_headers = [], string $additional_params = ''): bool {}

/**
 * Calculate the hash value needed by EZMLM
 * @link https://php.net/manual/en/function.ezmlm-hash.php
 * @param string $addr <p>
 * The email address that's being hashed.
 * </p>
 * @return int The hash value of addr.
 * @removed 8.0
 */
#[Deprecated(since: '7.4')]
function ezmlm_hash(string $addr): int {}

/**
 * Open connection to system logger
 * @link https://php.net/manual/en/function.openlog.php
 * @param string $prefix <p>
 * The string ident is added to each message.
 * </p>
 * @param int $flags <p>
 * The option argument is used to indicate
 * what logging options will be used when generating a log message.
 * <table>
 * openlog Options
 * <tr valign="top">
 * <td>Constant</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_CONS</td>
 * <td>
 * if there is an error while sending data to the system logger,
 * write directly to the system console
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_NDELAY</td>
 * <td>
 * open the connection to the logger immediately
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_ODELAY</td>
 * <td>
 * (default) delay opening the connection until the first
 * message is logged
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_PERROR</td>
 * <td>print log message also to standard error</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_PID</td>
 * <td>include PID with each message</td>
 * </tr>
 * </table>
 * You can use one or more of this options. When using multiple options
 * you need to OR them, i.e. to open the connection
 * immediately, write to the console and include the PID in each message,
 * you will use: LOG_CONS | LOG_NDELAY | LOG_PID
 * </p>
 * @param int $facility <p>
 * The facility argument is used to specify what
 * type of program is logging the message. This allows you to specify
 * (in your machine's syslog configuration) how messages coming from
 * different facilities will be handled.
 * <table>
 * openlog Facilities
 * <tr valign="top">
 * <td>Constant</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_AUTH</td>
 * <td>
 * security/authorization messages (use
 * LOG_AUTHPRIV instead
 * in systems where that constant is defined)
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_AUTHPRIV</td>
 * <td>security/authorization messages (private)</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_CRON</td>
 * <td>clock daemon (cron and at)</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_DAEMON</td>
 * <td>other system daemons</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_KERN</td>
 * <td>kernel messages</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_LOCAL0 ... LOG_LOCAL7</td>
 * <td>reserved for local use, these are not available in Windows</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_LPR</td>
 * <td>line printer subsystem</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_MAIL</td>
 * <td>mail subsystem</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_NEWS</td>
 * <td>USENET news subsystem</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_SYSLOG</td>
 * <td>messages generated internally by syslogd</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_USER</td>
 * <td>generic user-level messages</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_UUCP</td>
 * <td>UUCP subsystem</td>
 * </tr>
 * </table>
 * </p>
 * <p>
 * LOG_USER is the only valid log type under Windows
 * operating systems
 * </p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(["8.2" => "true"], default: "bool")]
function openlog(string $prefix, int $flags, int $facility) {}
