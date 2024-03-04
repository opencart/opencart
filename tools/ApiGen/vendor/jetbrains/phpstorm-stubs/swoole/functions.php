<?php

declare(strict_types=1);

/**
 * Gets the current Swoole version. This information is also available in the predefined constant SWOOLE_VERSION.
 *
 * @return string returns a string containing the version of Swoole
 */
function swoole_version() {}

/**
 * Gets the number of CPU cores.
 *
 * @return int returns the number of CPU cores
 */
function swoole_cpu_num() {}

/**
 * @return mixed
 */
function swoole_last_error() {}

/**
 * @param $domain_name[required]
 * @param $timeout[optional]
 * @param $type[optional]
 * @return mixed
 */
function swoole_async_dns_lookup_coro($domain_name, $timeout = null, $type = null) {}

/**
 * @param $settings[required]
 * @return mixed
 */
function swoole_async_set($settings) {}

/**
 * @return int|false
 */
function swoole_coroutine_create(callable $func, ...$params) {}

/**
 * Defers the execution of a callback function until the surrounding function of a coroutine returns.
 *
 * @return void
 * @example
 * <pre>
 * swoole_coroutine_create(function () {  // The surrounding function of a coroutine.
 *   echo '1';
 *   swoole_coroutine_defer(function () { // The callback function to be deferred.
 *     echo '3';
 *   });
 *   echo '2';
 * });
 * <pre>
 */
function swoole_coroutine_defer(callable $callback) {}

/**
 * @param $domain[required]
 * @param $type[required]
 * @param $protocol[required]
 * @return mixed
 */
function swoole_coroutine_socketpair($domain, $type, $protocol) {}

/**
 * @param $count[optional]
 * @param $sleep_time[optional]
 * @return mixed
 */
function swoole_test_kernel_coroutine($count = null, $sleep_time = null) {}

/**
 * @param $read_array[required]
 * @param $write_array[required]
 * @param $error_array[required]
 * @param $timeout[optional]
 * @return mixed
 */
function swoole_client_select(&$read_array, &$write_array, &$error_array, $timeout = null) {}

/**
 * @param $read_array[required]
 * @param $write_array[required]
 * @param $error_array[required]
 * @param $timeout[optional]
 * @return mixed
 */
function swoole_select(&$read_array, &$write_array, &$error_array, $timeout = null) {}

/**
 * @param $process_name[required]
 * @return mixed
 */
function swoole_set_process_name($process_name) {}

/**
 * @return mixed
 */
function swoole_get_local_ip() {}

/**
 * @return mixed
 */
function swoole_get_local_mac() {}

/**
 * @param $errno[required]
 * @param $error_type[optional]
 * @return mixed
 */
function swoole_strerror($errno, $error_type = null) {}

/**
 * @return mixed
 */
function swoole_errno() {}

/**
 * @return mixed
 */
function swoole_clear_error() {}

/**
 * @return void
 */
function swoole_error_log(int $level, string $msg) {}

/**
 * @return void
 * @since 4.8.1
 */
function swoole_error_log_ex(int $level, int $error, string $msg) {}

/**
 * @return void
 * @since 4.8.1
 */
function swoole_ignore_error(int $error) {}

/**
 * @param $data[required]
 * @param $type[optional]
 * @return mixed
 */
function swoole_hashcode($data, $type = null) {}

/**
 * @param $suffix[required]
 * @param $mime_type[required]
 * @return mixed
 */
function swoole_mime_type_add($suffix, $mime_type) {}

/**
 * @param $suffix[required]
 * @param $mime_type[required]
 * @return mixed
 */
function swoole_mime_type_set($suffix, $mime_type) {}

/**
 * @param $suffix[required]
 * @return mixed
 */
function swoole_mime_type_delete($suffix) {}

/**
 * @param $filename[required]
 * @return mixed
 */
function swoole_mime_type_get($filename) {}

/**
 * @param $filename[required]
 * @return mixed
 */
function swoole_get_mime_type($filename) {}

/**
 * @param $filename[required]
 * @return mixed
 */
function swoole_mime_type_exists($filename) {}

/**
 * @return mixed
 */
function swoole_mime_type_list() {}

/**
 * @return mixed
 */
function swoole_clear_dns_cache() {}

/**
 * @param $str[required]
 * @param $offset[required]
 * @param $length[optional]
 * @param $options[optional]
 * @return mixed
 */
function swoole_substr_unserialize($str, $offset, $length = null, $options = null) {}

/**
 * @param $json[required]
 * @param $offset[required]
 * @param $length[optional]
 * @param $associative[optional]
 * @param $depth[optional]
 * @param $flags[optional]
 * @return mixed
 */
function swoole_substr_json_decode($json, $offset, $length = null, $associative = null, $depth = null, $flags = null) {}

