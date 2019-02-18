<?php

$_['advertise_google_push_limit'] = 1000; // No more than 1000
$_['advertise_google_report_limit'] = 1000; // No more than 1000
$_['advertise_google_product_status_limit'] = 1000; // No more than 1000

// An empty array means it is always required.
// An array with values means it is required only in these specific cases
$_['advertise_google_country_required_fields'] = array(
    'google_product_category' => array(
        'countries' => array(),
        'selected_field' => NULL
    ),
    'condition' => array(
        'countries' => array(),
        'selected_field' => NULL
    ),
    'adult' => array(
        'countries' => array(),
        'selected_field' => NULL
    ),
    'multipack' => array(
        'countries' => array('AU', 'BR', 'CZ', 'FR', 'DE', 'IT', 'JP', 'NL', 'ES', 'CH', 'GB', 'US'),
        'selected_field' => NULL
    ),
    'is_bundle' => array(
        'countries' => array('AU', 'BR', 'CZ', 'FR', 'DE', 'IT', 'JP', 'NL', 'ES', 'CH', 'GB', 'US'),
        'selected_field' => NULL
    ),
    'age_group' => array(
        'countries' => array('BR', 'FR', 'DE', 'JP', 'GB', 'US'),
        'selected_field' => array(
            'google_product_category' => array('1604', '178', '3032', '201', '187')
        )
    ),
    'color' => array(
        'countries' => array('BR', 'FR', 'DE', 'JP', 'GB', 'US'),
        'selected_field' => array(
            'google_product_category' => array('1604', '178', '3032', '201', '187')
        )
    ),
    'gender' => array(
        'countries' => array('BR', 'FR', 'DE', 'JP', 'GB', 'US'),
        'selected_field' => array(
            'google_product_category' => array('1604', '178', '3032', '201', '187')
        )
    ),
    'size' => array(
        'countries' => array('BR', 'FR', 'DE', 'JP', 'GB', 'US'),
        'selected_field' => array(
            'google_product_category' => array('1604', '187')
        )
    ),
    'size_type' => array(
        'countries' => array('BR', 'FR', 'DE', 'JP', 'GB', 'US'),
        'selected_field' => array(
            'google_product_category' => array('1604', '187')
        )
    ),
    'size_system' => array(
        'countries' => array('BR', 'FR', 'DE', 'JP', 'GB', 'US'),
        'selected_field' => array(
            'google_product_category' => array('1604', '187')
        )
    )
);

$_['advertise_google_tax_usa_states'] = array(
    '21132' => 'Alaska',
    '21133' => 'Alabama',
    '21135' => 'Arkansas',
    '21136' => 'Arizona',
    '21137' => 'California',
    '21138' => 'Colorado',
    '21139' => 'Connecticut',
    '21140' => 'District of Columbia',
    '21141' => 'Delaware',
    '21142' => 'Florida',
    '21143' => 'Georgia',
    '21144' => 'Hawaii',
    '21145' => 'Iowa',
    '21146' => 'Idaho',
    '21147' => 'Illinois',
    '21148' => 'Indiana',
    '21149' => 'Kansas',
    '21150' => 'Kentucky',
    '21151' => 'Louisiana',
    '21152' => 'Massachusetts',
    '21153' => 'Maryland',
    '21154' => 'Maine',
    '21155' => 'Michigan',
    '21156' => 'Minnesota',
    '21157' => 'Missouri',
    '21158' => 'Mississippi',
    '21159' => 'Montana',
    '21160' => 'North Carolina',
    '21161' => 'North Dakota',
    '21162' => 'Nebraska',
    '21163' => 'New Hampshire',
    '21164' => 'New Jersey',
    '21165' => 'New Mexico',
    '21166' => 'Nevada',
    '21167' => 'New York',
    '21168' => 'Ohio',
    '21169' => 'Oklahoma',
    '21170' => 'Oregon',
    '21171' => 'Pennsylvania',
    '21172' => 'Rhode Island',
    '21173' => 'South Carolina',
    '21174' => 'South Dakota',
    '21175' => 'Tennessee',
    '21176' => 'Texas',
    '21177' => 'Utah',
    '21178' => 'Virginia',
    '21179' => 'Vermont',
    '21180' => 'Washington',
    '21182' => 'Wisconsin',
    '21183' => 'West Virginia',
    '21184' => 'Wyoming'
);

