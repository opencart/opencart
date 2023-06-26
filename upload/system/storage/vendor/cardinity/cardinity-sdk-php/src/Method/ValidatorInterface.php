<?php

namespace Cardinity\Method;

use Cardinity\Exception;

/**
 * Interface for method validators
 */
interface ValidatorInterface
{
    /**
     * Validates given method values against its constraints
     *
     * @param MethodInterface $method
     * @throws Exception\InvalidAttributeValue
     */
    public function validate(MethodInterface $method);
}
