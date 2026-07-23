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
class PhoneNumberAlternateFormats_91 extends PhoneMetadata
{
    protected const ID = '';
    protected const COUNTRY_CODE = 91;

    protected ?string $internationalPrefix = '';

    public function __construct()
    {
        $this->numberFormat = [
            (new NumberFormat())
                ->setPattern('(\d{2})(\d{2})(\d{6})')
                ->setFormat('$1 $2 $3')
                ->setLeadingDigitsPattern([
                    '6(?:[09]|2(?:[02-7]|8[0-35-9])|5[02-689]|6[024-9]|8[124-9])|7(?:[07]|3[025-9]|4[0-35689]|6(?:[02-9]|1[0-257-9])|8[0-79]|9(?:[089]|31))|8(?:0(?:[01589]|6[67])|1[0-57-9]|2[235-9]|3[03-57-9]|[45]|6[02457-9]|7[1-69]|8(?:[0-25-9]|4[0147-9])|9(?:[02-9]|1[0-27-9]))|9|[67]1[013-9]|(?:67|72)[0235-9]|(?:63|75)[02-46-9]|6(?:29|35)[0-46-9]|(?:64|(?:79|80)7)[02-9]|(?:6(?:[2-4]1|5[17]|6[13]|7[14]|80)|7(?:12|88))[0189]|(?:612|7(?:2[14]|3[134]|4[47]|5[15])|8(?:16|2[014]|3[126]|6[136]|7[078]|83))[017-9]',
                ])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})')
                ->setFormat('$1 $2 $3 $4 $5')
                ->setLeadingDigitsPattern([
                    '6(?:[09]|2(?:[02-7]|8[0-35-9])|5[02-689]|6[024-9]|8[124-9])|7(?:[07]|3[025-9]|4[0-35689]|6(?:[02-9]|1[0-257-9])|8[0-79]|9(?:[089]|31))|8(?:0(?:[01589]|6[67])|1[0-57-9]|2[235-9]|3[03-57-9]|[45]|6[02457-9]|7[1-69]|8(?:[0-25-9]|4[0147-9])|9(?:[02-9]|1[0-27-9]))|9|[67]1[013-9]|(?:67|72)[0235-9]|(?:63|75)[02-46-9]|6(?:29|35)[0-46-9]|(?:64|(?:79|80)7)[02-9]|(?:6(?:[2-4]1|5[17]|6[13]|7[14]|80)|7(?:12|88))[0189]|(?:612|7(?:2[14]|3[134]|4[47]|5[15])|8(?:16|2[014]|3[126]|6[136]|7[078]|83))[017-9]',
                ])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{2})(\d{4})(\d{4})')
                ->setFormat('$1 $2 $3')
                ->setLeadingDigitsPattern(['79(?:[089]|31|7[02-9])|80(?:[01589]|6[67]|7[02-9])'])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{3})(\d{3})(\d{4})')
                ->setFormat('$1 $2 $3')
                ->setLeadingDigitsPattern([
                    '7(?:1[013-9]|2[0235-9]|3[025-9]|4[0-35689]|5[02-46-9]|6(?:[02-9]|1[0-257-9])|7|8[0-79])|8(?:1[0-57-9]|2[235-9]|3[03-57-9]|[45]|6[02457-9]|7[1-69]|8(?:[0-25-9]|4[0147-9])|9(?:[02-9]|1[0-27-9]))|7(?:12|88)[0189]|(?:7(?:2[14]|3[134]|4[47]|5[15])|8(?:16|2[014]|3[126]|6[136]|7[078]|83))[017-9]',
                ])
                ->setNationalPrefixOptionalWhenFormatting(false),
            (new NumberFormat())
                ->setPattern('(\d{4})(\d{3})(\d{3})')
                ->setFormat('$1 $2 $3')
                ->setLeadingDigitsPattern([
                    '7(?:1(?:[013-9]|2[0189])|2[0235-9]|3[025-9]|4[0-35689]|5[02-46-9]|6(?:[02-9]|1[0-257-9])|7)|80(?:[01589]|6[67]|7[02-9])|7(?:2[14]|3[134]|4[47]|5[15])[017-9]',
                ])
                ->setNationalPrefixOptionalWhenFormatting(false),
        ];
    }
}