$_['advertise_google_google_product_categories'] = array(
    '0' => 'Other (Not on the list)',
    '1604' => 'Apparel & Accessories > Clothing',
    '178' => 'Apparel & Accessories > Clothing Accessories > Sunglasses',
    '3032' => 'Apparel & Accessories > Handbags, Wallets & Cases > Handbags',
    '201' => 'Apparel & Accessories > Jewelry > Watches',
    '187' => 'Apparel & Accessories > Shoes',
    '784' => 'Media > Books',
    '839' => 'Media > DVDs & Videos',
    '855' => 'Media > Music & Sound Recordings',
    '1279' => 'Software > Video Game Software'
);

$_['advertise_google_size_systems'] = array('AU','BR','CN','DE','EU','FR','IT','JP','MEX','UK','US');

$_['advertise_google_reporting_intervals'] = array(
    'TODAY',
    'YESTERDAY',
    'LAST_7_DAYS',
    'LAST_WEEK',
    'LAST_WEEK_SUN_SAT',
    'LAST_BUSINESS_WEEK',
    'LAST_14_DAYS',
    'LAST_30_DAYS',
    'THIS_WEEK_MON_TODAY',
    'THIS_WEEK_SUN_TODAY',
    'THIS_MONTH'
);

$_['advertise_google_reporting_intervals_default'] = 'LAST_30_DAYS';

// https://support.google.com/adwords/answer/2454022?hl=en&co=ADWORDS.IsAWNCustomer%3Dfalse
$_['advertise_google_countries'] = array(
    'AR' => "Argentina",
    'AU' => "Australia",
    'AT' => "Austria",
    'BE' => "Belgium",
    'BR' => "Brazil",
    'CA' => "Canada",
    'CL' => "Chile",
    'CO' => "Colombia",
    'CZ' => "Czechia",
    'DK' => "Denmark",
    'FR' => "France",
    'DE' => "Germany",
    'HK' => "Hong Kong",
    'IN' => "India",
    'ID' => "Indonesia",
    'IE' => "Ireland",
    'IL' => "Israel",
    'IT' => "Italy",
    'JP' => "Japan",
    'MY' => "Malaysia",
    'MX' => "Mexico",
    'NL' => "Netherlands",
    'NZ' => "New Zealand",
    'NO' => "Norway",
    'PH' => "Philippines",
    'PL' => "Poland",
    'PT' => "Portugal",
    'RU' => "Russia",
    'SA' => "Saudi Arabia",
    'SG' => "Singapore",
    'ZA' => "South Africa",
    'KR' => "South Korea",
    'ES' => "Spain",
    'SE' => "Sweden",
    'CH' => "Switzerland",
    'TW' => "Taiwan",
    'TH' => "Thailand",
    'TR' => "Turkey",
    'UA' => "Ukraine",
    'AE' => "United Arab Emirates",
    'GB' => "United Kingdom",
    'US' => "United States",
    'VN' => "Vietnam"
);

