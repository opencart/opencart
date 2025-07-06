<?php

namespace spec\Cardinity\Method\Settlement;

use PhpSpec\ObjectBehavior;

class CreateSpec extends ObjectBehavior
{
    private $paymentId = 'cb5e1c95-7685-4499-a2b1-ae0f28297b92';
    private $description = 'description';
    private $amount = '10.00';

    function let()
    {
        $this->beConstructedWith($this->paymentId, $this->amount, $this->description);
    }

    function it_is_initializable()
    {
        $this->shouldImplement('Cardinity\Method\MethodInterface');
    }

    function it_contains_loaded_options()
    {
        $this->getAttributes()->shouldReturn([
            'amount' => $this->amount,
            'description' => $this->description,
        ]);
    }

    function it_does_not_contain_optional_properties()
    {
        $this->beConstructedWith($this->paymentId, $this->amount);

        $this->getAttributes()->shouldReturn([
            'amount' => $this->amount,
        ]);
    }

    function it_has_action()
    {
        $this->getAction()->shouldReturn(
            sprintf('payments/%s/settlements', $this->paymentId)
        );
    }

    function it_has_method()
    {
        $this->getMethod()->shouldReturn('POST');
    }

    function it_has_body()
    {
        $this->getAttributes()->shouldBeArray();
    }

    function it_creates_result_object()
    {
        $this->createResultObject()->shouldReturnAnInstanceOf('Cardinity\Method\Settlement\Settlement');
    }

    function it_has_validation_constraints()
    {
        $this->getValidationConstraints()->shouldReturnAnInstanceOf('Symfony\Component\Validator\Constraint');
    }
}
