<?php

// Start of posix v.
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 * Send a signal to a process
 * @link https://php.net/manual/en/function.posix-kill.php
 * @param int $process_id <p>
 * The process identifier.
 * </p>
 * @param int $signal <p>
 * One of the PCNTL signals constants.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function posix_kill(int $process_id, int $signal): bool {}

/**
 * Return the current process identifier
 * @link https://php.net/manual/en/function.posix-getpid.php
 * @return int the identifier, as an integer.
 */
#[Pure]
function posix_getpid(): int {}

/**
 * Return the parent process identifier
 * @link https://php.net/manual/en/function.posix-getppid.php
 * @return int the identifier, as an integer.
 */
#[Pure]
function posix_getppid(): int {}

/**
 * Return the real user ID of the current process
 * @link https://php.net/manual/en/function.posix-getuid.php
 * @return int the user id, as an integer
 */
#[Pure]
function posix_getuid(): int {}

/**
 * Set the UID of the current process
 * @link https://php.net/manual/en/function.posix-setuid.php
 * @param int $user_id <p>
 * The user id.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function posix_setuid(int $user_id): bool {}

/**
 * Return the effective user ID of the current process
 * @link https://php.net/manual/en/function.posix-geteuid.php
 * @return int the user id, as an integer
 */
#[Pure]
function posix_geteuid(): int {}

/**
 * Set the effective UID of the current process
 * @link https://php.net/manual/en/function.posix-seteuid.php
 * @param int $user_id <p>
 * The user id.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function posix_seteuid(int $user_id): bool {}

/**
 * Set system resource limits
 * @link https://php.net/manual/en/function.posix-setrlimit.php
 * @param int $resource <p>
 * The
 * {@link https://php.net/manual/en/posix.constants.setrlimit.php resource limit constant}
 * corresponding to the limit that is being set.
 * </p>
 * @param int $soft_limit The soft limit, in whatever unit the resource limit requires, or POSIX_RLIMIT_INFINITY.
 * @param int $hard_limit The hard limit, in whatever unit the resource limit requires, or POSIX_RLIMIT_INFINITY.
 * @return bool Returns TRUE on success or FALSE on failure.
 * @since 7.0
 */
function posix_setrlimit(int $resource, int $soft_limit, int $hard_limit): bool {}
/**
 * Return the real group ID of the current process
 * @link https://php.net/manual/en/function.posix-getgid.php
 * @return int the real group id, as an integer.
 */
#[Pure]
function posix_getgid(): int {}

/**
 * Set the GID of the current process
 * @link https://php.net/manual/en/function.posix-setgid.php
 * @param int $group_id <p>
 * The group id.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function posix_setgid(int $group_id): bool {}

/**
 * Return the effective group ID of the current process
 * @link https://php.net/manual/en/function.posix-getegid.php
 * @return int an integer of the effective group ID.
 */
#[Pure]
function posix_getegid(): int {}

/**
 * Set the effective GID of the current process
 * @link https://php.net/manual/en/function.posix-setegid.php
 * @param int $group_id <p>
 * The group id.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function posix_setegid(int $group_id): bool {}

/**
 * Return the group set of the current process
 * @link https://php.net/manual/en/function.posix-getgroups.php
 * @return array|false an array of integers containing the numeric group ids of the group
 * set of the current process.
 */
#[Pure]
function posix_getgroups(): array|false {}

/**
 * Return login name
 * @link https://php.net/manual/en/function.posix-getlogin.php
 * @return string|false the login name of the user, as a string.
 */
#[Pure]
function posix_getlogin(): string|false {}

/**
 * Return the current process group identifier
 * @link https://php.net/manual/en/function.posix-getpgrp.php
 * @return int the identifier, as an integer.
 */
#[Pure]
function posix_getpgrp(): int {}

/**
 * Make the current process a session leader
 * @link https://php.net/manual/en/function.posix-setsid.php
 * @return int the session id, or -1 on errors.
 */
