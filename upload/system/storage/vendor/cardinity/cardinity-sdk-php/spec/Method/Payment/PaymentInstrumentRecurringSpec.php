<?php

namespace spec\Cardinity\Method\Payment;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentInstrumentRecurringSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldImplement('Cardinity\Method\ResultObjectInterface');
    }
}
