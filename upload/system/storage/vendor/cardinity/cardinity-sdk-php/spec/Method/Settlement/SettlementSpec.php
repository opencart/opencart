<?php

namespace spec\Cardinity\Method\Settlement;

use PhpSpec\ObjectBehavior;

class SettlementSpec extends ObjectBehavior
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
