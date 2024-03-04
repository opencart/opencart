<?php

namespace parallel\Events;

/**
 * When an Event is returned, @see Event::$object shall be removed from the loop that returned it, should the event be a
 * write event the Input for @see Event::$source shall also be removed.
 */
final class Event
{
    /**
     * Shall be one of Event\Type constants
     * @var int
     */
    public $type;

    /**
     * Shall be the source of the event (target name)
     * @var string
     */
    public $source;

    /**
     * Shall be either Future or Channel
     * @var object
     */
    public $object;

    /**
     * Shall be set for Read/Error events
     * @var mixed
     */
    public $value;
}