function posix_setsid(): int {}

/**
 * Set process group id for job control
 * @link https://php.net/manual/en/function.posix-setpgid.php
 * @param int $process_id <p>
 * The process id.
 * </p>
 * @param int $process_group_id <p>
 * The process group id.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function posix_setpgid(int $process_id, int $process_group_id): bool {}

/**
 * Get process group id for job control
 * @link https://php.net/manual/en/function.posix-getpgid.php
 * @param int $process_id <p>
 * The process id.
 * </p>
 * @return int|false the identifier, as an integer.
 */
#[Pure]
function posix_getpgid(int $process_id): int|false {}

/**
 * Get the current sid of the process
 * @link https://php.net/manual/en/function.posix-getsid.php
 * @param int $process_id <p>
 * The process identifier. If set to 0, the current process is
 * assumed. If an invalid <i>pid</i> is
 * specified, then <b>FALSE</b> is returned and an error is set which
 * can be checked with <b>posix_get_last_error</b>.
 * </p>
 * @return int|false the identifier, as an integer.
 */
#[Pure]
function posix_getsid(int $process_id): int|false {}

/**
 * Get system name
 * @link https://php.net/manual/en/function.posix-uname.php
 * @return array|false a hash of strings with information about the
 * system. The indices of the hash are
 * sysname - operating system name (e.g. Linux)
 * nodename - system name (e.g. valiant)
 * release - operating system release (e.g. 2.2.10)
 * version - operating system version (e.g. #4 Tue Jul 20
 * 17:01:36 MEST 1999)
 * machine - system architecture (e.g. i586)
 * domainname - DNS domainname (e.g. example.com)
 * </p>
 * <p>
 * domainname is a GNU extension and not part of POSIX.1, so this
 * field is only available on GNU systems or when using the GNU
 * libc.
 */
#[Pure]
#[ArrayShape([
    'sysname' => 'string',
    'nodename' => 'string',
    'release' => 'string',
    'version' => 'string',
    'machine' => 'string',
    'domainname' => 'string',
])]
function posix_uname(): array|false {}

/**
 * Get process times
 * @link https://php.net/manual/en/function.posix-times.php
 * @return array|false a hash of strings with information about the current
 * process CPU usage. The indices of the hash are:
 * ticks - the number of clock ticks that have elapsed since
 * reboot.
 * utime - user time used by the current process.
 * stime - system time used by the current process.
 * cutime - user time used by current process and children.
 * cstime - system time used by current process and children.
 */
#[Pure]
#[ArrayShape([
    'ticks' => 'int',
    'utime' => 'int',
    'stime' => 'int',
    'cutime' => 'int',
    'cstime' => 'int'
])]
function posix_times(): array|false {}

/**
 * Get path name of controlling terminal
 * @link https://php.net/manual/en/function.posix-ctermid.php
 * @return string|false Upon successful completion, returns string of the pathname to
 * the current controlling terminal. Otherwise <b>FALSE</b> is returned and errno
 * is set, which can be checked with <b>posix_get_last_error</b>.
 */
#[Pure]
function posix_ctermid(): string|false {}

/**
 * Determine terminal device name
 * @link https://php.net/manual/en/function.posix-ttyname.php
 * @param int $file_descriptor <p>
 * The file descriptor.
 * </p>
 * @return string|false On success, returns a string of the absolute path of the
 * <i>fd</i>. On failure, returns <b>FALSE</b>
 */
#[Pure]
function posix_ttyname($file_descriptor): string|false {}

/**
 * Determine if a file descriptor is an interactive terminal
 * @link https://php.net/manual/en/function.posix-isatty.php
 * @param mixed $file_descriptor <p>
 * The file descriptor, which is expected to be either a file resource or an integer.
 * An integer will be assumed to be a file descriptor that can be passed
 * directly to the underlying system call.<br />
 * In almost all cases, you will want to provide a file resource.
 * </p>
 * @return bool <b>TRUE</b> if <i>fd</i> is an open descriptor connected
 * to a terminal and <b>FALSE</b> otherwise.
 */
