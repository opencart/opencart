<?php

namespace spec\Cardinity\Method\VoidPayment;

use PhpSpec\ObjectBehavior;

class GetSpec extends ObjectBehavior
{
    private $paymentId = 'cb5e1c95-7685-4499-a2b1-ae0f28297b92';
    private $voidId = '25e6f869-6675-4488-bd47-ccd298f74b3f';

    function let()
    {
        $this->beConstructedWith($this->paymentId, $this->voidId);
    }

    function it_is_initializable()
    {
        $this->shouldImplement('Cardinity\Method\MethodInterface');
    }

    function it_has_payment_id()
    {
        $this->getPaymentId()->shouldReturn($this->paymentId);
    }

    function it_has_settlement_id()
    {
        $this->getvoidId()->shouldReturn($this->voidId);
    }

    function it_has_action()
    {
        $this->getAction()->shouldReturn(
            'payments/' . $this->paymentId . '/voids/' . $this->voidId
        );
    }

    function it_has_method()
    {
        $this->getMethod()->shouldReturn('GET');
    }

    function it_has_query()
    {
        $this->getAttributes()->shouldBeArray();
    }

    function it_creates_result_object()
    {
        $this->createResultObject()->shouldReturnAnInstanceOf('Cardinity\Method\VoidPayment\VoidPayment');
    }

    function it_has_validation_constraints()
    {
        $this->getValidationConstraints()->shouldReturnAnInstanceOf('Symfony\Component\Validator\Constraint');
    }
}
