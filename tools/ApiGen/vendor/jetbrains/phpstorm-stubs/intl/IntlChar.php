<?php

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;
use JetBrains\PhpStorm\Pure;

/**
 * <p>IntlChar provides access to a number of utility methods that can be used to access information about Unicode characters.</p>
 * <p>The methods and constants adhere closely to the names and behavior used by the underlying ICU library.</p>
 * @since 7.0
 */
class IntlChar
{
    public const UNICODE_VERSION = 13.0;
    public const CODEPOINT_MIN = 0;
    public const CODEPOINT_MAX = 1114111;
    public const FOLD_CASE_DEFAULT = 0;
    public const FOLD_CASE_EXCLUDE_SPECIAL_I = 1;
    public const PROPERTY_ALPHABETIC = 0;
    public const PROPERTY_BINARY_START = 0;
    public const PROPERTY_ASCII_HEX_DIGIT = 1;
    public const PROPERTY_BIDI_CONTROL = 2;
    public const PROPERTY_BIDI_MIRRORED = 3;
    public const PROPERTY_DASH = 4;
    public const PROPERTY_DEFAULT_IGNORABLE_CODE_POINT = 5;
    public const PROPERTY_DEPRECATED = 6;
    public const PROPERTY_DIACRITIC = 7;
    public const PROPERTY_EXTENDER = 8;
    public const PROPERTY_FULL_COMPOSITION_EXCLUSION = 9;
    public const PROPERTY_GRAPHEME_BASE = 10;
    public const PROPERTY_GRAPHEME_EXTEND = 11;
    public const PROPERTY_GRAPHEME_LINK = 12;
    public const PROPERTY_HEX_DIGIT = 13;
    public const PROPERTY_HYPHEN = 14;
    public const PROPERTY_ID_CONTINUE = 15;
    public const PROPERTY_ID_START = 16;
    public const PROPERTY_IDEOGRAPHIC = 17;
    public const PROPERTY_IDS_BINARY_OPERATOR = 18;
    public const PROPERTY_IDS_TRINARY_OPERATOR = 19;
    public const PROPERTY_JOIN_CONTROL = 20;
    public const PROPERTY_LOGICAL_ORDER_EXCEPTION = 21;
    public const PROPERTY_LOWERCASE = 22;
    public const PROPERTY_MATH = 23;
    public const PROPERTY_NONCHARACTER_CODE_POINT = 24;
    public const PROPERTY_QUOTATION_MARK = 25;
    public const PROPERTY_RADICAL = 26;
    public const PROPERTY_SOFT_DOTTED = 27;
    public const PROPERTY_TERMINAL_PUNCTUATION = 28;
    public const PROPERTY_UNIFIED_IDEOGRAPH = 29;
    public const PROPERTY_UPPERCASE = 30;
    public const PROPERTY_WHITE_SPACE = 31;
    public const PROPERTY_XID_CONTINUE = 32;
    public const PROPERTY_XID_START = 33;
    public const PROPERTY_CASE_SENSITIVE = 34;
    public const PROPERTY_S_TERM = 35;
    public const PROPERTY_VARIATION_SELECTOR = 36;
    public const PROPERTY_NFD_INERT = 37;
    public const PROPERTY_NFKD_INERT = 38;
    public const PROPERTY_NFC_INERT = 39;
    public const PROPERTY_NFKC_INERT = 40;
    public const PROPERTY_SEGMENT_STARTER = 41;
    public const PROPERTY_PATTERN_SYNTAX = 42;
    public const PROPERTY_PATTERN_WHITE_SPACE = 43;
    public const PROPERTY_POSIX_ALNUM = 44;
    public const PROPERTY_POSIX_BLANK = 45;
    public const PROPERTY_POSIX_GRAPH = 46;
    public const PROPERTY_POSIX_PRINT = 47;
    public const PROPERTY_POSIX_XDIGIT = 48;
    public const PROPERTY_CASED = 49;
    public const PROPERTY_CASE_IGNORABLE = 50;
    public const PROPERTY_CHANGES_WHEN_LOWERCASED = 51;
    public const PROPERTY_CHANGES_WHEN_UPPERCASED = 52;
    public const PROPERTY_CHANGES_WHEN_TITLECASED = 53;
    public const PROPERTY_CHANGES_WHEN_CASEFOLDED = 54;
    public const PROPERTY_CHANGES_WHEN_CASEMAPPED = 55;
    public const PROPERTY_CHANGES_WHEN_NFKC_CASEFOLDED = 56;
    public const PROPERTY_BINARY_LIMIT = 65;
    public const PROPERTY_BIDI_CLASS = 4096;
    public const PROPERTY_INT_START = 4096;
    public const PROPERTY_BLOCK = 4097;
    public const PROPERTY_CANONICAL_COMBINING_CLASS = 4098;
    public const PROPERTY_DECOMPOSITION_TYPE = 4099;
    public const PROPERTY_EAST_ASIAN_WIDTH = 4100;
    public const PROPERTY_GENERAL_CATEGORY = 4101;
    public const PROPERTY_JOINING_GROUP = 4102;
    public const PROPERTY_JOINING_TYPE = 4103;
    public const PROPERTY_LINE_BREAK = 4104;
    public const PROPERTY_NUMERIC_TYPE = 4105;
    public const PROPERTY_SCRIPT = 4106;
    public const PROPERTY_HANGUL_SYLLABLE_TYPE = 4107;
    public const PROPERTY_NFD_QUICK_CHECK = 4108;
    public const PROPERTY_NFKD_QUICK_CHECK = 4109;
    public const PROPERTY_NFC_QUICK_CHECK = 4110;
    public const PROPERTY_NFKC_QUICK_CHECK = 4111;
    public const PROPERTY_LEAD_CANONICAL_COMBINING_CLASS = 4112;
    public const PROPERTY_TRAIL_CANONICAL_COMBINING_CLASS = 4113;
    public const PROPERTY_GRAPHEME_CLUSTER_BREAK = 4114;
    public const PROPERTY_SENTENCE_BREAK = 4115;
    public const PROPERTY_WORD_BREAK = 4116;
    public const PROPERTY_BIDI_PAIRED_BRACKET_TYPE = 4117;
    public const PROPERTY_INT_LIMIT = 4121;
    public const PROPERTY_GENERAL_CATEGORY_MASK = 8192;
    public const PROPERTY_MASK_START = 8192;
    public const PROPERTY_MASK_LIMIT = 8193;
    public const PROPERTY_NUMERIC_VALUE = 12288;
    public const PROPERTY_DOUBLE_START = 12288;
    public const PROPERTY_DOUBLE_LIMIT = 12289;
    public const PROPERTY_AGE = 16384;
    public const PROPERTY_STRING_START = 16384;
    public const PROPERTY_BIDI_MIRRORING_GLYPH = 16385;
    public const PROPERTY_CASE_FOLDING = 16386;
    public const PROPERTY_ISO_COMMENT = 16387;
    public const PROPERTY_LOWERCASE_MAPPING = 16388;
    public const PROPERTY_NAME = 16389;
    public const PROPERTY_SIMPLE_CASE_FOLDING = 16390;
    public const PROPERTY_SIMPLE_LOWERCASE_MAPPING = 16391;
    public const PROPERTY_SIMPLE_TITLECASE_MAPPING = 16392;
    public const PROPERTY_SIMPLE_UPPERCASE_MAPPING = 16393;
    public const PROPERTY_TITLECASE_MAPPING = 16394;
    public const PROPERTY_UNICODE_1_NAME = 16395;
    public const PROPERTY_UPPERCASE_MAPPING = 16396;
    public const PROPERTY_BIDI_PAIRED_BRACKET = 16397;
    public const PROPERTY_STRING_LIMIT = 16398;
    public const PROPERTY_SCRIPT_EXTENSIONS = 28672;
    public const PROPERTY_OTHER_PROPERTY_START = 28672;
    public const PROPERTY_OTHER_PROPERTY_LIMIT = 28673;
    public const PROPERTY_INVALID_CODE = -1;
    public const CHAR_CATEGORY_UNASSIGNED = 0;
    public const CHAR_CATEGORY_GENERAL_OTHER_TYPES = 0;
    public const CHAR_CATEGORY_UPPERCASE_LETTER = 1;
    public const CHAR_CATEGORY_LOWERCASE_LETTER = 2;
    public const CHAR_CATEGORY_TITLECASE_LETTER = 3;
    public const CHAR_CATEGORY_MODIFIER_LETTER = 4;
    public const CHAR_CATEGORY_OTHER_LETTER = 5;
    public const CHAR_CATEGORY_NON_SPACING_MARK = 6;
    public const CHAR_CATEGORY_ENCLOSING_MARK = 7;
    public const CHAR_CATEGORY_COMBINING_SPACING_MARK = 8;
    public const CHAR_CATEGORY_DECIMAL_DIGIT_NUMBER = 9;
    public const CHAR_CATEGORY_LETTER_NUMBER = 10;
    public const CHAR_CATEGORY_OTHER_NUMBER = 11;
    public const CHAR_CATEGORY_SPACE_SEPARATOR = 12;
    public const CHAR_CATEGORY_LINE_SEPARATOR = 13;
    public const CHAR_CATEGORY_PARAGRAPH_SEPARATOR = 14;
    public const CHAR_CATEGORY_CONTROL_CHAR = 15;
    public const CHAR_CATEGORY_FORMAT_CHAR = 16;
    public const CHAR_CATEGORY_PRIVATE_USE_CHAR = 17;
    public const CHAR_CATEGORY_SURROGATE = 18;
    public const CHAR_CATEGORY_DASH_PUNCTUATION = 19;
    public const CHAR_CATEGORY_START_PUNCTUATION = 20;
    public const CHAR_CATEGORY_END_PUNCTUATION = 21;
    public const CHAR_CATEGORY_CONNECTOR_PUNCTUATION = 22;
    public const CHAR_CATEGORY_OTHER_PUNCTUATION = 23;
    public const CHAR_CATEGORY_MATH_SYMBOL = 24;
    public const CHAR_CATEGORY_CURRENCY_SYMBOL = 25;
    public const CHAR_CATEGORY_MODIFIER_SYMBOL = 26;
    public const CHAR_CATEGORY_OTHER_SYMBOL = 27;
    public const CHAR_CATEGORY_INITIAL_PUNCTUATION = 28;
    public const CHAR_CATEGORY_FINAL_PUNCTUATION = 29;
    public const CHAR_CATEGORY_CHAR_CATEGORY_COUNT = 30;
    public const CHAR_DIRECTION_LEFT_TO_RIGHT = 0;
    public const CHAR_DIRECTION_RIGHT_TO_LEFT = 1;
    public const CHAR_DIRECTION_EUROPEAN_NUMBER = 2;
    public const CHAR_DIRECTION_EUROPEAN_NUMBER_SEPARATOR = 3;
    public const CHAR_DIRECTION_EUROPEAN_NUMBER_TERMINATOR = 4;
    public const CHAR_DIRECTION_ARABIC_NUMBER = 5;
    public const CHAR_DIRECTION_COMMON_NUMBER_SEPARATOR = 6;
    public const CHAR_DIRECTION_BLOCK_SEPARATOR = 7;
    public const CHAR_DIRECTION_SEGMENT_SEPARATOR = 8;
    public const CHAR_DIRECTION_WHITE_SPACE_NEUTRAL = 9;
    public const CHAR_DIRECTION_OTHER_NEUTRAL = 10;
    public const CHAR_DIRECTION_LEFT_TO_RIGHT_EMBEDDING = 11;
    public const CHAR_DIRECTION_LEFT_TO_RIGHT_OVERRIDE = 12;
    public const CHAR_DIRECTION_RIGHT_TO_LEFT_ARABIC = 13;
    public const CHAR_DIRECTION_RIGHT_TO_LEFT_EMBEDDING = 14;
    public const CHAR_DIRECTION_RIGHT_TO_LEFT_OVERRIDE = 15;
    public const CHAR_DIRECTION_POP_DIRECTIONAL_FORMAT = 16;
    public const CHAR_DIRECTION_DIR_NON_SPACING_MARK = 17;
    public const CHAR_DIRECTION_BOUNDARY_NEUTRAL = 18;
    public const CHAR_DIRECTION_FIRST_STRONG_ISOLATE = 19;
    public const CHAR_DIRECTION_LEFT_TO_RIGHT_ISOLATE = 20;
    public const CHAR_DIRECTION_RIGHT_TO_LEFT_ISOLATE = 21;
    public const CHAR_DIRECTION_POP_DIRECTIONAL_ISOLATE = 22;
    public const CHAR_DIRECTION_CHAR_DIRECTION_COUNT = 23;
    public const BLOCK_CODE_NO_BLOCK = 0;
    public const BLOCK_CODE_BASIC_LATIN = 1;
    public const BLOCK_CODE_LATIN_1_SUPPLEMENT = 2;
    public const BLOCK_CODE_LATIN_EXTENDED_A = 3;
    public const BLOCK_CODE_LATIN_EXTENDED_B = 4;
    public const BLOCK_CODE_IPA_EXTENSIONS = 5;
    public const BLOCK_CODE_SPACING_MODIFIER_LETTERS = 6;
    public const BLOCK_CODE_COMBINING_DIACRITICAL_MARKS = 7;
    public const BLOCK_CODE_GREEK = 8;
    public const BLOCK_CODE_CYRILLIC = 9;
    public const BLOCK_CODE_ARMENIAN = 10;
    public const BLOCK_CODE_HEBREW = 11;
    public const BLOCK_CODE_ARABIC = 12;
    public const BLOCK_CODE_SYRIAC = 13;
    public const BLOCK_CODE_THAANA = 14;
    public const BLOCK_CODE_DEVANAGARI = 15;
    public const BLOCK_CODE_BENGALI = 16;
    public const BLOCK_CODE_GURMUKHI = 17;
    public const BLOCK_CODE_GUJARATI = 18;
    public const BLOCK_CODE_ORIYA = 19;
    public const BLOCK_CODE_TAMIL = 20;
    public const BLOCK_CODE_TELUGU = 21;
    public const BLOCK_CODE_KANNADA = 22;
    public const BLOCK_CODE_MALAYALAM = 23;
    public const BLOCK_CODE_SINHALA = 24;
    public const BLOCK_CODE_THAI = 25;
    public const BLOCK_CODE_LAO = 26;
    public const BLOCK_CODE_TIBETAN = 27;
    public const BLOCK_CODE_MYANMAR = 28;
    public const BLOCK_CODE_GEORGIAN = 29;
    public const BLOCK_CODE_HANGUL_JAMO = 30;
    public const BLOCK_CODE_ETHIOPIC = 31;
    public const BLOCK_CODE_CHEROKEE = 32;
    public const BLOCK_CODE_UNIFIED_CANADIAN_ABORIGINAL_SYLLABICS = 33;
    public const BLOCK_CODE_OGHAM = 34;
    public const BLOCK_CODE_RUNIC = 35;
    public const BLOCK_CODE_KHMER = 36;
    public const BLOCK_CODE_MONGOLIAN = 37;
    public const BLOCK_CODE_LATIN_EXTENDED_ADDITIONAL = 38;
    public const BLOCK_CODE_GREEK_EXTENDED = 39;
    public const BLOCK_CODE_GENERAL_PUNCTUATION = 40;
    public const BLOCK_CODE_SUPERSCRIPTS_AND_SUBSCRIPTS = 41;
    public const BLOCK_CODE_CURRENCY_SYMBOLS = 42;
    public const BLOCK_CODE_COMBINING_MARKS_FOR_SYMBOLS = 43;
    public const BLOCK_CODE_LETTERLIKE_SYMBOLS = 44;
    public const BLOCK_CODE_NUMBER_FORMS = 45;
    public const BLOCK_CODE_ARROWS = 46;
    public const BLOCK_CODE_MATHEMATICAL_OPERATORS = 47;
    public const BLOCK_CODE_MISCELLANEOUS_TECHNICAL = 48;
    public const BLOCK_CODE_CONTROL_PICTURES = 49;
    public const BLOCK_CODE_OPTICAL_CHARACTER_RECOGNITION = 50;
    public const BLOCK_CODE_ENCLOSED_ALPHANUMERICS = 51;
    public const BLOCK_CODE_BOX_DRAWING = 52;
    public const BLOCK_CODE_BLOCK_ELEMENTS = 53;
    public const BLOCK_CODE_GEOMETRIC_SHAPES = 54;
    public const BLOCK_CODE_MISCELLANEOUS_SYMBOLS = 55;
    public const BLOCK_CODE_DINGBATS = 56;
    public const BLOCK_CODE_BRAILLE_PATTERNS = 57;
    public const BLOCK_CODE_CJK_RADICALS_SUPPLEMENT = 58;
    public const BLOCK_CODE_KANGXI_RADICALS = 59;
    public const BLOCK_CODE_IDEOGRAPHIC_DESCRIPTION_CHARACTERS = 60;
    public const BLOCK_CODE_CJK_SYMBOLS_AND_PUNCTUATION = 61;
    public const BLOCK_CODE_HIRAGANA = 62;
    public const BLOCK_CODE_KATAKANA = 63;
    public const BLOCK_CODE_BOPOMOFO = 64;
    public const BLOCK_CODE_HANGUL_COMPATIBILITY_JAMO = 65;
    public const BLOCK_CODE_KANBUN = 66;
    public const BLOCK_CODE_BOPOMOFO_EXTENDED = 67;
    public const BLOCK_CODE_ENCLOSED_CJK_LETTERS_AND_MONTHS = 68;
    public const BLOCK_CODE_CJK_COMPATIBILITY = 69;
    public const BLOCK_CODE_CJK_UNIFIED_IDEOGRAPHS_EXTENSION_A = 70;
    public const BLOCK_CODE_CJK_UNIFIED_IDEOGRAPHS = 71;
    public const BLOCK_CODE_YI_SYLLABLES = 72;
    public const BLOCK_CODE_YI_RADICALS = 73;
    public const BLOCK_CODE_HANGUL_SYLLABLES = 74;
    public const BLOCK_CODE_HIGH_SURROGATES = 75;
    public const BLOCK_CODE_HIGH_PRIVATE_USE_SURROGATES = 76;
    public const BLOCK_CODE_LOW_SURROGATES = 77;
    public const BLOCK_CODE_PRIVATE_USE_AREA = 78;
    public const BLOCK_CODE_PRIVATE_USE = 78;
    public const BLOCK_CODE_CJK_COMPATIBILITY_IDEOGRAPHS = 79;
    public const BLOCK_CODE_ALPHABETIC_PRESENTATION_FORMS = 80;
    public const BLOCK_CODE_ARABIC_PRESENTATION_FORMS_A = 81;
    public const BLOCK_CODE_COMBINING_HALF_MARKS = 82;
    public const BLOCK_CODE_CJK_COMPATIBILITY_FORMS = 83;
    public const BLOCK_CODE_SMALL_FORM_VARIANTS = 84;
    public const BLOCK_CODE_ARABIC_PRESENTATION_FORMS_B = 85;
    public const BLOCK_CODE_SPECIALS = 86;
    public const BLOCK_CODE_HALFWIDTH_AND_FULLWIDTH_FORMS = 87;
    public const BLOCK_CODE_OLD_ITALIC = 88;
    public const BLOCK_CODE_GOTHIC = 89;
    public const BLOCK_CODE_DESERET = 90;
    public const BLOCK_CODE_BYZANTINE_MUSICAL_SYMBOLS = 91;
    public const BLOCK_CODE_MUSICAL_SYMBOLS = 92;
    public const BLOCK_CODE_MATHEMATICAL_ALPHANUMERIC_SYMBOLS = 93;
    public const BLOCK_CODE_CJK_UNIFIED_IDEOGRAPHS_EXTENSION_B = 94;
    public const BLOCK_CODE_CJK_COMPATIBILITY_IDEOGRAPHS_SUPPLEMENT = 95;
    public const BLOCK_CODE_TAGS = 96;
    public const BLOCK_CODE_CYRILLIC_SUPPLEMENT = 97;
    public const BLOCK_CODE_CYRILLIC_SUPPLEMENTARY = 97;
    public const BLOCK_CODE_TAGALOG = 98;
    public const BLOCK_CODE_HANUNOO = 99;
    public const BLOCK_CODE_BUHID = 100;
    public const BLOCK_CODE_TAGBANWA = 101;
    public const BLOCK_CODE_MISCELLANEOUS_MATHEMATICAL_SYMBOLS_A = 102;
    public const BLOCK_CODE_SUPPLEMENTAL_ARROWS_A = 103;
    public const BLOCK_CODE_SUPPLEMENTAL_ARROWS_B = 104;
    public const BLOCK_CODE_MISCELLANEOUS_MATHEMATICAL_SYMBOLS_B = 105;
    public const BLOCK_CODE_SUPPLEMENTAL_MATHEMATICAL_OPERATORS = 106;
    public const BLOCK_CODE_KATAKANA_PHONETIC_EXTENSIONS = 107;
    public const BLOCK_CODE_VARIATION_SELECTORS = 108;
    public const BLOCK_CODE_SUPPLEMENTARY_PRIVATE_USE_AREA_A = 109;
    public const BLOCK_CODE_SUPPLEMENTARY_PRIVATE_USE_AREA_B = 110;
    public const BLOCK_CODE_LIMBU = 111;
    public const BLOCK_CODE_TAI_LE = 112;
    public const BLOCK_CODE_KHMER_SYMBOLS = 113;
    public const BLOCK_CODE_PHONETIC_EXTENSIONS = 114;
    public const BLOCK_CODE_MISCELLANEOUS_SYMBOLS_AND_ARROWS = 115;
    public const BLOCK_CODE_YIJING_HEXAGRAM_SYMBOLS = 116;
    public const BLOCK_CODE_LINEAR_B_SYLLABARY = 117;
    public const BLOCK_CODE_LINEAR_B_IDEOGRAMS = 118;
    public const BLOCK_CODE_AEGEAN_NUMBERS = 119;
    public const BLOCK_CODE_UGARITIC = 120;
    public const BLOCK_CODE_SHAVIAN = 121;
    public const BLOCK_CODE_OSMANYA = 122;
    public const BLOCK_CODE_CYPRIOT_SYLLABARY = 123;
    public const BLOCK_CODE_TAI_XUAN_JING_SYMBOLS = 124;
    public const BLOCK_CODE_VARIATION_SELECTORS_SUPPLEMENT = 125;
    public const BLOCK_CODE_ANCIENT_GREEK_MUSICAL_NOTATION = 126;
    public const BLOCK_CODE_ANCIENT_GREEK_NUMBERS = 127;
    public const BLOCK_CODE_ARABIC_SUPPLEMENT = 128;
    public const BLOCK_CODE_BUGINESE = 129;
    public const BLOCK_CODE_CJK_STROKES = 130;
    public const BLOCK_CODE_COMBINING_DIACRITICAL_MARKS_SUPPLEMENT = 131;
    public const BLOCK_CODE_COPTIC = 132;
    public const BLOCK_CODE_ETHIOPIC_EXTENDED = 133;
    public const BLOCK_CODE_ETHIOPIC_SUPPLEMENT = 134;
    public const BLOCK_CODE_GEORGIAN_SUPPLEMENT = 135;
    public const BLOCK_CODE_GLAGOLITIC = 136;
    public const BLOCK_CODE_KHAROSHTHI = 137;
    public const BLOCK_CODE_MODIFIER_TONE_LETTERS = 138;
    public const BLOCK_CODE_NEW_TAI_LUE = 139;
    public const BLOCK_CODE_OLD_PERSIAN = 140;
    public const BLOCK_CODE_PHONETIC_EXTENSIONS_SUPPLEMENT = 141;
    public const BLOCK_CODE_SUPPLEMENTAL_PUNCTUATION = 142;
    public const BLOCK_CODE_SYLOTI_NAGRI = 143;
    public const BLOCK_CODE_TIFINAGH = 144;
    public const BLOCK_CODE_VERTICAL_FORMS = 145;
    public const BLOCK_CODE_NKO = 146;
    public const BLOCK_CODE_BALINESE = 147;
    public const BLOCK_CODE_LATIN_EXTENDED_C = 148;
    public const BLOCK_CODE_LATIN_EXTENDED_D = 149;
    public const BLOCK_CODE_PHAGS_PA = 150;
    public const BLOCK_CODE_PHOENICIAN = 151;
    public const BLOCK_CODE_CUNEIFORM = 152;
    public const BLOCK_CODE_CUNEIFORM_NUMBERS_AND_PUNCTUATION = 153;
    public const BLOCK_CODE_COUNTING_ROD_NUMERALS = 154;
    public const BLOCK_CODE_SUNDANESE = 155;
    public const BLOCK_CODE_LEPCHA = 156;
    public const BLOCK_CODE_OL_CHIKI = 157;
    public const BLOCK_CODE_CYRILLIC_EXTENDED_A = 158;
    public const BLOCK_CODE_VAI = 159;
    public const BLOCK_CODE_CYRILLIC_EXTENDED_B = 160;
    public const BLOCK_CODE_SAURASHTRA = 161;
    public const BLOCK_CODE_KAYAH_LI = 162;
    public const BLOCK_CODE_REJANG = 163;
    public const BLOCK_CODE_CHAM = 164;
    public const BLOCK_CODE_ANCIENT_SYMBOLS = 165;
    public const BLOCK_CODE_PHAISTOS_DISC = 166;
    public const BLOCK_CODE_LYCIAN = 167;
    public const BLOCK_CODE_CARIAN = 168;
    public const BLOCK_CODE_LYDIAN = 169;
    public const BLOCK_CODE_MAHJONG_TILES = 170;
    public const BLOCK_CODE_DOMINO_TILES = 171;
    public const BLOCK_CODE_SAMARITAN = 172;
    public const BLOCK_CODE_UNIFIED_CANADIAN_ABORIGINAL_SYLLABICS_EXTENDED = 173;
    public const BLOCK_CODE_TAI_THAM = 174;
    public const BLOCK_CODE_VEDIC_EXTENSIONS = 175;
    public const BLOCK_CODE_LISU = 176;
    public const BLOCK_CODE_BAMUM = 177;
    public const BLOCK_CODE_COMMON_INDIC_NUMBER_FORMS = 178;
    public const BLOCK_CODE_DEVANAGARI_EXTENDED = 179;
    public const BLOCK_CODE_HANGUL_JAMO_EXTENDED_A = 180;
    public const BLOCK_CODE_JAVANESE = 181;
    public const BLOCK_CODE_MYANMAR_EXTENDED_A = 182;
    public const BLOCK_CODE_TAI_VIET = 183;
    public const BLOCK_CODE_MEETEI_MAYEK = 184;
    public const BLOCK_CODE_HANGUL_JAMO_EXTENDED_B = 185;
    public const BLOCK_CODE_IMPERIAL_ARAMAIC = 186;
    public const BLOCK_CODE_OLD_SOUTH_ARABIAN = 187;
    public const BLOCK_CODE_AVESTAN = 188;
    public const BLOCK_CODE_INSCRIPTIONAL_PARTHIAN = 189;
    public const BLOCK_CODE_INSCRIPTIONAL_PAHLAVI = 190;
    public const BLOCK_CODE_OLD_TURKIC = 191;
    public const BLOCK_CODE_RUMI_NUMERAL_SYMBOLS = 192;
    public const BLOCK_CODE_KAITHI = 193;
    public const BLOCK_CODE_EGYPTIAN_HIEROGLYPHS = 194;
    public const BLOCK_CODE_ENCLOSED_ALPHANUMERIC_SUPPLEMENT = 195;
    public const BLOCK_CODE_ENCLOSED_IDEOGRAPHIC_SUPPLEMENT = 196;
    public const BLOCK_CODE_CJK_UNIFIED_IDEOGRAPHS_EXTENSION_C = 197;
    public const BLOCK_CODE_MANDAIC = 198;
    public const BLOCK_CODE_BATAK = 199;
    public const BLOCK_CODE_ETHIOPIC_EXTENDED_A = 200;
    public const BLOCK_CODE_BRAHMI = 201;
    public const BLOCK_CODE_BAMUM_SUPPLEMENT = 202;
    public const BLOCK_CODE_KANA_SUPPLEMENT = 203;
    public const BLOCK_CODE_PLAYING_CARDS = 204;
    public const BLOCK_CODE_MISCELLANEOUS_SYMBOLS_AND_PICTOGRAPHS = 205;
    public const BLOCK_CODE_EMOTICONS = 206;
    public const BLOCK_CODE_TRANSPORT_AND_MAP_SYMBOLS = 207;
    public const BLOCK_CODE_ALCHEMICAL_SYMBOLS = 208;
    public const BLOCK_CODE_CJK_UNIFIED_IDEOGRAPHS_EXTENSION_D = 209;
    public const BLOCK_CODE_ARABIC_EXTENDED_A = 210;
    public const BLOCK_CODE_ARABIC_MATHEMATICAL_ALPHABETIC_SYMBOLS = 211;
    public const BLOCK_CODE_CHAKMA = 212;
    public const BLOCK_CODE_MEETEI_MAYEK_EXTENSIONS = 213;
    public const BLOCK_CODE_MEROITIC_CURSIVE = 214;
    public const BLOCK_CODE_MEROITIC_HIEROGLYPHS = 215;
    public const BLOCK_CODE_MIAO = 216;
    public const BLOCK_CODE_SHARADA = 217;
    public const BLOCK_CODE_SORA_SOMPENG = 218;
    public const BLOCK_CODE_SUNDANESE_SUPPLEMENT = 219;
    public const BLOCK_CODE_TAKRI = 220;
    public const BLOCK_CODE_BASSA_VAH = 221;
    public const BLOCK_CODE_CAUCASIAN_ALBANIAN = 222;
    public const BLOCK_CODE_COPTIC_EPACT_NUMBERS = 223;
    public const BLOCK_CODE_COMBINING_DIACRITICAL_MARKS_EXTENDED = 224;
    public const BLOCK_CODE_DUPLOYAN = 225;
    public const BLOCK_CODE_ELBASAN = 226;
    public const BLOCK_CODE_GEOMETRIC_SHAPES_EXTENDED = 227;
    public const BLOCK_CODE_GRANTHA = 228;
    public const BLOCK_CODE_KHOJKI = 229;
    public const BLOCK_CODE_KHUDAWADI = 230;
    public const BLOCK_CODE_LATIN_EXTENDED_E = 231;
    public const BLOCK_CODE_LINEAR_A = 232;
    public const BLOCK_CODE_MAHAJANI = 233;
    public const BLOCK_CODE_MANICHAEAN = 234;
    public const BLOCK_CODE_MENDE_KIKAKUI = 235;
    public const BLOCK_CODE_MODI = 236;
    public const BLOCK_CODE_MRO = 237;
    public const BLOCK_CODE_MYANMAR_EXTENDED_B = 238;
    public const BLOCK_CODE_NABATAEAN = 239;
    public const BLOCK_CODE_OLD_NORTH_ARABIAN = 240;
    public const BLOCK_CODE_OLD_PERMIC = 241;
    public const BLOCK_CODE_ORNAMENTAL_DINGBATS = 242;
    public const BLOCK_CODE_PAHAWH_HMONG = 243;
    public const BLOCK_CODE_PALMYRENE = 244;
    public const BLOCK_CODE_PAU_CIN_HAU = 245;
    public const BLOCK_CODE_PSALTER_PAHLAVI = 246;
    public const BLOCK_CODE_SHORTHAND_FORMAT_CONTROLS = 247;
    public const BLOCK_CODE_SIDDHAM = 248;
    public const BLOCK_CODE_SINHALA_ARCHAIC_NUMBERS = 249;
    public const BLOCK_CODE_SUPPLEMENTAL_ARROWS_C = 250;
    public const BLOCK_CODE_TIRHUTA = 251;
    public const BLOCK_CODE_WARANG_CITI = 252;
    public const BLOCK_CODE_COUNT = 309;
    public const BLOCK_CODE_INVALID_CODE = -1;
    public const BPT_NONE = 0;
    public const BPT_OPEN = 1;
    public const BPT_CLOSE = 2;
    public const BPT_COUNT = 3;
    public const EA_NEUTRAL = 0;
    public const EA_AMBIGUOUS = 1;
    public const EA_HALFWIDTH = 2;
    public const EA_FULLWIDTH = 3;
    public const EA_NARROW = 4;
    public const EA_WIDE = 5;
    public const EA_COUNT = 6;
    public const UNICODE_CHAR_NAME = 0;
    public const UNICODE_10_CHAR_NAME = 1;
    public const EXTENDED_CHAR_NAME = 2;
    public const CHAR_NAME_ALIAS = 3;
    public const CHAR_NAME_CHOICE_COUNT = 4;
    public const SHORT_PROPERTY_NAME = 0;
    public const LONG_PROPERTY_NAME = 1;
    public const PROPERTY_NAME_CHOICE_COUNT = 2;
    public const DT_NONE = 0;
    public const DT_CANONICAL = 1;
    public const DT_COMPAT = 2;
    public const DT_CIRCLE = 3;
    public const DT_FINAL = 4;
    public const DT_FONT = 5;
    public const DT_FRACTION = 6;
    public const DT_INITIAL = 7;
    public const DT_ISOLATED = 8;
    public const DT_MEDIAL = 9;
    public const DT_NARROW = 10;
    public const DT_NOBREAK = 11;
    public const DT_SMALL = 12;
    public const DT_SQUARE = 13;
    public const DT_SUB = 14;
    public const DT_SUPER = 15;
    public const DT_VERTICAL = 16;
    public const DT_WIDE = 17;
    public const DT_COUNT = 18;
    public const JT_NON_JOINING = 0;
    public const JT_JOIN_CAUSING = 1;
    public const JT_DUAL_JOINING = 2;
    public const JT_LEFT_JOINING = 3;
    public const JT_RIGHT_JOINING = 4;
    public const JT_TRANSPARENT = 5;
    public const JT_COUNT = 6;
    public const JG_NO_JOINING_GROUP = 0;
    public const JG_AIN = 1;
    public const JG_ALAPH = 2;
    public const JG_ALEF = 3;
    public const JG_BEH = 4;
    public const JG_BETH = 5;
    public const JG_DAL = 6;
    public const JG_DALATH_RISH = 7;
    public const JG_E = 8;
    public const JG_FEH = 9;
    public const JG_FINAL_SEMKATH = 10;
    public const JG_GAF = 11;
    public const JG_GAMAL = 12;
    public const JG_HAH = 13;
    public const JG_TEH_MARBUTA_GOAL = 14;
    public const JG_HAMZA_ON_HEH_GOAL = 14;
    public const JG_HE = 15;
    public const JG_HEH = 16;
    public const JG_HEH_GOAL = 17;
    public const JG_HETH = 18;
    public const JG_KAF = 19;
    public const JG_KAPH = 20;
    public const JG_KNOTTED_HEH = 21;
    public const JG_LAM = 22;
    public const JG_LAMADH = 23;
    public const JG_MEEM = 24;
    public const JG_MIM = 25;
    public const JG_NOON = 26;
    public const JG_NUN = 27;
    public const JG_PE = 28;
    public const JG_QAF = 29;
    public const JG_QAPH = 30;
    public const JG_REH = 31;
    public const JG_REVERSED_PE = 32;
    public const JG_SAD = 33;
    public const JG_SADHE = 34;
    public const JG_SEEN = 35;
    public const JG_SEMKATH = 36;
    public const JG_SHIN = 37;
    public const JG_SWASH_KAF = 38;
    public const JG_SYRIAC_WAW = 39;
    public const JG_TAH = 40;
    public const JG_TAW = 41;
    public const JG_TEH_MARBUTA = 42;
    public const JG_TETH = 43;
    public const JG_WAW = 44;
    public const JG_YEH = 45;
    public const JG_YEH_BARREE = 46;
    public const JG_YEH_WITH_TAIL = 47;
    public const JG_YUDH = 48;
    public const JG_YUDH_HE = 49;
    public const JG_ZAIN = 50;
    public const JG_FE = 51;
    public const JG_KHAPH = 52;
    public const JG_ZHAIN = 53;
    public const JG_BURUSHASKI_YEH_BARREE = 54;
    public const JG_FARSI_YEH = 55;
    public const JG_NYA = 56;
    public const JG_ROHINGYA_YEH = 57;
    public const JG_MANICHAEAN_ALEPH = 58;
    public const JG_MANICHAEAN_AYIN = 59;
    public const JG_MANICHAEAN_BETH = 60;
    public const JG_MANICHAEAN_DALETH = 61;
    public const JG_MANICHAEAN_DHAMEDH = 62;
    public const JG_MANICHAEAN_FIVE = 63;
    public const JG_MANICHAEAN_GIMEL = 64;
    public const JG_MANICHAEAN_HETH = 65;
    public const JG_MANICHAEAN_HUNDRED = 66;
    public const JG_MANICHAEAN_KAPH = 67;
    public const JG_MANICHAEAN_LAMEDH = 68;
    public const JG_MANICHAEAN_MEM = 69;
    public const JG_MANICHAEAN_NUN = 70;
    public const JG_MANICHAEAN_ONE = 71;
    public const JG_MANICHAEAN_PE = 72;
    public const JG_MANICHAEAN_QOPH = 73;
    public const JG_MANICHAEAN_RESH = 74;
    public const JG_MANICHAEAN_SADHE = 75;
    public const JG_MANICHAEAN_SAMEKH = 76;
    public const JG_MANICHAEAN_TAW = 77;
    public const JG_MANICHAEAN_TEN = 78;
    public const JG_MANICHAEAN_TETH = 79;
    public const JG_MANICHAEAN_THAMEDH = 80;
    public const JG_MANICHAEAN_TWENTY = 81;
    public const JG_MANICHAEAN_WAW = 82;
    public const JG_MANICHAEAN_YODH = 83;
    public const JG_MANICHAEAN_ZAYIN = 84;
    public const JG_STRAIGHT_WAW = 85;
    public const JG_COUNT = 102;
    public const GCB_OTHER = 0;
    public const GCB_CONTROL = 1;
    public const GCB_CR = 2;
    public const GCB_EXTEND = 3;
    public const GCB_L = 4;
    public const GCB_LF = 5;
    public const GCB_LV = 6;
    public const GCB_LVT = 7;
    public const GCB_T = 8;
    public const GCB_V = 9;
    public const GCB_SPACING_MARK = 10;
    public const GCB_PREPEND = 11;
    public const GCB_REGIONAL_INDICATOR = 12;
    public const GCB_COUNT = 18;
    public const WB_OTHER = 0;
    public const WB_ALETTER = 1;
    public const WB_FORMAT = 2;
    public const WB_KATAKANA = 3;
    public const WB_MIDLETTER = 4;
    public const WB_MIDNUM = 5;
    public const WB_NUMERIC = 6;
    public const WB_EXTENDNUMLET = 7;
    public const WB_CR = 8;
    public const WB_EXTEND = 9;
    public const WB_LF = 10;
    public const WB_MIDNUMLET = 11;
    public const WB_NEWLINE = 12;
    public const WB_REGIONAL_INDICATOR = 13;
    public const WB_HEBREW_LETTER = 14;
    public const WB_SINGLE_QUOTE = 15;
    public const WB_DOUBLE_QUOTE = 16;
    public const WB_COUNT = 23;
    public const SB_OTHER = 0;
    public const SB_ATERM = 1;
    public const SB_CLOSE = 2;
    public const SB_FORMAT = 3;
    public const SB_LOWER = 4;
    public const SB_NUMERIC = 5;
    public const SB_OLETTER = 6;
    public const SB_SEP = 7;
    public const SB_SP = 8;
    public const SB_STERM = 9;
    public const SB_UPPER = 10;
    public const SB_CR = 11;
    public const SB_EXTEND = 12;
    public const SB_LF = 13;
    public const SB_SCONTINUE = 14;
    public const SB_COUNT = 15;
    public const LB_UNKNOWN = 0;
    public const LB_AMBIGUOUS = 1;
    public const LB_ALPHABETIC = 2;
    public const LB_BREAK_BOTH = 3;
    public const LB_BREAK_AFTER = 4;
    public const LB_BREAK_BEFORE = 5;
    public const LB_MANDATORY_BREAK = 6;
    public const LB_CONTINGENT_BREAK = 7;
    public const LB_CLOSE_PUNCTUATION = 8;
    public const LB_COMBINING_MARK = 9;
    public const LB_CARRIAGE_RETURN = 10;
    public const LB_EXCLAMATION = 11;
    public const LB_GLUE = 12;
    public const LB_HYPHEN = 13;
    public const LB_IDEOGRAPHIC = 14;
    public const LB_INSEPARABLE = 15;
    public const LB_INSEPERABLE = 15;
    public const LB_INFIX_NUMERIC = 16;
    public const LB_LINE_FEED = 17;
    public const LB_NONSTARTER = 18;
    public const LB_NUMERIC = 19;
    public const LB_OPEN_PUNCTUATION = 20;
    public const LB_POSTFIX_NUMERIC = 21;
    public const LB_PREFIX_NUMERIC = 22;
    public const LB_QUOTATION = 23;
    public const LB_COMPLEX_CONTEXT = 24;
    public const LB_SURROGATE = 25;
    public const LB_SPACE = 26;
    public const LB_BREAK_SYMBOLS = 27;
    public const LB_ZWSPACE = 28;
    public const LB_NEXT_LINE = 29;
    public const LB_WORD_JOINER = 30;
    public const LB_H2 = 31;
    public const LB_H3 = 32;
    public const LB_JL = 33;
    public const LB_JT = 34;
    public const LB_JV = 35;
    public const LB_CLOSE_PARENTHESIS = 36;
    public const LB_CONDITIONAL_JAPANESE_STARTER = 37;
    public const LB_HEBREW_LETTER = 38;
    public const LB_REGIONAL_INDICATOR = 39;
    public const LB_COUNT = 43;
    public const NT_NONE = 0;
    public const NT_DECIMAL = 1;
    public const NT_DIGIT = 2;
    public const NT_NUMERIC = 3;
    public const NT_COUNT = 4;
    public const HST_NOT_APPLICABLE = 0;
    public const HST_LEADING_JAMO = 1;
    public const HST_VOWEL_JAMO = 2;
    public const HST_TRAILING_JAMO = 3;
    public const HST_LV_SYLLABLE = 4;
    public const HST_LVT_SYLLABLE = 5;
    public const HST_COUNT = 6;
    public const NO_NUMERIC_VALUE = -123456789;

