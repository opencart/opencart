<?php

namespace parallel\Events;

/**
 * ### Events Input
 * ---------------------------------------------------------------------------------------------------------------------
 * An Input object is a container for data that the @see \parallel\Events object will write to @see \parallel\Channel
 * objects as they become available. Multiple event loops may share an Input container - parallel does not verify the
 * contents of the container when it is set as the input for a \parallel\Events object.
 *
 * Note: When a parallel\Events object performs a write, the target is removed from the input object as if
 * @see Input::remove() were called.
 */
final class Input
{
    /**
     * Shall set input for the given target
     *
     * @param string $target
     * @param mixed  $value
     *
     * @throws Input\Error\Existence if input for target already exists.
     * @throws Input\Error\IllegalValue if value is illegal (object, null).
     */
    public function add(string $target, $value): void {}

    /**
     * Shall remove input for the given target
     * @param string $target
     * @throws Input\Error\Existence if input for target does not exist.
     */
    public function remove(string $target): void {}

    /**
     * Shall remove input for all targets
     */
    public function clear(): void {}
}
