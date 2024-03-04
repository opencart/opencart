<?php
/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_DEFAULT_TCP_HOST', '127.0.0.1');

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_DEFAULT_TCP_PORT', 4730);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_DEFAULT_SOCKET_TIMEOUT', 10);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_DEFAULT_SOCKET_SEND_SIZE', 32768);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_DEFAULT_SOCKET_RECV_SIZE', 32768);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_MAX_ERROR_SIZE', 1024);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_PACKET_HEADER_SIZE', 12);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_JOB_HANDLE_SIZE', 64);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_OPTION_SIZE', 64);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_UNIQUE_SIZE', 64);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_MAX_COMMAND_ARGS', 8);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_ARGS_BUFFER_SIZE', 128);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_SEND_BUFFER_SIZE', 8192);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_RECV_BUFFER_SIZE', 8192);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_WAIT_TIMEOUT', 10000);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_SUCCESS', 0);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_IO_WAIT', 1);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_SHUTDOWN', 2);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_SHUTDOWN_GRACEFUL', 3);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_ERRNO', 4);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_EVENT', 5);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_TOO_MANY_ARGS', 6);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_NO_ACTIVE_FDS', 7);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_INVALID_MAGIC', 8);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_INVALID_COMMAND', 9);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_INVALID_PACKET', 10);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_UNEXPECTED_PACKET', 11);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_GETADDRINFO', 12);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_NO_SERVERS', 13);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_LOST_CONNECTION', 14);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_MEMORY_ALLOCATION_FAILURE', 15);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_JOB_EXISTS', 16);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_JOB_QUEUE_FULL', 17);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_SERVER_ERROR', 18);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORK_ERROR', 19);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORK_DATA', 20);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORK_WARNING', 21);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORK_STATUS', 22);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORK_EXCEPTION', 23);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORK_FAIL', 24);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_NOT_CONNECTED', 25);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COULD_NOT_CONNECT', 26);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_SEND_IN_PROGRESS', 27);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_RECV_IN_PROGRESS', 28);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_NOT_FLUSHING', 29);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_DATA_TOO_LARGE', 30);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_INVALID_FUNCTION_NAME', 31);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_INVALID_WORKER_FUNCTION', 32);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_NO_REGISTERED_FUNCTIONS', 34);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_NO_JOBS', 35);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_ECHO_DATA_CORRUPTION', 36);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_NEED_WORKLOAD_FN', 37);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_PAUSE', 38);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_UNKNOWN_STATE', 39);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_PTHREAD', 40);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_PIPE_EOF', 41);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_QUEUE_ERROR', 42);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_FLUSH_DATA', 43);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_SEND_BUFFER_TOO_SMALL', 44);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_IGNORE_PACKET', 45);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_UNKNOWN_OPTION', 46);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_TIMEOUT', 47);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_MAX_RETURN', 49);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_VERBOSE_NEVER', 0);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_VERBOSE_FATAL', 1);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_VERBOSE_ERROR', 2);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_VERBOSE_INFO', 3);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_VERBOSE_DEBUG', 4);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_VERBOSE_CRAZY', 5);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_VERBOSE_MAX', 6);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_NON_BLOCKING', 0);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_DONT_TRACK_PACKETS', 1);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CON_READY', 0);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CON_PACKET_IN_USE', 1);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CON_EXTERNAL_FD', 2);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CON_IGNORE_LOST_CONNECTION', 3);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CON_CLOSE_AFTER_FLUSH', 4);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CON_SEND_STATE_NONE', 0);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CON_RECV_STATE_READ_DATA', 2);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_MAGIC_TEXT', 0);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_MAGIC_REQUEST', 1);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_MAGIC_RESPONSE', 2);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_TEXT', 0);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_CAN_DO', 1);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_CANT_DO', 2);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_RESET_ABILITIES', 3);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_PRE_SLEEP', 4);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_UNUSED', 5);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_NOOP', 6);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_SUBMIT_JOB', 7);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_JOB_CREATED', 8);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_GRAB_JOB', 9);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_NO_JOB', 10);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_JOB_ASSIGN', 11);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_WORK_STATUS', 12);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_WORK_COMPLETE', 13);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_WORK_FAIL', 14);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_GET_STATUS', 15);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_ECHO_REQ', 16);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_ECHO_RES', 17);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_SUBMIT_JOB_BG', 18);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_ERROR', 19);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_STATUS_RES', 20);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_SUBMIT_JOB_HIGH', 21);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_SET_CLIENT_ID', 22);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_CAN_DO_TIMEOUT', 23);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_ALL_YOURS', 24);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_WORK_EXCEPTION', 25);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_OPTION_REQ', 26);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_OPTION_RES', 27);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_WORK_DATA', 28);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_WORK_WARNING', 29);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_GRAB_JOB_UNIQ', 30);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_JOB_ASSIGN_UNIQ', 31);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_SUBMIT_JOB_HIGH_BG', 32);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_SUBMIT_JOB_LOW', 33);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_SUBMIT_JOB_LOW_BG', 34);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_SUBMIT_JOB_SCHED', 35);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_SUBMIT_JOB_EPOCH', 36);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_COMMAND_MAX', 37);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_TASK_STATE_NEW', 0);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_TASK_STATE_SUBMIT', 1);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_TASK_STATE_WORKLOAD', 2);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_TASK_STATE_WORK', 3);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_TASK_STATE_CREATED', 4);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_TASK_STATE_DATA', 5);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_TASK_STATE_WARNING', 6);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_TASK_STATE_STATUS', 7);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_TASK_STATE_COMPLETE', 8);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_TASK_STATE_EXCEPTION', 9);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_TASK_STATE_FAIL', 10);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_TASK_STATE_FINISHED', 11);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_JOB_PRIORITY_HIGH', 0);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_JOB_PRIORITY_NORMAL', 1);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_JOB_PRIORITY_LOW', 2);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_JOB_PRIORITY_MAX', 3);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CLIENT_ALLOCATED', 1);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CLIENT_NON_BLOCKING', 2);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CLIENT_TASK_IN_USE', 4);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CLIENT_UNBUFFERED_RESULT', 8);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CLIENT_NO_NEW', 16);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CLIENT_FREE_TASKS', 32);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CLIENT_STATE_IDLE', 0);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CLIENT_STATE_NEW', 1);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CLIENT_STATE_SUBMIT', 2);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_CLIENT_STATE_PACKET', 3);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_ALLOCATED', 1);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_NON_BLOCKING', 2);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_PACKET_INIT', 4);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_GRAB_JOB_IN_USE', 8);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_PRE_SLEEP_IN_USE', 16);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_WORK_JOB_IN_USE', 32);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_CHANGE', 64);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_GRAB_UNIQ', 128);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_TIMEOUT_RETURN', 256);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_STATE_START', 0);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_STATE_FUNCTION_SEND', 1);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_STATE_CONNECT', 2);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_STATE_GRAB_JOB_SEND', 3);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_STATE_GRAB_JOB_RECV', 4);

