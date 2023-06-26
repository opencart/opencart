<?php

namespace spec\Cardinity\Method\Payment;

use PhpSpec\ObjectBehavior;

class CreateSpec extends ObjectBehavior
{
    private $options;

    function let()
    {
        $this->options = [
            'amount' => 12.99,
            'currency' => 'EUR',
            'settle' => true,
            'order_id' => 'ABC123',
            'description' => 'Description',
            'country' => 'LT',
            'payment_method' => 'card',
            'payment_instrument' => [
                'pan' => '123456789123',
                'exp_year' => '2014',
                'exp_month' => '12',
                'cvc' => '456',
                'holder' => 'Mr Tester',
            ],
        ];
        $this->beConstructedWith($this->options);
    }

    function it_is_initializable()
    {
        $this->shouldImplement('Cardinity\Method\MethodInterface');
    }

    function it_contains_loaded_options()
    {
        $this->getAttributes()->shouldReturn($this->options);
    }

    function it_has_action()
    {
        $this->getAction()->shouldReturn('payments');
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
        $this->createResultObject()->shouldReturnAnInstanceOf('Cardinity\Method\Payment\Payment');
    }

    function it_has_validation_constraints()
    {
        $this->getValidationConstraints()->shouldReturnAnInstanceOf('Symfony\Component\Validator\Constraint');
    }
}