/**
 * @return mixed
 */
function swoole_internal_call_user_shutdown_begin() {}

/**
 * Get all PHP objects of current call stack.
 *
 * @return array|false Return an array of objects back; return FALSE when no objects exist or when error happens.
 * @since 4.8.1
 */
function swoole_get_objects() {}

/**
 * Get status information of current call stack.
 *
 * @return array The array contains two fields: "object_num" (# of objects) and "resource_num" (# of resources).
 * @since 4.8.1
 */
function swoole_get_vm_status() {}

/**
 * @return array|false Return the specified object back; return FALSE when no object found or when error happens.
 * @since 4.8.1
 */
function swoole_get_object_by_handle(int $handle) {}

/**
 * This function is an alias of function swoole_coroutine_create(); it's available only when directive
 * "swoole.use_shortname" is not explicitly turned off.
 *
 * @return int|false
 * @see swoole_coroutine_create()
 */
function go(callable $func, ...$params) {}

/**
 * Defers the execution of a callback function until the surrounding function of a coroutine returns.
 *
 * This function is an alias of function swoole_coroutine_defer(); it's available only when directive
 * "swoole.use_shortname" is not explicitly turned off.
 *
 * @return void
 * @see swoole_coroutine_defer()
 *
 * @example
 * <pre>
 * go(function () {      // The surrounding function of a coroutine.
 *   echo '1';
 *   defer(function () { // The callback function to be deferred.
 *     echo '3';
 *   });
 *   echo '2';
 * });
 * <pre>
 */
function defer(callable $callback) {}

/**
 * @param $fd[required]
 * @param $read_callback[required]
 * @param $write_callback[optional]
 * @param $events[optional]
 * @return mixed
 */
function swoole_event_add($fd, $read_callback, $write_callback = null, $events = null) {}

/**
 * @param $fd[required]
 * @return mixed
 */
function swoole_event_del($fd) {}

/**
 * @param $fd[required]
 * @param $read_callback[optional]
 * @param $write_callback[optional]
 * @param $events[optional]
 * @return mixed
 */
function swoole_event_set($fd, $read_callback = null, $write_callback = null, $events = null) {}

/**
 * @param $fd[required]
 * @param $events[optional]
 * @return mixed
 */
function swoole_event_isset($fd, $events = null) {}

/**
 * @return mixed
 */
function swoole_event_dispatch() {}

/**
 * This function is an alias of method \Swoole\Event::defer().
 *
 * @return true
 * @see \Swoole\Event::defer()
 */
function swoole_event_defer(callable $callback) {}

/**
 * @param $callback[required]
 * @param $before[optional]
 * @return mixed
 */
function swoole_event_cycle($callback, $before = null) {}

/**
 * @param $fd[required]
 * @param $data[required]
 * @return mixed
 */
function swoole_event_write($fd, $data) {}

/**
 * @return mixed
 */
function swoole_event_wait() {}

/**
 * @return mixed
 */
function swoole_event_exit() {}

/**
 * This function is an alias of method \Swoole\Timer::set().
 *
 * @return void
 * @see \Swoole\Timer::set()
 */
function swoole_timer_set(array $settings) {}

/**
 * This function is an alias of method \Swoole\Timer::after().
 *
 * @return int
 * @see \Swoole\Timer::after()
 */
function swoole_timer_after(int $ms, callable $callback, ...$params) {}

/**
 * This function is an alias of method \Swoole\Timer::tick().
 *
 * @return int
 * @see \Swoole\Timer::tick()
 */
function swoole_timer_tick(int $ms, callable $callback, ...$params) {}

/**
 * This function is an alias of method \Swoole\Timer::exists().
 *
 * @return bool
 * @see \Swoole\Timer::exists()
 */
function swoole_timer_exists(int $timer_id) {}

/**
 * This function is an alias of method \Swoole\Timer::info().
 *
 * @return array
 * @see \Swoole\Timer::info()
 */
function swoole_timer_info(int $timer_id) {}

/**
 * This function is an alias of method \Swoole\Timer::stats().
 *
 * @return array
 * @see \Swoole\Timer::stats()
 */
function swoole_timer_stats() {}

/**
 * This function is an alias of method \Swoole\Timer::list().
 *
 * @return \Swoole\timer\Iterator
 * @see \Swoole\Timer::list()
 */
function swoole_timer_list() {}

/**
 * This function is an alias of method \Swoole\Timer::clear().
 *
 * @return bool
 * @see \Swoole\Timer::clear()
 */
function swoole_timer_clear(int $timer_id) {}

/**
 * This function is an alias of method \Swoole\Timer::clearAll().
 *
 * @return bool
 * @see \Swoole\Timer::clearAll()
 */
function swoole_timer_clear_all() {}
