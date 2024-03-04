<?php 

/** @generate-function-entries */
class IntlDateFormatter
{
    /**
     * @param IntlTimeZone|DateTimeZone|string|null $timezone
     * @param IntlCalendar|int|null $calendar
     */
    #[\Until('8.1')]
    public function __construct(?string $locale, int $dateType, int $timeType, $timezone = null, $calendar = null, ?string $pattern = null)
    {
    }
    /**
     * @param (IntlTimeZone | DateTimeZone | string | null) $timezone
     * @tentative-return-type
     * @alias datefmt_create
     * @return (IntlDateFormatter | null)
     */
    #[\Until('8.1')]
    public static function create(?string $locale, int $dateType, int $timeType, $timezone = null, IntlCalendar|int|null $calendar = null, ?string $pattern = null)
    {
    }
    /**
     * @param IntlTimeZone|DateTimeZone|string|null $timezone
     * @param IntlCalendar|int|null $calendar
     */
    #[\Since('8.1')]
    public function __construct(?string $locale, int $dateType = IntlDateFormatter::FULL, int $timeType = IntlDateFormatter::FULL, $timezone = null, $calendar = null, ?string $pattern = null)
    {
    }
    /**
     * @param IntlTimeZone|DateTimeZone|string|null $timezone
     * @tentative-return-type
     * @alias datefmt_create
     */
    #[\Since('8.1')]
    public static function create(?string $locale, int $dateType = IntlDateFormatter::FULL, int $timeType = IntlDateFormatter::FULL, $timezone = null, IntlCalendar|int|null $calendar = null, ?string $pattern = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias datefmt_get_datetype
     * @return (int | false)
     */
    public function getDateType()
    {
    }
    /**
     * @tentative-return-type
     * @alias datefmt_get_timetype
     * @return (int | false)
     */
    public function getTimeType()
    {
    }
    /**
     * @tentative-return-type
     * @alias datefmt_get_calendar
     * @return (int | false)
     */
    public function getCalendar()
    {
    }
    /**
     * @tentative-return-type
     * @alias datefmt_set_calendar
     * @return bool
     */
    public function setCalendar(IntlCalendar|int|null $calendar)
    {
    }
    /**
     * @tentative-return-type
     * @alias datefmt_get_timezone_id
     * @return (string | false)
     */
    public function getTimeZoneId()
    {
    }
    /**
     * @tentative-return-type
     * @alias datefmt_get_calendar_object
     * @return (IntlCalendar | false | null)
     */
    public function getCalendarObject()
    {
    }
    /**
     * @tentative-return-type
     * @alias datefmt_get_timezone
     * @return (IntlTimeZone | false)
     */
    public function getTimeZone()
    {
    }
    /**
     * @param (IntlTimeZone | DateTimeZone | string | null) $timezone
     * @tentative-return-type
     * @alias datefmt_set_timezone
     * @return (bool | null)
     */
    public function setTimeZone($timezone)
    {
    }
    /**
     * @tentative-return-type
     * @alias datefmt_set_pattern
     * @return bool
     */
    public function setPattern(string $pattern)
    {
    }
    /**
     * @tentative-return-type
     * @alias datefmt_get_pattern
     * @return (string | false)
     */
    public function getPattern()
    {
    }
    /**
     * @tentative-return-type
     * @alias datefmt_get_locale
     * @return (string | false)
     */
    public function getLocale(int $type = ULOC_ACTUAL_LOCALE)
    {
    }
    /**
     * @tentative-return-type
     * @alias datefmt_set_lenient
     * @return void
     */
    public function setLenient(bool $lenient)
    {
    }
    /**
     * @tentative-return-type
     * @alias datefmt_is_lenient
     * @return bool
     */
    public function isLenient()
    {
    }
    /**
     * @param (IntlCalendar | DateTimeInterface | array | string | int | float) $datetime
     * @tentative-return-type
     * @alias datefmt_format
     * @return (string | false)
     */
    public function format($datetime)
    {
    }
    /**
     * @param (IntlCalendar | DateTimeInterface) $datetime
     * @param (array | int | string | null) $format
     * @tentative-return-type
     * @alias datefmt_format_object
     * @return (string | false)
     */
    public static function formatObject($datetime, $format = null, ?string $locale = null)
    {
    }
    /**
     * @param int $offset
     * @tentative-return-type
     * @alias datefmt_parse
     * @return (int | float | false)
     */
    public function parse(string $string, &$offset = null)
    {
    }
    /**
     * @param int $offset
     * @return array|false
     * @alias datefmt_localtime
     */
    public function localtime(string $string, &$offset = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias datefmt_get_error_code
     * @return int
     */
    public function getErrorCode()
    {
    }
    /**
     * @tentative-return-type
     * @alias datefmt_get_error_message
     * @return string
     */
    public function getErrorMessage()
    {
    }
}