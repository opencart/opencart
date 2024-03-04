<?php

namespace parallel;

use Throwable;

/**
 * A Future represents the return value or uncaught exception from a task, and exposes an API for cancellation.
 *
 * The behaviour of a future also allows it to be used as a simple synchronization point even where the task does not
 * return a value explicitly.
 *
 * @see https://www.php.net/manual/en/class.parallel-future.php
 */
final class Future
{
    /* Resolution */

    /**
     * Shall return (and if necessary wait for) return from task
     *
     * @return mixed
     *
     * @throws Future\Error if waiting failed (internal error).
     * @throws Future\Error\Killed if \parallel\Runtime executing task was killed.
     * @throws Future\Error\Cancelled if task was cancelled.
     * @throws Future\Error\Foreign if task raised an unrecognized uncaught exception.
     * @throws Throwable Shall rethrow \Throwable uncaught in task
     */
    public function value() {}

    /* State */

    /**
     * Shall indicate if the task is completed
     * @return bool
     */
    public function done(): bool {}

    /**
     * Shall indicate if the task was cancelled
     * @return bool
     */
    public function cancelled(): bool {}

    /* Cancellation */

    /**
     * Shall try to cancel the task
     * Note: If task is running, it will be interrupted.
     * Warning: Internal function calls in progress cannot be interrupted.
     *
     * @return bool
     *
     * @throws Future\Error\Killed if \parallel\Runtime executing task was killed.
     * @throws Future\Error\Cancelled if task was already cancelled.
     */
    public function cancel(): bool {}
}