// https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
$_['advertise_google_languages'] = array(
    'ab' => "Abkhazian",
    'aa' => "Afar",
    'af' => "Afrikaans",
    'ak' => "Akan",
    'sq' => "Albanian",
    'am' => "Amharic",
    'ar' => "Arabic",
    'an' => "Aragonese",
    'hy' => "Armenian",
    'as' => "Assamese",
    'av' => "Avaric",
    'ae' => "Avestan",
    'ay' => "Aymara",
    'az' => "Azerbaijani",
    'bm' => "Bambara",
    'ba' => "Bashkir",
    'eu' => "Basque",
    'be' => "Belarusian",
    'bn' => "Bengali",
    'bh' => "Bihari languages",
    'bi' => "Bislama",
    'bs' => "Bosnian",
    'br' => "Breton",
    'bg' => "Bulgarian",
    'my' => "Burmese",
    'ca' => "Catalan, Valencian",
    'ch' => "Chamorro",
    'ce' => "Chechen",
    'ny' => "Chichewa, Chewa, Nyanja",
    'zh' => "Chinese",
    'cv' => "Chuvash",
    'kw' => "Cornish",
    'co' => "Corsican",
    'cr' => "Cree",
    'hr' => "Croatian",
    'cs' => "Czech",
    'da' => "Danish",
    'dv' => "Divehi, Dhivehi, Maldivian",
    'nl' => "Dutch, Flemish",
    'dz' => "Dzongkha",
    'en' => "English",
    'eo' => "Esperanto",
    'et' => "Estonian",
    'ee' => "Ewe",
    'fo' => "Faroese",
    'fj' => "Fijian",
    'fl' => "Filipino",
    'fi' => "Finnish",
    'fr' => "French",
    'ff' => "Fulah",
    'gl' => "Galician",
    'ka' => "Georgian",
    'de' => "German",
    'el' => "Greek (modern)",
    'gn' => "Guaraní",
    'gu' => "Gujarati",
    'ht' => "Haitian, Haitian Creole",
    'ha' => "Hausa",
    'he' => "Hebrew (modern)",
    'hz' => "Herero",
    'hi' => "Hindi",
    'ho' => "Hiri Motu",
    'hu' => "Hungarian",
    'ia' => "Interlingua",
    'id' => "Indonesian",
    'ie' => "Interlingue",
    'ga' => "Irish",
    'ig' => "Igbo",
    'ik' => "Inupiaq",
    'io' => "Ido",
    'is' => "Icelandic",
    'it' => "Italian",
    'iu' => "Inuktitut",
    'ja' => "Japanese",
    'jv' => "Javanese",
    'kl' => "Kalaallisut, Greenlandic",
    'kn' => "Kannada",
    'kr' => "Kanuri",
    'ks' => "Kashmiri",
    'kk' => "Kazakh",
    'km' => "Central Khmer",
    'ki' => "Kikuyu, Gikuyu",
    'rw' => "Kinyarwanda",
    'ky' => "Kirghiz, Kyrgyz",
    'kv' => "Komi",
    'kg' => "Kongo",
    'ko' => "Korean",
    'ku' => "Kurdish",
    'kj' => "Kuanyama, Kwanyama",
    'la' => "Latin",
    'lb' => "Luxembourgish, Letzeburgesch",
    'lg' => "Ganda",
    'li' => "Limburgan, Limburger, Limburgish",
    'ln' => "Lingala",
    'lo' => "Lao",
    'lt' => "Lithuanian",
    'lu' => "Luba-Katanga",
    'lv' => "Latvian",
    'gv' => "Manx",
    'mk' => "Macedonian",
    'mg' => "Malagasy",
    'ms' => "Malay",
    'ml' => "Malayalam",
    'mt' => "Maltese",
    'mi' => "Maori",
    'mr' => "Marathi",
    'mh' => "Marshallese",
    'mn' => "Mongolian",
    'na' => "Nauru",
    'nv' => "Navajo, Navaho",
    'nd' => "North Ndebele",
    'ne' => "Nepali",
    'ng' => "Ndonga",
    'nb' => "Norwegian Bokmål",
    'nn' => "Norwegian Nynorsk",
    'no' => "Norwegian",
    'ii' => "Sichuan Yi, Nuosu",
    'nr' => "South Ndebele",
    'oc' => "Occitan",
    'oj' => "Ojibwa",
    'cu' => "Church Slavic, Church Slavonic, Old Church Slavonic, Old Slavonic, Old Bulgarian",
    'om' => "Oromo",
    'or' => "Oriya",
    'os' => "Ossetian, Ossetic",
    'pa' => "Panjabi, Punjabi",
    'pi' => "Pali",
    'fa' => "Persian",
    'pl' => "Polish",
    'ps' => "Pashto, Pushto",
    'pt' => "Portuguese",
    'qu' => "Quechua",
    'rm' => "Romansh",
    'rn' => "Rundi",
    'ro' => "Romanian, Moldavian, Moldovan",
    'ru' => "Russian",
    'sa' => "Sanskrit",
    'sc' => "Sardinian",
    'sd' => "Sindhi",
    'se' => "Northern Sami",
    'sm' => "Samoan",
    'sg' => "Sango",
    'sr' => "Serbian",
    'gd' => "Gaelic, Scottish Gaelic",
    'sn' => "Shona",
    'si' => "Sinhala, Sinhalese",
    'sk' => "Slovak",
    'sl' => "Slovenian",
    'so' => "Somali",
    'st' => "Southern Sotho",
    'es' => "Spanish, Castilian",
    'su' => "Sundanese",
    'sw' => "Swahili",
    'ss' => "Swati",
    'sv' => "Swedish",
    'ta' => "Tamil",
    'te' => "Telugu",
    'tg' => "Tajik",
    'th' => "Thai",
    'ti' => "Tigrinya",
    'bo' => "Tibetan",
    'tk' => "Turkmen",
    'tl' => "Tagalog",
    'tn' => "Tswana",
    'to' => "Tongan (Tonga Islands)",
    'tr' => "Turkish",
    'ts' => "Tsonga",
    'tt' => "Tatar",
    'tw' => "Twi",
    'ty' => "Tahitian",
    'ug' => "Uighur, Uyghur",
    'uk' => "Ukrainian",
    'ur' => "Urdu",
    'uz' => "Uzbek",
    've' => "Venda",
    'vi' => "Vietnamese",
    'vo' => "Volapük",
    'wa' => "Walloon",
    'cy' => "Welsh",
    'wo' => "Wolof",
    'fy' => "Western Frisian",
    'xh' => "Xhosa",
    'yi' => "Yiddish",
    'yo' => "Yoruba",
    'za' => "Zhuang, Chuang",
    'zu' => "Zulu"
);