#[Pure]
function posix_isatty($file_descriptor): bool {}

/**
 * Pathname of current directory
 * @link https://php.net/manual/en/function.posix-getcwd.php
 * @return string|false a string of the absolute pathname on success.
 * On error, returns <b>FALSE</b> and sets errno which can be checked with
 * <b>posix_get_last_error</b>.
 */
#[Pure(true)]
function posix_getcwd(): string|false {}

/**
 * Create a fifo special file (a named pipe)
 * @link https://php.net/manual/en/function.posix-mkfifo.php
 * @param string $filename <p>
 * Path to the FIFO file.
 * </p>
 * @param int $permissions <p>
 * The second parameter <i>mode</i> has to be given in
 * octal notation (e.g. 0644). The permission of the newly created
 * FIFO also depends on the setting of the current
 * <b>umask</b>. The permissions of the created file are
 * (mode & ~umask).
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function posix_mkfifo(string $filename, int $permissions): bool {}

/**
 * Create a special or ordinary file (POSIX.1)
 * @link https://php.net/manual/en/function.posix-mknod.php
 * @param string $filename <p>
 * The file to create
 * </p>
 * @param int $flags <p>
 * This parameter is constructed by a bitwise OR between file type (one of
 * the following constants: <b>POSIX_S_IFREG</b>,
 * <b>POSIX_S_IFCHR</b>, <b>POSIX_S_IFBLK</b>,
 * <b>POSIX_S_IFIFO</b> or
 * <b>POSIX_S_IFSOCK</b>) and permissions.
 * </p>
 * @param int $major [optional] <p>
 * The major device kernel identifier (required to pass when using
 * <b>S_IFCHR</b> or <b>S_IFBLK</b>).
 * </p>
 * @param int $minor [optional] <p>
 * The minor device kernel identifier.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function posix_mknod(string $filename, int $flags, int $major = 0, int $minor = 0): bool {}

/**
 * Determine accessibility of a file
 * @link https://php.net/manual/en/function.posix-access.php
 * @param string $filename <p>
 * The name of the file to be tested.
 * </p>
 * @param int $flags [optional] <p>
 * A mask consisting of one or more of <b>POSIX_F_OK</b>,
 * <b>POSIX_R_OK</b>, <b>POSIX_W_OK</b> and
 * <b>POSIX_X_OK</b>.
 * </p>
 * <p>
 * <b>POSIX_R_OK</b>, <b>POSIX_W_OK</b> and
 * <b>POSIX_X_OK</b> request checking whether the file
 * exists and has read, write and execute permissions, respectively.
 * <b>POSIX_F_OK</b> just requests checking for the
 * existence of the file.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function posix_access(string $filename, int $flags = POSIX_F_OK): bool {}

/**
 * Return info about a group by name
 * @link https://php.net/manual/en/function.posix-getgrnam.php
 * @param string $name <p>The name of the group</p>
 * @return array|false The array elements returned are:
 * <table>
 * The group information array
 * <tr valign="top">
 * <td>Element</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>name</td>
 * <td>
 * The name element contains the name of the group. This is
 * a short, usually less than 16 character "handle" of the
 * group, not the real, full name. This should be the same as
 * the <i>name</i> parameter used when
 * calling the function, and hence redundant.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>passwd</td>
 * <td>
 * The passwd element contains the group's password in an
 * encrypted format. Often, for example on a system employing
 * "shadow" passwords, an asterisk is returned instead.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>gid</td>
 * <td>
 * Group ID of the group in numeric form.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>members</td>
 * <td>
 * This consists of an array of
 * string's for all the members in the group.
 * </td>
 * </tr>
 * </table>
 */
