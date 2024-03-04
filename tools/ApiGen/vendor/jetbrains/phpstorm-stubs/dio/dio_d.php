<?php
/**
 * @link https://www.php.net/manual/en/dio.constants.php
 * Direct IO Constants
 * Table of Contents
 * O_RDONLY - opens the file for read access.
 * O_WRONLY - opens the file for write access.
 * O_RDWR - opens the file for both reading and writing.
 * O_CREAT - creates the file, if it doesn't already exist.
 * O_EXCL - if both O_CREAT and O_EXCL are set and the file already exists, dio_open() will fail.
 * O_TRUNC - if the file exists and is opened for write access, the file will be truncated to zero length.
 * O_APPEND - write operations write data at the end of the file.
 * O_NONBLOCK - sets non blocking mode.
 * O_NOCTTY - prevent the OS from assigning the opened file as the process's controlling terminal when opening a TTY device file.
 * F_SETLK - Lock is set or cleared. If the lockis held by someone else dio_fcntl() returns-1.
 * F_SETLKW - like F_SETLK,but in case the lock is held by someone else, dio_fcntl() waits until the lock is released.
 * F_GETLK - dio_fcntl() returns an associative array (as described below) if someone elseprevents lock. If there is no obstruction key "type" will setto F_UNLCK.
 * F_DUPFD - finds the lowest numbered availablefile descriptor greater than or equal to argsand returns them.
 * F_SETFL - Sets the file descriptors flags tothe value specified by args, which can be O_APPEND, O_NONBLOCK or O_ASYNC. To use O_ASYNC you will need to use the PCNTL extension.
 * O_NDELAY -
 * O_SYNC -
 * O_ASYNC -
 * S_IRWXU -
 * S_IRUSR -
 * S_IWUSR -
 * S_IXUSR -
 * S_IRWXG -
 * S_IRGRP -
 * S_IWGRP -
 * S_IXGRP -
 * S_IRWXO -
 * S_IROTH -
 * S_IWOTH -
 * S_IXOTH -
 * F_GETFD -
 * F_GETFL -
 * F_SETOWN -
 * F_GETOWN -
 * F_UNLCK -
 * F_RDLCK -
 * F_WRLCK -
 */

/**
 * O_RDONLY - opens the file for read access.
 */
define('O_RDONLY', 0);

/**
 * O_WRONLY - opens the file for write access.
 */
define('O_WRONLY', 1);

/**
 * O_RDWR - opens the file for both reading and writing.
 */
define('O_RDWR', 2);

/**
 * O_CREAT - creates the file, if it doesn't already exist.
 */
define('O_CREAT', 64);

/**
 * O_EXCL - if both O_CREAT and O_EXCL are set and the file already exists, dio_open() will fail.
 */
define('O_EXCL', 128);

/**
 * O_TRUNC - if the file exists and is opened for write access, the file will be truncated to zero length.
 */
define('O_TRUNC', 512);

/**
 * O_APPEND - write operations write data at the end of the file.
 */
define('O_APPEND', 1024);

/**
 * O_NONBLOCK - sets non blocking mode.
 */
define('O_NONBLOCK', 2048);

define('O_NDELAY', 2048);
define('O_SYNC', 1052672);
define('O_ASYNC', 8192);

/**
 * O_NOCTTY - prevent the OS from assigning the opened file as the process's controlling terminal when opening a TTY device file.
 */
define('O_NOCTTY', 256);

define('S_IRWXU', 448);
define('S_IRUSR', 256);
define('S_IWUSR', 128);
define('S_IXUSR', 64);
define('S_IRWXG', 56);
define('S_IRGRP', 32);
define('S_IWGRP', 16);
define('S_IXGRP', 8);
define('S_IRWXO', 7);
define('S_IROTH', 4);
define('S_IWOTH', 2);
define('S_IXOTH', 1);

/**
 * F_DUPFD - finds the lowest numbered availablefile descriptor greater than or equal to argsand returns them.
 */
define('F_DUPFD', 0);

define('F_GETFD', 1);
define('F_GETFL', 3);

/**
 * F_SETFL - Sets the file descriptors flags tothe value specified by args, which can be O_APPEND, O_NONBLOCK or O_ASYNC. To use O_ASYNC you will need to use the PCNTL extension.
 */
define('F_SETFL', 4);

/**
 * F_GETLK - dio_fcntl() returns an associative array (as described below) if someone elseprevents lock. If there is no obstruction key "type" will setto F_UNLCK.
 */
define('F_GETLK', 5);

/**
 * F_SETLK - Lock is set or cleared. If the lockis held by someone else dio_fcntl() returns-1.
 */
define('F_SETLK', 6);

/**
 * F_SETLKW - like F_SETLK,but in case the lock is held by someone else, dio_fcntl() waits until the lock is released.
 */
define('F_SETLKW', 7);

define('F_SETOWN', 8);
define('F_GETOWN', 9);
define('F_UNLCK', 2);
define('F_RDLCK', 0);
define('F_WRLCK', 1);
