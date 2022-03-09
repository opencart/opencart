<?php

namespace spec\Cardinity\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Validator\ConstraintViolationList;

class InvalidAttributeValueSpec extends ObjectBehavior
{
    private $violation = 'This value should not be blank.';

    function let(ConstraintViolationList $violations)
    {
        $violations->__toString()->willReturn($this->violation);

        $this->beConstructedWith(
            'Message',
            $violations
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('\RuntimeException');
    }

    function it_returns_message()
    {
        $this->getMessage()->shouldStartWith('Message');
    }

    function it_returns_violations(ConstraintViolationList $violations)
    {
        $this->getViolations()->shouldReturn($violations);
    }

    function it_should_have_message_containing_violations()
    {
        $string = 'Violations: ' . $this->violation;
        $this->getMessage()->shouldEndWith($string);
    }
}