/**
 * @link https://php.net/manual/en/gearman.constants.php
 */
define('GEARMAN_WORKER_STATE_PRE_SLEEP', 5);

function gearman_version() {}

function gearman_bugreport() {}

/**
 * @param $verbose
 */
function gearman_verbose_name($verbose) {}

/**
 * @param $client_object
 */
function gearman_client_return_code($client_object) {}

/**
 * @param $client_object
 */
function gearman_client_create($client_object) {}

/**
 * @param $client_object
 */
function gearman_client_clone($client_object) {}

/**
 * @param $client_object
 */
function gearman_client_error($client_object) {}

/**
 * @param $client_object
 */
function gearman_client_errno($client_object) {}

/**
 * @param $client_object
 */
function gearman_client_options($client_object) {}

/**
 * @param $client_object
 * @param $option
 */
function gearman_client_set_options($client_object, $option) {}

/**
 * @param $client_object
 * @param $option
 */
function gearman_client_add_options($client_object, $option) {}

/**
 * @param $client_object
 * @param $option
 */
function gearman_client_remove_options($client_object, $option) {}

/**
 * @param $client_object
 */
function gearman_client_timeout($client_object) {}

/**
 * @param $client_object
 * @param $timeout
 */
function gearman_client_set_timeout($client_object, $timeout) {}

/**
 * @param $client_object
 */
function gearman_client_context($client_object) {}

/**
 * @param $client_object
 * @param $context
 */
function gearman_client_set_context($client_object, $context) {}

/**
 * @param $client_object
 * @param $host
 * @param $port
 */
function gearman_client_add_server($client_object, $host, $port) {}

/**
 * @param $client_object
 * @param $servers
 */
function gearman_client_add_servers($client_object, $servers) {}

/**
 * @param $client_object
 */
function gearman_client_wait($client_object) {}

/**
 * @param $client_object
 * @param $function_name
 * @param $workload
 * @param $unique
 */
function gearman_client_do($client_object, $function_name, $workload, $unique) {}

/**
 * @param $client_object
 * @param $function_name
 * @param $workload
 * @param $unique
 */
function gearman_client_do_high($client_object, $function_name, $workload, $unique) {}

/**
 * @param $client_object
 * @param string $function_name
 * @param string $workload
 * @param string $unique
 */
function gearman_client_do_normal($client_object, $function_name, $workload, $unique) {}

/**
 * @param $client_object
 * @param $function_name
 * @param $workload
 * @param $unique
 */
function gearman_client_do_low($client_object, $function_name, $workload, $unique) {}

/**
 * @param $client_object
 */
function gearman_client_do_job_handle($client_object) {}

/**
 * @param $client_object
 */
function gearman_client_do_status($client_object) {}

/**
 * @param $client_object
 * @param $function_name
 * @param $workload
 * @param $unique
 */
function gearman_client_do_background($client_object, $function_name, $workload, $unique) {}

/**
 * @param $client_object
 * @param $function_name
 * @param $workload
 * @param $unique
 */
function gearman_client_do_high_background($client_object, $function_name, $workload, $unique) {}

/**
 * @param $client_object
 * @param $function_name
 * @param $workload
 * @param $unique
 */
function gearman_client_do_low_background($client_object, $function_name, $workload, $unique) {}

/**
 * @param $client_object
 * @param $job_handle
 */
function gearman_client_job_status($client_object, $job_handle) {}

/**
 * @param $client_object
 * @param $workload
 */
function gearman_client_echo($client_object, $workload) {}

/**
 * @param $client_object
 * @param $function_name
 * @param $workload
 * @param $context
 * @param $unique
 */
function gearman_client_add_task($client_object, $function_name, $workload, $context, $unique) {}

/**
 * @param $client_object
 * @param $function_name
 * @param $workload
 * @param $context
 * @param $unique
 */
function gearman_client_add_task_high($client_object, $function_name, $workload, $context, $unique) {}

/**
 * @param $client_object
 * @param $function_name
 * @param $workload
 * @param $context
 * @param $unique
 */
function gearman_client_add_task_low($client_object, $function_name, $workload, $context, $unique) {}

