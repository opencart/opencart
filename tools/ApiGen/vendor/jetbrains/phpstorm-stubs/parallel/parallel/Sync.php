<?php

namespace parallel;

/**
 * The Sync class provides access to low level synchronization primitives, mutex, condition variables, and allows the
 * implementation of semaphores.
 *
 * Synchronization for most applications is much better implemented using channels, however, in some cases authors of
 * low level code may find it useful to be able to access these lower level mechanisms.
 */
final class Sync
{
    /* Constructor */

    /**
     * Shall construct a new synchronization object with no value
     * Shall construct a new synchronization object containing the given scalar value
     *
     * @param string|int|float|bool $value
     *
     * @throws Sync\Error\IllegalValue if value is non-scalar.
     */
    public function __construct($value = null) {}

    /* Access */

    /**
     * Shall atomically return the synchronization objects value
     * @return string|int|float|bool
     */
    public function get() {}

    /**
     * Shall atomically set the value of the synchronization object
     * @param string|int|float|bool $value
     *
     * @throws Sync\Error\IllegalValue if value is non-scalar.
     */
    public function set($value) {}

    /* Synchronization */

    /**
     * Shall wait for notification on this synchronization object
     * @return bool
     */
    public function wait(): bool {}

    /**
     * Shall notify one (by default) or all threads waiting on the synchronization object
     * @param bool $all
     *
     * @return bool
     */
    public function notify(bool $all = null): bool {}

    /**
     * Shall exclusively enter into the critical code
     * @param callable $block
     */
    public function __invoke(callable $block) {}
}
