<?php

declare(strict_types=1);

/**
 * @author giggsey
 * @created: 02/10/13 16:52
 * @project libphonenumber-for-php
 */

namespace libphonenumber;

use Giggsey\Locale\Locale;
use libphonenumber\prefixmapper\PrefixFileReader;

/**
 * @phpstan-consistent-constructor
 * @no-named-arguments
 */
class PhoneNumberToCarrierMapper
{
    /**
     * @var PhoneNumberToCarrierMapper[]
     */
    protected static array $instance = [];
    protected PhoneNumberUtil $phoneUtil;
    protected PrefixFileReader $prefixFileReader;

    protected function __construct(string $phonePrefixDataNamespace)
    {
        $this->prefixFileReader = new PrefixFileReader($phonePrefixDataNamespace);
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * Gets a {@link PhoneNumberToCarrierMapper} instance to carry out international carrier lookup.
     *
     * <p> The {@link PhoneNumberToCarrierMapper} is implemented as a singleton. Therefore, calling
     * this method multiple times will only result in one instance being created.
     */
    public static function getInstance(?string $mappingNamespace = __NAMESPACE__ . '\\carrier\\data\\'): PhoneNumberToCarrierMapper
    {
        if (!isset(static::$instance[$mappingNamespace])) {
            static::$instance[$mappingNamespace] = new static($mappingNamespace);
        }

        return static::$instance[$mappingNamespace];
    }

    /**
     * Returns a carrier name for the given phone number, in the language provided. The carrier name
     * is the one the number was originally allocated to, however if the country supports mobile
     * number portability the number might not belong to the returned carrier anymore. If no mapping
     * is found an empty string is returned.
     *
     * <p>This method assumes the validity of the number passed in has already been checked, and that
     * the number is suitable for carrier lookup. We consider mobile and pager numbers possible
     * candidates for carrier lookup.
     *
     * @param PhoneNumber $number a valid phone number for which we want to get a carrier name
     * @param string $languageCode the language code in which the name should be written
     * @return string a carrier name for the given phone number
     */
    public function getNameForValidNumber(PhoneNumber $number, string $languageCode): string
    {
        $languageStr = Locale::getPrimaryLanguage($languageCode);
        $scriptStr = '';
        $regionStr = Locale::getRegion($languageCode);

        return $this->prefixFileReader->getDescriptionForNumber($number, $languageStr, $scriptStr, $regionStr);
    }


    /**
     * Gets the name of the carrier for the given phone number, in the language provided. As per
     * {@see getNameForValidNumber(PhoneNumber, Locale)} but explicitly checks the validity of
     * the number passed in.
     *
     * @param PhoneNumber $number The phone number  for which we want to get a carrier name
     * @param string $languageCode Language code for which the description should be written
     * @return string a carrier name for the given phone number, or empty string if the number passed in is
     *                invalid
     */
    public function getNameForNumber(PhoneNumber $number, string $languageCode): string
    {
        $numberType = $this->phoneUtil->getNumberType($number);
        if ($this->isMobile($numberType)) {
            return $this->getNameForValidNumber($number, $languageCode);
        }
        return '';
    }

    /**
     * Gets the name of the carrier for the given phone number only when it is 'safe' to display to
     * users. A carrier name is considered safe if the number is valid and for a region that doesn't
     * support
     * {@linkplain http://en.wikipedia.org/wiki/Mobile_number_portability mobile number portability}.
     *
     * @param $number PhoneNumber the phone number for which we want to get a carrier name
     * @param $languageCode String the language code in which the name should be written
     * @return string a carrier name that is safe to display to users, or the empty string
     */
    public function getSafeDisplayName(PhoneNumber $number, string $languageCode): string
    {
        if ($this->phoneUtil->isMobileNumberPortableRegion($this->phoneUtil->getRegionCodeForNumber($number))) {
            return '';
        }

        return $this->getNameForNumber($number, $languageCode);
    }

    /**
     * Checks if the supplied number type supports carrier lookup.
     */
    protected function isMobile(PhoneNumberType $numberType): bool
    {
        return ($numberType === PhoneNumberType::MOBILE ||
            $numberType === PhoneNumberType::FIXED_LINE_OR_MOBILE ||
            $numberType === PhoneNumberType::PAGER
        );
    }
}
