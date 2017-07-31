<?php

namespace Cardinity\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class InvalidAttributeValue extends Runtime
{
    /** @type ConstraintViolationListInterface */
    private $violations;

    public function __construct($message, ConstraintViolationListInterface $violations)
    {
        $message .= ' Violations: ' . $violations->__toString();
        parent::__construct($message);

        $this->violations = $violations;
    }

    public function getViolations()
    {
        return $this->violations;
    }
}
