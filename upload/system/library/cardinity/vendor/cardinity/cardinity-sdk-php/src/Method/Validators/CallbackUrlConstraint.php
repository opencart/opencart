<?php

namespace Cardinity\Method\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CallbackUrlConstraint extends Constraint
{
    const RESTRICTED_DOMAIN = 'localhost';
    const RESTRICTED_IP = '127.0.0.1';

    public $message = 'The url "{{ param }}" contains restricted values. Do not use "' .
        self::RESTRICTED_DOMAIN . '" or "' . self::RESTRICTED_IP . '".';
}
