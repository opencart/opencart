<?php

namespace Cardinity\Exception;

class Declined extends Request
{
    protected $code = 402;
    protected $message = 'Request Failed – Your request was valid but it was declined.';
}
