<?php
/**
 * @link https://www.php.net/manual/en/ref.dio.php
 * Direct IO Functions
 * Table of Contents
 * dio_close — Closes the file descriptor given by fd
 * dio_fcntl — Performs a c library fcntl on fd
 * dio_open — Opens a file (creating it if necessary) at a lower level than the C library input/ouput stream functions allow
 * dio_read — Reads bytes from a file descriptor
 * dio_seek — Seeks to pos on fd from whence
 * dio_stat — Gets stat information about the file descriptor fd
 * dio_tcsetattr — Sets terminal attributes and baud rate for a serial port
 * dio_truncate — Truncates file descriptor fd to offset bytes
 * dio_write — Writes data to fd with optional truncation at length
 * dio_raw - Opens a raw direct IO stream.
 * dio_serial - Opens a serial direct IO stream.
 */

/**
 * Closes the file descriptor given by fd.
 *
 * dio_close ( resource $fd ) : void
 *
 * @link https://www.php.net/manual/en/function.dio-close.php
 * @param resource $fd The file descriptor returned by dio_open()
 * @return void
 */
function dio_close($fd): void {}

/**
 * The dio_fcntl() function performs the operation specified by cmd on the file descriptor fd.
 *
 * dio_fcntl ( resource $fd , int $cmd [, mixed $args ] ) : mixed
 *
 * Some commands require additional arguments args to be supplied.
 * @link https://www.php.net/manual/en/function.dio-fcntl.php
 * @param resource $fd The file descriptor returned by dio_open().
 * @param int $cmd The file descriptor returned by dio_open().
 * @param mixed ...$args args is an associative array, when cmd is F_SETLK or F_SETLLW, with the following keys:
 * <ul>
 * <li>"start" - offset where lock begins</li>
 * <li>"length" - size of locked area. zero means to end of file</li>
 * <li>"whence" - Where l_start is relative to: can be SEEK_SET, SEEK_END and SEEK_CUR</li>
 * <li>"type" - type of lock: can be F_RDLCK (read lock), F_WRLCK (write lock) or F_UNLCK (unlock)</li>
 * </ul>
 * @return mixed Returns the result of the C call.
 */
function dio_fcntl($fd, int $cmd, ...$args) {}

/**
 * Opens a file (creating it if necessary) at a lower level than theC library input/ouput stream functions allow
 *
 * dio_open ( string $filename , int $flags [, int $mode = 0 ] ) : resource
 *
 * @link https://www.php.net/manual/en/function.dio-open.php
 * @param string $filename The pathname of the file to open.
 * @param int $flags The flags parameter is a bitwise-ORed value comprising flags from the following list.
 * <ul>
 * <li>O_RDONLY - opens the file for read access.</li>
 * <li>O_WRONLY - opens the file for write access.</li>
 * <li>O_RDWR - opens the file for both reading and writing.</li>
 * <li>O_CREAT - creates the file, if it doesn't already exist.</li>
 * <li>O_EXCL - if both O_CREAT and O_EXCL are set and the file already exists, dio_open() will fail.</li>
 * <li>O_TRUNC - if the file exists and is opened for write access, the file will be truncated to zero length.</li>
 * <li>O_APPEND - write operations write data at the end of the file.</li>
 * <li>O_NONBLOCK - sets non blocking mode.</li>
 * <li>O_NOCTTY - prevent the OS from assigning the opened file as the process's controllingterminal when opening a TTY device file.</li>
 * </ul>
 * @param int $mode If flags contains O_CREAT, mode will set the permissions of the file (creation permissions).
 * @return resource|false A file descriptor or FALSE on error.
 */
function dio_open(string $filename, int $flags, int $mode = 0) {}

/**
 * Reads bytes from a file descriptor.
 *
 * dio_read ( resource $fd [, int $len = 1024 ] ) : string
 *
 * @param resource $fd The file descriptor returned by dio_open().
 * @param int $len The number of bytes to read. If not specified, dio_read() reads 1k sized block.
 * @return string The bytes read from fd.
 * @link https://www.php.net/manual/en/function.dio-read.php
 */
function dio_read($fd, int $len = 1024) {}

/**
 * Seeks to pos on fd from whence
 *
 * dio_seek ( resource $fd , int $pos [, int $whence = SEEK_SET ] ): int
 *
 * @param resource $fd The file descriptor returned by dio_open().
 * @param int $pos The new position.
 * @param int $whence Specifies how the position pos should be interpreted:
 * <ul>
 * <li>SEEK_SET - (Default) Specifies that pos is specified from the beginning of the file.</li>
 * <li>SEEK_CUR - Specifies that pos is a count of characters from the current file position. This count may be positive or negative.</li>
 * <li>SEEK_END - Specifies that pos is a count of characters from the end of the file.</li>
 * </ul>
 * @return int
 * @link https://www.php.net/manual/en/function.dio-seek.php
 */
function dio_seek($fd, int $pos, int $whence = SEEK_SET) {}

