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
class PhoneNumberAlternateFormats_7 extends PhoneMetadata
{
    protected const ID = '';
    protected const COUNTRY_CODE = 7;

    protected ?string $internationalPrefix = '';

    public function __construct()
    {
        $this->numberFormat = [
            (new NumberFormat())
                ->setPattern('(\d{4})(\d{3})(\d{3})')
                ->setFormat('$1 $2 $3')
                ->setLeadingDigitsPattern(['[3489]|7(?:1(?:[0-356]2|4[29]|7|8[27])|2(?:1[23]|[2-9]2))'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{5})(\d{5})')
                ->setFormat('$1 $2')
                ->setLeadingDigitsPattern(['[3489]|72(?:6|7[457])|7(?:12|2[49])[35]|7(?:1[13-58]|2[1-38])[3-5]|7(?:1[06]|25)[3-6]'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{3})(\d{3})(\d{2})(\d{2})')
                ->setFormat('$1 $2 $3 $4')
                ->setLeadingDigitsPattern(['7(?:1|2(?:[1-689]|7[2457]))'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{3})(\d{2})(\d{2})(\d{3})')
                ->setFormat('$1 $2 $3 $4')
                ->setLeadingDigitsPattern(['[3489]|7(?:[04-9]|1(?:04|[236]3|4[3-5]|5[34])|2(?:13|34|7[39]))'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{3})(\d)(\d{2})(\d{2})(\d{2})')
                ->setFormat('$1 $2 $3 $4 $5')
                ->setLeadingDigitsPattern(['[3489]|7(?:[04-9]|1(?:04|[236]3|4[3-5]|5[34])|2(?:13|34|7[39]))'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{4})(\d{2})(\d{2})(\d{2})')
                ->setFormat('$1 $2 $3 $4')
                ->setLeadingDigitsPattern(['[3489]'])
                ->setNationalPrefixOptionalWhenFormatting(false),
        ];
    }
}