#[Pure]
function posix_getgrnam(string $name): array|false {}

/**
 * Return info about a group by group id
 * @link https://php.net/manual/en/function.posix-getgrgid.php
 * @param int $group_id <p>
 * The group id.
 * </p>
 * @return array|false The array elements returned are:
 * <table>
 * The group information array
 * <tr valign="top">
 * <td>Element</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>name</td>
 * <td>
 * The name element contains the name of the group. This is
 * a short, usually less than 16 character "handle" of the
 * group, not the real, full name.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>passwd</td>
 * <td>
 * The passwd element contains the group's password in an
 * encrypted format. Often, for example on a system employing
 * "shadow" passwords, an asterisk is returned instead.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>gid</td>
 * <td>
 * Group ID, should be the same as the
 * <i>gid</i> parameter used when calling the
 * function, and hence redundant.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>members</td>
 * <td>
 * This consists of an array of
 * string's for all the members in the group.
 * </td>
 * </tr>
 * </table>
 */
#[Pure]
function posix_getgrgid(int $group_id): array|false {}

/**
 * Return info about a user by username
 * @link https://php.net/manual/en/function.posix-getpwnam.php
 * @param string $username <p>
 * An alphanumeric username.
 * </p>
 * @return array|false On success an array with the following elements is returned, else
 * <b>FALSE</b> is returned:
 * <table>
 * The user information array
 * <tr valign="top">
 * <td>Element</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>name</td>
 * <td>
 * The name element contains the username of the user. This is
 * a short, usually less than 16 character "handle" of the
 * user, not the real, full name. This should be the same as
 * the <i>username</i> parameter used when
 * calling the function, and hence redundant.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>passwd</td>
 * <td>
 * The passwd element contains the user's password in an
 * encrypted format. Often, for example on a system employing
 * "shadow" passwords, an asterisk is returned instead.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>uid</td>
 * <td>
 * User ID of the user in numeric form.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>gid</td>
 * <td>
 * The group ID of the user. Use the function
 * <b>posix_getgrgid</b> to resolve the group
 * name and a list of its members.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>gecos</td>
 * <td>
 * GECOS is an obsolete term that refers to the finger
 * information field on a Honeywell batch processing system.
 * The field, however, lives on, and its contents have been
 * formalized by POSIX. The field contains a comma separated
 * list containing the user's full name, office phone, office
 * number, and home phone number. On most systems, only the
 * user's full name is available.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>dir</td>
 * <td>
 * This element contains the absolute path to the home
 * directory of the user.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>shell</td>
 * <td>
 * The shell element contains the absolute path to the
 * executable of the user's default shell.
 * </td>
 * </tr>
 * </table>
 */
#[Pure]
#[ArrayShape([
    "name" => "string",
    "passwd" => "string",
    "uid" => "int",
    "gid" => "int",
    "gecos" => "string",
    "dir" => "string",
    "shell" => "string",
])]
function posix_getpwnam(string $username): array|false {}

/**
 * Return info about a user by user id
 * @link https://php.net/manual/en/function.posix-getpwuid.php
 * @param int $user_id <p>
 * The user identifier.
 * </p>
 * @return array|false an associative array with the following elements:
 * <table>
 * The user information array
 * <tr valign="top">
 * <td>Element</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>name</td>
 * <td>
 * The name element contains the username of the user. This is
 * a short, usually less than 16 character "handle" of the
 * user, not the real, full name.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>passwd</td>
 * <td>
 * The passwd element contains the user's password in an
 * encrypted format. Often, for example on a system employing
 * "shadow" passwords, an asterisk is returned instead.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>uid</td>
 * <td>
 * User ID, should be the same as the
 * <i>uid</i> parameter used when calling the
 * function, and hence redundant.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>gid</td>
 * <td>
 * The group ID of the user. Use the function
 * <b>posix_getgrgid</b> to resolve the group
 * name and a list of its members.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>gecos</td>
 * <td>
 * GECOS is an obsolete term that refers to the finger
 * information field on a Honeywell batch processing system.
 * The field, however, lives on, and its contents have been
 * formalized by POSIX. The field contains a comma separated
 * list containing the user's full name, office phone, office
 * number, and home phone number. On most systems, only the
 * user's full name is available.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>dir</td>
 * <td>
 * This element contains the absolute path to the
 * home directory of the user.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>shell</td>
 * <td>
 * The shell element contains the absolute path to the
 * executable of the user's default shell.
 * </td>
 * </tr>
 * </table>
 */
