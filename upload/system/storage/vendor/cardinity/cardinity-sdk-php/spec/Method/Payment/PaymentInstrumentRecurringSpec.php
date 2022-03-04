<?php

namespace spec\Cardinity\Method\Payment;

use PhpSpec\ObjectBehavior;

class PaymentInstrumentRecurringSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldImplement('Cardinity\Method\ResultObjectInterface');
    }
}
