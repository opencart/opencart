<?php

declare(strict_types=1);

namespace Swoole\Coroutine;

class Scheduler
{
    /**
     * Add a task (implemented in a callback).
     *
     * @return false|void Returns FALSE if the scheduler has already been started; otherwise nothing returns.
     * @see \Swoole\Coroutine\Scheduler::start()
     */
    public function add(callable $func, ...$params) {}

    /**
     * Add a list of tasks (implemented in callbacks).
     *
     * @return false|void Returns FALSE if the scheduler has already been started; otherwise nothing returns.
     * @see \Swoole\Coroutine\Scheduler::start()
     */
    public function parallel(int $n, callable $func, ...$params) {}

    /**
     * To set runtime configurations of coroutines.
     *
     * This method is an alias of method \Swoole\Coroutine::set().
     *
     * @return void
     * @see \Swoole\Coroutine::set()
     */
    public function set(array $settings) {}

    /**
     * To get runtime configurations of coroutines.
     *
     * This method is an alias of method \Swoole\Coroutine::getOptions().
     *
     * @return array|null
     * @see \Swoole\Coroutine::getOptions()
     * @since Swoole 4.6.0
     */
    public function getOptions() {}

    /**
     * Start running the list of tasks (callbacks) added through method add() and parallel().
     *
     * @return bool
     * @see \Swoole\Coroutine\Scheduler::add()
     * @see \Swoole\Coroutine\Scheduler::parallel()
     */
    public function start() {}
}
