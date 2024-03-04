<?php

declare(strict_types=1);

namespace Swoole\Coroutine;

class Context extends \ArrayObject
{
    public const STD_PROP_LIST = 1;
    public const ARRAY_AS_PROPS = 2;
}
