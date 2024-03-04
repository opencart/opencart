<?php

// Start of date v.5.3.2-0.dotdeb.1
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Pure;

/**
 * Parse about any English textual datetime description into a Unix timestamp
 * @link https://php.net/manual/en/function.strtotime.php
 * @param string $datetime <p>
 * The string to parse. Before PHP 5.0.0, microseconds weren't allowed in
 * the time, since PHP 5.0.0 they are allowed but ignored.
 * </p>
 * @param int|null $baseTimestamp [optional] <p>
 * Default value: null
 * The timestamp which is used as a base for the calculation of relative
 * dates.
 * </p>
 * @return int|false a timestamp on success, false otherwise. Previous to PHP 5.1.0,
 * this function would return -1 on failure.
 */
#[Pure(true)]
function strtotime(string $datetime, ?int $baseTimestamp): int|false {}

/**
 * Format a local time/date
 * @link https://php.net/manual/en/function.date.php
 * @param string $format <p>
 * The format of the outputted date string. See the formatting
 * options below. There are also several
 * predefined date constants
 * that may be used instead, so for example DATE_RSS
 * contains the format string 'D, d M Y H:i:s'.
 * </p>
 * <p>
 * <br>
 * The following characters are recognized in the
 * format parameter string:
 * <br><br>
 * <table>
 * <tr valign="top" colspan="3" bgcolor="silver">
 * <th>format character</th>
 * <th>Description</th>
 * <th>Example returned values</th>
 * </tr>
 * <tr valign="top">
 * <td><b>Day</b></td>
 * <td>---</td>
 * <td>---</td>
 * </tr>
 * <tr valign="top">
 * <td>d</td>
 * <td>Day of the month, 2 digits with leading zeros</td>
 * <td>01 to 31</td>
 * </tr>
 * <tr valign="top">
 * <td>D</td>
 * <td>A textual representation of a day, three letters</td>
 * <td>Mon through Sun</td>
 * </tr>
 * <tr valign="top">
 * <td>j</td>
 * <td>Day of the month without leading zeros</td>
 * <td>1 to 31</td>
 * </tr>
 * <tr valign="top">
 * <td>l (lowercase 'L')</td>
 * <td>A full textual representation of the day of the week</td>
 * <td>Sunday through Saturday</td>
 * </tr>
 * <tr valign="top">
 * <td>N</td>
 * <td>ISO-8601 numeric representation of the day of the week (added in
 * PHP 5.1.0)</td>
 * <td>1 (for Monday) through 7 (for Sunday)</td>
 * </tr>
 * <tr valign="top">
 * <td>S</td>
 * <td>English ordinal suffix for the day of the month, 2 characters</td>
 * <td>
 * st, nd, rd or
 * th. Works well with j
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>w</td>
 * <td>Numeric representation of the day of the week</td>
 * <td>0 (for Sunday) through 6 (for Saturday)</td>
 * </tr>
 * <tr valign="top">
 * <td>z</td>
 * <td>The day of the year (starting from 0)</td>
 * <td>0 through 365</td>
 * </tr>
 * <tr valign="top">
 * <td><b>Week</b></td>
 * <td>---</td>
 * <td>---</td>
 * </tr>
 * <tr valign="top">
 * <td>W</td>
 * <td>ISO-8601 week number of year, weeks starting on Monday (added in PHP 4.1.0)</td>
 * <td>Example: 42 (the 42nd week in the year)</td>
 * </tr>
 * <tr valign="top">
 * <td><b>Month</b></td>
 * <td>---</td>
 * <td>---</td>
 * </tr>
 * <tr valign="top">
 * <td>F</td>
 * <td>A full textual representation of a month, such as January or March</td>
 * <td>January through December</td>
 * </tr>
 * <tr valign="top">
 * <td>m</td>
 * <td>Numeric representation of a month, with leading zeros</td>
 * <td>01 through 12</td>
 * </tr>
 * <tr valign="top">
 * <td>M</td>
 * <td>A short textual representation of a month, three letters</td>
 * <td>Jan through Dec</td>
 * </tr>
 * <tr valign="top">
 * <td>n</td>
 * <td>Numeric representation of a month, without leading zeros</td>
 * <td>1 through 12</td>
 * </tr>
 * <tr valign="top">
 * <td>t</td>
 * <td>Number of days in the given month</td>
 * <td>28 through 31</td>
 * </tr>
 * <tr valign="top">
 * <td><b>Year</b></td>
 * <td>---</td>
 * <td>---</td>
 * </tr>
 * <tr valign="top">
 * <td>L</td>
 * <td>Whether it's a leap year</td>
 * <td>1 if it is a leap year, 0 otherwise.</td>
 * </tr>
 * <tr valign="top">
 * <td>o</td>
 * <td>ISO-8601 year number. This has the same value as
 * Y, except that if the ISO week number
 * (W) belongs to the previous or next year, that year
 * is used instead. (added in PHP 5.1.0)</td>
 * <td>Examples: 1999 or 2003</td>
 * </tr>
 * <tr valign="top">
 * <td>Y</td>
 * <td>A full numeric representation of a year, 4 digits</td>
 * <td>Examples: 1999 or 2003</td>
 * </tr>
 * <tr valign="top">
 * <td>y</td>
 * <td>A two digit representation of a year</td>
 * <td>Examples: 99 or 03</td>
 * </tr>
 * <tr valign="top">
 * <td><b>Time</b></td>
 * <td>---</td>
 * <td>---</td>
 * </tr>
 * <tr valign="top">
 * <td>a</td>
 * <td>Lowercase Ante meridiem and Post meridiem</td>
 * <td>am or pm</td>
 * </tr>
 * <tr valign="top">
 * <td>A</td>
 * <td>Uppercase Ante meridiem and Post meridiem</td>
 * <td>AM or PM</td>
 * </tr>
 * <tr valign="top">
 * <td>B</td>
 * <td>Swatch Internet time</td>
 * <td>000 through 999</td>
 * </tr>
 * <tr valign="top">
 * <td>g</td>
 * <td>12-hour format of an hour without leading zeros</td>
 * <td>1 through 12</td>
 * </tr>
 * <tr valign="top">
 * <td>G</td>
 * <td>24-hour format of an hour without leading zeros</td>
 * <td>0 through 23</td>
 * </tr>
 * <tr valign="top">
 * <td>h</td>
 * <td>12-hour format of an hour with leading zeros</td>
 * <td>01 through 12</td>
 * </tr>
 * <tr valign="top">
 * <td>H</td>
 * <td>24-hour format of an hour with leading zeros</td>
 * <td>00 through 23</td>
 * </tr>
 * <tr valign="top">
 * <td>i</td>
 * <td>Minutes with leading zeros</td>
 * <td>00 to 59</td>
 * </tr>
 * <tr valign="top">
 * <td>s</td>
 * <td>Seconds, with leading zeros</td>
 * <td>00 through 59</td>
 * </tr>
 * <tr valign="top">
 * <td>u</td>
 * <td>Microseconds (added in PHP 5.2.2)</td>
 * <td>Example: 654321</td>
 * </tr>
 * <tr valign="top">
 * <td><b>Timezone</b></td>
 * <td>---</td>
 * <td>---</td>
 * </tr>
 * <tr valign="top">
 * <td>e</td>
 * <td>Timezone identifier (added in PHP 5.1.0)</td>
 * <td>Examples: UTC, GMT, Atlantic/Azores</td>
 * </tr>
 * <tr valign="top">
 * <td>I (capital i)</td>
 * <td>Whether or not the date is in daylight saving time</td>
 * <td>1 if Daylight Saving Time, 0 otherwise.</td>
 * </tr>
 * <tr valign="top">
 * <td>O</td>
 * <td>Difference to Greenwich time (GMT) in hours</td>
 * <td>Example: +0200</td>
 * </tr>
 * <tr valign="top">
 * <td>P</td>
 * <td>Difference to Greenwich time (GMT) with colon between hours and minutes (added in PHP 5.1.3)</td>
 * <td>Example: +02:00</td>
 * </tr>
 * <tr valign="top">
 * <td>T</td>
 * <td>Timezone abbreviation</td>
 * <td>Examples: EST, MDT ...</td>
 * </tr>
 * <tr valign="top">
 * <td>Z</td>
 * <td>Timezone offset in seconds. The offset for timezones west of UTC is always
 * negative, and for those east of UTC is always positive.</td>
 * <td>-43200 through 50400</td>
 * </tr>
 * <tr valign="top">
 * <td><b>Full Date/Time</b></td>
 * <td>---</td>
 * <td>---</td>
 * </tr>
 * <tr valign="top">
 * <td>c</td>
 * <td>ISO 8601 date (added in PHP 5)</td>
 * <td>2004-02-12T15:19:21+00:00</td>
 * </tr>
 * <tr valign="top">
 * <td>r</td>
 * <td>RFC 2822 formatted date</td>
 * <td>Example: Thu, 21 Dec 2000 16:01:07 +0200</td>
 * </tr>
 * <tr valign="top">
 * <td>U</td>
 * <td>Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)</td>
 * <td>See also time</td>
 * </tr>
 * </table>
 * </p>
 * <p>
 * Unrecognized characters in the format string will be printed
 * as-is. The Z format will always return
 * 0 when using gmdate.
 * </p>
 * <p>
 * Since this function only accepts integer timestamps the
 * u format character is only useful when using the
 * date_format function with user based timestamps
 * created with date_create.
 * </p>
 * @param int|null $timestamp [optional] Default value: time(). The optional timestamp parameter is an integer Unix timestamp
 * that defaults to the current local time if a timestamp is not given.
 * @return string|false a formatted date string. If a non-numeric value is used for
 * timestamp, false is returned and an
 * E_WARNING level error is emitted.
 */