$_['advertise_google_currencies'] = array(
    "AED" => "United Arab Emirates Dirham",
    "AFN" => "Afghanistan Afghani",
    "ALL" => "Albania Lek",
    "AMD" => "Armenia Dram",
    "ANG" => "Netherlands Antilles Guilder",
    "AOA" => "Angola Kwanza",
    "ARS" => "Argentina Peso",
    "AUD" => "Australia Dollar",
    "AWG" => "Aruba Guilder",
    "AZN" => "Azerbaijan Manat",
    "BAM" => "Bosnia and Herzegovina Convertible Marka",
    "BBD" => "Barbados Dollar",
    "BDT" => "Bangladesh Taka",
    "BGN" => "Bulgaria Lev",
    "BHD" => "Bahrain Dinar",
    "BIF" => "Burundi Franc",
    "BMD" => "Bermuda Dollar",
    "BND" => "Brunei Darussalam Dollar",
    "BOB" => "Bolivia Bolíviano",
    "BRL" => "Brazil Real",
    "BSD" => "Bahamas Dollar",
    "BTN" => "Bhutan Ngultrum",
    "BWP" => "Botswana Pula",
    "BYN" => "Belarus Ruble",
    "BZD" => "Belize Dollar",
    "CAD" => "Canada Dollar",
    "CDF" => "Congo/Kinshasa Franc",
    "CHF" => "Switzerland Franc",
    "CLP" => "Chile Peso",
    "CNY" => "China Yuan Renminbi",
    "COP" => "Colombia Peso",
    "CRC" => "Costa Rica Colon",
    "CUC" => "Cuba Convertible Peso",
    "CUP" => "Cuba Peso",
    "CVE" => "Cape Verde Escudo",
    "CZK" => "Czech Republic Koruna",
    "DJF" => "Djibouti Franc",
    "DKK" => "Denmark Krone",
    "DOP" => "Dominican Republic Peso",
    "DZD" => "Algeria Dinar",
    "EGP" => "Egypt Pound",
    "ERN" => "Eritrea Nakfa",
    "ETB" => "Ethiopia Birr",
    "EUR" => "Euro Member Countries",
    "FJD" => "Fiji Dollar",
    "FKP" => "Falkland Islands (Malvinas) Pound",
    "GBP" => "United Kingdom Pound",
    "GEL" => "Georgia Lari",
    "GGP" => "Guernsey Pound",
    "GHS" => "Ghana Cedi",
    "GIP" => "Gibraltar Pound",
    "GMD" => "Gambia Dalasi",
    "GNF" => "Guinea Franc",
    "GTQ" => "Guatemala Quetzal",
    "GYD" => "Guyana Dollar",
    "HKD" => "Hong Kong Dollar",
    "HNL" => "Honduras Lempira",
    "HRK" => "Croatia Kuna",
    "HTG" => "Haiti Gourde",
    "HUF" => "Hungary Forint",
    "IDR" => "Indonesia Rupiah",
    "ILS" => "Israel Shekel",
    "IMP" => "Isle of Man Pound",
    "INR" => "India Rupee",
    "IQD" => "Iraq Dinar",
    "IRR" => "Iran Rial",
    "ISK" => "Iceland Krona",
    "JEP" => "Jersey Pound",
    "JMD" => "Jamaica Dollar",
    "JOD" => "Jordan Dinar",
    "JPY" => "Japan Yen",
    "KES" => "Kenya Shilling",
    "KGS" => "Kyrgyzstan Som",
    "KHR" => "Cambodia Riel",
    "KMF" => "Comorian Franc",
    "KPW" => "Korea (North) Won",
    "KRW" => "Korea (South) Won",
    "KWD" => "Kuwait Dinar",
    "KYD" => "Cayman Islands Dollar",
    "KZT" => "Kazakhstan Tenge",
    "LAK" => "Laos Kip",
    "LBP" => "Lebanon Pound",
    "LKR" => "Sri Lanka Rupee",
    "LRD" => "Liberia Dollar",
    "LSL" => "Lesotho Loti",
    "LYD" => "Libya Dinar",
    "MAD" => "Morocco Dirham",
    "MDL" => "Moldova Leu",
    "MGA" => "Madagascar Ariary",
    "MKD" => "Macedonia Denar",
    "MMK" => "Myanmar (Burma) Kyat",
    "MNT" => "Mongolia Tughrik",
    "MOP" => "Macau Pataca",
    "MRU" => "Mauritania Ouguiya",
    "MUR" => "Mauritius Rupee",
    "MVR" => "Maldives (Maldive Islands) Rufiyaa",
    "MWK" => "Malawi Kwacha",
    "MXN" => "Mexico Peso",
    "MYR" => "Malaysia Ringgit",
    "MZN" => "Mozambique Metical",
    "NAD" => "Namibia Dollar",
    "NGN" => "Nigeria Naira",
    "NIO" => "Nicaragua Cordoba",
    "NOK" => "Norway Krone",
    "NPR" => "Nepal Rupee",
    "NZD" => "New Zealand Dollar",
    "OMR" => "Oman Rial",
    "PAB" => "Panama Balboa",
    "PEN" => "Peru Sol",
    "PGK" => "Papua New Guinea Kina",
    "PHP" => "Philippines Piso",
    "PKR" => "Pakistan Rupee",
    "PLN" => "Poland Zloty",
    "PYG" => "Paraguay Guarani",
    "QAR" => "Qatar Riyal",
    "RON" => "Romania Leu",
    "RSD" => "Serbia Dinar",
    "RUB" => "Russia Ruble",
    "RWF" => "Rwanda Franc",
    "SAR" => "Saudi Arabia Riyal",
    "SBD" => "Solomon Islands Dollar",
    "SCR" => "Seychelles Rupee",
    "SDG" => "Sudan Pound",
    "SEK" => "Sweden Krona",
    "SGD" => "Singapore Dollar",
    "SHP" => "Saint Helena Pound",
    "SLL" => "Sierra Leone Leone",
    "SOS" => "Somalia Shilling",
    "SPL*" => "Seborga Luigino",
    "SRD" => "Suriname Dollar",
    "STN" => "São Tomé and Príncipe Dobra",
    "SVC" => "El Salvador Colon",
    "SYP" => "Syria Pound",
    "SZL" => "Swaziland Lilangeni",
    "THB" => "Thailand Baht",
    "TJS" => "Tajikistan Somoni",
    "TMT" => "Turkmenistan Manat",
    "TND" => "Tunisia Dinar",
    "TOP" => "Tonga Pa'anga",
    "TRY" => "Turkey Lira",
    "TTD" => "Trinidad and Tobago Dollar",
    "TVD" => "Tuvalu Dollar",
    "TWD" => "Taiwan New Dollar",
    "TZS" => "Tanzania Shilling",
    "UAH" => "Ukraine Hryvnia",
    "UGX" => "Uganda Shilling",
    "USD" => "United States Dollar",
    "UYU" => "Uruguay Peso",
    "UZS" => "Uzbekistan Som",
    "VEF" => "Venezuela Bolívar",
    "VND" => "Viet Nam Dong",
    "VUV" => "Vanuatu Vatu",
    "WST" => "Samoa Tala",
    "XAF" => "Communauté Financière Africaine (BEAC) CFA Franc BEAC",
    "XCD" => "East Caribbean Dollar",
    "XDR" => "International Monetary Fund (IMF) Special Drawing Rights",
    "XOF" => "Communauté Financière Africaine (BCEAO) Franc",
    "XPF" => "Comptoirs Français du Pacifique (CFP) Franc",
    "YER" => "Yemen Rial",
    "ZAR" => "South Africa Rand",
    "ZMW" => "Zambia Kwacha",
    "ZWD" => "Zimbabwe Dollar"
);

