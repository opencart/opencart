<?php

namespace Cardinity\Exception;

class NotFound extends Request
{
    protected $code = 404;
    protected $message = 'Not Found – The specified resource could not be found.';
}
