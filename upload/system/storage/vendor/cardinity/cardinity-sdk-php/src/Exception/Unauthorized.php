<?php

namespace Cardinity\Exception;

class Unauthorized extends Request
{
    protected $code = 401;
    protected $message = 'Unauthorized – Your authorization information was missing or wrong.';
}
