<?php

namespace spec\Cardinity\Exception;

use PhpSpec\ObjectBehavior;

class ForbiddenSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Cardinity\Exception\Forbidden');
    }

    function it_should_return_correct_code()
    {
        $this->getCode()->shouldReturn(403);
    }
}
