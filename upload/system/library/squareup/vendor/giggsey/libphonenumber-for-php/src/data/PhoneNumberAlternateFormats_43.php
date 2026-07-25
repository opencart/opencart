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
class PhoneNumberAlternateFormats_43 extends PhoneMetadata
{
    protected const ID = '';
    protected const COUNTRY_CODE = 43;

    protected ?string $internationalPrefix = '';

    public function __construct()
    {
        $this->numberFormat = [
            (new NumberFormat())
                ->setPattern('(\d)(\d{3})(\d{2})(\d{2,3})')
                ->setFormat('$1 $2 $3 $4')
                ->setLeadingDigitsPattern(['1'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d)(\d{4,6})')
                ->setFormat('$1 $2')
                ->setLeadingDigitsPattern(['5[079]'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d)(\d{7,8})')
                ->setFormat('$1 $2')
                ->setLeadingDigitsPattern(['5[079]'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{2})(\d{2})(\d{2})(\d{2,3})')
                ->setFormat('$1 $2 $3 $4')
                ->setLeadingDigitsPattern(['5[079]'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{2})(\d{6,7})')
                ->setFormat('$1 $2')
                ->setLeadingDigitsPattern(['5[079]'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d)(\d{9,12})')
                ->setFormat('$1 $2')
                ->setLeadingDigitsPattern(['5[079]'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{2})(\d{2})(\d{2})(\d{4})')
                ->setFormat('$1 $2 $3 $4')
                ->setLeadingDigitsPattern(['5[079]'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{2})(\d{2})(\d{2})(\d{2})(\d{2,4})')
                ->setFormat('$1 $2 $3 $4 $5')
                ->setLeadingDigitsPattern(['5[079]'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{2})(\d{5})(\d{4,6})')
                ->setFormat('$1 $2 $3')
                ->setLeadingDigitsPattern(['5[079]'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{3})(\d{3})(\d{3})(\d{3,4})')
                ->setFormat('$1 $2 $3 $4')
                ->setLeadingDigitsPattern(['(?:31|4)6|51|6(?:485|5[0-3579]|[6-9])|7(?:20|32|8)|[89]'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{3})(\d{3})(\d{2})(\d{2,3})')
                ->setFormat('$1 $2 $3 $4')
                ->setLeadingDigitsPattern(['(?:31|4)6|51|6(?:485|5[0-3579]|[6-9])|7(?:20|32|8)|[89]'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{3})(\d{2})(\d{2})(\d{2,3})')
                ->setFormat('$1 $2 $3 $4')
                ->setLeadingDigitsPattern(['(?:31|4)6|51|6(?:485|5[0-3579]|[6-9])|7(?:20|32|8)|[89]'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{4})(\d{3})(\d{3,4})')
                ->setFormat('$1 $2 $3')
                ->setLeadingDigitsPattern(['2|3(?:1[1-578]|[3-68])|4[2378]|5[2-6]|6(?:[12]|4(?:[135-7]|8[34])|5[468])|7(?:2[1-8]|35|[4-79])'])
                ->setNationalPrefixOptionalWhenFormatting(false),
        ];
    }
}
