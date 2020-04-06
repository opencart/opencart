<?php

namespace Cardinity\Exception;

class Forbidden extends Request
{
    protected $code = 403;
    protected $message = 'Forbidden – You do not have access to this resource.';
}