#[Pure(true)]
#[LanguageLevelTypeAware(["8.0" => "string"], default: "string|false")]
function date(string $format, ?int $timestamp) {}

/**
 * Format a local time/date as integer
 * @link https://php.net/manual/en/function.idate.php
 * @param string $format <p>
 * <table>
 * The following characters are recognized in the
 * format parameter string
 * <tr valign="top">
 * <td>format character</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>B</td>
 * <td>Swatch Beat/Internet Time</td>
 * </tr>
 * <tr valign="top">
 * <td>d</td>
 * <td>Day of the month</td>
 * </tr>
 * <tr valign="top">
 * <td>h</td>
 * <td>Hour (12 hour format)</td>
 * </tr>
 * <tr valign="top">
 * <td>H</td>
 * <td>Hour (24 hour format)</td>
 * </tr>
 * <tr valign="top">
 * <td>i</td>
 * <td>Minutes</td>
 * </tr>
 * <tr valign="top">
 * <td>I (uppercase i)</td>
 * <td>returns 1 if DST is activated,
 * 0 otherwise</td>
 * </tr>
 * <tr valign="top">
 * <td>L (uppercase l)</td>
 * <td>returns 1 for leap year,
 * 0 otherwise</td>
 * </tr>
 * <tr valign="top">
 * <td>m</td>
 * <td>Month number</td>
 * </tr>
 * <tr valign="top">
 * <td>s</td>
 * <td>Seconds</td>
 * </tr>
 * <tr valign="top">
 * <td>t</td>
 * <td>Days in current month</td>
 * </tr>
 * <tr valign="top">
 * <td>U</td>
 * <td>Seconds since the Unix Epoch - January 1 1970 00:00:00 UTC -
 * this is the same as time</td>
 * </tr>
 * <tr valign="top">
 * <td>w</td>
 * <td>Day of the week (0 on Sunday)</td>
 * </tr>
 * <tr valign="top">
 * <td>W</td>
 * <td>ISO-8601 week number of year, weeks starting on
 * Monday</td>
 * </tr>
 * <tr valign="top">
 * <td>y</td>
 * <td>Year (1 or 2 digits - check note below)</td>
 * </tr>
 * <tr valign="top">
 * <td>Y</td>
 * <td>Year (4 digits)</td>
 * </tr>
 * <tr valign="top">
 * <td>z</td>
 * <td>Day of the year</td>
 * </tr>
 * <tr valign="top">
 * <td>Z</td>
 * <td>Timezone offset in seconds</td>
 * </tr>
 * </table>
 * </p>
 * @param int|null $timestamp [optional]
 * @return int|false an integer.
 * <p>
 * As idate always returns an integer and
 * as they can't start with a "0", idate may return
 * fewer digits than you would expect. See the example below.
 * </p>
 */
#[Pure(true)]
function idate(string $format, ?int $timestamp): int|false {}

/**
 * Format a GMT/UTC date/time
 * @link https://php.net/manual/en/function.gmdate.php
 * @param string $format <p>
 * The format of the outputted date string. See the formatting
 * options for the date function.
 * </p>
 * @param int|null $timestamp [optional]
 * @return string|false a formatted date string. If a non-numeric value is used for
 * timestamp, false is returned and an
 * E_WARNING level error is emitted.
 */
#[Pure(true)]
#[LanguageLevelTypeAware(["8.0" => "string"], default: "string|false")]
function gmdate(string $format, ?int $timestamp) {}

/**
 * Get Unix timestamp for a date
 * @link https://php.net/manual/en/function.mktime.php
 * @param int $hour <p>
 * The number of the hour.
 * </p>
 * @param int|null $minute <p>
 * The number of the minute.
 * </p>
 * @param int|null $second <p>
 * The number of seconds past the minute.
 * </p>
 * @param int|null $month <p>
 * The number of the month.
 * </p>
 * @param int|null $day <p>
 * The number of the day.
 * </p>
 * @param int|null $year [optional] <p>
 * The number of the year, may be a two or four digit value,
 * with values between 0-69 mapping to 2000-2069 and 70-100 to
 * 1970-2000. On systems where time_t is a 32bit signed integer, as
 * most common today, the valid range for year
 * is somewhere between 1901 and 2038. However, before PHP 5.1.0 this
 * range was limited from 1970 to 2038 on some systems (e.g. Windows).
 * </p>
 * @param int $is_dst [optional] <p>
 * This parameter can be set to 1 if the time is during daylight savings time (DST),
 * 0 if it is not, or -1 (the default) if it is unknown whether the time is within
 * daylight savings time or not. If it's unknown, PHP tries to figure it out itself.
 * This can cause unexpected (but not incorrect) results.
 * Some times are invalid if DST is enabled on the system PHP is running on or
 * is_dst is set to 1. If DST is enabled in e.g. 2:00, all times
 * between 2:00 and 3:00 are invalid and mktime returns an undefined
 * (usually negative) value.
 * Some systems (e.g. Solaris 8) enable DST at midnight so time 0:30 of the day when DST
 * is enabled is evaluated as 23:30 of the previous day.
 * </p>
 * <p>
 * As of PHP 5.1.0, this parameter became deprecated. As a result, the
 * new timezone handling features should be used instead.
 * </p>
 * <p>
 * This parameter has been removed in PHP 7.0.0.
 * </p>
 * @return int|false mktime returns the Unix timestamp of the arguments
 * given.
 * If the arguments are invalid, the function returns false (before PHP 5.1
 * it returned -1).
 */