/**
 * @param $client_object
 * @param $function_name
 * @param $workload
 * @param $context
 * @param $unique
 */
function gearman_client_add_task_background($client_object, $function_name, $workload, $context, $unique) {}

/**
 * @param $client_object
 * @param $function_name
 * @param $workload
 * @param $context
 * @param $unique
 */
function gearman_client_add_task_high_background($client_object, $function_name, $workload, $context, $unique) {}

/**
 * @param $client_object
 * @param $function_name
 * @param $workload
 * @param $context
 * @param $unique
 */
function gearman_client_add_task_low_background($client_object, $function_name, $workload, $context, $unique) {}

/**
 * @param $client_object
 * @param $job_handle
 * @param $context
 */
function gearman_client_add_task_status($client_object, $job_handle, $context) {}

/**
 * @param $client_object
 * @param $callback
 */
function gearman_client_set_workload_fn($client_object, $callback) {}

/**
 * @param $client_object
 * @param $callback
 */
function gearman_client_set_created_fn($client_object, $callback) {}

/**
 * @param $client_object
 * @param $callback
 */
function gearman_client_set_data_fn($client_object, $callback) {}

/**
 * @param $client_object
 * @param $callback
 */
function gearman_client_set_warning_fn($client_object, $callback) {}

/**
 * @param $client_object
 * @param $callback
 */
function gearman_client_set_status_fn($client_object, $callback) {}

/**
 * @param $client_object
 * @param $callback
 */
function gearman_client_set_complete_fn($client_object, $callback) {}

/**
 * @param $client_object
 * @param $callback
 */
function gearman_client_set_exception_fn($client_object, $callback) {}

/**
 * @param $client_object
 * @param $callback
 */
function gearman_client_set_fail_fn($client_object, $callback) {}

/**
 * @param $client_object
 */
function gearman_client_clear_fn($client_object) {}

/**
 * @param $data
 */
function gearman_client_run_tasks($data) {}

/**
 * @param $task_object
 */
function gearman_task_return_code($task_object) {}

/**
 * @param $task_object
 */
function gearman_task_function_name($task_object) {}

/**
 * @param $task_object
 */
function gearman_task_unique($task_object) {}

/**
 * @param $task_object
 */
function gearman_task_job_handle($task_object) {}

/**
 * @param $task_object
 */
function gearman_task_is_known($task_object) {}

/**
 * @param $task_object
 */
function gearman_task_is_running($task_object) {}

/**
 * @param $task_object
 */
function gearman_task_numerator($task_object) {}

/**
 * @param $task_object
 */
function gearman_task_denominator($task_object) {}

/**
 * @param $task_object
 * @param $data
 */
function gearman_task_send_workload($task_object, $data) {}

/**
 * @param $task_object
 */
function gearman_task_data($task_object) {}

/**
 * @param $task_object
 */
function gearman_task_data_size($task_object) {}

/**
 * @param $task_object
 * @param $data_len
 */
function gearman_task_recv_data($task_object, $data_len) {}

/**
 * @param $worker_object
 */
function gearman_worker_return_code($worker_object) {}

function gearman_worker_create() {}

/**
 * @param $worker_object
 */
function gearman_worker_clone($worker_object) {}

/**
 * @param $worker_object
 */
function gearman_worker_error($worker_object) {}

/**
 * @param $worker_object
 */
function gearman_worker_errno($worker_object) {}

/**
 * @param $worker_object
 */
function gearman_worker_options($worker_object) {}

/**
 * @param $worker_object
 * @param $option
 */
function gearman_worker_set_options($worker_object, $option) {}

/**
 * @param $worker_object
 * @param $option
 */
function gearman_worker_add_options($worker_object, $option) {}

/**
 * @param $worker_object
 * @param $option
 */
function gearman_worker_remove_options($worker_object, $option) {}

/**
 * @param $worker_object
 */
function gearman_worker_timeout($worker_object) {}

/**
 * @param $worker_object
 * @param $timeout
 */
function gearman_worker_set_timeout($worker_object, $timeout) {}

/**
 * @param $worker_object
 * @param $host
 * @param $port
 */
function gearman_worker_add_server($worker_object, $host, $port) {}

/**
 * @param $worker_object
 * @param $servers
 */
function gearman_worker_add_servers($worker_object, $servers) {}

/**
 * @param $worker_object
 */
function gearman_worker_wait($worker_object) {}

/**
 * @param $worker_object
 * @param $function_name
 * @param $timeout
 */
function gearman_worker_register($worker_object, $function_name, $timeout) {}

/**
 * @param $worker_object
 * @param $function_name
 */
function gearman_worker_unregister($worker_object, $function_name) {}

/**
 * @param $worker_object
 */
function gearman_worker_unregister_all($worker_object) {}

/**
 * @param $worker_object
 */
function gearman_worker_grab_job($worker_object) {}

/**
 * @param $worker_object
 * @param $function_name
 * @param $function
 * @param $data
 * @param $timeout
 */
function gearman_worker_add_function($worker_object, $function_name, $function, $data, $timeout) {}

/**
 * @param $worker_object
 */
function gearman_worker_work($worker_object) {}

/**
 * @param $worker_object
 * @param $workload
 */
function gearman_worker_echo($worker_object, $workload) {}

/**
 * @param $job_object
 */
function gearman_job_return_code($job_object) {}

/**
 * @param $job_object
 * @param $data
 */
function gearman_job_send_data($job_object, $data) {}

/**
 * @param $job_object
 * @param $warning
 */
function gearman_job_send_warning($job_object, $warning) {}

/**
 * @param $job_object
 * @param $numerator
 * @param $denominator
 */
