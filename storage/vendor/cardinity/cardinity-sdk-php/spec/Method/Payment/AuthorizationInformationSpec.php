<?php

namespace spec\Cardinity\Method\Payment;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AuthorizationInformationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldImplement('Cardinity\Method\ResultObjectInterface');
    }
}
