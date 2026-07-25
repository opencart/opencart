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
class PhoneNumberAlternateFormats_95 extends PhoneMetadata
{
    protected const ID = '';
    protected const COUNTRY_CODE = 95;

    protected ?string $internationalPrefix = '';

    public function __construct()
    {
        $this->numberFormat = [
            (new NumberFormat())
                ->setPattern('(\d)(\d{4})(\d{5})')
                ->setFormat('$1 $2 $3')
                ->setLeadingDigitsPattern(['92'])
                ->setNationalPrefixOptionalWhenFormatting(false),
        ];
    }
}
