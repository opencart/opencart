<?php

namespace spec\Cardinity\Method\VoidPayment;

use PhpSpec\ObjectBehavior;

class VoidPaymentSpec extends ObjectBehavior
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