#[Pure(true)]
function mktime(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] int $hour = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] int $hour,
    ?int $minute = null,
    ?int $second = null,
    ?int $month = null,
    ?int $day = null,
    ?int $year = null,
    #[Deprecated('Use the new timezone handling functions instead', since: '5.3')]
    #[PhpStormStubsElementAvailable(from: '5.5', to: '5.6')] $is_dst = -1
): int|false {}

/**
 * Get Unix timestamp for a GMT date
 * @link https://php.net/manual/en/function.gmmktime.php
 * @param int $hour <p>
 * The hour
 * </p>
 * @param int $minute <p>
 * The minute
 * </p>
 * @param int $second <p>
 * The second
 * </p>
 * @param int $month <p>
 * The month
 * </p>
 * @param int $day <p>
 * The day
 * </p>
 * @param int $year <p>
 * The year
 * </p>
 * @param int $is_dst <p>
 * Parameters always represent a GMT date so is_dst
 * doesn't influence the result.
 * </p>
 * @return int|false a integer Unix timestamp.
 */
#[Pure(true)]
function gmmktime(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] int $hour = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] int $hour,
    ?int $minute = null,
    ?int $second = null,
    ?int $month = null,
    ?int $day = null,
    ?int $year = null,
    #[PhpStormStubsElementAvailable(from: '5.5', to: '5.6')] $is_dst = null
): int|false {}

/**
 * Validate a Gregorian date
 * @link https://php.net/manual/en/function.checkdate.php
 * @param int $month <p>
 * The month is between 1 and 12 inclusive.
 * </p>
 * @param int $day <p>
 * The day is within the allowed number of days for the given
 * month. Leap years
 * are taken into consideration.
 * </p>
 * @param int $year <p>
 * The year is between 1 and 32767 inclusive.
 * </p>
 * @return bool true if the date given is valid; otherwise returns false.
 */
#[Pure(true)]
function checkdate(int $month, int $day, int $year): bool {}