function gearman_job_send_status($job_object, $numerator, $denominator) {}

/**
 * @param $job_object
 * @param $result
 */
function gearman_job_send_complete($job_object, $result) {}

/**
 * @param $job_object
 * @param $exception
 */
function gearman_job_send_exception($job_object, $exception) {}

/**
 * @param $job_object
 */
function gearman_job_send_fail($job_object) {}

/**
 * Get the job handle
 * @param $job_object
 */
function gearman_job_handle($job_object) {}

/**
 * @param $job_object
 */
function gearman_job_function_name($job_object) {}

/**
 * @param $job_object
 */
function gearman_job_unique($job_object) {}

/**
 * @param $job_object
 */
function gearman_job_workload($job_object) {}

/**
 * @param $job_object
 */
function gearman_job_workload_size($job_object) {}

/**
 * Class: GearmanClient
 */
class GearmanClient
{
    /**
     * Creates a GearmanClient instance representing a client that connects to the job
     * server and submits tasks to complete.
     *
     * @link https://php.net/manual/en/gearmanclient.construct.php
     */
    public function __construct() {}

    /**
     * Returns the last Gearman return code.
     *
     * @link https://php.net/manual/en/gearmanclient.returncode.php
     * @return int A valid Gearman return code
     */
    public function returnCode() {}

    /**
     * Returns an error string for the last error encountered.
     *
     * @link https://php.net/manual/en/gearmanclient.error.php
     * @return string A human readable error string
     */
    public function error() {}

    /**
     * Value of errno in the case of a GEARMAN_ERRNO return value.
     *
     * @link https://php.net/manual/en/gearmanclient.geterrno.php
     * @return int A valid Gearman errno
     */
    public function getErrno() {}

    public function options() {}

    /**
     * Sets one or more client options.
     *
     * @link https://php.net/manual/en/gearmanclient.setoptions.php
     * @param int $options The options to be set
     * @return bool Always returns true
     */
    public function setOptions($options) {}

    /**
     * Adds one or more options to those already set.
     *
     * @link https://php.net/manual/en/gearmanclient.addoptions.php
     * @param int $options The options to add
     * @return bool Always returns true
     */
    public function addOptions($options) {}

    /**
     * Removes (unsets) one or more options.
     *
     * @link https://php.net/manual/en/gearmanclient.removeoptions.php
     * @param int $options The options to be removed (unset)
     * @return bool Always returns true
     */
    public function removeOptions($options) {}

    /**
     * Returns the timeout in milliseconds to wait for I/O activity.
     *
     * @link https://php.net/manual/en/gearmanclient.timeout.php
     * @return int Timeout in milliseconds to wait for I/O activity. A negative value means an
     *         infinite timeout
     */
    public function timeout() {}

    /**
     * Sets the timeout for socket I/O activity.
     *
     * @link https://php.net/manual/en/gearmanclient.settimeout.php
     * @param int $timeout An interval of time in milliseconds
     * @return bool Always returns true
     */
    public function setTimeout($timeout) {}

    /**
     * Get the application context previously set with GearmanClient::setContext.
     *
     * @link https://php.net/manual/en/gearmanclient.context.php
     * @return string The same context data structure set with GearmanClient::setContext
     */
    public function context() {}

    /**
     * Sets an arbitrary string to provide application context that can later be
     * retrieved by GearmanClient::context.
     *
     * @link https://php.net/manual/en/gearmanclient.setcontext.php
     * @param string $context Arbitrary context data
     * @return bool Always returns true
     */
    public function setContext($context) {}

    /**
     * Adds a job server to a list of servers that can be used to run a task. No socket
     * I/O happens here; the server is simply added to the list.
     *
     * @link https://php.net/manual/en/gearmanclient.addserver.php
     * @param string $host
     * @param int $port
     * @return bool
     */
    public function addServer($host = '127.0.0.1', $port = 4730) {}

    /**
     * Adds a list of job servers that can be used to run a task. No socket I/O happens
     * here; the servers are simply added to the full list of servers.
     *
     * @link https://php.net/manual/en/gearmanclient.addservers.php
     * @param string $servers A comma-separated list of servers, each server specified
     *        in the format host:port
     * @return bool
     */
    public function addServers($servers = '127.0.0.1:4730') {}

    public function wait() {}

    /**
     * Runs a single high priority task and returns a string representation of the
     * result. It is up to the GearmanClient and GearmanWorker to agree on the format
     * of the result. High priority tasks will get precedence over normal and low
     * priority tasks in the job queue.
     *
     * @link https://php.net/manual/en/gearmanclient.dohigh.php
     * @param string $function_name
     * @param string $workload
     * @param string $unique
     * @return string A string representing the results of running a task
     */
    public function doHigh($function_name, $workload, $unique = null) {}

    /**
     * Runs a single task and returns a string representation of the
     * result. It is up to the GearmanClient and GearmanWorker to agree on the format
     * of the result. Normal and high priority tasks will get precedence over low
     * priority tasks in the job queue.
     *
     * @link https://php.net/manual/en/gearmanclient.dolow.php
     * @param string $function_name
     * @param string $workload
     * @param string $unique
     * @return string A string representing the results of running a task
     */
    public function doNormal($function_name, $workload, $unique = null) {}

    /**
     * Runs a single low priority task and returns a string representation of the
     * result. It is up to the GearmanClient and GearmanWorker to agree on the format
     * of the result. Normal and high priority tasks will get precedence over low
     * priority tasks in the job queue.
     *
     * @link https://php.net/manual/en/gearmanclient.dolow.php
     * @param string $function
     * @param string $workload
     * @param string|null $unique
     * @return string A string representing the results of running a task
     */
    public function doLow($function, $workload, $unique = null) {}

