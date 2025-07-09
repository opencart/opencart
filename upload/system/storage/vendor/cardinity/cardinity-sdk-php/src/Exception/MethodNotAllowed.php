<?php

namespace Cardinity\Exception;

class MethodNotAllowed extends Request
{
    protected $code = 405;
    protected $message = 'Method Not Allowed – You tried to access a resource using an invalid HTTP method.';
}
