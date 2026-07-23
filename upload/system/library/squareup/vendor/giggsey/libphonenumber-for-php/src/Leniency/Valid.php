<?php

declare(strict_types=1);

namespace libphonenumber\Leniency;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberMatcher;
use libphonenumber\PhoneNumberUtil;

/**
 * @no-named-arguments
 */
class Valid extends AbstractLeniency
{
    protected static int $level = 2;

    /**
     * Phone numbers accepted are PhoneNumberUtil::isPossibleNumber() and PhoneNumberUtil::isValidNumber().
     * Numbers written in national format must have their national-prefix present if it is usually written
     * for a number of this type.
     */
    public static function verify(PhoneNumber $number, string $candidate, PhoneNumberUtil $util): bool
    {
        if (!$util->isValidNumber($number)
            || !PhoneNumberMatcher::containsOnlyValidXChars($number, $candidate, $util)) {
            return false;
        }

        return PhoneNumberMatcher::isNationalPrefixPresentIfRequired($number, $util);
    }
}
