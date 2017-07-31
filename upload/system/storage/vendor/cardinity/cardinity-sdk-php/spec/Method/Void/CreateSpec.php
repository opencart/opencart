<?php

namespace spec\Cardinity\Method\Void;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CreateSpec extends ObjectBehavior
{
    private $paymentId = 'cb5e1c95-7685-4499-a2b1-ae0f28297b92';
    private $description = 'description';

    function let()
    {
        $this->beConstructedWith($this->paymentId, $this->description);
    }

    function it_is_initializable()
    {
        $this->shouldImplement('Cardinity\Method\MethodInterface');
    }

    function it_contains_loaded_options()
    {
        $this->getAttributes()->shouldReturn([
            'description' => $this->description,
        ]);
    }

    function it_does_not_contain_optional_properties()
    {
        $this->beConstructedWith($this->paymentId);

        $this->getAttributes()->shouldReturn([]);
    }

    function it_has_action()
    {
        $this->getAction()->shouldReturn(
            sprintf('payments/%s/voids', $this->paymentId)
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
        $this->createResultObject()
            ->shouldReturnAnInstanceOf('Cardinity\Method\Void\Void')
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
