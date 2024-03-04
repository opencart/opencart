<?php

declare(strict_types=1);

namespace Swoole;

class Coroutine
{
    /**
     * @return mixed
     */
    public static function create(callable $func, ...$params) {}

    /**
     * @param mixed $callback
     * @return mixed
     */
    public static function defer($callback) {}

    /**
     * To set runtime configurations of coroutines.
     *
     * @return void
     */
    public static function set(array $options) {}

    /**
     * To get runtime configurations of coroutines.
     *
     * @return array|null
     */
    public static function getOptions() {}

    /**
     * @param mixed $cid
     * @return mixed
     */
    public static function exists($cid) {}

    /**
     * @return mixed
     */
    public static function yield() {}

    /**
     * @param mixed $cid
     * @return mixed
     */
    public static function cancel($cid) {}

    /**
     * Waits for a list of coroutines to finish.
     *
     * This method is similar to class \Swoole\Coroutine\WaitGroup and \Swoole\Coroutine\Barrier. They are different
     * implementations of the same functionality.
     *
     * @param array $cid_array An array of coroutines.
     * @param int $timeout
     * @return bool TRUE if succeeds; otherwise FALSE.
     * @see \Swoole\Coroutine\WaitGroup
     * @see \Swoole\Coroutine\Barrier
     * @since 4.8.0
     */
    public static function join($cid_array, $timeout = -1) {}

    /**
     * @return mixed
     */
    public static function isCanceled() {}

    /**
     * @return mixed
     */
    public static function suspend() {}

    /**
     * @param mixed $cid
     * @return mixed
     */
    public static function resume($cid) {}

    /**
     * @return mixed
     */
    public static function stats() {}

    /**
     * @return mixed
     */
    public static function getCid() {}

    /**
     * @return mixed
     */
    public static function getuid() {}

    /**
     * @param mixed|null $cid
     * @return mixed
     */
    public static function getPcid($cid = null) {}

    /**
     * @param mixed|null $cid
     * @return mixed
     */
    public static function getContext($cid = null) {}

    /**
     * @param mixed|null $cid
     * @param mixed|null $options
     * @param mixed|null $limit
     * @return mixed
     */
    public static function getBackTrace($cid = null, $options = null, $limit = null) {}

    /**
     * @param mixed|null $cid
     * @param mixed|null $options
     * @param mixed|null $limit
     * @return mixed
     */
    public static function printBackTrace($cid = null, $options = null, $limit = null) {}

    /**
     * @param mixed|null $cid
     * @return mixed
     */
    public static function getElapsed($cid = null) {}

    /**
     * Get memory usage of a coroutine.
     *
     * @param int $cid If this parameter is not passed in, current coroutine ID will be used.
     * @return int|false Memory usage of the coroutine; FALSE if the specified coroutine doesn't exist.
     * @since 4.8.0
     */
    public static function getStackUsage(int $cid = null) {}

    /**
     * @return mixed
     */
    public static function list() {}

    /**
     * @return mixed
     */
    public static function listCoroutines() {}

    /**
     * @return mixed
     */
    public static function enableScheduler() {}

    /**
     * @return mixed
     */
    public static function disableScheduler() {}

    /**
     * @param mixed $domain_name
     * @param mixed|null $family
     * @param mixed|null $timeout
     * @return mixed
     */
    public static function gethostbyname($domain_name, $family = null, $timeout = null) {}

    /**
     * @param mixed $domain_name
     * @param mixed|null $timeout
     * @param mixed|null $type
     * @return mixed
     */
    public static function dnsLookup($domain_name, $timeout = null, $type = null) {}

    /**
     * @param mixed $command
     * @param mixed|null $get_error_stream
     * @return mixed
     */
    public static function exec($command, $get_error_stream = null) {}

    /**
     * @param mixed $seconds
     * @return mixed
     */
    public static function sleep($seconds) {}

    /**
     * @param mixed $hostname
     * @param mixed|null $family
     * @param mixed|null $socktype
     * @param mixed|null $protocol
     * @param mixed|null $service
     * @param mixed|null $timeout
     * @return mixed
     */
    public static function getaddrinfo($hostname, $family = null, $socktype = null, $protocol = null, $service = null, $timeout = null) {}

    /**
     * @param mixed $path
     * @return mixed
     */
    public static function statvfs($path) {}

    /**
     * @param mixed $filename
     * @return mixed
     */
    public static function readFile($filename) {}

    /**
     * @param mixed $filename
     * @param mixed $data
     * @param mixed|null $flags
     * @return mixed
     */
    public static function writeFile($filename, $data, $flags = null) {}

    /**
     * @param mixed|null $timeout
     * @return mixed
     */
    public static function wait($timeout = null) {}

    /**
     * @param mixed $pid
     * @param mixed|null $timeout
     * @return mixed
     */
    public static function waitPid($pid, $timeout = null) {}

    /**
     * @param mixed $signo
     * @param mixed|null $timeout
     * @return mixed
     */
    public static function waitSignal($signo, $timeout = null) {}

    /**
     * @param mixed $fd
     * @param mixed|null $events
     * @param mixed|null $timeout
     * @return mixed
     */
    public static function waitEvent($fd, $events = null, $timeout = null) {}

    /**
     * @param mixed $handle
     * @param mixed|null $length
     * @return mixed
     */
    public static function fread($handle, $length = null) {}

    /**
     * @param mixed $handle
     * @return mixed
     */
    public static function fgets($handle) {}

    /**
     * @param mixed $handle
     * @param mixed $string
     * @param mixed|null $length
     * @return mixed
     */
    public static function fwrite($handle, $string, $length = null) {}
}
