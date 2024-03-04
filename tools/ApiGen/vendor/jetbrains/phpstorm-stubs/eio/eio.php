<?php

const EIO_DEBUG = 0;
const EIO_SEEK_SET = 0;
const EIO_SEEK_CUR = 0;
const EIO_SEEK_END = 0;
const EIO_PRI_MIN = 0;
const EIO_PRI_DEFAULT = 0;
const EIO_PRI_MAX = 0;
const EIO_READDIR_DENTS = 0;
const EIO_READDIR_DIRS_FIRST = 0;
const EIO_READDIR_STAT_ORDER = 0;
const EIO_READDIR_FOUND_UNKNOWN = 0;
const EIO_DT_UNKNOWN = 0;
const EIO_DT_FIFO = 0;
const EIO_DT_CHR = 0;
const EIO_DT_MPC = 0;
const EIO_DT_DIR = 0;
const EIO_DT_NAM = 0;
const EIO_DT_BLK = 0;
const EIO_DT_MPB = 0;
const EIO_DT_REG = 0;
const EIO_DT_NWK = 0;
const EIO_DT_CMP = 0;
const EIO_DT_LNK = 0;
const EIO_DT_SOCK = 0;
const EIO_DT_DOOR = 0;
const EIO_DT_WHT = 0;
const EIO_DT_MAX = 0;
const EIO_O_RDONLY = 0;
const EIO_O_WRONLY = 0;
const EIO_O_RDWR = 0;
const EIO_O_NONBLOCK = 0;
const EIO_O_APPEND = 0;
const EIO_O_CREAT = 0;
const EIO_O_TRUNC = 0;
const EIO_O_EXCL = 0;
const EIO_O_FSYNC = 0;
const EIO_S_IRUSR = 0;
const EIO_S_IWUSR = 0;
const EIO_S_IXUSR = 0;
const EIO_S_IRGRP = 0;
const EIO_S_IWGRP = 0;
const EIO_S_IXGRP = 0;
const EIO_S_IROTH = 0;
const EIO_S_IWOTH = 0;
const EIO_S_IXOTH = 0;
const EIO_S_IFREG = 0;
const EIO_S_IFCHR = 0;
const EIO_S_IFBLK = 0;
const EIO_S_IFIFO = 0;
const EIO_S_IFSOCK = 0;
const EIO_SYNC_FILE_RANGE_WAIT_BEFORE = 0;
const EIO_SYNC_FILE_RANGE_WRITE = 0;
const EIO_SYNC_FILE_RANGE_WAIT_AFTER = 0;
const EIO_FALLOC_FL_KEEP_SIZE = 0;

/**
 * Polls libeio until all requests proceeded
 * @link https://www.php.net/manual/en/function.eio-event-loop.php
 * @return bool returns true on success, or false on failure.
 */
function eio_event_loop(): bool {}

/**
 * Can be to be called whenever there are pending requests that need finishing
 * @link https://www.php.net/manual/en/function.eio-poll.php
 * @return int If any request invocation returns a non-zero value, returns that value. Otherwise, it returns 0.
 */
function eio_poll(): int {}

/**
 * Opens a file
 * @link https://www.php.net/manual/en/function.eio-open.php
 * @param string $path Path of the file to be opened.
 * @param int $flags One of EIO_O_* constants, or their combinations.
 * @param int $mode One of EIO_S_I* constants, or their combination (via bitwise OR operator).
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX.
 * @param mixed $callback function is called when the request is done.
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|false returns file descriptor in result argument of callback on success; otherwise, result is equal to -1.
 */
function eio_open(string $path, int $flags, int $mode, int $pri, mixed $callback, mixed $data = null) {}

/**
 * Truncate a file
 * @link https://www.php.net/manual/en/function.eio-truncate.php
 * @param string $path File path
 * @param int $offset Offset from beginning of the file.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|false returns request resource on success, or false on failure.
 */
