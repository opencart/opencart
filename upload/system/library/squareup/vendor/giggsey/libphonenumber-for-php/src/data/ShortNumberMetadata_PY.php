<?php

/**
 * libphonenumber-for-php data file
 * This file has been @generated from libphonenumber data
 * Do not modify!
 * @internal
 */

declare(strict_types=1);

namespace libphonenumber\data;

use libphonenumber\PhoneMetadata;
use libphonenumber\PhoneNumberDesc;

/**
 * @internal
 */
class ShortNumberMetadata_PY extends PhoneMetadata
{
    protected const ID = 'PY';
    protected const COUNTRY_CODE = 0;

    protected ?string $internationalPrefix = '';

    public function __construct()
    {
        $this->generalDesc = (new PhoneNumberDesc())
            ->setNationalNumberPattern('[159]\d\d(?:\d{4})?')
            ->setPossibleLength([3, 7]);
        $this->premiumRate = PhoneNumberDesc::empty();
        $this->tollFree = (new PhoneNumberDesc())
            ->setNationalNumberPattern('128|911')
            ->setExampleNumber('128')
            ->setPossibleLength([3]);
        $this->emergency = (new PhoneNumberDesc())
            ->setNationalNumberPattern('128|911')
            ->setExampleNumber('128')
            ->setPossibleLength([3]);
        $this->short_code = (new PhoneNumberDesc())
            ->setNationalNumberPattern('(?:1[01]|51)\d{5}|911|1[1-9]\d')
            ->setExampleNumber('110');
        $this->standard_rate = PhoneNumberDesc::empty();
        $this->carrierSpecific = (new PhoneNumberDesc())
            ->setNationalNumberPattern('(?:1[01]|51)\d{5}')
            ->setExampleNumber('1000000')
            ->setPossibleLength([7]);
        $this->smsServices = (new PhoneNumberDesc())
            ->setNationalNumberPattern('(?:1[01]|51)\d{5}')
            ->setExampleNumber('1000000')
            ->setPossibleLength([7]);
    }
}
