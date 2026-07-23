<?php

declare(strict_types=1);

namespace libphonenumber\prefixmapper;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;

/**
 * A utility that maps phone number prefixes to a description string,
 * which may be, for example, the geographical area the prefix covers.
 *
 * Class PhonePrefixMap
 * @package libphonenumber\prefixmapper
 * @internal
 */
class PhonePrefixMap
{
    /**
     * @var array<string|int,string>
     */
    protected array $phonePrefixMapStorage = [];
    protected PhoneNumberUtil $phoneUtil;

    /**
     * @param array<string|int,string> $map
     */
    public function __construct(array $map)
    {
        $this->phonePrefixMapStorage = $map;
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * Returns the description of the {@code $number}. This method distinguishes the case of an invalid
     * prefix and a prefix for which the name is not available in the current language. If the
     * description is not available in the current language an empty string is returned. If no
     * description was found for the provided number, null is returned.
     *
     * @param PhoneNumber $number The phone number to look up
     * @return string|null the description of the number
     */
    public function lookup(PhoneNumber $number): ?string
    {
        $phonePrefix = $number->getCountryCode() . $this->phoneUtil->getNationalSignificantNumber($number);

        return $this->lookupKey($phonePrefix);
    }

    public function lookupKey(string $key): ?string
    {
        if (count($this->phonePrefixMapStorage) === 0) {
            return null;
        }

        while ($key !== '') {
            if (isset($this->phonePrefixMapStorage[$key])) {
                return $this->phonePrefixMapStorage[$key];
            }

            $key = substr($key, 0, -1);
        }

        return null;
    }
}