/*
 * These entries are defined based on this help article:
 * https://support.google.com/merchants/answer/160637?hl=en
 */
$_['advertise_google_targets'] = array(
    array(
        'country' => 'AR',
        'languages' => array('es'),
        'currencies' => array('ARS')
    ),
    array(
        'country' => 'AU',
        'languages' => array('en', 'zh'),
        'currencies' => array('AUD')
    ),
    array(
        'country' => 'AT',
        'languages' => array('de', 'en'),
        'currencies' => array('EUR')
    ),
    array(
        'country' => 'BE',
        'languages' => array('fr', 'nl', 'en'),
        'currencies' => array('EUR')
    ),
    array(
        'country' => 'BR',
        'languages' => array('pt'),
        'currencies' => array('BRL')
    ),
    array(
        'country' => 'CA',
        'languages' => array('en', 'fr', 'zh'),
        'currencies' => array('CAD')
    ),
    array(
        'country' => 'CL',
        'languages' => array('es'),
        'currencies' => array('CLP')
    ),
    array(
        'country' => 'CO',
        'languages' => array('es'),
        'currencies' => array('COP')
    ),
    array(
        'country' => 'CZ',
        'languages' => array('cs', 'en'),
        'currencies' => array('CZK')
    ),
    array(
        'country' => 'DK',
        'languages' => array('da', 'en'),
        'currencies' => array('DKK')
    ),
    array(
        'country' => 'FR',
        'languages' => array('fr'),
        'currencies' => array('EUR')
    ),
    array(
        'country' => 'DE',
        'languages' => array('de', 'en'),
        'currencies' => array('EUR')
    ),
    array(
        'country' => 'HK',
        'languages' => array('zh', 'en'),
        'currencies' => array('HKD')
    ),
    array(
        'country' => 'IN',
        'languages' => array('en'),
        'currencies' => array('INR')
    ),
    array(
        'country' => 'ID',
        'languages' => array('id', 'en'),
        'currencies' => array('IDR')
    ),
    array(
        'country' => 'IE',
        'languages' => array('en'),
        'currencies' => array('EUR')
    ),
    array(
        'country' => 'IL',
        'languages' => array('he', 'en'),
        'currencies' => array('ILS')
    ),
    array(
        'country' => 'IT',
        'languages' => array('it'),
        'currencies' => array('EUR')
    ),
    array(
        'country' => 'JP',
        'languages' => array('ja'),
        'currencies' => array('JPY')
    ),
    array(
        'country' => 'MY',
        'languages' => array('en', 'zh'),
        'currencies' => array('MYR')
    ),
    array(
        'country' => 'MX',
        'languages' => array('es', 'en'),
        'currencies' => array('MXN')
    ),
    array(
        'country' => 'NL',
        'languages' => array('nl', 'en'),
        'currencies' => array('EUR')
    ),
    array(
        'country' => 'NZ',
        'languages' => array('en'),
        'currencies' => array('NZD')
    ),
    array(
        'country' => 'NO',
        'languages' => array('no', 'en'),
        'currencies' => array('NOK')
    ),
    array(
        'country' => 'PH',
        'languages' => array('en'),
        'currencies' => array('PHP')
    ),
    array(
        'country' => 'PL',
        'languages' => array('pl'),
        'currencies' => array('PLN')
    ),
    array(
        'country' => 'PT',
        'languages' => array('pt'),
        'currencies' => array('EUR')
    ),
    array(
        'country' => 'RU',
        'languages' => array('ru'),
        'currencies' => array('RUB')
    ),
    array(
        'country' => 'SA',
        'languages' => array('ar', 'en'),
        'currencies' => array('SAR')
    ),
    array(
        'country' => 'SG',
        'languages' => array('en', 'zh'),
        'currencies' => array('SGD')
    ),
    array(
        'country' => 'ZA',
        'languages' => array('en'),
        'currencies' => array('ZAR')
    ),
    array(
        'country' => 'KR',
        'languages' => array('ko', 'en'),
        'currencies' => array('KRW')
    ),
    array(
        'country' => 'ES',
        'languages' => array('es'),
        'currencies' => array('EUR')
    ),
    array(
        'country' => 'SE',
        'languages' => array('sv', 'en'),
        'currencies' => array('SEK')
    ),
    array(
        'country' => 'CH',
        'languages' => array('en', 'de', 'fr', 'it'),
        'currencies' => array('CHF')
    ),
    array(
        'country' => 'TW',
        'languages' => array('zh', 'en'),
        'currencies' => array('TWD')
    ),
    array(
        'country' => 'TH',
        'languages' => array('th', 'en'),
        'currencies' => array('THB')
    ),
    array(
        'country' => 'TR',
        'languages' => array('tr', 'en'),
        'currencies' => array('TRY')
    ),
    array(
        'country' => 'UA',
        'languages' => array('uk', 'ru'),
        'currencies' => array('UAH')
    ),
    array(
        'country' => 'AE',
        'languages' => array('en'),
        'currencies' => array('AED')
    ),
    array(
        'country' => 'GB',
        'languages' => array('en'),
        'currencies' => array('GBP')
    ),
    array(
        'country' => 'US',
        'languages' => array('en', 'es', 'zh'),
        'currencies' => array('USD')
    ),
    array(
        'country' => 'VN',
        'languages' => array('vi', 'en'),
        'currencies' => array('VND')
    )
);