function eio_truncate(string $path, int $offset = 0, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Create directory
 * @link https://www.php.net/manual/en/function.eio-mkdir.php
 * @param string $path Path for the new directory.
 * @param int $mode Access mode, e.g. 0755
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|false returns request resource on success, or false on failure.
 */
function eio_mkdir(string $path, int $mode, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Remove a directory
 * @link https://www.php.net/manual/en/function.eio-rmdir.php
 * @param string $path Directory path
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_rmdir(string $path, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Delete a name and possibly the file it refers to
 * @link https://www.php.net/manual/en/function.eio-unlink.php
 * @param string $path Path to file
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_unlink(string $path, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Change file last access and modification times
 * @link https://www.php.net/manual/en/function.eio-utime.php
 * @param string $path Path to the file.
 * @param float $atime Access time
 * @param float $mtime Modification time
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_utime(string $path, float $atime, float $mtime, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Create a special or ordinary file
 * @link https://www.php.net/manual/en/function.eio-mknod.php
 * @param string $path Path for the new node(file).
 * @param int $mode Specifies both the permissions to use and the type of node to be created
 * @param int $dev If the file type is EIO_S_IFCHR or EIO_S_IFBLK then dev specifies the major and minor numbers of the newly created device special file.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_mknod(string $path, int $mode, int $dev, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Create a hardlink for file
 * @link https://www.php.net/manual/en/function.eio-link.php
 * @param string $path Source file path.
 * @param string $new_path Target file path.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_link(string $path, string $new_path, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Create a symbolic link
 * @link https://www.php.net/manual/en/function.eio-symlink.php
 * @param string $path Source path.
 * @param string $new_path Target path.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_symlink(string $path, string $new_path, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Change the name or location of a file
 * @link https://www.php.net/manual/en/function.eio-rename.php
 * @param string $path Source path.
 * @param string $new_path Target path.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_rename(string $path, string $new_path, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Close file
 * @link https://www.php.net/manual/en/function.eio-close.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_close(mixed $fd, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Commit buffer cache to disk
 * @link https://www.php.net/manual/en/function.eio-sync.php
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_sync(int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Synchronize a file's in-core state with storage device
 * @link https://www.php.net/manual/en/function.eio-fsync.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_fsync(mixed $fd, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Synchronize a file's in-core state with storage device
 * @link https://www.php.net/manual/en/function.eio-fdatasync.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_fdatasync(mixed $fd, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Change file last access and modification times
 * @link https://www.php.net/manual/en/function.eio-futime.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param float $atime Access time
 * @param float $mtime Modification time
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_futime(mixed $fd, float $atime, float $mtime, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Truncate a file
 * @link https://www.php.net/manual/en/function.eio-ftruncate.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param int $offset Offset from beginning of the file.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_ftruncate(mixed $fd, int $offset = 0, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Change file/directory permissions
 * @link https://www.php.net/manual/en/function.eio-chmod.php
 * @param string $path Path to the target file or directory
 * @param int $mode The new permissions. E.g. 0644.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_chmod(string $path, int $mode, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Change file permissions
 * @link https://www.php.net/manual/en/function.eio-fchmod.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param int $mode The new permissions. E.g. 0644.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_fchmod(mixed $fd, int $mode, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Change file/directory permissions
 * @link https://www.php.net/manual/en/function.eio-chown.php
 * @param string $path Path to file or directory.
 * @param int $uid User ID. Is ignored when equal to -1.
 * @param int $gid Group ID. Is ignored when equal to -1.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_chown(string $path, int $uid, int $gid = -1, int $pri = 0, mixed $callback = null, ?mixed $data = null) {}

/**
 * Change file ownership
 * @link https://www.php.net/manual/en/function.eio-fchown.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param int $uid User ID. Is ignored when equal to -1.
 * @param int $gid Group ID. Is ignored when equal to -1.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_fchown(mixed $fd, int $uid, int $gid = -1, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Duplicate a file descriptor
 * @link https://www.php.net/manual/en/function.eio-dup2.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param mixed $fd2 Stream, Socket resource, or numeric file descriptor
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_dup2(mixed $fd, mixed $fd2, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Read from a file descriptor at given offset
 * @link https://www.php.net/manual/en/function.eio-read.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param int $length Maximum number of bytes to read.
 * @param int $offset Offset within the file.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_read(mixed $fd, int $length, int $offset, int $pri, mixed $callback, mixed $data = null) {}

/**
 * Write to file
 * @link https://www.php.net/manual/en/function.eio-write.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param mixed $str Source string
 * @param int $length Maximum number of bytes to write.
 * @param int $offset Offset from the beginning of file.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_write(mixed $fd, mixed $str, int $length = 0, int $offset = 0, int $pri = 0, mixed $callback = null, mixed $data = null) {}

/**
 * Read value of a symbolic link
 * @link https://www.php.net/manual/en/function.eio-readlink.php
 * @param string $path Source symbolic link path
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_readlink(string $path, int $pri, mixed $callback, mixed $data = null) {}

/**
 * Get the canonicalized absolute pathname
 * @link https://www.php.net/manual/en/function.eio-realpath.php
 * @param string $path Short pathname
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_realpath(string $path, int $pri, mixed $callback, mixed $data = null) {}

/**
 * Get file status
 * @link https://www.php.net/manual/en/function.eio-stat.php
 * @param string $path The file path
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_stat(string $path, int $pri, mixed $callback, mixed $data = null) {}

/**
 * Get file status
 * @link https://www.php.net/manual/en/function.eio-lstat.php
 * @param string $path The file path
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_lstat(string $path, int $pri, mixed $callback, mixed $data = null) {}

/**
 * Get file status
 * @link https://www.php.net/manual/en/function.eio-fstat.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_fstat(mixed $fd, int $pri, mixed $callback, mixed $data = null) {}

/**
 * Get file system statistics
 * @link https://www.php.net/manual/en/function.eio-statvfs.php
 * @param string $path Pathname of any file within the mounted file system
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_statvfs(string $path, int $pri, mixed $callback, mixed $data = null) {}

/**
 * Get file system statistics
 * @link https://www.php.net/manual/en/function.eio-fstatvfs.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_fstatvfs(mixed $fd, int $pri, mixed $callback, mixed $data = null) {}

/**
 * Reads through a whole directory
 * @link https://www.php.net/manual/en/function.eio-readdir.php
 * @param string $path Directory path.
 * @param int $flags Combination of EIO_READDIR_* constants.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_readdir(string $path, int $flags, int $pri, mixed $callback, mixed $data = null) {}

/**
 * Transfer data between file descriptors
 * @link https://www.php.net/manual/en/function.eio-sendfile.php
 * @param mixed $out_fd Output stream, Socket resource, or file descriptor. Should be opened for writing.
 * @param mixed $in_fd Input stream, Socket resource, or file descriptor. Should be opened for reading.
 * @param int $offset Offset within the source file.
 * @param int $length Number of bytes to copy.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_sendfile(mixed $out_fd, mixed $in_fd, int $offset, int $length, int $pri, mixed $callback, mixed $data = null) {}

/**
 * Perform file readahead into page cache
 * @link https://www.php.net/manual/en/function.eio-readahead.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param int $offset Starting point from which data is to be read.
 * @param int $length Number of bytes to be read.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_readahead(mixed $fd, int $offset, int $length, int $pri = EIO_PRI_DEFAULT, mixed $callback = null, mixed $data = null) {}

/**
 * Repositions the offset of the open file associated with the fd argument to the argument offset according to the directive whence
 * @link https://www.php.net/manual/en/function.eio-seek.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param int $offset Starting point from which data is to be read.
 * @param int $length Number of bytes to be read.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_seek(mixed $fd, int $offset, int $length, int $pri = EIO_PRI_DEFAULT, mixed $callback = null, mixed $data = null) {}

/**
 * Calls Linux' syncfs syscall, if available
 * @link https://www.php.net/manual/en/function.eio-syncfs.php
 * @param mixed $fd File descriptor
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_syncfs(mixed $fd, int $pri = EIO_PRI_DEFAULT, mixed $callback = null, mixed $data = null) {}

/**
 * Sync a file segment with disk
 * @link https://www.php.net/manual/en/function.eio-sync-file-range.php
 * @param mixed $fd File descriptor
 * @param int $offset The starting byte of the file range to be synchronized
 * @param int $nbytes Specifies the length of the range to be synchronized, in bytes. If nbytes is zero, then all bytes from offset through to the end of file are synchronized.
 * @param int $flags A bit-mask. Can include any of the following values: EIO_SYNC_FILE_RANGE_WAIT_BEFORE, EIO_SYNC_FILE_RANGE_WRITE, EIO_SYNC_FILE_RANGE_WAIT_AFTER.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_sync_file_range(mixed $fd, int $offset, int $nbytes, int $flags, int $pri = EIO_PRI_DEFAULT, mixed $callback = null, mixed $data = null) {}

/**
 * Allows the caller to directly manipulate the allocated disk space for a file
 * @link https://www.php.net/manual/en/function.eio-fallocate.php
 * @param mixed $fd Stream, Socket resource, or numeric file descriptor
 * @param int $mode Currently only one flag is supported for mode: EIO_FALLOC_FL_KEEP_SIZE (the same as POSIX constant FALLOC_FL_KEEP_SIZE).
 * @param int $offset Specifies start of the byte range.
 * @param int $length Specifies length the byte range.
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_fallocate(mixed $fd, int $mode, int $offset, int $length, int $pri = EIO_PRI_DEFAULT, mixed $callback = null, mixed $data = null) {}

/**
 * Execute custom request like any other eio_* call
 * @link https://www.php.net/manual/en/function.eio-custom.php
 * @param mixed $execute Specifies the request function
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_custom(mixed $execute, int $pri, mixed $callback, mixed $data = null) {}

/**
 * Artificially increase load. Could be useful in tests, benchmarking
 * @link https://www.php.net/manual/en/function.eio-busy.php
 * @param int $delay Delay in seconds
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_busy(int $delay, int $pri = EIO_PRI_DEFAULT, mixed $callback = null, mixed $data = null) {}

/**
 * Does nothing, except go through the whole request cycle
 * @link https://www.php.net/manual/en/function.eio-nop.php
 * @param int $pri The request priority: EIO_PRI_DEFAULT, EIO_PRI_MIN, EIO_PRI_MAX
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource|bool returns request resource on success, or false on failure.
 */
function eio_nop(int $pri = EIO_PRI_DEFAULT, mixed $callback = null, mixed $data = null) {}

/**
 * Cancels a request
 * @link https://www.php.net/manual/en/function.eio-cancel.php
 * @param resource $req The request resource
 */
function eio_cancel($req): void {}

/**
 * Creates a request group
 * @link https://www.php.net/manual/en/function.eio-grp.php
 * @param mixed $callback function is called when the request is done
 * @param mixed $data Arbitrary variable passed to callback
 * @return resource returns request group resource on success, or false on failure.
 */
function eio_grp(mixed $callback, mixed $data = null) {}

/**
 * Adds a request to the request group
 * @link https://www.php.net/manual/en/function.eio-grp-add.php
 * @param resource $grp The request group resource returned by eio_grp()
 * @param resource $req The request resource
 */
function eio_grp_add($grp, $req): void {}

/**
 * Set group limit
 * @link https://www.php.net/manual/en/function.eio-grp-limit.php
 * @param resource $grp The request group resource.
 * @param int $limit Number of requests in the group.
 */
function eio_grp_limit($grp, int $limit): void {}

/**
 * Cancels a request group
 * @link https://www.php.net/manual/en/function.eio-grp-cancel.php
 * @param resource $grp The request group resource returned by eio_grp().
 */
function eio_grp_cancel($grp): void {}

/**
 * Set maximum poll time
 * @link https://www.php.net/manual/en/function.eio-set-max-poll-time.php
 * @param float $nseconds Number of seconds
 */
function eio_set_max_poll_time(float $nseconds): void {}

/**
 * Set maximum number of requests processed in a poll
 * @link https://www.php.net/manual/en/function.eio-set-max-poll-reqs.php
 * @param int $value Number of requests
 */
function eio_set_max_poll_reqs(int $value): void {}

/**
 * Set minimum parallel thread number
 * @link https://www.php.net/manual/en/function.eio-set-min-parallel.php
 * @param int $value Number of parallel threads.
 */
function eio_set_min_parallel(int $value): void {}

/**
 * Set maximum parallel threads
 * @link https://www.php.net/manual/en/function.eio-set-max-parallel.php
 * @param int $value Number of parallel threads
 */
function eio_set_max_parallel(int $value): void {}

/**
 * Set maximum number of idle threads
 * @link https://www.php.net/manual/en/function.eio-set-max-idle.php
 * @param int $value Number of idle threads.
 */
function eio_set_max_idle(int $value): void {}

/**
 * Returns number of threads currently in use
 * @link https://www.php.net/manual/en/function.eio-nthreads.php
 * @return int returns number of threads currently in use.
 */
function eio_nthreads(): int {}

/**
 * Returns number of requests to be processed
 * @link https://www.php.net/manual/en/function.eio-nreqs.php
 * @return int returns number of requests to be processed.
 */
function eio_nreqs(): int {}

/**
 * Returns number of not-yet handled requests
 * @link https://www.php.net/manual/en/function.eio-nready.php
 * @return int returns number of not-yet handled requests
 */
function eio_nready(): int {}

/**
 * Returns number of finished, but unhandled requests
 * @link https://www.php.net/manual/en/function.eio-npending.php
 * @return int returns number of finished, but unhandled requests.
 */
function eio_npending(): int {}

/**
 * Get stream representing a variable used in internal communications with libeio
 * @link https://www.php.net/manual/en/function.eio-get-event-stream.php
 * @return resource|null returns stream on success; otherwise, null
 */
function eio_get_event_stream() {}

/**
 * Returns string describing the last error associated with a request resource
 * @link https://www.php.net/manual/en/function.eio-get-last-error.php
 * @param resource $req The request resource
 * @return string returns string describing the last error associated with the request resource specified by req.
 */
function eio_get_last_error($req): string {}