#[Pure]
#[ArrayShape([
    'name' => 'string',
    'passwd' => 'string',
    'uid' => 'int',
    'gid' => 'int',
    'gecos' => 'string',
    'dir' => 'string',
    'shell' => 'string',
])]
function posix_getpwuid(int $user_id): array|false {}

/**
 * Return info about system resource limits
 * @link https://php.net/manual/en/function.posix-getrlimit.php
 * @return array|false an associative array of elements for each
 * limit that is defined. Each limit has a soft and a hard limit.
 * <table>
 * List of possible limits returned
 * <tr valign="top">
 * <td>Limit name</td>
 * <td>Limit description</td>
 * </tr>
 * <tr valign="top">
 * <td>core</td>
 * <td>
 * The maximum size of the core file. When 0, not core files are
 * created. When core files are larger than this size, they will
 * be truncated at this size.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>totalmem</td>
 * <td>
 * The maximum size of the memory of the process, in bytes.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>virtualmem</td>
 * <td>
 * The maximum size of the virtual memory for the process, in bytes.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>data</td>
 * <td>
 * The maximum size of the data segment for the process, in bytes.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>stack</td>
 * <td>
 * The maximum size of the process stack, in bytes.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>rss</td>
 * <td>
 * The maximum number of virtual pages resident in RAM
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>maxproc</td>
 * <td>
 * The maximum number of processes that can be created for the
 * real user ID of the calling process.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>memlock</td>
 * <td>
 * The maximum number of bytes of memory that may be locked into RAM.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>cpu</td>
 * <td>
 * The amount of time the process is allowed to use the CPU.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>filesize</td>
 * <td>
 * The maximum size of the data segment for the process, in bytes.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>openfiles</td>
 * <td>
 * One more than the maximum number of open file descriptors.
 * </td>
 * </tr>
 * </table>
 */
#[Pure]
function posix_getrlimit(): array|false {}

/**
 * Retrieve the error number set by the last posix function that failed
 * @link https://php.net/manual/en/function.posix-get-last-error.php
 * @return int the errno (error number) set by the last posix function that
 * failed. If no errors exist, 0 is returned.
 */
#[Pure(true)]
function posix_get_last_error(): int {}

/**
 * Alias of <b>posix_get_last_error</b>
 * @link https://php.net/manual/en/function.posix-errno.php
 */
#[Pure(true)]
function posix_errno(): int {}

/**
 * Retrieve the system error message associated with the given errno
 * @link https://php.net/manual/en/function.posix-strerror.php
 * @param int $error_code <p>
 * A POSIX error number, returned by
 * <b>posix_get_last_error</b>. If set to 0, then the
 * string "Success" is returned.
 * </p>
 * @return string the error message, as a string.
 */
#[Pure]
function posix_strerror(int $error_code): string {}

/**
 * Calculate the group access list
 * @link https://php.net/manual/en/function.posix-initgroups.php
 * @param string $username <p>
 * The user to calculate the list for.
 * </p>
 * @param int $group_id <p>
 * Typically the group number from the password file.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
#[Pure]
function posix_initgroups(string $username, int $group_id): bool {}

/**
 * Check whether the file exists.
 * @link https://php.net/manual/en/posix.constants.php
 */
