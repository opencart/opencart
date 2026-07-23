<?php

declare(strict_types=1);

namespace libphonenumber\prefixmapper;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;

/**
 * @internal
 */
class PrefixTimeZonesMap
{
    public const RAW_STRING_TIMEZONES_SEPARATOR = '&';
    protected PhonePrefixMap $phonePrefixMap;

    /**
     * @param array<string|int,string> $map
     */
    public function __construct(array $map)
    {
        $this->phonePrefixMap = new PhonePrefixMap($map);
    }

    /**
     * As per {@see lookupTimeZonesForNumber(long)}, but receives the number as a PhoneNumber
     * instead of a long.
     *
     * @param $number PhoneNumber the phone number to look up
     * @return string[] the list of corresponding time zones
     */
    public function lookupTimeZonesForNumber(PhoneNumber $number): array
    {
        $phonePrefix = $number->getCountryCode() . PhoneNumberUtil::getInstance()->getNationalSignificantNumber(
            $number
        );

        return $this->lookupTimeZonesForNumberKey($phonePrefix);
    }

    /**
     * Returns the list of time zones {@code key} corresponds to.
     *
     * <p>{@code key} could be the calling country code and the full significant number of a
     * certain number, or it could be just a phone-number prefix.
     * For example, the full number 16502530000 (from the phone-number +1 650 253 0000) is a valid
     * input. Also, any of its prefixes, such as 16502, is also valid.
     *
     * @return string[] the list of corresponding time zones
     */
    protected function lookupTimeZonesForNumberKey(string $key): array
    {
        // Lookup in the map data. The returned String may consist of several time zones, so it must be
        // split.
        $timezonesString = $this->phonePrefixMap->lookupKey($key);

        if ($timezonesString === null) {
            return [];
        }

        return $this->tokenizeRawOutputString($timezonesString);
    }

    /**
     * Split {@code timezonesString} into all the time zones that are part of it.
     *
     * @param $timezonesString string
     * @return string[]
     */
    protected function tokenizeRawOutputString(string $timezonesString): array
    {
        return explode(static::RAW_STRING_TIMEZONES_SEPARATOR, $timezonesString);
    }

    /**
     * Returns the list of time zones {@code number}'s calling country code corresponds to.
     *
     * @param $number PhoneNumber the phone number to look up
     * @return string[] the list of corresponding time zones
     */
    public function lookupCountryLevelTimeZonesForNumber(PhoneNumber $number): array
    {
        return $this->lookupTimeZonesForNumberKey((string) $number->getCountryCode());
    }
}
