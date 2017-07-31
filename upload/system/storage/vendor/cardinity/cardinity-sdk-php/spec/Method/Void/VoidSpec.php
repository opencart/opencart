<?php

namespace spec\Cardinity\Method\Void;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VoidSpec extends ObjectBehavior
{
    function it_implements_result_object_behaviour()
    {
        $this->shouldImplement('Cardinity\Method\ResultObjectInterface');
    }

    function it_is_serializable()
    {
        $this->shouldImplement('\Serializable');
    }
}
