<?php

namespace parallel;

/**
 * ### Unbuffered Channels
 * ---------------------------------------------------------------------------------------------------------------------
 * An unbuffered channel will block on calls to @see Channel::send() until there is a receiver, and block on calls
 * to @see Channel::recv() until there is a sender. This means an unbuffered channel is not only a way to share
 * data among tasks but also a simple method of synchronization.
 *
 *  An unbuffered channel is the fastest way to share data among tasks, requiring the least copying.
 *
 * ### Buffered Channels
 * ---------------------------------------------------------------------------------------------------------------------
 *  A buffered channel will not block on calls to @see Channel::send() until capacity is reached, calls to
 * @see Channel::recv() will block until there is data in the buffer.
 *
 * ### Closures over Channels
 * ---------------------------------------------------------------------------------------------------------------------
 * A powerful feature of parallel channels is that they allow the exchange of closures between tasks (and runtimes).
 *
 * When a closure is sent over a channel the closure is buffered, it doesn't change the buffering of the channel
 *     transmitting the closure, but it does effect the static scope inside the closure: The same closure sent to
 *     different runtimes, or the same runtime, will not share their static scope.
 *
 * This means that whenever a closure is executed that was transmitted by a channel, static state will be as it was
 *     when the closure was buffered.
 *
 * ### Anonymous Channels
 * ---------------------------------------------------------------------------------------------------------------------
 * The anonymous channel constructor allows the programmer to avoid assigning names to every channel: parallel will
 *     generate a unique name for anonymous channels.
 */
final class Channel
{
    /**
     * Constant for Infinitely Buffered
     */
    public const Infinite = -1;

    /* Anonymous Constructor */

    /**
     * Shall make an anonymous unbuffered channel
     * Shall make an anonymous buffered channel with the given capacity
     *
     * @param null|int $capacity May be Channel::Infinite or a positive integer
     */
    public function __construct(?int $capacity = null) {}

    /* Access */

    /**
     * Shall make an unbuffered channel with the given name
     * Shall make a buffered channel with the given name and capacity
     *
     * @param string $name     The name of the channel.
     * @param null|int $capacity May be Channel::Infinite or a positive integer
     *
     * @return Channel
     *
     * @throws Channel\Error\Existence if channel already exists.
     */
    public static function make(string $name, ?int $capacity = null): Channel {}

    /**
     * Shall open the channel with the given name
     *
     * @param string $name
     * @return Channel
     *
     * @throws Channel\Error\Existence if channel does not exist.
     */
    public static function open(string $name): Channel {}

    /* Sharing */

    /**
     * Shall send the given value on this channel
     * @param mixed $value
     *
     * @throws Channel\Error\Closed if channel is closed.
     * @throws Channel\Error\IllegalValue if value is illegal.
     */
    public function send($value): void {}

    /**
     * Shall recv a value from this channel
     * @return mixed
     *
     * @throws Channel\Error\Closed if channel is closed.
     */
    public function recv() {}

    /* Closing */

    /**
     * Shall close this channel
     * @throws Channel\Error\Closed if channel is closed.
     */
    public function close(): void {}

    /**
     * Returns name of channel
     * @return string
     */
    public function __toString(): string {}
}
