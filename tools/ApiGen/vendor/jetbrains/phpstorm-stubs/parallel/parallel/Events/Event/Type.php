<?php

namespace parallel\Events\Event;

final class Type
{
    /** Event::$object was read into Event::$value */
    public const Read = 1;

    /** Input for Event::$source written to Event::$object */
    public const Write = 2;

    /** Event::$object (Channel) was closed */
    public const Close = 3;

    /** Event::$object (Future) was cancelled */
    public const Cancel = 5;

    /** Runtime executing Event::$object (Future) was killed */
    public const Kill = 6;

    /** Event::$object (Future) raised error */
    public const Error = 4;
}
