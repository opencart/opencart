<?php 

/** @generate-function-entries */
class MessageFormatter
{
    public function __construct(string $locale, string $pattern)
    {
    }
    /**
     * @tentative-return-type
     * @alias msgfmt_create
     * @return (MessageFormatter | null)
     */
    public static function create(string $locale, string $pattern)
    {
    }
    /**
     * @tentative-return-type
     * @alias msgfmt_format
     * @return (string | false)
     */
    public function format(array $values)
    {
    }
    /**
     * @tentative-return-type
     * @alias msgfmt_format_message
     * @return (string | false)
     */
    public static function formatMessage(string $locale, string $pattern, array $values)
    {
    }
    /**
     * @return array|false
     * @alias msgfmt_parse
     */
    public function parse(string $string)
    {
    }
    /**
     * @return array|false
     * @alias msgfmt_parse_message
     */
    public static function parseMessage(string $locale, string $pattern, string $message)
    {
    }
    /**
     * @tentative-return-type
     * @alias msgfmt_set_pattern
     * @return bool
     */
    public function setPattern(string $pattern)
    {
    }
    /**
     * @tentative-return-type
     * @alias msgfmt_get_pattern
     * @return (string | false)
     */
    public function getPattern()
    {
    }
    /**
     * @tentative-return-type
     * @alias msgfmt_get_locale
     * @return string
     */
    public function getLocale()
    {
    }
    /**
     * @tentative-return-type
     * @alias msgfmt_get_error_code
     * @return int
     */
    public function getErrorCode()
    {
    }
    /**
     * @tentative-return-type
     * @alias msgfmt_get_error_message
     * @return string
     */
    public function getErrorMessage()
    {
    }
}