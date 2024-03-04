<?php 

class IntlGregorianCalendar extends \IntlCalendar
{
    /**
     * @param DateTimeZone|IntlTimeZone|string|int|null $timezoneOrYear
     * @param string|int|null $localeOrMonth
     * @param int $day
     * @param int $hour
     * @param int $minute
     * @param int $second
     */
    public function __construct($timezoneOrYear = UNKNOWN, $localeOrMonth = UNKNOWN, $day = UNKNOWN, $hour = UNKNOWN, $minute = UNKNOWN, $second = UNKNOWN)
    {
    }
    /**
     * @tentative-return-type
     * @alias intlgregcal_set_gregorian_change
     * @return bool
     */
    public function setGregorianChange(float $timestamp)
    {
    }
    /**
     * @tentative-return-type
     * @alias intlgregcal_get_gregorian_change
     * @return float
     */
    public function getGregorianChange()
    {
    }
    /**
     * @tentative-return-type
     * @alias intlgregcal_is_leap_year
     * @return bool
     */
    public function isLeapYear(int $year)
    {
    }
}