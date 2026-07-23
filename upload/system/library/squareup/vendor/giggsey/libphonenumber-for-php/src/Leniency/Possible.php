<?php

declare(strict_types=1);

namespace libphonenumber\Leniency;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;

/**
 * @no-named-arguments
 */
class Possible extends AbstractLeniency
{
    protected static int $level = 1;

    /**
     * Phone numbers accepted are PhoneNumberUtil::isPossibleNumber(), but not necessarily
     * PhoneNumberUtil::isValidNumber().
     */
    public static function verify(PhoneNumber $number, string $candidate, PhoneNumberUtil $util): bool
    {
        return $util->isPossibleNumber($number);
    }
}
