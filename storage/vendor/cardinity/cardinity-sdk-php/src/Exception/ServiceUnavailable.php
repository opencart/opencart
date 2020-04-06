<?php

namespace Cardinity\Exception;

class ServiceUnavailable extends Request
{
    protected $code = 503;
    protected $message = 'Service Unavailable – We’re temporarily off-line for 
    maintenance. Please try again later.';
}
