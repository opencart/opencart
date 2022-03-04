<?php

namespace Cardinity\Method\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;


class CallbackUrlConstraintValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint) {
        if (!$constraint instanceof CallbackUrlConstraint) {
            throw new UnexpectedTypeException($constraint, CallbackUrlConstraint::class);
        }
        if (null === $value || '' === $value) {
            return;
        }
        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }
        if (strpos($value, $constraint::RESTRICTED_DOMAIN) ||
            strpos($value, $constraint::RESTRICTED_IP)
        ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ param }}', $value)
                ->addViolation();
        }
    }
}
