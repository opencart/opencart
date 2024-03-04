<?php

declare(strict_types=1);

namespace Swoole;

class Timer
{
    /**
     * @return void
     */
    public static function set(array $settings) {}

    /**
     * @return int
     */
    public static function tick(int $ms, callable $callback, ...$params) {}

    /**
     * @return int
     */
    public static function after(int $ms, callable $callback, ...$params) {}

    /**
     * @return bool
     */
    public static function exists(int $timer_id) {}

    /**
     * @return array
     */
    public static function info(int $timer_id) {}

    /**
     * @return array
     */
    public static function stats() {}

    /**
     * @return \Swoole\timer\Iterator
     */
    public static function list() {}

    /**
     * @return bool
     */
    public static function clear(int $timer_id) {}

    /**
     * @return bool
     */
    public static function clearAll() {}
}
