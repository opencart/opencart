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
class PhoneNumberAlternateFormats_66 extends PhoneMetadata
{
    protected const ID = '';
    protected const COUNTRY_CODE = 66;

    protected ?string $internationalPrefix = '';

    public function __construct()
    {
        $this->numberFormat = [
            (new NumberFormat())
                ->setPattern('(\d{4})(\d{4})')
                ->setFormat('$1 $2')
                ->setLeadingDigitsPattern(['2'])
                ->setNationalPrefixOptionalWhenFormatting(false),
        ];
    }
}
