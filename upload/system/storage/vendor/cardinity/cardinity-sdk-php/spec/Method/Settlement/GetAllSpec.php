<?php

namespace spec\Cardinity\Method\Settlement;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GetAllSpec extends ObjectBehavior
{
    private $paymentId = 'cb5e1c95-7685-4499-a2b1-ae0f28297b92';

    function let()
    {
        $this->beConstructedWith($this->paymentId);
    }

    function it_is_initializable()
    {
        $this->shouldImplement('Cardinity\Method\MethodInterface');
    }

    function it_has_payment_id()
    {
        $this->getPaymentId()->shouldReturn($this->paymentId);
    }

    function it_has_action()
    {
        $this->getAction()->shouldReturn(
            'payments/' . $this->paymentId . '/settlements'
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

    function it_has_create_result()
    {
        $this->createResultObject()
            ->shouldReturnAnInstanceOf('Cardinity\Method\Settlement\Settlement')
        ;
    }

    function it_has_validation_constraints()
    {
        $this
            ->getValidationConstraints()
            ->shouldReturnAnInstanceOf('Symfony\Component\Validator\Constraint')
        ;
    }
}
