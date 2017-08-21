<?php

namespace Cardinity\Exception;

class InternalServerError extends Request
{
    protected $code = 500;
    protected $message = 'Internal Server Error – We had a problem on our end. Try again later.';
}