/**
 * Gets stat information about the file descriptor fd
 *
 * dio_stat ( resource $fd ) : array
 *
 * @param resource $fd The file descriptor returned by dio_open().
 * @return array|null Returns an associative array with the following keys:
 * <ul>
 * <li>"device" - device</li>
 * <li>"inode" - inode</li>
 * <li>"mode" - mode</li>
 * <li>"nlink" - number of hard links</li>
 * <li>"uid" - user id</li>
 * <li>"gid" - group id</li>
 * <li>"device_type" - device type (if inode device)</li>
 * <li>"size" - total size in bytes</li>
 * <li>"blocksize" - blocksize</li>
 * <li>"blocks" - number of blocks allocated</li>
 * <li>"atime" - time of last access</li>
 * <li>"mtime" - time of last modification</li>
 * <li>"ctime" - time of last change</li>
 * </ul>
 * On error dio_stat() returns NULL.
 * @link https://www.php.net/manual/en/function.dio-stat.php
 */
function dio_stat($fd) {}

/**
 * Sets terminal attributes and baud rate for a serial port
 *
 * dio_tcsetattr ( resource $fd , array $options ) : bool
 *
 * @param resource $fd The file descriptor returned by dio_open().
 * @param array $options The currently available options are:
 * <ul>
 * <li>"baud" - baud rate of the port - can be 38400, 19200, 9600, 4800, 2400, 1800, 1200, 600, 300, 200, 150, 134, 110, 75 or 50, default value is 9600.</li>
 * <li>"bits" - data bits - can be 8,7,6 or 5. Default value is 8.</li>
 * <li>"stop" - stop bits - can be 1 or 2. Default value is 1.</li>
 * <li>"parity" - can be 0,1 or 2. Default value is 0.</li>
 * </ul>
 * @return void
 * @link https://www.php.net/manual/en/function.dio-tcsetattr.php
 */
function dio_tcsetattr($fd, array $options) {}

/**
 * Truncates a file to at most offset bytes in size.
 *
 * dio_truncate ( resource $fd , int $offset ) : bool
 *
 * If the file previously was larger than this size, the extra data is lost.
 * If the file previously was shorter, it is unspecified whether the file is left unchanged or is extended.
 * In the latter case the extended part reads as zero bytes.
 * @param resource $fd The file descriptor returned by dio_open().
 * @param int $offset The offset in bytes.
 * @return bool Returns TRUE on success or FALSE on failure.
 * @link https://www.php.net/manual/en/function.dio-truncate.php
 */
function dio_truncate($fd, int $offset) {}

/**
 * Writes data to fd with optional truncation at length
 *
 * dio_write ( resource $fd , string $data [, int $len = 0 ] ) : int
 *
 * @link https://www.php.net/manual/en/function.dio-write.php
 * @param resource $fd The file descriptor returned by dio_open().
 * @param string $data The written data.
 * @param int $len The length of data to write in bytes. If not specified, the function writes all the data to the specified file.
 * @return int Returns the number of bytes written to fd.
 */
function dio_write($fd, string $data, int $len = 0) {}

/**
 * Opens a raw direct IO stream.
 *
 * dio_raw ( string filename , string mode [, array options] ) : ?resource
 *
 * @param string $filename The pathname of the file to open.
 * @param string $mode The mode parameter specifies the type of access you require to the stream (as fopen()).
 * @param array|null $options The currently available options are:
 * <ul>
 * <li>"data_rate" - baud rate of the port - can be 75, 110, 134, 150, 300, 600, 1200, 1800, 2400, 4800, 7200, 9600, 14400, 19200, 38400, 57600, 115200, 56000, 128000 or 256000 default value is 9600.</li>
 * <li>"data_bits" - can be 8, 7, 6 or 5. Default value is 8.</li>
 * <li>"stop_bits" - can be 1 or 2. Default value is 1.</li>
 * <li>"parity" - can be 0, 1 or 2. Default value is 0.</li>
 * <li>"flow_control" - can be 0 or 1. Default value is 1.</li>
 * <li>"is_canonical" - can be 0 or 1. Default value is 1.</li>
 * </ul>
 * @return resource|null A stream resource or null on error.
 */
function dio_raw(string $filename, string $mode, ?array $options) {}

/**
 * Opens a serial direct IO stream.
 *
 * dio_serial ( string $filename , string $mode [, array $options = null] ) : ?resource
 *
 * @param string $filename The pathname of the file to open.
 * @param string $mode The mode parameter specifies the type of access you require to the stream (as fopen()).
 * @param array|null $options The currently available options are:
 * <ul>
 * <li>"data_rate" - baud rate of the port - can be 75, 110, 134, 150, 300, 600, 1200, 1800, 2400, 4800, 7200, 9600, 14400, 19200, 38400, 57600, 115200, 56000, 128000 or 256000 default value is 9600.</li>
 * <li>"data_bits" - can be 8, 7, 6 or 5. Default value is 8.</li>
 * <li>"stop_bits" - can be 1 or 2. Default value is 1.</li>
 * <li>"parity" - can be 0, 1 or 2. Default value is 0.</li>
 * <li>"flow_control" - can be 0 or 1. Default value is 1.</li>
 * <li>"is_canonical" - can be 0 or 1. Default value is 1.</li>
 * </ul>
 * @return resource|null A stream resource or null on error.
 */
function dio_serial(string $filename, string $mode, ?array $options) {}
