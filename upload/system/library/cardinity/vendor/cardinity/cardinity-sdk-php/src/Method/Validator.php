<?php

namespace Cardinity\Method;

use Cardinity\Exception;
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

    /**
     * Validates given method values against its constraints
     *
     * @param MethodInterface $method
     * @throws Exception\InvalidAttributeValue
     */
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

        $countable = is_array($violations) || $violations instanceof \Countable;

        if ($countable && count($violations) !== 0) {
            throw new Exception\InvalidAttributeValue(
                'Your method contains invalid attribute value',
                $violations
            );
        }
    }
}
