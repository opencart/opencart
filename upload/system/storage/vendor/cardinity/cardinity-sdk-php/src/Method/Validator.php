<?php

namespace Cardinity\Method;

use Cardinity\Exception;
use Cardinity\Method\MethodInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as BaseValidator;

class Validator implements ValidatorInterface
{
    /** @type BaseValidator */
    private $validator;

    /**
     * @param BaseValidator $validator
     */
    public function __construct(BaseValidator $validator)
    {
        $this->validator = $validator;
    }

    public function validate(MethodInterface $method)
    {
        $constraints = $method->getValidationConstraints();
        if (empty($constraints)) {
            return;
        }

        $violations = $this->validator->validate(
            $method->getAttributes(),
            $constraints
        );

        if (count($violations) !== 0) {
            throw new Exception\InvalidAttributeValue(
                'Your method contains invalid attribute value',
                $violations
            );
        }
    }
}
