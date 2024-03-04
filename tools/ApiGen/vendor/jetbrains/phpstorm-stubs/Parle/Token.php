<?php

namespace Parle;

class Token
{
    /* Constants */
    /** @var int End of input token id. */
    public const EOI = 0;

    /** @var int Unknown token id. */
    public const UNKNOWN = -1;

    /** @var int Skip token id. */
    public const SKIP = -2;

    /* Properties */
    /** @var int Token id. */
    public $id;

    /** @var string Token value. */
    public $value;
}