    /**
     * Gets that job handle for a running task. This should be used between repeated
     * GearmanClient::do calls. The job handle can then be used to get information on
     * the task.
     *
     * @link https://php.net/manual/en/gearmanclient.dojobhandle.php
     * @return string The job handle for the running task
     */
    public function doJobHandle() {}

    /**
     * Returns the status for the running task. This should be used between repeated
     * GearmanClient::do calls.
     *
     * @link https://php.net/manual/en/gearmanclient.dostatus.php
     * @return array An array representing the percentage completion given as a fraction, with
     *         the first element the numerator and the second element the denomintor
     */
    public function doStatus() {}

    /**
     * Runs a task in the background, returning a job handle which can be used to get
     * the status of the running task.
     *
     * @link https://php.net/manual/en/gearmanclient.dobackground.php
     * @param string $function
     * @param string $workload
     * @param string|null $unique
     * @return string The job handle for the submitted task
     */
    public function doBackground($function, $workload, $unique = null) {}

    /**
     * Runs a high priority task in the background, returning a job handle which can be
     * used to get the status of the running task. High priority tasks take precedence
     * over normal and low priority tasks in the job queue.
     *
     * @link https://php.net/manual/en/gearmanclient.dohighbackground.php
     * @param string $function
     * @param string $workload
     * @param string|null $unique
     * @return string The job handle for the submitted task
     */
    public function doHighBackground($function, $workload, $unique = null) {}

    /**
     * Runs a low priority task in the background, returning a job handle which can be
     * used to get the status of the running task. Normal and high priority tasks take
     * precedence over low priority tasks in the job queue.
     *
     * @link https://php.net/manual/en/gearmanclient.dolowbackground.php
     * @param string $function
     * @param string $workload
     * @param string|null $unique
     * @return string The job handle for the submitted task
     */
    public function doLowBackground($function, $workload, $unique = null) {}

    /**
     * Object oriented style (method):.
     *
     * @link https://php.net/manual/en/gearmanclient.jobstatus.php
     * @param string $job_handle
     * @return array An array containing status information for the job corresponding to the
     *         supplied job handle. The first array element is a boolean indicating whether the
     *         job is even known, the second is a boolean indicating whether the job is still
     *         running, and the third and fourth elements correspond to the numerator and
     *         denominator of the fractional completion percentage, respectively
     */
    public function jobStatus($job_handle) {}

    /**
     * Adds a task to be run in parallel with other tasks. Call this method for all the
     * tasks to be run in parallel, then call GearmanClient::runTasks to perform the
     * work. Note that enough workers need to be available for the tasks to all run in
     * parallel.
     *
     * @link https://php.net/manual/en/gearmanclient.addtask.php
     * @param string $function_name
     * @param string $workload
     * @param mixed $context
     * @param string $unique
     * @return GearmanTask|false A GearmanTask object or false if the task could not be added
     */
    public function addTask($function_name, $workload, $context = null, $unique = null) {}

    /**
     * Adds a high priority task to be run in parallel with other tasks. Call this
     * method for all the high priority tasks to be run in parallel, then call
     * GearmanClient::runTasks to perform the work. Tasks with a high priority will be
     * selected from the queue before those of normal or low priority.
     *
     * @link https://php.net/manual/en/gearmanclient.addtaskhigh.php
     * @param string $function_name
     * @param string $workload
     * @param mixed $context
     * @param string $unique
     * @return GearmanTask|false A GearmanTask object or false if the task could not be added
     */
    public function addTaskHigh($function_name, $workload, $context = null, $unique = null) {}

    /**
     * Adds a low priority background task to be run in parallel with other tasks. Call
     * this method for all the tasks to be run in parallel, then call
     * GearmanClient::runTasks to perform the work. Tasks with a low priority will be
     * selected from the queue after those of normal or low priority.
     *
     * @link https://php.net/manual/en/gearmanclient.addtasklow.php
     * @param string $function_name
     * @param string $workload
     * @param mixed $context
     * @param string $unique
     * @return GearmanTask|false A GearmanTask object or false if the task could not be added
     */
    public function addTaskLow($function_name, $workload, $context = null, $unique = null) {}

    /**
     * Adds a background task to be run in parallel with other tasks. Call this method
     * for all the tasks to be run in parallel, then call GearmanClient::runTasks to
     * perform the work.
     *
     * @link https://php.net/manual/en/gearmanclient.addtaskbackground.php
     * @param string $function_name
     * @param string $workload
     * @param mixed $context
     * @param string $unique
     * @return GearmanTask|false A GearmanTask object or false if the task could not be added
     */
    public function addTaskBackground($function_name, $workload, $context = null, $unique = null) {}

    /**
     * Adds a high priority background task to be run in parallel with other tasks.
     * Call this method for all the tasks to be run in parallel, then call
     * GearmanClient::runTasks to perform the work. Tasks with a high priority will be
     * selected from the queue before those of normal or low priority.
     *
     * @link https://php.net/manual/en/gearmanclient.addtaskhighbackground.php
     * @param string $function_name
     * @param string $workload
     * @param mixed $context
     * @param string $unique
     * @return GearmanTask|false A GearmanTask object or false if the task could not be added
     */
    public function addTaskHighBackground($function_name, $workload, $context = null, $unique = null) {}

