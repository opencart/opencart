<?php

namespace Cardinity\Exception;

class NotAcceptable extends Request
{
    protected $code = 406;
    protected $message = 'Not Acceptable – Wrong Accept headers sent in the request.';
}