define('POSIX_F_OK', 0);

/**
 * Check whether the file exists and has execute permissions.
 * @link https://php.net/manual/en/posix.constants.php
 */
define('POSIX_X_OK', 1);

/**
 * Check whether the file exists and has write permissions.
 * @link https://php.net/manual/en/posix.constants.php
 */
define('POSIX_W_OK', 2);

/**
 * Check whether the file exists and has read permissions.
 * @link https://php.net/manual/en/posix.constants.php
 */
define('POSIX_R_OK', 4);

/**
 * Normal file
 * @link https://php.net/manual/en/posix.constants.php
 */
define('POSIX_S_IFREG', 32768);

/**
 * Character special file
 * @link https://php.net/manual/en/posix.constants.php
 */
define('POSIX_S_IFCHR', 8192);

/**
 * Block special file
 * @link https://php.net/manual/en/posix.constants.php
 */
define('POSIX_S_IFBLK', 24576);

/**
 * FIFO (named pipe) special file
 * @link https://php.net/manual/en/posix.constants.php
 */
define('POSIX_S_IFIFO', 4096);

/**
 * Socket
 * @link https://php.net/manual/en/posix.constants.php
 */
define('POSIX_S_IFSOCK', 49152);

/**
 * The maximum size of the process's address space in bytes. See also PHP's memory_limit configuration directive.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_AS', 5);
/**
 * The maximum size of a core file. If the limit is set to 0, no core file will be generated.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_CORE', 4);

/**
 * The maximum amount of CPU time that the process can use, in seconds.
 * When the soft limit is hit, a SIGXCPU signal will be sent, which can be caught with pcntl_signal().
 * Depending on the operating system, additional SIGXCPU signals may be sent each second until the hard limit is hit,
 * at which point an uncatchable SIGKILL signal is sent. See also set_time_limit().
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_CPU', 0);

/**
 * The maximum size of the process's data segment, in bytes.
 * It is extremely unlikely that this will have any effect on
 * the execution of PHP unless an extension is in use that calls brk() or sbrk().
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_DATA', 2);

/**
 * The maximum size of files that the process can create, in bytes.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_FSIZE', 1);

/**
 * The maximum number of locks that the process can create.
 * This is only supported on extremely old Linux kernels.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_LOCKS', 10);

/**
 * The maximum number of bytes that can be allocated for POSIX message queues.
 * PHP does not ship with support for POSIX message queues,
 * so this limit will not have any effect unless you are using an extension that implements that support.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_MSGQUEUE', 12);

/**
 * The maximum value to which the process can be <a href="https://php.net/manual/en/function.pcntl-setpriority.php"> reniced</a> to. The value that will be used will be 20 - limit, as resource limit values cannot be negative.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_NICE', 13);

/**
 * The maximum real time priority that can be set via the sched_setscheduler() and sched_setparam() system calls.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_RTPRIO', 14);

/**
 * The maximum amount of CPU time, in microseconds,
 * that the process can consume without making a blocking system call if it is using real time scheduling.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_RTTIME', 15);

/**
 * The maximum number of signals that can be queued for the real user ID of the process.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_SIGPENDING', 11);

/**
 * The maximum number of bytes that can be locked into memory.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_MEMLOCK', 6);

/**
 * A value one greater than the maximum file descriptor number that can be opened by this process.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_NOFILE', 8);

/**
 * The maximum number of processes (and/or threads, on some operating systems)
 * that can be created for the real user ID of the process.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_NPROC', 7);

/**
 * The maximum size of the process's resident set, in pages.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_RSS', 5);

/**
 * The maximum size of the process stack, in bytes.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_STACK', 3);

/**
 * Used to indicate an infinite value for a resource limit.
 * @link https://php.net/manual/en/posix.constants.setrlimit.php
 */
define('POSIX_RLIMIT_INFINITY', 9223372036854775807);

// End of posix v.
