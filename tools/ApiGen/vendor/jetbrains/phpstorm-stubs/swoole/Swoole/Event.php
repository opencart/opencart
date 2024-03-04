<?php

declare(strict_types=1);

namespace Swoole;

class Event
{
    /**
     * @param mixed $fd
     * @param mixed|null $events
     * @return mixed
     */
    public static function add($fd, ?callable $read_callback, ?callable $write_callback = null, $events = null) {}

    /**
     * @param mixed $fd
     * @return mixed
     */
    public static function del($fd) {}

    /**
     * @param mixed $fd
     * @param mixed|null $events
     * @return mixed
     */
    public static function set($fd, ?callable $read_callback = null, ?callable $write_callback = null, $events = null) {}

    /**
     * @param mixed $fd
     * @param mixed|null $events
     * @return mixed
     */
    public static function isset($fd, $events = null) {}

    /**
     * @return mixed
     */
    public static function dispatch() {}

    /**
     * @return true
     */
    public static function defer(callable $callback) {}

    /**
     * @param mixed|null $before
     * @return mixed
     */
    public static function cycle(?callable $callback, $before = null) {}

    /**
     * @param mixed $fd
     * @param mixed $data
     * @return mixed
     */
    public static function write($fd, $data) {}

    /**
     * @return mixed
     */
    public static function wait() {}

    /**
     * @return mixed
     */
    public static function rshutdown() {}

    /**
     * @return mixed
     */
    public static function exit() {}
}