/**
 * Format a local time/date according to locale settings
 * The following characters are recognized in the
 * format parameter string
 * <table>
 * <tr valign="top" colspan="3" bgcolor="silver">
 * <th>format</th>
 * <th>Description</th>
 * <th>Example returned values</th>
 * <th>
 * Day</th>
 * </tr>
 * <tr valign="top">
 * <td>%a</td>
 * <td>An abbreviated textual representation of the day</td>
 * <td>Sun through Sat</td>
 * </tr>
 * <tr valign="top">
 * <td>%A</td>
 * <td>A full textual representation of the day</td>
 * <td>Sunday through Saturday</td>
 * </tr>
 * <tr valign="top">
 * <td>%d</td>
 * <td>Two-digit day of the month (with leading zeros)</td>
 * <td>01 to 31</td>
 * </tr>
 * <tr valign="top">
 * <td>%e</td>
 * <td>Day of the month, with a space preceding single digits</td>
 * <td> 1 to 31</td>
 * </tr>
 * <tr valign="top">
 * <td>%j</td>
 * <td>Day of the year, 3 digits with leading zeros</td>
 * <td>001 to 366</td>
 * </tr>
 * <tr valign="top">
 * <td>%u</td>
 * <td>ISO-8601 numeric representation of the day of the week</td>
 * <td>1 (for Monday) though 7 (for Sunday)</td>
 * </tr>
 * <tr valign="top">
 * <td>%w</td>
 * <td>Numeric representation of the day of the week</td>
 * <td>0 (for Sunday) through 6 (for Saturday)</td>
 * </tr>
 * <tr valign="top">
 * <th colspan="3" bgcolor="silver">Week</th>
 * </tr>
 * <tr valign="top">
 * <td>%U</td>
 * <td>Week number of the given year, starting with the first
 * Sunday as the first week</td>
 * <td>13 (for the 13th full week of the year)</td>
 * </tr>
 * <tr valign="top">
 * <td>%V</td>
 * <td>ISO-8601:1988 week number of the given year, starting with
 * the first week of the year with at least 4 weekdays, with Monday
 * being the start of the week</td>
 * <td>01 through 53 (where 53
 * accounts for an overlapping week)</td>
 * </tr>
 * <tr valign="top">
 * <td>%W</td>
 * <td>A numeric representation of the week of the year, starting
 * with the first Monday as the first week</td>
 * <td>46 (for the 46th week of the year beginning
 * with a Monday)</td>
 * </tr>
 * <tr valign="top">
 * <th colspan="3" bgcolor="silver">Month</th>
 * </tr>
 * <tr valign="top">
 * <td>%b</td>
 * <td>Abbreviated month name, based on the locale</td>
 * <td>Jan through Dec</td>
 * </tr>
 * <tr valign="top">
 * <td>%B</td>
 * <td>Full month name, based on the locale</td>
 * <td>January through December</td>
 * </tr>
 * <tr valign="top">
 * <td>%h</td>
 * <td>Abbreviated month name, based on the locale (an alias of %b)</td>
 * <td>Jan through Dec</td>
 * </tr>
 * <tr valign="top">
 * <td>%m</td>
 * <td>Two digit representation of the month</td>
 * <td>01 (for January) through 12 (for December)</td>
 * </tr>
 * <tr valign="top">
 * <th colspan="3" bgcolor="silver">Year</th>
 * </tr>
 * <tr valign="top">
 * <td>%C</td>
 * <td>Two digit representation of the century (year divided by 100, truncated to an integer)</td>
 * <td>19 for the 20th Century</td>
 * </tr>
 * <tr valign="top">
 * <td>%g</td>
 * <td>Two digit representation of the year going by ISO-8601:1988 standards (see %V)</td>
 * <td>Example: 09 for the week of January 6, 2009</td>
 * </tr>
 * <tr valign="top">
 * <td>%G</td>
 * <td>The full four-digit version of %g</td>
 * <td>Example: 2008 for the week of January 3, 2009</td>
 * </tr>
 * <tr valign="top">
 * <td>%y</td>
 * <td>Two digit representation of the year</td>
 * <td>Example: 09 for 2009, 79 for 1979</td>
 * </tr>
 * <tr valign="top">
 * <td>%Y</td>
 * <td>Four digit representation for the year</td>
 * <td>Example: 2038</td>
 * </tr>
 * <tr valign="top">
 * <th colspan="3" bgcolor="silver">Time</th>
 * </tr>
 * <tr valign="top">
 * <td>%H</td>
 * <td>Two digit representation of the hour in 24-hour format</td>
 * <td>00 through 23</td>
 * </tr>
 * <tr valign="top">
 * <td>%I</td>
 * <td>Two digit representation of the hour in 12-hour format</td>
 * <td>01 through 12</td>
 * </tr>
 * <tr valign="top">
 * <td>%l (lower-case 'L')</td>
 * <td>Hour in 12-hour format, with a space preceding single digits</td>
 * <td> 1 through 12</td>
 * </tr>
 * <tr valign="top">
 * <td>%M</td>
 * <td>Two digit representation of the minute</td>
 * <td>00 through 59</td>
 * </tr>
 * <tr valign="top">
 * <td>%p</td>
 * <td>UPPER-CASE 'AM' or 'PM' based on the given time</td>
 * <td>Example: AM for 00:31, PM for 22:23</td>
 * </tr>
 * <tr valign="top">
 * <td>%P</td>
 * <td>lower-case 'am' or 'pm' based on the given time</td>
 * <td>Example: am for 00:31, pm for 22:23</td>
 * </tr>
 * <tr valign="top">
 * <td>%r</td>
 * <td>Same as "%I:%M:%S %p"</td>
 * <td>Example: 09:34:17 PM for 21:34:17</td>
 * </tr>
 * <tr valign="top">
 * <td>%R</td>
 * <td>Same as "%H:%M"</td>
 * <td>Example: 00:35 for 12:35 AM, 16:44 for 4:44 PM</td>
 * </tr>
 * <tr valign="top">
 * <td>%S</td>
 * <td>Two digit representation of the second</td>
 * <td>00 through 59</td>
 * </tr>
 * <tr valign="top">
 * <td>%T</td>
 * <td>Same as "%H:%M:%S"</td>
 * <td>Example: 21:34:17 for 09:34:17 PM</td>
 * </tr>
 * <tr valign="top">
 * <td>%X</td>
 * <td>Preferred time representation based on locale, without the date</td>
 * <td>Example: 03:59:16 or 15:59:16</td>
 * </tr>
 * <tr valign="top">
 * <td>%z</td>
 * <td>Either the time zone offset from UTC or the abbreviation (depends
 * on operating system)</td>
 * <td>Example: -0500 or EST for Eastern Time</td>
 * </tr>
 * <tr valign="top">
 * <td>%Z</td>
 * <td>The time zone offset/abbreviation option NOT given by %z (depends
 * on operating system)</td>
 * <td>Example: -0500 or EST for Eastern Time</td>
 * </tr>
 * <tr valign="top">
 * <th colspan="3" bgcolor="silver">Time and Date Stamps</th>
 * </tr>
 * <tr valign="top">
 * <td>%c</td>
 * <td>Preferred date and time stamp based on local</td>
 * <td>Example: Tue Feb 5 00:45:10 2009 for
 * February 4, 2009 at 12:45:10 AM</td>
 * </tr>
 * <tr valign="top">
 * <td>%D</td>
 * <td>Same as "%m/%d/%y"</td>
 * <td>Example: 02/05/09 for February 5, 2009</td>
 * </tr>
 * <tr valign="top">
 * <td>%F</td>
 * <td>Same as "%Y-%m-%d" (commonly used in database datestamps)</td>
 * <td>Example: 2009-02-05 for February 5, 2009</td>
 * </tr>
 * <tr valign="top">
 * <td>%s</td>
 * <td>Unix Epoch Time timestamp (same as the time
 * function)</td>
 * <td>Example: 305815200 for September 10, 1979 08:40:00 AM</td>
 * </tr>
 * <tr valign="top">
 * <td>%x</td>
 * <td>Preferred date representation based on locale, without the time</td>
 * <td>Example: 02/05/09 for February 5, 2009</td>
 * </tr>
 * <tr valign="top">
 * <th colspan="3" bgcolor="silver">Miscellaneous</th>
 * </tr>
 * <tr valign="top">
 * <td>%n</td>
 * <td>A newline character ("\n")</td>
 * <td>---</td>
 * </tr>
 * <tr valign="top">
 * <td>%t</td>
 * <td>A Tab character ("\t")</td>
 * <td>---</td>
 * </tr>
 * <tr valign="top">
 * <td>%%</td>
 * <td>A literal percentage character ("%")</td>
 * <td>---</td>
 * </tr>
 * </table>
 * <p>
 * Maximum length of this parameter is 1023 characters.
 * </p>
 * Contrary to ISO-9899:1999, Sun Solaris starts with Sunday as 1.
 * As a result, %u may not function as described in this manual.
 * @link https://php.net/manual/en/function.strftime.php
 * @param string $format
 * @param int|null $timestamp [optional] defaults to the value of time()
 * Unix timestamp that defaults to the current local time if a timestamp is not given..
 * @return string|false a string formatted according format
 * using the given timestamp or the current
 * local time if no timestamp is given. Month and weekday names and
 * other language-dependent strings respect the current locale set
 * with setlocale.
 * @deprecated 8.1
 */
#[Deprecated(since: '8.1')]
function strftime(string $format, ?int $timestamp): string|false {}

/**
 * Format a GMT/UTC time/date according to locale settings
 * @link https://php.net/manual/en/function.gmstrftime.php
 * @param string $format <p>
 * See description in strftime.
 * </p>
 * @param int|null $timestamp [optional]
 * @return string|false a string formatted according to the given format string
 * using the given timestamp or the current
 * local time if no timestamp is given. Month and weekday names and
 * other language dependent strings respect the current locale set
 * with setlocale.
 * @deprecated 8.1
 */
#[Deprecated(since: '8.1')]
function gmstrftime(string $format, ?int $timestamp): string|false {}

/**
 * Return current Unix timestamp
 * @link https://php.net/manual/en/function.time.php
 * @return int <p>Returns the current time measured in the number of seconds since the Unix Epoch (January 1 1970 00:00:00 GMT).</p>
 */
function time(): int {}

/**
 * Get the local time
 * @link https://php.net/manual/en/function.localtime.php
 * @param int|null $timestamp [optional]
 * @param bool $associative [optional] <p>
 * If set to false or not supplied then the array is returned as a regular,
 * numerically indexed array. If the argument is set to true then
 * localtime returns an associative array containing
 * all the different elements of the structure returned by the C
 * function call to localtime. The names of the different keys of
 * the associative array are as follows:
 * </p>
 * "tm_sec" - seconds
 * @return array
 */
#[Pure(true)]
#[ArrayShape([
    'tm_sec' => 'int',
    'tm_min' => 'int',
    'tm_hour' => 'int',
    'tm_mday' => 'int',
    'tm_mon' => 'int',
    'tm_year' => 'int',
    'tm_wday' => 'int',
    'tm_yday' => 'int',
    'tm_isdst' => 'int',
])]
function localtime(?int $timestamp, bool $associative = false): array {}