    /**
     * Adds a low priority background task to be run in parallel with other tasks. Call
     * this method for all the tasks to be run in parallel, then call
     * GearmanClient::runTasks to perform the work. Tasks with a low priority will be
     * selected from the queue after those of normal or high priority.
     *
     * @link https://php.net/manual/en/gearmanclient.addtasklowbackground.php
     * @param string $function_name
     * @param string $workload
     * @param mixed $context
     * @param string $unique
     * @return GearmanTask|false A GearmanTask object or false if the task could not be added
     */
    public function addTaskLowBackground($function_name, $workload, $context = null, $unique = null) {}

    /**
     * Used to request status information from the Gearman server, which will call the
     * specified status callback (set using GearmanClient::setStatusCallback).
     *
     * @link https://php.net/manual/en/gearmanclient.addtaskstatus.php
     * @param string $job_handle The job handle for the task to get status for
     * @param string $context Data to be passed to the status callback, generally a
     *        reference to an array or object
     * @return GearmanTask A GearmanTask object
     */
    public function addTaskStatus($job_handle, $context = null) {}

    /**
     * Sets a function to be called when a worker needs to send back data prior to job
     * completion. A worker can do this when it needs to send updates, send partial
     * results, or flush data during long running jobs. The callback should accept a
     * single argument, a GearmanTask object.
     *
     * @link https://php.net/manual/en/gearmanclient.setworkloadcallback.php
     * @param callable $callback A function to call
     * @return bool
     */
    public function setWorkloadCallback($callback) {}

    /**
     * Sets a function to be called when a task is received and queued by the Gearman
     * job server. The callback should accept a single argument, a GearmanClient oject.
     *
     * @link https://php.net/manual/en/gearmanclient.setcreatedcallback.php
     * @param string $callback A function to call
     * @return bool
     */
    public function setCreatedCallback($callback) {}

    /**
     * Sets the callback function for accepting data packets for a task. The callback
     * function should take a single argument, a GearmanTask object.
     *
     * @link https://php.net/manual/en/gearmanclient.setdatacallback.php
     * @param callable $callback A function or method to call
     * @return bool
     */
    public function setDataCallback($callback) {}

    /**
     * Sets a function to be called when a worker sends a warning. The callback should
     * accept a single argument, a GearmanTask object.
     *
     * @link https://php.net/manual/en/gearmanclient.setwarningcallback.php
     * @param callable $callback A function to call
     * @return bool
     */
    public function setWarningCallback($callback) {}

    /**
     * Sets a callback function used for getting updated status information from a
     * worker. The function should accept a single argument, a GearmanTask object.
     *
     * @link https://php.net/manual/en/gearmanclient.setstatuscallback.php
     * @param callable $callback A function to call
     * @return bool
     */
    public function setStatusCallback($callback) {}

    /**
     * Use to set a function to be called when a task is completed. The callback
     * function should accept a single argument, a GearmanTask oject.
     *
     * @link https://php.net/manual/en/gearmanclient.setcompletecallback.php
     * @param callable $callback A function to be called
     * @return bool
     */
    public function setCompleteCallback($callback) {}

    /**
     * Specifies a function to call when a worker for a task sends an exception.
     *
     * @link https://php.net/manual/en/gearmanclient.setexceptioncallback.php
     * @param callable $callback Function to call when the worker throws an exception
     * @return bool
     */
    public function setExceptionCallback($callback) {}

    /**
     * Sets the callback function to be used when a task does not complete
     * successfully. The function should accept a single argument, a GearmanTask object.
     *
     * @link https://php.net/manual/en/gearmanclient.setfailcallback.php
     * @param callable $callback A function to call
     * @return bool
     */
    public function setFailCallback($callback) {}

    /**
     * Clears all the task callback functions that have previously been set.
     *
     * @link https://php.net/manual/en/gearmanclient.clearcallbacks.php
     * @return bool Always returns true
     */
    public function clearCallbacks() {}

    /**
     * For a set of tasks previously added with GearmanClient::addTask,
     * GearmanClient::addTaskHigh, GearmanClient::addTaskLow,
     * GearmanClient::addTaskBackground, GearmanClient::addTaskHighBackground, or
     * GearmanClient::addTaskLowBackground, this call starts running the tasks in
     * parallel.
     *
     * @link https://php.net/manual/en/gearmanclient.runtasks.php
     * @return bool
     */
    public function runTasks() {}

    /**
     * Sends some arbitrary data to all job servers to see if they echo it back.
     * The data sent is not used or processed in any other way. Primarily used for testing and debugging.
     *
     * @link https://php.net/manual/en/gearmanclient.ping.php
     * @param string $workload
     * @return bool
     */
    public function ping($workload) {}
}

/**
 * Class: GearmanTask
 */
class GearmanTask
{
    /**
     * Returns the last Gearman return code for this task.
     *
     * @link https://php.net/manual/en/gearmantask.returncode.php
     * @return int A valid Gearman return code
     */
    public function returnCode() {}

    /**
     * Returns the name of the function this task is associated with, i.e., the
     * function the Gearman worker calls.
     *
     * @link https://php.net/manual/en/gearmantask.functionname.php
     * @return string A function name
     */
    public function functionName() {}

    /**
     * Returns the unique identifier for this task. This is assigned by the
     * GearmanClient, as opposed to the job handle which is set by the Gearman job
     * server.
     *
     * @link https://php.net/manual/en/gearmantask.unique.php
     * @return string|false The unique identifier, or false if no identifier is assigned
     */
    public function unique() {}

    /**
     * Returns the job handle for this task.
     *
     * @link https://php.net/manual/en/gearmantask.jobhandle.php
     * @return string The opaque job handle
     */
    public function jobHandle() {}

