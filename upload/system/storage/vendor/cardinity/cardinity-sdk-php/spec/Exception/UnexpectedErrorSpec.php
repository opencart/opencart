<?php

namespace spec\Cardinity\Exception;

use PhpSpec\ObjectBehavior;

class UnexpectedErrorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\RuntimeException');
    }
}
