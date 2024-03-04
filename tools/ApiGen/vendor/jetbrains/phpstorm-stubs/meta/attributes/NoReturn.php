<?php

namespace JetBrains\PhpStorm;

use Attribute;

/**
 * You can use this facility to mark the function as halting the execution flow.
 * Such marked functions will be treated like die() or exit() calls by control flow inspections.
 * In most cases, just annotation function with 0 arguments will work.
 * To mark the function as the exit point only when it's called with some constant arguments, specify them in $arguments param
 *
 * {@see NoReturn::ANY_ARGUMENT}
 */
#[Attribute(Attribute::TARGET_FUNCTION|Attribute::TARGET_METHOD)]
class NoReturn
{
    /**
     * Use this constant to skip function argument on the specified position
     */
    public const ANY_ARGUMENT = 1;

    public function __construct(...$arguments) {}
}