/**
 * Get date/time information
 * @link https://php.net/manual/en/function.getdate.php
 * @param int|null $timestamp [optional]
 * @return array an associative array of information related to
 * the timestamp. Elements from the returned
 * associative array are as follows:
 * </p>
 * <p>
 * <table>
 * Key elements of the returned associative array
 * <tr valign="top">
 * <td>Key</td>
 * <td>Description</td>
 * <td>Example returned values</td>
 * </tr>
 * <tr valign="top">
 * <td>"seconds"</td>
 * <td>Numeric representation of seconds</td>
 * <td>0 to 59</td>
 * </tr>
 * <tr valign="top">
 * <td>"minutes"</td>
 * <td>Numeric representation of minutes</td>
 * <td>0 to 59</td>
 * </tr>
 * <tr valign="top">
 * <td>"hours"</td>
 * <td>Numeric representation of hours</td>
 * <td>0 to 23</td>
 * </tr>
 * <tr valign="top">
 * <td>"mday"</td>
 * <td>Numeric representation of the day of the month</td>
 * <td>1 to 31</td>
 * </tr>
 * <tr valign="top">
 * <td>"wday"</td>
 * <td>Numeric representation of the day of the week</td>
 * <td>0 (for Sunday) through 6 (for Saturday)</td>
 * </tr>
 * <tr valign="top">
 * <td>"mon"</td>
 * <td>Numeric representation of a month</td>
 * <td>1 through 12</td>
 * </tr>
 * <tr valign="top">
 * <td>"year"</td>
 * <td>A full numeric representation of a year, 4 digits</td>
 * <td>Examples: 1999 or 2003</td>
 * </tr>
 * <tr valign="top">
 * <td>"yday"</td>
 * <td>Numeric representation of the day of the year</td>
 * <td>0 through 365</td>
 * </tr>
 * <tr valign="top">
 * <td>"weekday"</td>
 * <td>A full textual representation of the day of the week</td>
 * <td>Sunday through Saturday</td>
 * </tr>
 * <tr valign="top">
 * <td>"month"</td>
 * <td>A full textual representation of a month, such as January or March</td>
 * <td>January through December</td>
 * </tr>
 * <tr valign="top">
 * <td>0</td>
 * <td>
 * Seconds since the Unix Epoch, similar to the values returned by
 * time and used by date.
 * </td>
 * <td>
 * System Dependent, typically -2147483648 through
 * 2147483647.
 * </td>
 * </tr>
 * </table>
 */
#[Pure(true)]
#[ArrayShape([
    'seconds' => 'int',
    'minutes' => 'int',
    'hours' => 'int',
    'mday' => 'int',
    'wday' => 'int',
    'mon' => 'int',
    'year' => 'int',
    'yday' => 'int',
    'weekday' => 'int',
    'month' => 'string',
    0 => 'int',
])]
function getdate(?int $timestamp): array {}

/**
 * Returns new DateTime object
 * @link https://php.net/manual/en/function.date-create.php
 * @param string $datetime [optional] <p>
 * String in a format accepted by strtotime.
 * </p>
 * @param DateTimeZone|null $timezone [optional] <p>
 * Time zone of the time.
 * </p>
 * @return DateTime|false DateTime object on success or false on failure.
 */
#[Pure(true)]
function date_create(string $datetime = 'now', ?DateTimeZone $timezone): DateTime|false {}

/**
 * (PHP 5.5)<br/>
 * Alias:
 * {@see DateTimeImmutable::__construct}
 * Returns new DateTimeImmutable object
 * @link https://php.net/manual/en/function.date-create-immutable.php
 * @see DateTimeImmutable::__construct()
 * @param string $datetime [optional] <p>
 * String in a format accepted by strtotime.
 * </p>
 * @param DateTimeZone|null $timezone [optional] <p>
 * Time zone of the time.
 * </p>
 * @return DateTimeImmutable|false DateTime object on success or false on failure.
 */
#[Pure(true)]
function date_create_immutable(string $datetime = 'now', ?DateTimeZone $timezone): DateTimeImmutable|false {}

/**
 * Returns new DateTimeImmutable object formatted according to the specified format
 * @link https://php.net/manual/en/function.date-create-immutable-from-format.php
 * @param string $format
 * @param string $datetime
 * @param DateTimeZone|null $timezone [optional]
 * @return DateTimeImmutable|false
 */
#[Pure(true)]
function date_create_immutable_from_format(string $format, string $datetime, ?DateTimeZone $timezone): DateTimeImmutable|false {}

/**
 * Alias:
 * {@see DateTime::createFromFormat}
 * @link https://php.net/manual/en/function.date-create-from-format.php
 * @param string $format Format accepted by  <a href="https://secure.php.net/manual/en/function.date.php">date()</a>.
 * <p>If format does not contain the character ! then portions of the generated time which are not specified in format will be set to the current system time.</p>
 * <p>If format contains the character !, then portions of the generated time not provided in format, as well as values to the left-hand side of the !, will be set to corresponding values from the Unix epoch.</p>
 * <p>The Unix epoch is 1970-01-01 00:00:00 UTC.</p>
 * @param string $datetime String representing the time.
 * @param DateTimeZone|null $timezone [optional] A DateTimeZone object representing the desired time zone.
 * @return DateTime|false <p> Returns a new
 * {@see DateTime} instance or <b>FALSE</b> on failure.</p>
 */
#[Pure(true)]
function date_create_from_format(string $format, string $datetime, ?DateTimeZone $timezone): DateTime|false {}

/**
 * Returns associative array with detailed info about given date
 * @link https://php.net/manual/en/function.date-parse.php
 * @param string $datetime <p>
 * Date in format accepted by strtotime.
 * </p>
 * @return array|false array with information about the parsed date
 * on success or false on failure.
 */
#[Pure(true)]
#[LanguageLevelTypeAware(["8.0" => "array"], default: "array|false")]
#[ArrayShape([
    "year" => "int",
    "month" => "int",
    "day" => "int",
    "hour" => "int",
    "minute" => "int",
    "second" => "int",
    "fraction" => "double",
    "is_localtime" => "bool",
    "zone_type" => "int",
    "zone" => "int",
    "is_dst" => "bool",
    "tz_abbr" => "string",
    "tz_id" => "string",
    "relative" => "array",
    "warning_count" => "int",
    "warnings" => "array",
    "error_count" => "int",
    "errors" => "array"
])]
function date_parse(string $datetime): false|array {}

/**
 * Get info about given date formatted according to the specified format
 * @link https://php.net/manual/en/function.date-parse-from-format.php
 * @param string $format <p>
 * Format accepted by date with some extras.
 * </p>
 * @param string $datetime <p>
 * String representing the date.
 * </p>
 * @return array associative array with detailed info about given date.
 */
#[Pure(true)]
#[ArrayShape([
    'year' => 'int',
    'month' => 'int',
    'day' => 'int',
    'hour' => 'int',
    'minute' => 'int',
    'second' => 'int',
    'fraction' => 'double',
    'is_localtime' => 'bool',
    'zone_type' => 'int',
    'zone' => 'int',
    'is_dst' => 'bool',
    'tz_abbr' => 'string',
    'tz_id' => 'string',
    'relative' => 'array',
    'warning_count' => 'int',
    'warnings' => 'array',
    'error_count' => 'int',
    'errors' => 'array'
])]
function date_parse_from_format(string $format, string $datetime): array {}

/**
 * Returns the warnings and errors
 * Alias:
 * {@see DateTime::getLastErrors}
 * @link https://php.net/manual/en/function.date-get-last-errors.php
 * @return array|false <p>Returns array containing info about warnings and errors.</p>
 */
#[ArrayShape(["warning_count" => "int", "warnings" => "string[]", "error_count" => "int", "errors" => "string[]"])]
#[Pure(true)]
function date_get_last_errors(): array|false {}