    /**
     * Gets the status information for whether or not this task is known to the job
     * server.
     *
     * @link https://php.net/manual/en/gearmantask.isknown.php
     * @return bool true if the task is known, false otherwise
     */
    public function isKnown() {}

    /**
     * Indicates whether or not this task is currently running.
     *
     * @link https://php.net/manual/en/gearmantask.isrunning.php
     * @return bool true if the task is running, false otherwise
     */
    public function isRunning() {}

    /**
     * Returns the numerator of the percentage of the task that is complete expressed
     * as a fraction.
     *
     * @link https://php.net/manual/en/gearmantask.tasknumerator.php
     * @return int|false A number between 0 and 100, or false if cannot be determined
     */
    public function taskNumerator() {}

    /**
     * Returns the denominator of the percentage of the task that is complete expressed
     * as a fraction.
     *
     * @link https://php.net/manual/en/gearmantask.taskdenominator.php
     * @return int|false A number between 0 and 100, or false if cannot be determined
     */
    public function taskDenominator() {}

    /**
     * .
     *
     * @link https://php.net/manual/en/gearmantask.sendworkload.php
     * @param string $data Data to send to the worker
     * @return int|false The length of data sent, or false if the send failed
     */
    public function sendWorkload($data) {}

    /**
     * Returns data being returned for a task by a worker.
     *
     * @link https://php.net/manual/en/gearmantask.data.php
     * @return string|false The serialized data, or false if no data is present
     */
    public function data() {}

    /**
     * Returns the size of the data being returned for a task.
     *
     * @link https://php.net/manual/en/gearmantask.datasize.php
     * @return int|false The data size, or false if there is no data
     */
    public function dataSize() {}

    /**
     * .
     *
     * @link https://php.net/manual/en/gearmantask.recvdata.php
     * @param int $data_len Length of data to be read
     * @return array|false An array whose first element is the length of data read and the second is
     *         the data buffer. Returns false if the read failed
     */
    public function recvData($data_len) {}
}

/**
 * Class: GearmanWorker
 */
class GearmanWorker
{
    /**
     * Creates a GearmanWorker instance representing a worker that connects to the job
     * server and accepts tasks to run.
     *
     * @link https://php.net/manual/en/gearmanworker.construct.php
     */
    public function __construct() {}

    /**
     * Returns the last Gearman return code.
     *
     * @link https://php.net/manual/en/gearmanworker.returncode.php
     * @return int A valid Gearman return code
     */
    public function returnCode() {}

    /**
     * Returns an error string for the last error encountered.
     *
     * @link https://php.net/manual/en/gearmanworker.error.php
     * @return string An error string
     */
    public function error() {}

    /**
     * Returns the value of errno in the case of a GEARMAN_ERRNO return value.
     *
     * @link https://php.net/manual/en/gearmanworker.geterrno.php
     * @return int A valid errno
     */
    public function getErrno() {}

    /**
     * Gets the options previously set for the worker.
     *
     * @link https://php.net/manual/en/gearmanworker.options.php
     * @return int The options currently set for the worker
     */
    public function options() {}

    /**
     * Sets one or more options to the supplied value.
     *
     * @link https://php.net/manual/en/gearmanworker.setoptions.php
     * @param int $option The options to be set
     * @return bool Always returns true
     */
    public function setOptions($option) {}

    /**
     * Adds one or more options to the options previously set.
     *
     * @link https://php.net/manual/en/gearmanworker.addoptions.php
     * @param int $option The options to be added
     * @return bool Always returns true
     */
    public function addOptions($option) {}

    /**
     * Removes (unsets) one or more worker options.
     *
     * @link https://php.net/manual/en/gearmanworker.removeoptions.php
     * @param int $option The options to be removed (unset)
     * @return bool Always returns true
     */
    public function removeOptions($option) {}

    /**
     * Returns the current time to wait, in milliseconds, for socket I/O activity.
     *
     * @link https://php.net/manual/en/gearmanworker.timeout.php
     * @return int A time period is milliseconds. A negative value indicates an infinite
     *         timeout
     */
    public function timeout() {}

    /**
     * Sets the interval of time to wait for socket I/O activity.
     *
     * @link https://php.net/manual/en/gearmanworker.settimeout.php
     * @param int $timeout An interval of time in milliseconds. A negative value
     *        indicates an infinite timeout
     * @return bool Always returns true
     */
    public function setTimeout($timeout) {}

    /**
     * Give the worker an identifier so it can be tracked when asking gearmand for
     * the list of available workers.
     *
     * @link https://php.net/manual/en/gearmanworker.setid.php
     * @param string $id A string identifier
     * @return bool Returns TRUE on success or FALSE on failure
     */
    public function setId($id) {}

    /**
     * Adds a job server to this worker. This goes into a list of servers than can be
     * used to run jobs. No socket I/O happens here.
     *
     * @link https://php.net/manual/en/gearmanworker.addserver.php
     * @param string $host
     * @param int $port
     * @return bool
     */
    public function addServer($host = '127.0.0.1', $port = 4730) {}

    /**
     * Adds one or more job servers to this worker. These go into a list of servers
     * that can be used to run jobs. No socket I/O happens here.
     *
     * @link https://php.net/manual/en/gearmanworker.addservers.php
     * @param string $servers A comma separated list of job servers in the format
     *        host:port. If no port is specified, it defaults to 4730
     * @return bool
     */
    public function addServers($servers = '127.0.0.1:4730') {}

    /**
     * Causes the worker to wait for activity from one of the Gearman job servers when
     * operating in non-blocking I/O mode. On failure, issues a E_WARNING with the last
     * Gearman error encountered.
     *
     * @link https://php.net/manual/en/gearmanworker.wait.php
     * @return bool
     */
    public function wait() {}

