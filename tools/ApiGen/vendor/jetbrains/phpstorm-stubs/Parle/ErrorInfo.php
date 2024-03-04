<?php

namespace Parle;

class ErrorInfo
{
    /* Properties */
    /** @var int Error id. */
    public $id;

    /** @var int Position in the input, where the error occurred. */
    public $position;

    /** @var Token|null If applicable - the Parle\Token related to the error, otherwise NULL. */
    public $token;
}