/**
 * Alias:
 * {@see DateTime::format}
 * @link https://php.net/manual/en/function.date-format.php
 * @param DateTimeInterface $object
 * @param string $format
 * @return string|false formatted date string on success or <b>FALSE</b> on failure.
 */
#[Pure(true)]
#[LanguageLevelTypeAware(["8.0" => "string"], default: "string|false")]
function date_format(DateTimeInterface $object, string $format) {}

/**
 * Alter the timestamp of a DateTime object by incrementing or decrementing
 * in a format accepted by strtotime().
 * Alias:
 * {@see DateTime::modify}
 * @link https://php.net/manual/en/function.date-modify.php
 * @param DateTime $object A DateTime object returned by date_create(). The function modifies this object.
 * @param string $modifier A date/time string. Valid formats are explained in {@link https://secure.php.net/manual/en/datetime.formats.php Date and Time Formats}.
 * @return DateTime|false Returns the DateTime object for method chaining or <b>FALSE</b> on failure.
 */
function date_modify(DateTime $object, string $modifier): DateTime|false {}

/**
 * Alias:
 * {@see DateTime::add}
 * @link https://php.net/manual/en/function.date-add.php
 * @param DateTime $object <p>Procedural style only: A
 * {@see DateTime} object returned by
 * {@see date_create()}. The function modifies this object.</p>
 * @param DateInterval $interval <p>A
 * {@see DateInterval} object</p>
 * @return DateTime|false <p>Returns the
 * {@see DateTime} object for method chaining or <b>FALSE</b> on failure.</p>
 */
#[LanguageLevelTypeAware(["8.0" => "DateTime"], default: "DateTime|false")]
function date_add(DateTime $object, DateInterval $interval) {}

/**
 * Subtracts an amount of days, months, years, hours, minutes and seconds from a datetime object
 * Alias:
 * {@see DateTime::sub}
 * @link https://php.net/manual/en/function.date-sub.php
 * @param DateTime $object Procedural style only: A
 * {@see DateTime} object returned by
 * {@see date_create()}. The function modifies this object.
 * @param DateInterval $interval <p>A
 * {@see DateInterval} object</p>
 * @return DateTime|false <p>Returns the
 * {@see DateTime} object for method chaining or <b>FALSE</b> on failure.</p>
 */
#[LanguageLevelTypeAware(["8.0" => "DateTime"], default: "DateTime|false")]
function date_sub(DateTime $object, DateInterval $interval) {}

/**
 * Alias:
 * {@see DateTime::getTimezone}
 * @link https://php.net/manual/en/function.date-timezone-get.php
 * @param DateTimeInterface $object <p>Procedural style only: A
 * {@see DateTime} object
 * returned by
 * {@see date_create()}</p>
 * @return DateTimeZone|false
 * <p>
 * Returns a
 * {@see DateTimeZone} object on success
 * or <b>FALSE</b> on failure.
 * </p>
 */
#[Pure(true)]
function date_timezone_get(DateTimeInterface $object): DateTimeZone|false {}

/**
 * Sets the time zone for the datetime object
 * Alias:
 * {@see DateTime::setTimezone}
 * @link https://php.net/manual/en/function.date-timezone-set.php
 * @param DateTime|DateTimeInterface $object <p>A
 * {@see DateTime} object returned by
 * {@see date_create()}. The function modifies this object.</p>
 * @param DateTimeZone $timezone <p>A
 * {@see DateTimeZone} object representing the desired time zone.</p>
 * @return DateTime|false <p>Returns the
 * {@see DateTime} object for method chaining or <b>FALSE</b> on failure.</p>
 */
