<?php

namespace spec\Cardinity\Method;

use Cardinity\Method\MethodInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorSpec extends ObjectBehavior
{
    function let(ValidatorInterface $validator)
    {
        $this->beConstructedWith($validator);
    }

    function it_implements_validator_interface()
    {
        $this->shouldImplement('Cardinity\Method\ValidatorInterface');
    }

    function it_validates_given_method_instance(
        MethodInterface $method,
        ValidatorInterface $validator
    ) {
        $attributes = ['field' => 'value'];
        $constraints = ['constraints'];

        $method->getAttributes()->shouldBeCalled()->willReturn($attributes);
        $method->getValidationConstraints()->shouldBeCalled()->willReturn($constraints);

        $validator->validate($attributes, $constraints)->shouldBeCalled();

        $this->validate($method);
    }

    function it_throws_exception_on_validation_failure(
        MethodInterface $method,
        ValidatorInterface $validator,
        ConstraintViolationList $violations
    ) {
        $attributes = ['field' => 'value'];
        $constraints = ['constraints'];

        $method->getValidationConstraints()->shouldBeCalled()->willReturn($constraints);
        $method->getAttributes()->shouldBeCalled()->willReturn($attributes);

        $violations->count()->willReturn(1);
        $violations->__toString()->willReturn('');

        $validator
            ->validate($attributes, $constraints)
            ->willReturn($violations)
        ;

        $this
            ->shouldThrow('Cardinity\Exception\InvalidAttributeValue')
            ->duringValidate($method)
        ;
    }

    function it_does_not_validate_method_with_no_constraints(
        MethodInterface $method,
        ValidatorInterface $validator
    ) {
        $constraints = [];

        $method->getValidationConstraints()->shouldBeCalled()->willReturn($constraints);

        $validator->validate([], $constraints)->shouldNotBeCalled();

        $this->validate($method);
    }
}   
    