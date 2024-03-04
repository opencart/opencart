<?php

// Start of calendar v.
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;

/**
 * Converts Julian Day Count to Gregorian date
 * @link https://php.net/manual/en/function.jdtogregorian.php
 * @param int $julian_day <p>
 * A julian day number as integer
 * </p>
 * @return string The gregorian date as a string in the form "month/day/year"
 */
function jdtogregorian(int $julian_day): string {}

/**
 * Converts a Gregorian date to Julian Day Count
 * @link https://php.net/manual/en/function.gregoriantojd.php
 * @param int $month <p>
 * The month as a number from 1 (for January) to 12 (for December)
 * </p>
 * @param int $day <p>
 * The day as a number from 1 to 31
 * </p>
 * @param int $year <p>
 * The year as a number between -4714 and 9999
 * </p>
 * @return int The julian day for the given gregorian date as an integer.
 */
function gregoriantojd(int $month, int $day, int $year): int {}

/**
 * Converts a Julian Day Count to a Julian Calendar Date
 * @link https://php.net/manual/en/function.jdtojulian.php
 * @param int $julian_day <p>
 * A julian day number as integer
 * </p>
 * @return string The julian date as a string in the form "month/day/year"
 */
function jdtojulian(int $julian_day): string {}

/**
 * Converts a Julian Calendar date to Julian Day Count
 * @link https://php.net/manual/en/function.juliantojd.php
 * @param int $month <p>
 * The month as a number from 1 (for January) to 12 (for December)
 * </p>
 * @param int $day <p>
 * The day as a number from 1 to 31
 * </p>
 * @param int $year <p>
 * The year as a number between -4713 and 9999
 * </p>
 * @return int The julian day for the given julian date as an integer.
 */
function juliantojd(int $month, int $day, int $year): int {}

/**
 * Converts a Julian day count to a Jewish calendar date
 * @link https://php.net/manual/en/function.jdtojewish.php
 * @param int $julian_day
 * @param bool $hebrew [optional] <p>
 * If the <i>hebrew</i> parameter is set to <b>TRUE</b>, the
 * <i>fl</i> parameter is used for Hebrew, string based,
 * output format.
 * </p>
 * @param int $flags [optional] <p>
 * The available formats are:
 * <b>CAL_JEWISH_ADD_ALAFIM_GERESH</b>,
 * <b>CAL_JEWISH_ADD_ALAFIM</b>,
 * <b>CAL_JEWISH_ADD_GERESHAYIM</b>.
 * </p>
 * @return string The jewish date as a string in the form "month/day/year"
 */
function jdtojewish(int $julian_day, bool $hebrew = false, int $flags = 0): string {}

/**
 * Converts a date in the Jewish Calendar to Julian Day Count
 * @link https://php.net/manual/en/function.jewishtojd.php
 * @param int $month <p>
 * The month as a number from 1 to 13
 * </p>
 * @param int $day <p>
 * The day as a number from 1 to 30
 * </p>
 * @param int $year <p>
 * The year as a number between 1 and 9999
 * </p>
 * @return int The julian day for the given jewish date as an integer.
 */
function jewishtojd(int $month, int $day, int $year): int {}

/**
 * Converts a Julian Day Count to the French Republican Calendar
 * @link https://php.net/manual/en/function.jdtofrench.php
 * @param int $julian_day
 * @return string The french revolution date as a string in the form "month/day/year"
 */
function jdtofrench(int $julian_day): string {}

/**
 * Converts a date from the French Republican Calendar to a Julian Day Count
 * @link https://php.net/manual/en/function.frenchtojd.php
 * @param int $month <p>
 * The month as a number from 1 (for Vendémiaire) to 13 (for the period of 5-6 days at the end of each year)
 * </p>
 * @param int $day <p>
 * The day as a number from 1 to 30
 * </p>
 * @param int $year <p>
 * The year as a number between 1 and 14
 * </p>
 * @return int The julian day for the given french revolution date as an integer.
 */
function frenchtojd(int $month, int $day, int $year): int {}

/**
 * Returns the day of the week
 * @link https://php.net/manual/en/function.jddayofweek.php
 * @param int $julian_day <p>
 * A julian day number as integer
 * </p>
 * @param int $mode [optional] <table>
 * Calendar week modes
 * <tr valign="top">
 * <td>Mode</td>
 * <td>Meaning</td>
 * </tr>
 * <tr valign="top">
 * <td>0 (Default)</td>
 * <td>
 * Return the day number as an int (0=Sunday, 1=Monday, etc)
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>1</td>
 * <td>
 * Returns string containing the day of week
 * (English-Gregorian)
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>2</td>
 * <td>
 * Return a string containing the abbreviated day of week
 * (English-Gregorian)
 * </td>
 * </tr>
 * </table>
 * @return string|int The gregorian weekday as either an integer or string.
 */
function jddayofweek(int $julian_day, int $mode = CAL_DOW_DAYNO): string|int {}

/**
 * Returns a month name
 * @link https://php.net/manual/en/function.jdmonthname.php
 * @param int $julian_day
 * @param int $mode
 * @return string The month name for the given Julian Day and <i>calendar</i>.
 */
function jdmonthname(int $julian_day, int $mode): string {}

/**
 * Get Unix timestamp for midnight on Easter of a given year
 * @link https://php.net/manual/en/function.easter-date.php
 * @param int|null $year [optional] <p>
 * The year as a number between 1970 an 2037
 * </p>
 * @param int $mode [optional] Allows Easter dates to be calculated based on the Julian calendar when set to CAL_EASTER_ALWAYS_JULIAN
 * @return int The easter date as a unix timestamp.
 */
