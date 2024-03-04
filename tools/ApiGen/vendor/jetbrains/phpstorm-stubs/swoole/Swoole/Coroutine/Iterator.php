<?php

declare(strict_types=1);

namespace Swoole\Coroutine;

class Iterator extends \ArrayIterator
{
    public const STD_PROP_LIST = 1;
    public const ARRAY_AS_PROPS = 2;
}
