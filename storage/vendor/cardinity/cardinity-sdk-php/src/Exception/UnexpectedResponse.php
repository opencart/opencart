<?php

namespace Cardinity\Exception;

class UnexpectedResponse extends Request
{
    protected $code = 0;
    protected $message = '';
}