    /**
     * Check a binary Unicode property for a code point
     * @link https://php.net/manual/en/intlchar.hasbinaryproperty.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @param int $property The Unicode property to lookup (see the IntlChar::PROPERTY_* constants).
     * @return bool|null Returns TRUE or FALSE according to the binary Unicode property value for codepoint.
     * Also FALSE if property is out of bounds or if the Unicode version does not have data for the property at all, or not for this code point.
     * Or NULL if <em>codepoint</em> is out of bounds.
     * @since 7.0
     */
    #[Pure]
    #[TentativeType]
    public static function hasBinaryProperty(
        #[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $property
    ): ?bool {}

    /**
     * @link https://php.net/manual/en/intlchar.charage.php
     * Get the "age" of the code point
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return array|null The Unicode version number, as an array. For example, version 1.3.31.2 would be represented as [1, 3, 31, 2].
     * Or NULL if <em>codepoint</em> is out of bounds.
     * @since 7.0
     */
    #[TentativeType]
    public static function charAge(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?array {}

    /**
     * @link https://php.net/manual/en/intlchar.chardigitvalue.php
     * Get the decimal digit value of a decimal digit character
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return int|null The decimal digit value of codepoint, or -1 if it is not a decimal digit character.
     * Or NULL if <em>codepoint</em> is out of bounds.
     * @since 7.0
     */
    #[TentativeType]
    public static function charDigitValue(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?int {}

    /**
     * Get bidirectional category value for a code point
     * @link https://php.net/manual/en/intlchar.chardirection.php
     * @param int|string $codepoint <p>The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")</p>
     * @return int|null <p>The bidirectional category value; one of the following constants:
     * </p>
     * <ul>
     * <li><b> IntlChar::CHAR_DIRECTION_LEFT_TO_RIGHT </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_EUROPEAN_NUMBER </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_EUROPEAN_NUMBER_SEPARATOR </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_EUROPEAN_NUMBER_TERMINATOR </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_ARABIC_NUMBER </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_COMMON_NUMBER_SEPARATOR </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_BLOCK_SEPARATOR </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_SEGMENT_SEPARATOR </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_WHITE_SPACE_NEUTRAL </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_OTHER_NEUTRAL </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_LEFT_TO_RIGHT_EMBEDDING </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_LEFT_TO_RIGHT_OVERRIDE </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_ARABIC </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_EMBEDDING </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_OVERRIDE </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_POP_DIRECTIONAL_FORMAT </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_DIR_NON_SPACING_MARK </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_BOUNDARY_NEUTRAL </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_FIRST_STRONG_ISOLATE </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_LEFT_TO_RIGHT_ISOLATE </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_ISOLATE </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_POP_DIRECTIONAL_ISOLATE </b></li>
     * <li><b> IntlChar::CHAR_DIRECTION_CHAR_DIRECTION_COUNT </b></li>
     * </ul>
     * Or NULL if <em>codepoint</em> is out of bounds.
     * @since 7.0
     */
    #[TentativeType]
    public static function charDirection(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?int {}

    /**
     * @link https://php.net/manual/en/intlchar.charfromname.php
     * Find Unicode character by name and return its code point value
     * @param string $name <p>Full name of the Unicode character.</p>
     * @param int $type [optional] <p>
     * Which set of names to use for the lookup. Can be any of these constants:
     * </p><ul>
     * <li><b> IntlChar::UNICODE_CHAR_NAME </b> (default)</li>
     * <li><b> IntlChar::UNICODE_10_CHAR_NAME </b></li>
     * <li><b> IntlChar::EXTENDED_CHAR_NAME </b></li>
     * <li><b> IntlChar::CHAR_NAME_ALIAS </b></li>
     * <li><b> IntlChar::CHAR_NAME_CHOICE_COUNT </b></li>
     * </ul>
     * @return int|null The Unicode value of the code point with the given name (as an integer), or NULL if there is no such code point.
     * @since 7.0
     */
    #[TentativeType]
    public static function charFromName(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $type = IntlChar::UNICODE_CHAR_NAME
    ): ?int {}

    /**
     * @link https://php.net/manual/en/intlchar.charmirror.php
     * Get the "mirror-image" character for a code point
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return int|string|null Returns another Unicode code point that may serve as a mirror-image substitute, or codepoint itself if there is no such mapping or codepoint does not have the Bidi_Mirrored property.
     * The return type will be integer unless the code point was passed as a UTF-8 string, in which case a string will be returned.
     * Or NULL if <em>codepoint</em> will be out of bound.
     */
    #[TentativeType]
    public static function charMirror(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): string|int|null {}

    /**
     * Retrieve the name of a Unicode character
     * @link https://php.net/manual/en/intlchar.charname.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @param int $type [optional] Which set of names to use for the lookup. Can be any of these constants:
     * <ul>
     * <li><b> IntlChar::UNICODE_CHAR_NAME </b> (default)</li>
     * <li><b> IntlChar::UNICODE_10_CHAR_NAME </b></li>
     * <li><b> IntlChar::EXTENDED_CHAR_NAME </b></li>
     * <li><b> IntlChar::CHAR_NAME_ALIAS </b></li>
     * <li><b> IntlChar::CHAR_NAME_CHOICE_COUNT </b></li>
     * </ul>
     * @return string|null The corresponding name, or an empty string if there is no name for this character, or NULL if <em>codepoint</em> is out of bounds.
     * @since 7.0
     */
    #[TentativeType]
    public static function charName(
        #[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $type = IntlChar::UNICODE_CHAR_NAME
    ): ?string {}

    /**
     * Get the general category value for a code point
     * @link https://php.net/manual/en/intlchar.chartype.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return int|null Returns the general category type, which may be one of the following constants:
     * </p><ul>
     * <li><b> IntlChar::CHAR_CATEGORY_UNASSIGNED </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_GENERAL_OTHER_TYPES </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_UPPERCASE_LETTER </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_LOWERCASE_LETTER </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_TITLECASE_LETTER </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_MODIFIER_LETTER </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_OTHER_LETTER </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_NON_SPACING_MARK </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_ENCLOSING_MARK </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_COMBINING_SPACING_MARK </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_DECIMAL_DIGIT_NUMBER </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_LETTER_NUMBER </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_OTHER_NUMBER </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_SPACE_SEPARATOR </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_LINE_SEPARATOR </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_PARAGRAPH_SEPARATOR </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_CONTROL_CHAR </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_FORMAT_CHAR </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_PRIVATE_USE_CHAR </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_SURROGATE </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_DASH_PUNCTUATION </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_START_PUNCTUATION </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_END_PUNCTUATION </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_CONNECTOR_PUNCTUATION </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_OTHER_PUNCTUATION </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_MATH_SYMBOL </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_CURRENCY_SYMBOL </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_MODIFIER_SYMBOL </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_OTHER_SYMBOL </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_INITIAL_PUNCTUATION </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_FINAL_PUNCTUATION </b></li>
     * <li><b> IntlChar::CHAR_CATEGORY_CHAR_CATEGORY_COUNT </b></li></ul>
     * <p>Or NULL if <em>codepoint</em> is out of bound.</p
     * @since 7.0
     */
    #[TentativeType]
    public static function charType(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?int {}

    /**
     * Return Unicode character by code point value
     * @link https://php.net/manual/en/intlchar.chr.php
     * @param mixed $codepoint <p>The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")</p>
     * @return string|null A string containing the single character specified by the Unicode code point value.
     * Or NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function chr(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?string {}

    /**
     * Get the decimal digit value of a code point for a given radix
     * @link https://php.net/manual/en/intlchar.digit.php
     * @param int|string $codepoint <p>The integer codepoint value (e.g. <em>0x2603</em> for <em>U+2603 SNOWMAN</em>), or the character encoded as a UTF-8 string (e.g. <em>"\u{2603}"</em>)</p>
     * @param int $base <p>The radix (defaults to 10).</p>
     * @return int|false|null Returns the numeric value represented by the character in the specified radix,
     * or <b>FALSE</b> if there is no value or if the value exceeds the radix,
     * or <b>NULL</b> if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function digit(
        #[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $base = 10
    ): int|false|null {}

    /**
     * Enumerate all assigned Unicode characters within a range
     * @link https://php.net/manual/en/intlchar.enumcharnames.php
     * @param int|string $start The first code point in the enumeration range.
     * @param int|string $end One more than the last code point in the enumeration range (the first one after the range).
     * @param callable $callback <p>
     * The function that is to be called for each character name.  The following three arguments will be passed into it:
     * </p><ul>
     * <li>integer <em>$codepoint</em> - The numeric code point value</li>
     * <li>integer <em>$nameChoice</em> - The same value as the <b>nameChoice</b> parameter below</li>
     * <li>string <em>$name</em> - The name of the character</li>
     * </ul>
     * @param int $type [optional]  <p>
     * Selector for which kind of names to enumerate.  Can be any of these constants:
     * </p><ul>
     * <li><b>IntlChar::UNICODE_CHAR_NAME</b> (default)</li>
     * <li><b>IntlChar::UNICODE_10_CHAR_NAME</b></li>
     * <li><b>IntlChar::EXTENDED_CHAR_NAME</b></li>
     * <li><b>IntlChar::CHAR_NAME_ALIAS</b></li>
     * <li><b>IntlChar::CHAR_NAME_CHOICE_COUNT</b></li>
     * </ul>
     * @since 7.0
     */
    #[TentativeType]
    public static function enumCharNames(
        #[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $start,
        #[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $end,
        #[LanguageLevelTypeAware(['8.0' => 'callable'], default: '')] $callback,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $type = IntlChar::UNICODE_CHAR_NAME
    ): ?bool {}

    /**
     * Enumerate all code points with their Unicode general categories
     * @link https://php.net/manual/en/intlchar.enumchartypes.php
     * @param callable $callback <p>
     * The function that is to be called for each contiguous range of code points with the same general category.
     * The following three arguments will be passed into it:
     * </p><ul>
     * <li>integer <em>$start</em> - The starting code point of the range</li>
     * <li>integer <em>$end</em> - The ending code point of the range</li>
     * <li>integer <em>$name</em> - The category type (one of the <em>IntlChar::CHAR_CATEGORY_*</em> constants)</li>
     * </ul>
     * @since 7.0
     */
    #[TentativeType]
    public static function enumCharTypes(
        #[PhpStormStubsElementAvailable(from: '7.0', to: '7.4')] $callback = null,
        #[PhpStormStubsElementAvailable(from: '8.0')] callable $callback
    ): void {}

    /**
     * Perform case folding on a code point
     * @link https://php.net/manual/en/intlchar.foldcase.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @param int $options [optional] Either IntlChar::FOLD_CASE_DEFAULT (default) or IntlChar::FOLD_CASE_EXCLUDE_SPECIAL_I.
     * @return int|string|null Returns the Simple_Case_Folding of the code point, if any; otherwise the code point itself.
     * Returns NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function foldCase(
        #[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $options = IntlChar::FOLD_CASE_DEFAULT
    ): string|int|null {}

    /**
     * Get character representation for a given digit and radix
     * @link https://php.net/manual/en/intlchar.fordigit.php
     * @param int $digit <p>The number to convert to a character.</p>
     * @param int $base [optional] <p>The radix (defaults to 10).</p>
     * @return int The character representation (as a string) of the specified digit in the specified radix.
     * @since 7.0
     */
    #[TentativeType]
    public static function forDigit(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $digit,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $base = 10
    ): int {}

    /**
     * Get the paired bracket character for a code point
     * @link https://php.net/manual/en/intlchar.getbidipairedbracket.php
     * @param int|string $codepoint <p>The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")</p>
     * @return int|string|null  Returns the paired bracket code point, or <em>codepoint</em> itself if there is no such mapping.
     * The return type will be integer unless the code point was passed as a UTF-8 string, in which case a string will be returned.
     * Or NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function getBidiPairedBracket(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): string|int|null {}

    /**
     * Get the Unicode allocation block containing a code point
     * @link https://php.net/manual/en/intlchar.getblockcode.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return int|null Returns the block value for <em>codepoint</em>, or NULL if <em>codepoint</em> is out of bound.
     * See the <em>IntlChar::BLOCK_CODE_*</em> constants for possible return values.
     * @since 7.0
     */
    #[TentativeType]
    public static function getBlockCode(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?int {}

    /**
     * Get the combining class of a code point
     * @link https://php.net/manual/en/intlchar.getcombiningclass.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return int|null Returns the combining class of the character.
     * Or NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function getCombiningClass(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?int {}

    /**
     * Get the FC_NFKC_Closure property for a code point
     * @link https://php.net/manual/en/intlchar.getfc-nfkc-closure.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return string|false|null Returns the FC_NFKC_Closure property string for the codepoint, or an empty string if there is none,
     * or NULL if <em>codepoint</em> is out of bound,
     * or FALSE if there was an error.
     * @since 7.0
     */
    #[TentativeType]
    public static function getFC_NFKC_Closure(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): string|false|null {}

    /**
     * Get the max value for a Unicode property
     * @link https://php.net/manual/en/intlchar.getintpropertymaxvalue.php
     * @param int $property The Unicode property to lookup (see the IntlChar::PROPERTY_* constants).
     * @return int The maximum value returned by {@see IntlChar::getIntPropertyValue()} for a Unicode property. <=0 if the property selector is out of range.
     * @since 7.0
     */
    #[TentativeType]
    public static function getIntPropertyMaxValue(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $property): int {}

    /**
     * Get the min value for a Unicode property
     * @link https://php.net/manual/en/intlchar.getintpropertyminvalue.php
     * @param int $property The Unicode property to lookup (see the IntlChar::PROPERTY_* constants).
     * @return int The minimum value returned by {@see IntlChar::getIntPropertyValue()} for a Unicode property. 0 if the property selector is out of range.
     * @since 7.0
     */
    #[TentativeType]
    public static function getIntPropertyMinValue(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $property): int {}

    /**
     * Get the value for a Unicode property for a code point
     * @link https://php.net/manual/en/intlchar.getintpropertyvalue.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @param int $property The Unicode property to lookup (see the IntlChar::PROPERTY_* constants).
     * @return int|null <p>
     * Returns the numeric value that is directly the property value or, for enumerated properties, corresponds to the
     * numeric value of the enumerated constant of the respective property value enumeration type.
     * </p>
     * <p>
     * Returns <em>0</em> or <em>1</em> (for <b>FALSE</b><b>/</b><b>TRUE</B>) for binary Unicode properties.
     * </p>
     * <p>
     * Returns a bit-mask for mask properties.
     * </p>
     * <p>
     * Returns <em>0</em> if <em>property</em> is out of bounds or if the Unicode version does not
     * have data for the property at all, or not for this code point.
     * </p>
     * <p>
     * Returns NULL if <em>codepoint</em> is out of bound.
     * </p>
     * @since 7.0
     */
    #[TentativeType]
    public static function getIntPropertyValue(
        #[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $property
    ): ?int {}

    /**
     * Get the numeric value for a Unicode code point
     * @link https://php.net/manual/en/intlchar.getnumericvalue.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return float|null Numeric value of codepoint, or float(-123456789) if none is defined, or NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function getNumericValue(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?float {}

    /**
     * Get the property constant value for a given property name
     * @link https://php.net/manual/en/intlchar.getpropertyenum.php
     * @param string $alias The property name to be matched. The name is compared using "loose matching" as described in PropertyAliases.txt.
     * @return int Returns an IntlChar::PROPERTY_ constant value, or <b>IntlChar::PROPERTY_INVALID_CODE</b> if the given name does not match any property.
     * @since 7.0
     */
    #[TentativeType]
    public static function getPropertyEnum(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $alias): int {}

    /**
     * Get the Unicode name for a property
     * @link https://php.net/manual/en/intlchar.getpropertyname.php
     * @param int $property <p>The Unicode property to lookup (see the IntlChar::PROPERTY_* constants).</p>
     * <p><b>IntlChar::PROPERTY_INVALID_CODE</b> should not be used. Also, if property is out of range, FALSE is returned.</p>
     * @param int $type <p> Selector for which name to get. If out of range, FALSE is returned.</p>
     * <p>All properties have a long name. Most have a short name, but some do not. Unicode allows for additional names; if present these will be returned by adding 1, 2, etc. to <b>IntlChar::LONG_PROPERTY_NAME</b>.</p>
     * @return string|false <p>
     * Returns the name, or <b>FALSE</b> if either the <em>property</em> or the <em>nameChoice</em>
     * is out of range.
     * </p>
     * <p>
     * If a given <em>nameChoice</em> returns <b>FALSE</b>, then all larger values of
     * <em>nameChoice</em> will return <b>FALSE</b>, with one exception: if <b>FALSE</b> is returned for
     * <b>IntlChar::SHORT_PROPERTY_NAME</b>, then <b>IntlChar::LONG_PROPERTY_NAME</b>
     * (and higher) may still return a non-<b>FALSE</b> value.
     * </p>
     * @since 7.0
     */
    #[TentativeType]
    public static function getPropertyName(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $property,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $type = IntlChar::LONG_PROPERTY_NAME
    ): string|false {}

    /**
     * Get the property value for a given value name
     * @link https://php.net/manual/en/intlchar.getpropertyvalueenum.php
     * @param int $property <p>The Unicode property to lookup (see the IntlChar::PROPERTY_* constants).
     * If out of range, or this method doesn't work with the given value, IntlChar::PROPERTY_INVALID_CODE is returned</p>
     * @param string $name <p> The value name to be matched. The name is compared using "loose matching" as described in PropertyValueAliases.txt.</p>
     * @return int Returns the corresponding value integer, or IntlChar::PROPERTY_INVALID_CODE if the given name does not match any value of the given property, or if the property is invalid.
     * @since 7.0
     */
    #[TentativeType]
    public static function getPropertyValueEnum(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $property,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name
    ): int {}

    /**
     * Get the Unicode name for a property value
     * @link https://php.net/manual/en/intlchar.getpropertyvaluename.php
     * @param int $property <p>
     * The Unicode property to lookup (see the IntlChar::PROPERTY_* constants).
     * If out of range, or this method doesn't work with the given value, FALSE is returned.
     * </p>
     * @param int $value <p>
     * Selector for a value for the given property. If out of range, <b>FALSE</b> is returned.
     * </p>
     * <p>
     * In general, valid values range from <em>0</em> up to some maximum. There are a couple exceptions:
     * </p><ul>
     * <li>
     * <b>IntlChar::PROPERTY_BLOCK</b> values begin at the non-zero value <b>IntlChar::BLOCK_CODE_BASIC_LATIN</b>
     * </li>
     * <li>
     * <b>IntlChar::PROPERTY_CANONICAL_COMBINING_CLASS</b> values are not contiguous and range from 0..240.
     * </li>
     * </ul>
     * @param int $type [optional] <p>
     * Selector for which name to get. If out of range, FALSE is returned.
     * All values have a long name. Most have a short name, but some do not. Unicode allows for additional names; if present these will be returned by adding 1, 2, etc. to IntlChar::LONG_PROPERTY_NAME.
     * </p>
     * @return  string|false Returns the name, or FALSE if either the property or the nameChoice is out of range.
     * If a given nameChoice returns FALSE, then all larger values of nameChoice will return FALSE, with one exception: if FALSE is returned for IntlChar::SHORT_PROPERTY_NAME, then IntlChar::LONG_PROPERTY_NAME (and higher) may still return a non-FALSE value.
     * @since 7.0
     */
    #[TentativeType]
    public static function getPropertyValueName(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $property,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $value,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $type = IntlChar::LONG_PROPERTY_NAME
    ): string|false {}

    /**
     * Get the Unicode version
     * @link https://php.net/manual/en/intlchar.getunicodeversion.php
     * @return array An array containing the Unicode version number.
     * @since 7.0
     */
    #[TentativeType]
    public static function getUnicodeVersion(): array {}

    /**
     * Check if code point is an alphanumeric character
     * @link https://php.net/manual/en/intlchar.isalnum.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is an alphanumeric character, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isalnum(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is a letter character
     * @link https://php.net/manual/en/intlchar.isalpha.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is a letter character, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isalpha(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is a base character
     * @link https://php.net/manual/en/intlchar.isbase.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is a base character, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isbase(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is a "blank" or "horizontal space" character
     * @link https://php.net/manual/en/intlchar.isblank.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is either a "blank" or "horizontal space" character, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isblank(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is a control character
     * @link https://php.net/manual/en/intlchar.iscntrl.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is a control character, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function iscntrl(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check whether the code point is defined
     * @link https://php.net/manual/en/intlchar.isdefined.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is a defined character, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isdefined(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is a digit character
     * @link https://php.net/manual/en/intlchar.isdigit.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is a digit character, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isdigit(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is a graphic character
     * @link https://php.net/manual/en/intlchar.isgraph.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is a "graphic" character, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isgraph(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is an ignorable character
     * @link https://php.net/manual/en/intlchar.isidignorable.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is ignorable in identifiers, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isIDIgnorable(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is permissible in an identifier
     * @link https://php.net/manual/en/intlchar.isidpart.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is the code point may occur in an identifier, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isIDPart(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is permissible as the first character in an identifier
     * @link https://php.net/manual/en/intlchar.isidstart.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint may start an identifier, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isIDStart(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is an ISO control code
     * @link https://php.net/manual/en/intlchar.isisocontrol.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is an ISO control code, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isISOControl(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is permissible in a Java identifier
     * @link https://php.net/manual/en/intlchar.isjavaidpart.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint may occur in a Java identifier, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isJavaIDPart(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is permissible as the first character in a Java identifier
     * @link https://php.net/manual/en/intlchar.isjavaidstart.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint may start a Java identifier, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isJavaIDStart(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is a space character according to Java
     * @link https://php.net/manual/en/intlchar.isjavaspacechar.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is a space character according to Java, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isJavaSpaceChar(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is a lowercase letter
     * @link https://php.net/manual/en/intlchar.islower.php
     * @param int|string $codepoint <p>The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN),
     * or the character encoded as a UTF-8 string (e.g. "\u{2603}")</p>
     * @return bool|null Returns TRUE if codepoint is an Ll lowercase letter, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function islower(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point has the Bidi_Mirrored property
     * @link https://php.net/manual/en/intlchar.ismirrored.php
     * @param int|string $codepoint <p>The integer codepoint value (e.g. <em>0x2603</em> for <em>U+2603 SNOWMAN</em>), or the character encoded as a UTF-8 string (e.g. <em>"\u{2603}"</em>)</p>
     * @return bool|null Returns TRUE if codepoint has the Bidi_Mirrored property, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isMirrored(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is a printable character
     * @link https://php.net/manual/en/intlchar.isprint.php
     * @param int|string $codepoint <p>The integer codepoint value (e.g. <em>0x2603</em> for <em>U+2603 SNOWMAN</em>), or the character encoded as a UTF-8 string (e.g. <em>"\u{2603}"</em>)</p>
     * @return bool|null Returns TRUE if codepoint is a printable character, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isprint(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is punctuation character
     * @link https://php.net/manual/en/intlchar.ispunct.php
     * @param int|string $codepoint <p>The integer codepoint value (e.g. <em>0x2603</em> for <em>U+2603 SNOWMAN</em>),
     * or the character encoded as a UTF-8 string (e.g. <em>"\u{2603}"</em>)</p>
     * @return bool|null Returns TRUE if codepoint is a punctuation character, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function ispunct(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is a space character
     * @link https://php.net/manual/en/intlchar.isspace.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is a space character, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isspace(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is a titlecase letter
     * @link https://php.net/manual/en/intlchar.istitle.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is a titlecase letter, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function istitle(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point has the Alphabetic Unicode property
     * @link https://php.net/manual/en/intlchar.isualphabetic.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint has the Alphabetic Unicode property, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isUAlphabetic(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point has the Lowercase Unicode property
     * @link https://php.net/manual/en/intlchar.isulowercase.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint has the Lowercase Unicode property, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isULowercase(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point has the general category "Lu" (uppercase letter)
     * @link https://php.net/manual/en/intlchar.isupper.php
     * @param int|string $codepoint <p>The integer codepoint value (e.g. <em>0x2603</em> for <em>U+2603 SNOWMAN</em>),
     * or the character encoded as a UTF-8 string (e.g. <em>"\u{2603}"</em>)</p>
     * @return bool|null Returns TRUE if codepoint is an Lu uppercase letter, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isupper(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point has the Uppercase Unicode property
     * @link https://php.net/manual/en/intlchar.isuuppercase.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint has the Uppercase Unicode property, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isUUppercase(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point has the White_Space Unicode property
     * @link https://php.net/manual/en/intlchar.isuwhitespace.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint has the White_Space Unicode property, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isUWhiteSpace(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is a whitespace character according to ICU
     * @link https://php.net/manual/en/intlchar.iswhitespace.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is a whitespace character according to ICU, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isWhitespace(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Check if code point is a hexadecimal digit
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return bool|null Returns TRUE if codepoint is a hexadecimal character, FALSE if not, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function isxdigit(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): ?bool {}

    /**
     * Return Unicode code point value of character
     * @link https://php.net/manual/en/intlchar.ord.php
     * @param int|string $character <p>A Unicode character.</p>
     * @return int|null Returns the Unicode code point value as an integer, NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function ord(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $character): ?int {}

    /**
     * Make Unicode character lowercase
     * @link https://php.net/manual/en/intlchar.tolower.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return int|string|null Returns the Simple_Lowercase_Mapping of the code point, if any; otherwise the code point itself.
     * The return type will be integer unless the code point was passed as a UTF-8 string, in which case a string will be returned.
     * Or NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function tolower(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): string|int|null {}

    /**
     * Make Unicode character titlecase
     * @link https://php.net/manual/en/intlchar.totitle.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return int|string|null  Returns the Simple_Titlecase_Mapping of the code point, if any; otherwise the code point itself.
     * The return type will be integer unless the code point was passed as a UTF-8 string, in which case a string will be returned.
     * Or NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function totitle(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): string|int|null {}

    /**
     * Make Unicode character uppercase
     * @link https://php.net/manual/en/intlchar.toupper.php
     * @param int|string $codepoint The integer codepoint value (e.g. 0x2603 for U+2603 SNOWMAN), or the character encoded as a UTF-8 string (e.g. "\u{2603}")
     * @return int|string|null Returns the Simple_Uppercase_Mapping of the code point, if any; otherwise the code point itself.
     * The return type will be integer unless the code point was passed as a UTF-8 string, in which case a string will be returned.
     * Or NULL if <em>codepoint</em> is out of bound.
     * @since 7.0
     */
    #[TentativeType]
    public static function toupper(#[LanguageLevelTypeAware(['8.0' => 'int|string'], default: '')] $codepoint): string|int|null {}
}