#[LanguageLevelTypeAware(["8.0" => "DateTime"], default: "DateTime|false")]
function date_timezone_set(#[LanguageLevelTypeAware(["8.0" => "DateTime"], default: "DateTimeInterface")] $object, DateTimeZone $timezone) {}

/**
 * Alias:
 * {@see DateTime::getOffset}
 * @link https://php.net/manual/en/function.date-offset-get.php
 * @param DateTimeInterface $object <p>Procedural style only: A {@see DateTime} object
 * returned by {@see date_create()}</p>
 * @return int|false <p>Returns the timezone offset in seconds from UTC on success or <b>FALSE</b> on failure.</p>
 */
#[Pure(true)]
#[LanguageLevelTypeAware(["8.0" => "int"], default: "int|false")]
function date_offset_get(DateTimeInterface $object) {}

/**
 * Returns the difference between two datetime objects
 * Alias:
 * {@see DateTime::diff}
 * @link https://php.net/manual/en/function.date-diff.php
 * @param DateTimeInterface $baseObject
 * @param DateTimeInterface $targetObject The date to compare to
 * @param bool $absolute [optional] Whether to return absolute difference.
 * @return DateInterval|false The DateInterval object representing the difference between the two dates or FALSE on failure.
 */
#[Pure(true)]
#[LanguageLevelTypeAware(["8.0" => "DateInterval"], default: "DateInterval|false")]
function date_diff(DateTimeInterface $baseObject, DateTimeInterface $targetObject, bool $absolute = false) {}

/**
 * Alias:
 * {@see DateTime::setTime}
 * @link https://php.net/manual/en/function.date-time-set.php
 * @param DateTime $object
 * @param int $hour
 * @param int $minute
 * @param int $second [optional]
 * @param int $microsecond [optional]
 * @return DateTime <p>Returns the
 * {@see DateTime} object for method chaining or <b>FALSE</b> on failure.</p>
 */
function date_time_set(
    DateTime $object,
    int $hour,
    int $minute,
    int $second = 0,
    #[PhpStormStubsElementAvailable(from: '7.1')] int $microsecond = 0
): DateTime {}

/**
 * Alias:
 * {@see DateTime::setDate}
 * @link https://php.net/manual/en/function.date-date-set.php
 * @param DateTime $object <p>Procedural style only: A {@see DateTime} object
 * returned by {@see date_create()}.
 * The function modifies this object.</p>
 * @param int $year <p>Year of the date.</p>
 * @param int $month <p>Month of the date.</p>
 * @param int $day <p>Day of the date.</p>
 * @return DateTime|false
 * <p>
 * Returns the
 * {@see DateTime} object for method chaining or <b>FALSE</b> on failure.
 * </p>
 */
#[LanguageLevelTypeAware(["8.0" => "DateTime"], default: "DateTime|false")]
function date_date_set(DateTime $object, int $year, int $month, int $day): DateTime|false {}

/**
 * Alias:
 * {@see DateTime::setISODate}
 * @link https://php.net/manual/en/function.date-isodate-set.php
 * @param DateTime $object
 * @param int $year <p>Year of the date</p>
 * @param int $week <p>Week of the date.</p>
 * @param int $dayOfWeek [optional] <p>Offset from the first day of the week.</p>
 * @return DateTime|false <p>
 * Returns the {@see DateTime} object for method chaining or <strong><code>FALSE</code></strong> on failure.
 * </p>
 */
#[LanguageLevelTypeAware(["8.0" => "DateTime"], default: "DateTime|false")]
function date_isodate_set(DateTime $object, int $year, int $week, int $dayOfWeek = 1) {}

/**
 * Sets the date and time based on an unix timestamp
 * Alias:
 * {@see DateTime::setTimestamp}
 * @link https://php.net/manual/en/function.date-timestamp-set.php
 * @param DateTime $object <p>Procedural style only: A
 * {@see DateTime} object returned by
 * {@see date_create()}. The function modifies this object.</p>
 * @param int $timestamp <p>Unix timestamp representing the date.</p>
 * @return DateTime|false
 * {@see DateTime} object for call chaining or <b>FALSE</b> on failure
 */
#[LanguageLevelTypeAware(["8.0" => "DateTime"], default: "DateTime|false")]
function date_timestamp_set(DateTime $object, int $timestamp): DateTime|false {}

/**
 * Gets the unix timestamp
 * Alias:
 * {@see DateTime::getTimestamp}
 * @link https://php.net/manual/en/function.date-timestamp-get.php
 * @param DateTimeInterface $object
 * @return int <p>Returns the Unix timestamp representing the date.</p>
 */
#[Pure(true)]
function date_timestamp_get(DateTimeInterface $object): int {}

/**
 * Returns new DateTimeZone object
 * @link https://php.net/manual/en/function.timezone-open.php
 * @param string $timezone <p>
 * Time zone identifier as full name (e.g. Europe/Prague) or abbreviation
 * (e.g. CET).
 * </p>
 * @return DateTimeZone|false DateTimeZone object on success or false on failure.
 */
#[Pure(true)]
function timezone_open(string $timezone): DateTimeZone|false {}

/**
 * Alias:
 * {@see DateTimeZone::getName}
 * @link https://php.net/manual/en/function.timezone-name-get.php
 * @param DateTimeZone $object <p>The
 * {@see DateTimeZone} for which to get a name.</p>
 * @return string One of the timezone names in the list of timezones.
 */
#[Pure]
function timezone_name_get(DateTimeZone $object): string {}

/**
 * Returns the timezone name from abbreviation
 * @link https://php.net/manual/en/function.timezone-name-from-abbr.php
 * @param string $abbr <p>
 * Time zone abbreviation.
 * </p>
 * @param int $utcOffset [optional] <p>
 * Offset from GMT in seconds. Defaults to -1 which means that first found
 * time zone corresponding to abbr is returned.
 * Otherwise exact offset is searched and only if not found then the first
 * time zone with any offset is returned.
 * </p>
 * @param int $isDST [optional] <p>
 * Daylight saving time indicator. If abbr doesn't
 * exist then the time zone is searched solely by
 * offset and isdst.
 * </p>
 * @return string|false time zone name on success or false on failure.
 * @since 5.1.3
 */
#[Pure(true)]
function timezone_name_from_abbr(string $abbr, int $utcOffset = -1, int $isDST = -1): string|false {}

/**
 * Alias:
 * {@link DateTimeZone::getOffset}
 * @link https://php.net/manual/en/function.timezone-offset-get.php
 * @param DateTimeZone $object <p>Procedural style only: A
 * {@see DateTimeZone} object
 * returned by
 * {@see timezone_open()}</p>
 * @param DateTimeInterface $datetime <p>DateTime that contains the date/time to compute the offset from.</p>
 * @return int|false <p>Returns time zone offset in seconds on success or <b>FALSE</b> on failure.</p>
 */
#[Pure(true)]
#[LanguageLevelTypeAware(["8.0" => "int"], default: "int|false")]
function timezone_offset_get(DateTimeZone $object, DateTimeInterface $datetime) {}

/**
 * Returns all transitions for the timezone
 * Alias:
 * {@see DateTimeZone::getTransitions}
 * @link https://php.net/manual/en/function.timezone-transitions-get.php
 * @param DateTimeZone $object <p>Procedural style only: A
 * {@see DateTimeZone} object returned by
 * {@see timezone_open()}</p>
 * @param int $timestampBegin [optional] <p>Begin timestamp</p>
 * @param int $timestampEnd [optional] <p>End timestamp</p>
 * @return array|false <p>Returns numerically indexed array containing associative array with all transitions on success or FALSE on failure.</p>
 */
#[Pure(true)]
function timezone_transitions_get(DateTimeZone $object, int $timestampBegin, int $timestampEnd): array|false {}

/**
 * Alias:
 * {@see DateTimeZone::getLocation}
 * @link https://php.net/manual/en/function.timezone-location-get.php
 * @param DateTimeZone $object <p>Procedural style only: A {@see DateTimeZone} object returned by {@see timezone_open()}</p>
 * @return array|false <p>Array containing location information about timezone.</p>
 */
#[Pure(true)]
#[ArrayShape([
    'country_code' => 'string',
    'latitude' => 'double',
    'longitude' => 'double',
    'comments' => 'string',
])]
function timezone_location_get(DateTimeZone $object): array|false {}

/**
 * Returns a numerically indexed array containing all defined timezone identifiers
 * Alias:
 * {@see DateTimeZone::listIdentifiers()}
 * @link https://php.net/manual/en/function.timezone-identifiers-list.php
 * @param int $timezoneGroup [optional] One of DateTimeZone class constants.
 * @param string|null $countryCode [optional] A two-letter ISO 3166-1 compatible country code.
 * Note: This option is only used when $timezoneGroup is set to DateTimeZone::PER_COUNTRY.
 * @return array|false Returns array on success or FALSE on failure.
 */
#[Pure(true)]
#[LanguageLevelTypeAware(["8.0" => "array"], default: "array|false")]
function timezone_identifiers_list(int $timezoneGroup = DateTimeZone::ALL, ?string $countryCode) {}

/**
 * Returns associative array containing dst, offset and the timezone name
 * Alias:
 * {@see DateTimeZone::listAbbreviations}
 * @link https://php.net/manual/en/function.timezone-abbreviations-list.php
 * @return array|false Array on success or <b>FALSE</b> on failure.
 */
#[Pure]
#[LanguageLevelTypeAware(["8.0" => "array"], default: "array|false")]
function timezone_abbreviations_list() {}

/**
 * Gets the version of the timezonedb
 * @link https://php.net/manual/en/function.timezone-version-get.php
 * @return string a string.
 */
#[Pure]
function timezone_version_get(): string {}

/**
 * Alias:
 * {@see DateInterval::createFromDateString}
 * @link https://php.net/manual/en/function.date-interval-create-from-date-string.php
 * @param string $datetime <p>A date with relative parts. Specifically, the relative formats supported by the parser used for
 * {@see strtotime()} and
 * {@see DateTime} will be used to construct the
 * {@see DateInterval}.</p>
 * @return DateInterval|false
 * <p>Returns a new DateInterval instance.</p>
 */
#[Pure(true)]
function date_interval_create_from_date_string(string $datetime): DateInterval|false {}

/**
 * Alias:
 * {@see DateInterval::format}
 * @link https://php.net/manual/en/function.date-interval-format.php
 * @param DateInterval $object
 * @param string $format
 * @return string
 */
#[Pure(true)]
function date_interval_format(DateInterval $object, string $format): string {}

/**
 * Sets the default timezone used by all date/time functions in a script
 * @link https://php.net/manual/en/function.date-default-timezone-set.php
 * @param string $timezoneId <p>
 * The timezone identifier, like UTC or
 * Europe/Lisbon. The list of valid identifiers is
 * available in the .
 * </p>
 * @return bool This function returns false if the
 * timezone_identifier isn't valid, or true
 * otherwise.
 */
function date_default_timezone_set(string $timezoneId): bool {}

