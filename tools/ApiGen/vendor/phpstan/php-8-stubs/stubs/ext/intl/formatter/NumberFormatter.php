<?php 

/** @generate-function-entries */
class NumberFormatter
{
    public function __construct(string $locale, int $style, ?string $pattern = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias numfmt_create
     * @return (NumberFormatter | null)
     */
    public static function create(string $locale, int $style, ?string $pattern = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias numfmt_format
     * @return (string | false)
     */
    public function format(int|float $num, int $type = NumberFormatter::TYPE_DEFAULT)
    {
    }
    /**
     * @param int $offset
     * @tentative-return-type
     * @alias numfmt_parse
     * @return (int | float | false)
     */
    public function parse(string $string, int $type = NumberFormatter::TYPE_DOUBLE, &$offset = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias numfmt_format_currency
     * @return (string | false)
     */
    public function formatCurrency(float $amount, string $currency)
    {
    }
    /**
     * @param string $currency
     * @param int $offset
     * @tentative-return-type
     * @alias numfmt_parse_currency
     * @return (float | false)
     */
    public function parseCurrency(string $string, &$currency, &$offset = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias numfmt_set_attribute
     * @return bool
     */
    public function setAttribute(int $attribute, int|float $value)
    {
    }
    /**
     * @tentative-return-type
     * @alias numfmt_get_attribute
     * @return (int | float | false)
     */
    public function getAttribute(int $attribute)
    {
    }
    /**
     * @tentative-return-type
     * @alias numfmt_set_text_attribute
     * @return bool
     */
    public function setTextAttribute(int $attribute, string $value)
    {
    }
    /**
     * @tentative-return-type
     * @alias numfmt_get_text_attribute
     * @return (string | false)
     */
    public function getTextAttribute(int $attribute)
    {
    }
    /**
     * @tentative-return-type
     * @alias numfmt_set_symbol
     * @return bool
     */
    public function setSymbol(int $symbol, string $value)
    {
    }
    /**
     * @tentative-return-type
     * @alias numfmt_get_symbol
     * @return (string | false)
     */
    public function getSymbol(int $symbol)
    {
    }
    /**
     * @tentative-return-type
     * @alias numfmt_set_pattern
     * @return bool
     */
    public function setPattern(string $pattern)
    {
    }
    /**
     * @tentative-return-type
     * @alias numfmt_get_pattern
     * @return (string | false)
     */
    public function getPattern()
    {
    }
    /**
     * @tentative-return-type
     * @alias numfmt_get_locale
     * @return (string | false)
     */
    public function getLocale(int $type = ULOC_ACTUAL_LOCALE)
    {
    }
    /**
     * @tentative-return-type
     * @alias numfmt_get_error_code
     * @return int
     */
    public function getErrorCode()
    {
    }
    /**
     * @tentative-return-type
     * @alias numfmt_get_error_message
     * @return string
     */
    public function getErrorMessage()
    {
    }
}