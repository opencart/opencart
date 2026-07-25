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
class ShortNumberMetadata_DE extends PhoneMetadata
{
    protected const ID = 'DE';
    protected const COUNTRY_CODE = 0;

    protected ?string $internationalPrefix = '';

    public function __construct()
    {
        $this->generalDesc = (new PhoneNumberDesc())
            ->setNationalNumberPattern('[1-46-8]\d{2,5}')
            ->setPossibleLength([3, 4, 5, 6]);
        $this->premiumRate = PhoneNumberDesc::empty();
        $this->tollFree = (new PhoneNumberDesc())
            ->setNationalNumberPattern('11(?:[02]|6\d{3})')
            ->setExampleNumber('110')
            ->setPossibleLength([3, 6]);
        $this->emergency = (new PhoneNumberDesc())
            ->setNationalNumberPattern('11[02]')
            ->setExampleNumber('110')
            ->setPossibleLength([3]);
        $this->short_code = (new PhoneNumberDesc())
            ->setNationalNumberPattern('11(?:[025]|6(?:00[06]|1(?:1[167]|23))|800\d)|22(?:044|5(?:43|80)|7700|922)|33(?:11|3[34])|44844|600\d\d|7(?:0\d{3}|464)|80808|118\d\d')
            ->setExampleNumber('110');
        $this->standard_rate = PhoneNumberDesc::empty();
        $this->carrierSpecific = (new PhoneNumberDesc())
            ->setNationalNumberPattern('(?:33[13]|746)\d|(?:22(?:[059]|7\d)|(?:44|80)8|600|70\d)\d\d')
            ->setExampleNumber('3310')
            ->setPossibleLength([4, 5, 6]);
        $this->smsServices = (new PhoneNumberDesc())
            ->setNationalNumberPattern('(?:333|746)\d|(?:22(?:[059]|7\d)|(?:44|80)8|600|70\d)\d\d')
            ->setExampleNumber('3330')
            ->setPossibleLength([4, 5, 6]);
    }
}