/**
 * Gets the default timezone used by all date/time functions in a script
 * @link https://php.net/manual/en/function.date-default-timezone-get.php
 * @return string a string.
 */
#[Pure]
function date_default_timezone_get(): string {}

/**
 * Returns time of sunrise for a given day and location
 * @link https://php.net/manual/en/function.date-sunrise.php
 * @param int $timestamp <p>
 * The timestamp of the day from which the sunrise
 * time is taken.
 * </p>
 * @param int $returnFormat [optional] <p>
 * <table>
 * format constants
 * <tr valign="top">
 * <td>constant</td>
 * <td>description</td>
 * <td>example</td>
 * </tr>
 * <tr valign="top">
 * <td>SUNFUNCS_RET_STRING</td>
 * <td>returns the result as string</td>
 * <td>16:46</td>
 * </tr>
 * <tr valign="top">
 * <td>SUNFUNCS_RET_DOUBLE</td>
 * <td>returns the result as float</td>
 * <td>16.78243132</td>
 * </tr>
 * <tr valign="top">
 * <td>SUNFUNCS_RET_TIMESTAMP</td>
 * <td>returns the result as integer (timestamp)</td>
 * <td>1095034606</td>
 * </tr>
 * </table>
 * </p>
 * @param float|null $latitude [optional] <p>
 * Defaults to North, pass in a negative value for South.
 * See also: date.default_latitude
 * </p>
 * @param float|null $longitude [optional] <p>
 * Defaults to East, pass in a negative value for West.
 * See also: date.default_longitude
 * </p>
 * @param float|null $zenith [optional] <p>
 * Default: date.sunrise_zenith
 * </p>
 * @param float|null $utcOffset [optional]
 * @return string|int|float|false the sunrise time in a specified format on
 * success or false on failure.
 * @deprecated in 8.1.  Use {@link date_sun_info} instead
 */
#[Pure(true)]
#[Deprecated(reason: 'in 8.1.  Use date_sun_info instead', since: '8.1')]
function date_sunrise(int $timestamp, int $returnFormat = SUNFUNCS_RET_STRING, ?float $latitude, ?float $longitude, ?float $zenith, ?float $utcOffset): string|int|float|false {}

/**
 * Returns time of sunset for a given day and location
 * @link https://php.net/manual/en/function.date-sunset.php
 * @param int $timestamp <p>
 * The timestamp of the day from which the sunset
 * time is taken.
 * </p>
 * @param int $returnFormat [optional] <p>
 * <table>
 * format constants
 * <tr valign="top">
 * <td>constant</td>
 * <td>description</td>
 * <td>example</td>
 * </tr>
 * <tr valign="top">
 * <td>SUNFUNCS_RET_STRING</td>
 * <td>returns the result as string</td>
 * <td>16:46</td>
 * </tr>
 * <tr valign="top">
 * <td>SUNFUNCS_RET_DOUBLE</td>
 * <td>returns the result as float</td>
 * <td>16.78243132</td>
 * </tr>
 * <tr valign="top">
 * <td>SUNFUNCS_RET_TIMESTAMP</td>
 * <td>returns the result as integer (timestamp)</td>
 * <td>1095034606</td>
 * </tr>
 * </table>
 * </p>
 * @param float|null $latitude [optional] <p>
 * Defaults to North, pass in a negative value for South.
 * See also: date.default_latitude
 * </p>
 * @param float|null $longitude [optional] <p>
 * Defaults to East, pass in a negative value for West.
 * See also: date.default_longitude
 * </p>
 * @param float|null $zenith [optional] <p>
 * Default: date.sunset_zenith
 * </p>
 * @param float|null $utcOffset [optional]
 * @return string|int|float|false the sunset time in a specified format on
 * success or false on failure.
 * @deprecated in 8.1.  Use {@link date_sun_info} instead
 */
#[Pure(true)]
#[Deprecated(reason: 'in 8.1.  Use date_sun_info instead', since: '8.1')]
function date_sunset(int $timestamp, int $returnFormat = SUNFUNCS_RET_STRING, ?float $latitude, ?float $longitude, ?float $zenith, ?float $utcOffset): string|int|float|false {}

/**
 * Returns an array with information about sunset/sunrise and twilight begin/end
 * @link https://php.net/manual/en/function.date-sun-info.php
 * @param int $timestamp <p>
 * Timestamp.
 * </p>
 * @param float $latitude <p>
 * Latitude in degrees.
 * </p>
 * @param float $longitude <p>
 * Longitude in degrees.
 * </p>
 * @return array{
 *              sunrise: int|bool,
 *              sunset: int|bool,
 *              transit: int|bool,
 *              civil_twilight_begin: int|bool,
 *              civil_twilight_end: int|bool,
 *              nautical_twilight_begin: int|bool,
 *              nautical_twilight_end: int|bool,
 *              astronomical_twilight_begin: int|bool,
 *              astronomical_twilight_end: int|bool,
 *         }|false Returns array on success or <strong><code>false</code></strong> on failure. The structure of the array is detailed in the following list:
 * <table>
 * <tr><td>sunrise</td><td>The timestamp of the sunrise (zenith angle = 90°35&#039;).</td></tr>
 * <tr><td>sunset</td><td>The timestamp of the sunset (zenith angle = 90°35&#039;).</td></tr>
 * <tr><td>transit</td><td>The timestamp when the sun is at its zenith, i.e. has reached its topmost point.</td></tr>
 * <tr><td>civil_twilight_begin</td><td>The start of the civil dawn (zenith angle = 96°). It ends at <code>sunrise</code>.</td></tr>
 * <tr><td>civil_twilight_end</td><td>The end of the civil dusk (zenith angle = 96°). It starts at <code>sunset</code>.</td></tr>
 * <tr><td>nautical_twilight_begin</td><td>The start of the nautical dawn (zenith angle = 102°). It ends at <code>civil_twilight_begin</code>.</td></tr>
 * <tr><td>nautical_twilight_end</td><td>The end of the nautical dusk (zenith angle = 102°). It starts at <code>civil_twilight_end</code>.</td></tr>
 * <tr><td>astronomical_twilight_begin</td><td>The start of the astronomical dawn (zenith angle = 108°). It ends at <code>nautical_twilight_begin</code>.</td></tr>
 * <tr><td>astronomical_twilight_end</td><td>The end of the astronomical dusk (zenith angle = 108°). It starts at <code>nautical_twilight_end</code>.</td></tr>
 * </table>
 * <br>
 * The values of the array elements are either UNIX timestamps, <strong><code>false</code></strong> if the
 * sun is below the respective zenith for the whole day, or <strong><code>true</code></strong> if the sun is
 * above the respective zenith for the whole day.
 * @since 5.1.2
 */
#[Pure(true)]
#[LanguageLevelTypeAware(["8.0" => "array"], default: "array|false")]
#[ArrayShape([
    "sunrise" => "int",
    "sunset" => "int",
    "transit" => "int",
    "civil_twilight_begin" => "int",
    "civil_twilight_end" => "int",
    "nautical_twilight_begin" => "int",
    "nautical_twilight_end" => "int",
    "astronomical_twilight_begin" => "int",
    "astronomical_twilight_end" => "int"
])]
function date_sun_info(int $timestamp, float $latitude, float $longitude): array|false {}

// End of date v.5.3.2-0.dotdeb.1