    /**
     * Registers a function name with the job server with an optional timeout. The
     * timeout specifies how many seconds the server will wait before marking a job as
     * failed. If the timeout is set to zero, there is no timeout.
     *
     * @link https://php.net/manual/en/gearmanworker.register.php
     * @param string $function_name The name of a function to register with the job
     *        server
     * @param int $timeout An interval of time in seconds
     * @return bool A standard Gearman return value
     */
    public function register($function_name, $timeout) {}

    /**
     * Unregisters a function name with the job servers ensuring that no more jobs (for
     * that function) are sent to this worker.
     *
     * @link https://php.net/manual/en/gearmanworker.unregister.php
     * @param string $function_name The name of a function to register with the job
     *        server
     * @return bool A standard Gearman return value
     */
    public function unregister($function_name) {}

    /**
     * Unregisters all previously registered functions, ensuring that no more jobs are
     * sent to this worker.
     *
     * @link https://php.net/manual/en/gearmanworker.unregisterall.php
     * @return bool A standard Gearman return value
     */
    public function unregisterAll() {}

    public function grabJob() {}

    /**
     * Registers a function name with the job server and specifies a callback
     * corresponding to that function. Optionally specify extra application context
     * data to be used when the callback is called and a timeout.
     *
     * @link https://php.net/manual/en/gearmanworker.addfunction.php
     * @param string $function_name The name of a function to register with the job
     *        server
     * @param callable $function A callback that gets called when a job for the
     *        registered function name is submitted
     * @param mixed $context A reference to arbitrary application context data that can
     *        be modified by the worker function
     * @param int $timeout An interval of time in seconds
     * @return bool
     */
    public function addFunction($function_name, $function, $context = null, $timeout = 0) {}

    /**
     * Waits for a job to be assigned and then calls the appropriate callback function.
     * Issues an E_WARNING with the last Gearman error if the return code is not one of
     * GEARMAN_SUCCESS, GEARMAN_IO_WAIT, or GEARMAN_WORK_FAIL.
     *
     * @link https://php.net/manual/en/gearmanworker.work.php
     * @return bool
     */
    public function work() {}
}

/**
 * Class: GearmanJob
 */
class GearmanJob
{
    /**
     * Returns the last return code issued by the job server.
     *
     * @link https://php.net/manual/en/gearmanjob.returncode.php
     * @return int A valid Gearman return code
     */
    public function returnCode() {}

    /**
     * Sets the return value for this job, indicates how the job completed.
     *
     * @link https://php.net/manual/en/gearmanjob.setreturn.php
     * @param int $gearman_return_t A valid Gearman return value
     * @return bool Description
     */
    public function setReturn($gearman_return_t) {}

    /**
     * Sends data to the job server (and any listening clients) for this job.
     *
     * @link https://php.net/manual/en/gearmanjob.senddata.php
     * @param string $data Arbitrary serialized data
     * @return bool
     */
    public function sendData($data) {}

    /**
     * Sends a warning for this job while it is running.
     *
     * @link https://php.net/manual/en/gearmanjob.sendwarning.php
     * @param string $warning A warning messages
     * @return bool
     */
    public function sendWarning($warning) {}

    /**
     * Sends status information to the job server and any listening clients. Use this
     * to specify what percentage of the job has been completed.
     *
     * @link https://php.net/manual/en/gearmanjob.sendstatus.php
     * @param int $numerator The numerator of the percentage completed expressed as a
     *        fraction
     * @param int $denominator The denominator of the percentage completed expressed as
     *        a fraction
     * @return bool
     */
    public function sendStatus($numerator, $denominator) {}

    /**
     * Sends result data and the complete status update for this job.
     *
     * @link https://php.net/manual/en/gearmanjob.sendcomplete.php
     * @param string $result Serialized result data
     * @return bool
     */
    public function sendComplete($result) {}

    /**
     * Sends the supplied exception when this job is running.
     *
     * @link https://php.net/manual/en/gearmanjob.sendexception.php
     * @param string $exception An exception description
     * @return bool
     */
    public function sendException($exception) {}

    /**
     * Sends failure status for this job, indicating that the job failed in a known way
     * (as opposed to failing due to a thrown exception).
     *
     * @link https://php.net/manual/en/gearmanjob.sendfail.php
     * @return bool
     */
    public function sendFail() {}

    /**
     * Returns the opaque job handle assigned by the job server.
     *
     * @link https://php.net/manual/en/gearmanjob.handle.php
     * @return string An opaque job handle
     */
    public function handle() {}

    /**
     * Returns the function name for this job. This is the function the work will
     * execute to perform the job.
     *
     * @link https://php.net/manual/en/gearmanjob.functionname.php
     * @return string The name of a function
     */
    public function functionName() {}

    /**
     * Returns the unique identifiter for this job. The identifier is assigned by the
     * client.
     *
     * @link https://php.net/manual/en/gearmanjob.unique.php
     * @return string An opaque unique identifier
     */
    public function unique() {}

    /**
     * Returns the workload for the job. This is serialized data that is to be
     * processed by the worker.
     *
     * @link https://php.net/manual/en/gearmanjob.workload.php
     * @return string Serialized data
     */
    public function workload() {}

    /**
     * Returns the size of the job's work load (the data the worker is to process) in
     * bytes.
     *
     * @link https://php.net/manual/en/gearmanjob.workloadsize.php
     * @return int The size in bytes
     */
    public function workloadSize() {}
}

/**
 * Class: GearmanException
 */
class GearmanException extends Exception {}