function easter_date(?int $year, #[PhpStormStubsElementAvailable(from: '8.0')] int $mode = CAL_EASTER_DEFAULT): int {}

/**
 * Get number of days after March 21 on which Easter falls for a given year
 * @link https://php.net/manual/en/function.easter-days.php
 * @param positive-int|null $year [optional] <p>
 * The year as a positive number
 * </p>
 * @param int $mode [optional] <p>
 * Allows to calculate easter dates based
 * on the Gregorian calendar during the years 1582 - 1752 when set to
 * <b>CAL_EASTER_ROMAN</b>. See the calendar constants for more valid
 * constants.
 * </p>
 * @return int The number of days after March 21st that the Easter Sunday
 * is in the given <i>year</i>.
 */
function easter_days(?int $year, int $mode = CAL_EASTER_DEFAULT): int {}

/**
 * Convert Unix timestamp to Julian Day
 * @link https://php.net/manual/en/function.unixtojd.php
 * @param int|null $timestamp defaults to time() <p>
 * A unix timestamp to convert.
 * </p>
 * @return int|false A julian day number as integer.
 */
function unixtojd(?int $timestamp = null): int|false {}

/**
 * Convert Julian Day to Unix timestamp
 * @link https://php.net/manual/en/function.jdtounix.php
 * @param int $julian_day <p>
 * A julian day number between 2440588 and 2465342.
 * </p>
 * @return int The unix timestamp for the start of the given julian day.
 */
function jdtounix(int $julian_day): int {}

/**
 * Converts from a supported calendar to Julian Day Count
 * @link https://php.net/manual/en/function.cal-to-jd.php
 * @param int $calendar <p>
 * Calendar to convert from, one of
 * <b>CAL_GREGORIAN</b>,
 * <b>CAL_JULIAN</b>,
 * <b>CAL_JEWISH</b> or
 * <b>CAL_FRENCH</b>.
 * </p>
 * @param int $month <p>
 * The month as a number, the valid range depends
 * on the <i>calendar</i>
 * </p>
 * @param int $day <p>
 * The day as a number, the valid range depends
 * on the <i>calendar</i>
 * </p>
 * @param int $year <p>
 * The year as a number, the valid range depends
 * on the <i>calendar</i>
 * </p>
 * @return int A Julian Day number.
 */
function cal_to_jd(int $calendar, int $month, int $day, int $year): int {}

/**
 * Converts from Julian Day Count to a supported calendar
 * @link https://php.net/manual/en/function.cal-from-jd.php
 * @param int $julian_day <p>
 * Julian day as integer
 * </p>
 * @param int $calendar <p>
 * Calendar to convert to
 * </p>
 * @return array an array containing calendar information like month, day, year,
 * day of week, abbreviated and full names of weekday and month and the
 * date in string form "month/day/year".
 */
#[ArrayShape([
    "date" => "string",
    "month" => "int",
    "day" => "int",
    "year" => "int",
    "dow" => "int",
    "abbrevdayname" => "string",
    "dayname" => "string",
    "abbrevmonth" => "string",
    "monthname" => "string"
])]
function cal_from_jd(int $julian_day, int $calendar): array {}

/**
 * Return the number of days in a month for a given year and calendar
 * @link https://php.net/manual/en/function.cal-days-in-month.php
 * @param int $calendar <p>
 * Calendar to use for calculation
 * </p>
 * @param int $month <p>
 * Month in the selected calendar
 * </p>
 * @param int $year <p>
 * Year in the selected calendar
 * </p>
 * @return int The length in days of the selected month in the given calendar
 */
function cal_days_in_month(int $calendar, int $month, int $year): int {}

/**
 * Returns information about a particular calendar
 * @link https://php.net/manual/en/function.cal-info.php
 * @param int $calendar [optional] <p>
 * Calendar to return information for. If no calendar is specified
 * information about all calendars is returned.
 * </p>
 * @return array
 */
#[ArrayShape(["months" => "array", "abbrevmonths" => "array", "maxdaysinmonth" => "int", "calname" => "string", "calsymbol" => "string"])]
function cal_info(int $calendar = -1): array {}

define('CAL_GREGORIAN', 0);
define('CAL_JULIAN', 1);
define('CAL_JEWISH', 2);
define('CAL_FRENCH', 3);
define('CAL_NUM_CALS', 4);
define('CAL_DOW_DAYNO', 0);
define('CAL_DOW_SHORT', 2);
define('CAL_DOW_LONG', 1);
define('CAL_MONTH_GREGORIAN_SHORT', 0);
define('CAL_MONTH_GREGORIAN_LONG', 1);
define('CAL_MONTH_JULIAN_SHORT', 2);
define('CAL_MONTH_JULIAN_LONG', 3);
define('CAL_MONTH_JEWISH', 4);
define('CAL_MONTH_FRENCH', 5);
define('CAL_EASTER_DEFAULT', 0);
define('CAL_EASTER_ROMAN', 1);
define('CAL_EASTER_ALWAYS_GREGORIAN', 2);
define('CAL_EASTER_ALWAYS_JULIAN', 3);
define('CAL_JEWISH_ADD_ALAFIM_GERESH', 2);
define('CAL_JEWISH_ADD_ALAFIM', 4);
define('CAL_JEWISH_ADD_GERESHAYIM', 8);

// End of calendar v.
