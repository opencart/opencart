<?php

/**
 * libphonenumber-for-php data file
 * This file has been @generated from libphonenumber data
 * Do not modify!
 * @internal
 */

declare(strict_types=1);

namespace libphonenumber\data;

use libphonenumber\NumberFormat;
use libphonenumber\PhoneMetadata;

/**
 * @internal
 */
class PhoneNumberAlternateFormats_55 extends PhoneMetadata
{
    protected const ID = '';
    protected const COUNTRY_CODE = 55;

    protected ?string $internationalPrefix = '';

    public function __construct()
    {
        $this->numberFormat = [
            (new NumberFormat())
                ->setPattern('(\d{2})(\d{8})')
                ->setFormat('$1 $2')
                ->setLeadingDigitsPattern(['[12467]|3[1-578]|5[13-5]|[89][1-9]'])
                ->setNationalPrefixOptionalWhenFormatting(false),
        ];
    }
}
