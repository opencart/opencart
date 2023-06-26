<?php

namespace spec\Cardinity\Method\Payment;

use PhpSpec\ObjectBehavior;

class GetAllSpec extends ObjectBehavior
{
    private $limit = 5;

    function let()
    {
        $this->beConstructedWith($this->limit);
    }

    function it_is_initializable()
    {
        $this->shouldImplement('Cardinity\Method\MethodResultCollectionInterface');
    }

    function it_has_limit()
    {
        $this->getLimit()->shouldReturn($this->limit);
    }

    function it_has_action()
    {
        $this->getAction()->shouldReturn('payments');
    }

    function it_has_method()
    {
        $this->getMethod()->shouldReturn('GET');
    }

    function it_has_body()
    {
        $this->getAttributes()->shouldBeArray();
    }

    function it_has_create_result()
    {
        $this->createResultObject()->shouldReturnAnInstanceOf('Cardinity\Method\Payment\Payment');
    }

    function it_has_validation_constraints()
    {
        $this->getValidationConstraints()->shouldReturnAnInstanceOf('Symfony\Component\Validator\Constraint');
    }
}
