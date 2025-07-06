<?php

namespace spec\Cardinity\Method\Payment;

use PhpSpec\ObjectBehavior;

class FinalizeSpec extends ObjectBehavior
{
    private $paymentId = 'cb5e1c95-7685-4499-a2b1-ae0f28297b92';
    private $authorizeData = 'fGxdUl3451vdas.......';

    function let()
    {
        $this->beConstructedWith($this->paymentId, $this->authorizeData);
    }

    function it_is_initializable()
    {
        $this->shouldImplement('Cardinity\Method\MethodInterface');
    }

    function it_has_payment_id()
    {
        $this->getPaymentId()->shouldReturn($this->paymentId);
    }

    function it_has_authorize_data()
    {
        $this->getAuthorizeData()->shouldReturn($this->authorizeData);
    }

    function it_has_action()
    {
        $this->getAction()->shouldReturn('payments/' . $this->paymentId);
    }

    function it_has_method()
    {
        $this->getMethod()->shouldReturn('PATCH');
    }

    function it_has_body()
    {
        $this->getAttributes()->shouldBeArray();
    }

    function it_creates_result_object()
    {
        $this->createResultObject()->shouldReturnAnInstanceOf('Cardinity\Method\Payment\Payment');
    }

    function it_has_validation_constraints()
    {
        $this->getValidationConstraints()->shouldReturnAnInstanceOf('Symfony\Component\Validator\Constraint');
    }
}
