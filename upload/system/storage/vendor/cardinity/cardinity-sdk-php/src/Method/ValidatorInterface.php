<?php

namespace Cardinity\Method;

/**
 * Interface for method validators
 */
interface ValidatorInterface
{
    /**
     * Validates given method values against its constraints
     *
     * @param MethodInterface $method
     * @throws Cardinity\Exception\InvalidData
     */
    public function validate(MethodInterface $method);
}
