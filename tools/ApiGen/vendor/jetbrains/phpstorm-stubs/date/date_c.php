<?php

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;
use JetBrains\PhpStorm\Pure;

/**
 * @since 5.5
 */
interface DateTimeInterface
{
    /**
     * @since 7.2
     */
    public const ATOM = 'Y-m-d\TH:i:sP';

    /**
     * @since 7.2
     */
    public const COOKIE = 'l, d-M-Y H:i:s T';

    /**
     * This format is not compatible with ISO-8601, but is left this way for backward compatibility reasons.
     * Use DateTime::ATOM or DATE_ATOM for compatibility with ISO-8601 instead.
     * @since 7.2
     * @deprecated
     */
    public const ISO8601 = 'Y-m-d\TH:i:sO';

    /**
     * @since 8.2
     */
    public const ISO8601_EXPANDED = DATE_ISO8601_EXPANDED;

    /**
     * @since 7.2
     */
    public const RFC822 = 'D, d M y H:i:s O';

    /**
     * @since 7.2
     */
    public const RFC850 = 'l, d-M-y H:i:s T';

    /**
     * @since 7.2
     */
    public const RFC1036 = 'D, d M y H:i:s O';

    /**
     * @since 7.2
     */
    public const RFC1123 = 'D, d M Y H:i:s O';

    /**
     * @since 7.2
     */
    public const RFC2822 = 'D, d M Y H:i:s O';

    /**
     * @since 7.2
     */
    public const RFC3339 = 'Y-m-d\TH:i:sP';

    /**
     * @since 7.2
     */
    public const RFC3339_EXTENDED = 'Y-m-d\TH:i:s.vP';

    /**
     * @since 7.2
     */
    public const RFC7231 = 'D, d M Y H:i:s \G\M\T';

    /**
     * @since 7.2
     */
    public const RSS = 'D, d M Y H:i:s O';

    /**
     * @since 7.2
     */
    public const W3C = 'Y-m-d\TH:i:sP';

    /* Methods */
    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Returns the difference between two DateTime objects
     * @link https://secure.php.net/manual/en/datetime.diff.php
     * @param DateTimeInterface $targetObject <p>The date to compare to.</p>
     * @param bool $absolute <p>Should the interval be forced to be positive?</p>
     * @return DateInterval
     * The https://secure.php.net/manual/en/class.dateinterval.php DateInterval} object representing the
     * difference between the two dates.
     */
    #[TentativeType]
    public function diff(
        DateTimeInterface $targetObject,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $absolute = false
    ): DateInterval;

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Returns date formatted according to given format
     * @link https://secure.php.net/manual/en/datetime.format.php
     * @param string $format <p>
     * Format accepted by  {@link https://secure.php.net/manual/en/function.date.php date()}.
     * </p>
     * @return string
     * Returns the formatted date string on success or <b>FALSE</b> on failure.
     * Since PHP8, it always returns <b>STRING</b>.
     */
    #[TentativeType]
    public function format(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $format): string;

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Returns the timezone offset
     * @return int|false
     * Returns the timezone offset in seconds from UTC on success
     * or <b>FALSE</b> on failure. Since PHP8, it always returns <b>INT</b>.
     */
    #[LanguageLevelTypeAware(["8.0" => "int"], default: "int|false")]
    #[TentativeType]
    public function getOffset(): int;

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Gets the Unix timestamp
     * @return int
     * Returns the Unix timestamp representing the date.
     */
    #[TentativeType]
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: 'int|false')]
    public function getTimestamp();

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Return time zone relative to given DateTime
     * @link https://secure.php.net/manual/en/datetime.gettimezone.php
     * @return DateTimeZone|false
     * Returns a {@link https://secure.php.net/manual/en/class.datetimezone.php DateTimeZone} object on success
     * or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function getTimezone(): DateTimeZone|false;

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * The __wakeup handler
     * @link https://secure.php.net/manual/en/datetime.wakeup.php
     * @return void Initializes a DateTime object.
     */
    #[TentativeType]
    public function __wakeup(): void;

    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function __serialize(): array;

    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function __unserialize(array $data): void;
}

/**
 * @since 5.5
 */
class DateTimeImmutable implements DateTimeInterface
{
    /* Methods */
    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * @link https://secure.php.net/manual/en/datetimeimmutable.construct.php
     * @param string $datetime [optional]
     * <p>A date/time string. Valid formats are explained in {@link https://secure.php.net/manual/en/datetime.formats.php Date and Time Formats}.</p>
     * <p>Enter <b>NULL</b> here to obtain the current time when using the <em>$timezone</em> parameter.</p>
     * @param null|DateTimeZone $timezone [optional] <p>
     * A {@link https://secure.php.net/manual/en/class.datetimezone.php DateTimeZone} object representing the timezone of <em>$datetime</em>.
     * </p>
     * <p>If <em>$timezone</em> is omitted, the current timezone will be used.</p>
     * <blockquote><p><b>Note</b>:</p><p>
     * The <em>$timezone</em> parameter and the current timezone are ignored when the <em>$datetime</em> parameter either
     * is a UNIX timestamp (e.g. <em>@946684800</em>) or specifies a timezone (e.g. <em>2010-01-28T15:00:00+02:00</em>).
     * </p></blockquote>
     * @throws Exception Emits Exception in case of an error.
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $datetime = "now",
        #[LanguageLevelTypeAware(['8.0' => 'DateTimeZone|null'], default: 'DateTimeZone')] $timezone = null
    ) {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Adds an amount of days, months, years, hours, minutes and seconds
     * @param DateInterval $interval
     * @return static
     */
    #[TentativeType]
    public function add(DateInterval $interval): DateTimeImmutable {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Returns new DateTimeImmutable object formatted according to the specified format
     * @link https://secure.php.net/manual/en/datetimeimmutable.createfromformat.php
     * @param string $format
     * @param string $datetime
     * @param null|DateTimeZone $timezone [optional]
     * @return DateTimeImmutable|false
     */
    #[TentativeType]
    public static function createFromFormat(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $format,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $datetime,
        #[LanguageLevelTypeAware(['8.0' => 'DateTimeZone|null'], default: 'DateTimeZone')] $timezone = null
    ): DateTimeImmutable|false {}

    /**
     * (PHP 5 &gt;=5.6.0)<br/>
     * Returns new DateTimeImmutable object encapsulating the given DateTime object
     * @link https://secure.php.net/manual/en/datetimeimmutable.createfrommutable.php
     * @param DateTime $object The mutable DateTime object that you want to convert to an immutable version. This object is not modified, but instead a new DateTimeImmutable object is created containing the same date time and timezone information.
     * @return DateTimeImmutable returns a new DateTimeImmutable instance.
     */
    #[TentativeType]
    #[LanguageLevelTypeAware(['8.2' => 'static'], default: 'DateTimeImmutable')]
    public static function createFromMutable(DateTime $object) {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Returns the warnings and errors
     * @link https://secure.php.net/manual/en/datetimeimmutable.getlasterrors.php
     * @return array|false Returns array containing info about warnings and errors.
     */
    #[ArrayShape(["warning_count" => "int", "warnings" => "string[]", "error_count" => "int", "errors" => "string[]"])]
    #[TentativeType]
    public static function getLastErrors(): array|false {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Alters the timestamp
     * @link https://secure.php.net/manual/en/datetimeimmutable.modify.php
     * @param string $modifier <p>A date/time string. Valid formats are explained in
     * {@link https://secure.php.net/manual/en/datetime.formats.php Date and Time Formats}.</p>
     * @return static|false Returns the newly created object or false on failure.
     * Returns the {@link https://secure.php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object for method chaining or <b>FALSE</b> on failure.
     */
    #[Pure]
    #[TentativeType]
    public function modify(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $modifier): DateTimeImmutable|false {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * The __set_state handler
     * @link https://secure.php.net/manual/en/datetimeimmutable.set-state.php
     * @param array $array <p>Initialization array.</p>
     * @return DateTimeImmutable
     * Returns a new instance of a {@link https://secure.php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object.
     */
    public static function __set_state(array $array) {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Sets the date
     * @link https://secure.php.net/manual/en/datetimeimmutable.setdate.php
     * @param int $year <p>Year of the date.</p>
     * @param int $month <p>Month of the date.</p>
     * @param int $day <p>Day of the date.</p>
     * @return static|false
     * Returns the {@link https://secure.php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object for method chaining or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function setDate(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $year,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $month,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $day
    ): DateTimeImmutable {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Sets the ISO date
     * @link https://php.net/manual/en/class.datetimeimmutable.php
     * @param int $year <p>Year of the date.</p>
     * @param int $week <p>Week of the date.</p>
     * @param int $dayOfWeek [optional] <p>Offset from the first day of the week.</p>
     * @return static|false
     * Returns the {@link https://secure.php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object for method chaining or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function setISODate(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $year,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $week,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $dayOfWeek = 1
    ): DateTimeImmutable {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Sets the time
     * @link https://secure.php.net/manual/en/datetimeimmutable.settime.php
     * @param int $hour <p> Hour of the time. </p>
     * @param int $minute <p> Minute of the time. </p>
     * @param int $second [optional] <p> Second of the time. </p>
     * @param int $microsecond [optional] <p> Microseconds of the time. Added since 7.1</p>
     * @return static|false
     * Returns the {@link https://secure.php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object for method chaining or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function setTime(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $hour,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $minute,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $second = 0,
        #[PhpStormStubsElementAvailable(from: '7.1')] #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $microsecond = 0
    ): DateTimeImmutable {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Sets the date and time based on an Unix timestamp
     * @link https://secure.php.net/manual/en/datetimeimmutable.settimestamp.php
     * @param int $timestamp <p>Unix timestamp representing the date.</p>
     * @return static
     * Returns the {@link https://secure.php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object for method chaining or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function setTimestamp(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $timestamp): DateTimeImmutable {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Sets the time zone
     * @link https://secure.php.net/manual/en/datetimeimmutable.settimezone.php
     * @param DateTimeZone $timezone <p>
     * A {@link https://secure.php.net/manual/en/class.datetimezone.php DateTimeZone} object representing the
     * desired time zone.
     * </p>
     * @return static
     * Returns the {@link https://secure.php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object for method chaining or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function setTimezone(DateTimeZone $timezone): DateTimeImmutable {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Subtracts an amount of days, months, years, hours, minutes and seconds
     * @link https://secure.php.net/manual/en/datetimeimmutable.sub.php
     * @param DateInterval $interval <p>
     * A {@link https://secure.php.net/manual/en/class.dateinterval.php DateInterval} object
     * </p>
     * @return static
     * Returns the {@link https://secure.php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object for method chaining or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function sub(DateInterval $interval): DateTimeImmutable {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Returns the difference between two DateTime objects
     * @link https://secure.php.net/manual/en/datetime.diff.php
     * @param DateTimeInterface $targetObject <p>The date to compare to.</p>
     * @param bool $absolute [optional] <p>Should the interval be forced to be positive?</p>
     * @return DateInterval|false
     * The {@link https://secure.php.net/manual/en/class.dateinterval.php DateInterval} object representing the
     * difference between the two dates or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function diff(
        #[LanguageLevelTypeAware(['8.0' => 'DateTimeInterface'], default: '')] $targetObject,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $absolute = false
    ): DateInterval {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Returns date formatted according to given format
     * @link https://secure.php.net/manual/en/datetime.format.php
     * @param string $format <p>
     * Format accepted by  {@link https://secure.php.net/manual/en/function.date.php date()}.
     * </p>
     * @return string
     * Returns the formatted date string on success or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function format(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $format): string {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Returns the timezone offset
     * @return int
     * Returns the timezone offset in seconds from UTC on success
     * or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function getOffset(): int {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Gets the Unix timestamp
     * @return int
     * Returns the Unix timestamp representing the date.
     */
    #[TentativeType]
    public function getTimestamp(): int {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * Return time zone relative to given DateTime
     * @link https://secure.php.net/manual/en/datetime.gettimezone.php
     * @return DateTimeZone|false
     * Returns a {@link https://secure.php.net/manual/en/class.datetimezone.php DateTimeZone} object on success
     * or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function getTimezone(): DateTimeZone|false {}

    /**
     * (PHP 5 &gt;=5.5.0)<br/>
     * The __wakeup handler
     * @link https://secure.php.net/manual/en/datetime.wakeup.php
     * @return void Initializes a DateTime object.
     */
    #[TentativeType]
    public function __wakeup(): void {}

    /**
     * @param DateTimeInterface $object
     * @return DateTimeImmutable
     * @since 8.0
     */
    public static function createFromInterface(DateTimeInterface $object): DateTimeImmutable {}

    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function __serialize(): array {}

    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function __unserialize(array $data): void {}
}

/**
 * Representation of date and time.
 * @link https://php.net/manual/en/class.datetime.php
 */
class DateTime implements DateTimeInterface
{
    /**
     * @removed 7.2
     */
    public const ATOM = 'Y-m-d\TH:i:sP';

    /**
     * @removed 7.2
     */
    public const COOKIE = 'l, d-M-Y H:i:s T';

    /**
     * @removed 7.2
     */
    public const ISO8601 = 'Y-m-d\TH:i:sO';

    /**
     * @removed 7.2
     */
    public const RFC822 = 'D, d M y H:i:s O';

    /**
     * @removed 7.2
     */
    public const RFC850 = 'l, d-M-y H:i:s T';

    /**
     * @removed 7.2
     */
    public const RFC1036 = 'D, d M y H:i:s O';

    /**
     * @removed 7.2
     */
    public const RFC1123 = 'D, d M Y H:i:s O';

    /**
     * @removed 7.2
     */
    public const RFC2822 = 'D, d M Y H:i:s O';

    /**
     * @removed 7.2
     */
    public const RFC3339 = 'Y-m-d\TH:i:sP';

    /**
     * @removed 7.2
     */
    public const RFC3339_EXTENDED = 'Y-m-d\TH:i:s.vP';

    /**
     * @removed 7.2
     */
    public const RFC7231 = 'D, d M Y H:i:s \G\M\T';

    /**
     * @removed 7.2
     */
    public const RSS = 'D, d M Y H:i:s O';

    /**
     * @removed 7.2
     */
    public const W3C = 'Y-m-d\TH:i:sP';

    /**
     * (PHP 5 &gt;=5.2.0)<br/>
     * @link https://php.net/manual/en/datetime.construct.php
     * @param string $datetime [optional]
     * <p>A date/time string. Valid formats are explained in {@link https://php.net/manual/en/datetime.formats.php Date and Time Formats}.</p>
     * <p>
     * Enter <b>now</b> here to obtain the current time when using
     * the <em>$timezone</em> parameter.
     * </p>
     * @param null|DateTimeZone $timezone [optional] <p>
     * A {@link https://php.net/manual/en/class.datetimezone.php DateTimeZone} object representing the
     * timezone of <em>$datetime</em>.
     * </p>
     * <p>
     * If <em>$timezone</em> is omitted,
     * the current timezone will be used.
     * </p>
     * <blockquote><p><b>Note</b>:
     * </p><p>
     * The <em>$timezone</em> parameter
     * and the current timezone are ignored when the
     * <em>$time</em> parameter either
     * is a UNIX timestamp (e.g. <em>@946684800</em>)
     * or specifies a timezone
     * (e.g. <em>2010-01-28T15:00:00+02:00</em>).
     * </p> <p></p></blockquote>
     * @throws Exception Emits Exception in case of an error.
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $datetime = 'now',
        #[LanguageLevelTypeAware(['8.0' => 'DateTimeZone|null'], default: 'DateTimeZone')] $timezone = null
    ) {}

    /**
     * @return void
     * @link https://php.net/manual/en/datetime.wakeup.php
     */
    #[TentativeType]
    public function __wakeup(): void {}

    /**
     * Returns date formatted according to given format.
     * @param string $format
     * @return string
     * @link https://php.net/manual/en/datetime.format.php
     */
    #[TentativeType]
    public function format(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $format): string {}

    /**
     * Alter the timestamp of a DateTime object by incrementing or decrementing
     * in a format accepted by strtotime().
     * @param string $modifier A date/time string. Valid formats are explained in <a href="https://secure.php.net/manual/en/datetime.formats.php">Date and Time Formats</a>.
     * @return static|false Returns the DateTime object for method chaining or FALSE on failure.
     * @link https://php.net/manual/en/datetime.modify.php
     */
    #[TentativeType]
    public function modify(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $modifier): DateTime|false {}

    /**
     * Adds an amount of days, months, years, hours, minutes and seconds to a DateTime object
     * @param DateInterval $interval
     * @return static
     * @link https://php.net/manual/en/datetime.add.php
     */
    #[TentativeType]
    public function add(DateInterval $interval): DateTime {}

    /**
     * @param DateTimeImmutable $object
     * @return DateTime
     * @since 7.3
     */
    #[TentativeType]
    #[LanguageLevelTypeAware(['8.2' => 'static'], default: 'DateTime')]
    public static function createFromImmutable(DateTimeImmutable $object) {}

    /**
     * Subtracts an amount of days, months, years, hours, minutes and seconds from a DateTime object
     * @param DateInterval $interval
     * @return static
     * @link https://php.net/manual/en/datetime.sub.php
     */
    #[TentativeType]
    public function sub(DateInterval $interval): DateTime {}

    /**
     * Get the TimeZone associated with the DateTime
     * @return DateTimeZone|false
     * @link https://php.net/manual/en/datetime.gettimezone.php
     */
    #[TentativeType]
    public function getTimezone(): DateTimeZone|false {}

    /**
     * Set the TimeZone associated with the DateTime
     * @param DateTimeZone $timezone
     * @return static
     * @link https://php.net/manual/en/datetime.settimezone.php
     */
    #[TentativeType]
    public function setTimezone(#[LanguageLevelTypeAware(['8.0' => 'DateTimeZone'], default: '')] $timezone): DateTime {}

    /**
     * Returns the timezone offset
     * @return int
     * @link https://php.net/manual/en/datetime.getoffset.php
     */
    #[TentativeType]
    public function getOffset(): int {}

    /**
     * Sets the current time of the DateTime object to a different time.
     * @param int $hour
     * @param int $minute
     * @param int $second
     * @param int $microsecond Added since 7.1
     * @return static
     * @link https://php.net/manual/en/datetime.settime.php
     */
    #[TentativeType]
    public function setTime(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $hour,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $minute,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $second = 0,
        #[PhpStormStubsElementAvailable(from: '7.1')] #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $microsecond = 0
    ): DateTime {}

    /**
     * Sets the current date of the DateTime object to a different date.
     * @param int $year
     * @param int $month
     * @param int $day
     * @return static
     * @link https://php.net/manual/en/datetime.setdate.php
     */
    #[TentativeType]
    public function setDate(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $year,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $month,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $day
    ): DateTime {}

    /**
     * Set a date according to the ISO 8601 standard - using weeks and day offsets rather than specific dates.
     * @param int $year
     * @param int $week
     * @param int $dayOfWeek
     * @return static
     * @link https://php.net/manual/en/datetime.setisodate.php
     */
    #[TentativeType]
    public function setISODate(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $year,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $week,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $dayOfWeek = 1
    ): DateTime {}

    /**
     * Sets the date and time based on a Unix timestamp.
     * @param int $timestamp
     * @return static
     * @link https://php.net/manual/en/datetime.settimestamp.php
     */
    #[TentativeType]
    public function setTimestamp(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $timestamp): DateTime {}

    /**
     * Gets the Unix timestamp.
     * @return int
     * @link https://php.net/manual/en/datetime.gettimestamp.php
     */
    #[TentativeType]
    public function getTimestamp(): int {}

    /**
     * Returns the difference between two DateTime objects represented as a DateInterval.
     * @param DateTimeInterface $targetObject The date to compare to.
     * @param bool $absolute [optional] Whether to return absolute difference.
     * @return DateInterval|false The DateInterval object representing the difference between the two dates.
     * @link https://php.net/manual/en/datetime.diff.php
     */
    #[TentativeType]
    public function diff(
        #[LanguageLevelTypeAware(['8.0' => 'DateTimeInterface'], default: '')] $targetObject,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $absolute = false
    ): DateInterval {}

    /**
     * Parse a string into a new DateTime object according to the specified format
     * @param string $format Format accepted by date().
     * @param string $datetime String representing the time.
     * @param null|DateTimeZone $timezone A DateTimeZone object representing the desired time zone.
     * @return DateTime|false
     * @link https://php.net/manual/en/datetime.createfromformat.php
     */
    #[TentativeType]
    public static function createFromFormat(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $format,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $datetime,
        #[LanguageLevelTypeAware(['8.0' => 'DateTimeZone|null'], default: 'DateTimeZone')] $timezone = null
    ): DateTime|false {}

    /**
     * Returns an array of warnings and errors found while parsing a date/time string
     * @return array|false
     * @link https://php.net/manual/en/datetime.getlasterrors.php
     */
    #[ArrayShape(["warning_count" => "int", "warnings" => "string[]", "error_count" => "int", "errors" => "string[]"])]
    #[TentativeType]
    public static function getLastErrors(): array|false {}

    /**
     * The __set_state handler
     * @link https://php.net/manual/en/datetime.set-state.php
     * @param array $array <p>Initialization array.</p>
     * @return DateTime <p>Returns a new instance of a DateTime object.</p>
     */
    public static function __set_state($array) {}

    /**
     * @param DateTimeInterface $object
     * @return DateTime
     * @since 8.0
     */
    public static function createFromInterface(DateTimeInterface $object): DateTime {}

    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function __serialize(): array {}

    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function __unserialize(array $data): void {}
}

/**
 * Representation of time zone
 * @link https://php.net/manual/en/class.datetimezone.php
 */
class DateTimeZone
{
    public const AFRICA = 1;
    public const AMERICA = 2;
    public const ANTARCTICA = 4;
    public const ARCTIC = 8;
    public const ASIA = 16;
    public const ATLANTIC = 32;
    public const AUSTRALIA = 64;
    public const EUROPE = 128;
    public const INDIAN = 256;
    public const PACIFIC = 512;
    public const UTC = 1024;
    public const ALL = 2047;
    public const ALL_WITH_BC = 4095;
    public const PER_COUNTRY = 4096;

    /**
     * @param string $timezone
     * @link https://php.net/manual/en/datetimezone.construct.php
     */
    public function __construct(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $timezone) {}

    /**
     * Returns the name of the timezone
     * @return string
     * @link https://php.net/manual/en/datetimezone.getname.php
     */
    #[TentativeType]
    public function getName(): string {}

    /**
     * Returns location information for a timezone
     * @return array|false
     * @link https://php.net/manual/en/datetimezone.getlocation.php
     */
    #[TentativeType]
    #[ArrayShape([
        'country_code' => 'string',
        'latitude' => 'double',
        'longitude' => 'double',
        'comments' => 'string',
    ])]
    public function getLocation(): array|false {}

    /**
     * Returns the timezone offset from GMT
     * @param DateTimeInterface $datetime
     * @return int
     * @link https://php.net/manual/en/datetimezone.getoffset.php
     */
    #[TentativeType]
    public function getOffset(DateTimeInterface $datetime): int {}

    /**
     * Returns all transitions for the timezone
     * @param int $timestampBegin
     * @param int $timestampEnd
     * @return array|false
     * @link https://php.net/manual/en/datetimezone.gettransitions.php
     */
    #[TentativeType]
    public function getTransitions(
        #[PhpStormStubsElementAvailable(from: '5.3', to: '5.6')] $timestampBegin,
        #[PhpStormStubsElementAvailable(from: '5.3', to: '5.6')] $timestampEnd,
        #[PhpStormStubsElementAvailable(from: '7.0')] #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $timestampBegin = null,
        #[PhpStormStubsElementAvailable(from: '7.0')] #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $timestampEnd = null
    ): array|false {}

    /**
     * Returns associative array containing dst, offset and the timezone name
     * @return array
     * @link https://php.net/manual/en/datetimezone.listabbreviations.php
     */
    #[TentativeType]
    public static function listAbbreviations(): array {}

    /**
     * Returns a numerically indexed array with all timezone identifiers
     * @param int $timezoneGroup
     * @param string $countryCode
     * @return array|false Returns the array of timezone identifiers, or <b>FALSE</b> on failure. Since PHP8, always returns <b>array</b>.
     * @link https://php.net/manual/en/datetimezone.listidentifiers.php
     */
    #[LanguageLevelTypeAware(["8.0" => "array"], default: "array|false")]
    #[TentativeType]
    public static function listIdentifiers(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $timezoneGroup = DateTimeZone::ALL,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $countryCode = null
    ): array {}

    /**
     * @link https://php.net/manual/en/datetime.wakeup.php
     */
    #[TentativeType]
    public function __wakeup(): void {}

    public static function __set_state($an_array) {}

    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function __serialize(): array {}

    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function __unserialize(array $data): void {}
}

/**
 * Representation of date interval. A date interval stores either a fixed amount of
 * time (in years, months, days, hours etc) or a relative time string in the format
 * that DateTime's constructor supports.
 * @link https://php.net/manual/en/class.dateinterval.php
 */
class DateInterval
{
    /**
     * Number of years
     * @var int
     */
    public $y;

    /**
     * Number of months
     * @var int
     */
    public $m;

    /**
     * Number of days
     * @var int
     */
    public $d;

    /**
     * Number of hours
     * @var int
     */
    public $h;

    /**
     * Number of minutes
     * @var int
     */
    public $i;

    /**
     * Number of seconds
     * @var int
     */
    public $s;

    /**
     * Number of microseconds
     * @since 7.1.0
     * @var float
     */
    public $f;

    /**
     * Is 1 if the interval is inverted and 0 otherwise
     * @var int
     */
    public $invert;

    /**
     * Total number of days the interval spans. If this is unknown, days will be FALSE.
     * @var int|false
     */
    public $days;

    /**
     * @param string $duration
     * @throws Exception when the $duration cannot be parsed as an interval.
     * @link https://php.net/manual/en/dateinterval.construct.php
     */
    public function __construct(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $duration) {}

    /**
     * Formats the interval
     * @param string $format
     * @return string
     * @link https://php.net/manual/en/dateinterval.format.php
     */
    #[TentativeType]
    public function format(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $format): string {}

    /**
     * Sets up a DateInterval from the relative parts of the string
     * @param string $datetime
     * @return DateInterval|false Returns a new {@link https://www.php.net/manual/en/class.dateinterval.php DateInterval}
     * instance on success, or <b>FALSE</b> on failure.
     * @link https://php.net/manual/en/dateinterval.createfromdatestring.php
     */
    #[TentativeType]
    public static function createFromDateString(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $datetime): DateInterval|false {}

    #[TentativeType]
    public function __wakeup(): void {}

    public static function __set_state($an_array) {}

    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function __serialize(): array {}

    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function __unserialize(array $data): void {}
}

/**
 * Representation of date period.
 * @link https://php.net/manual/en/class.dateperiod.php
 * @template TDate of DateTimeInterface
 * @template TEnd of ?DateTimeInterface
 * @implements \IteratorAggregate<int, TDate>
 */
class DatePeriod implements IteratorAggregate
{
    public const EXCLUDE_START_DATE = 1;

    /**
     * @since 8.2
     */
    public const INCLUDE_END_DATE = 2;

    /**
     * Start date
     * @var DateTimeInterface
     */
    #[LanguageLevelTypeAware(['8.2' => 'DateTimeInterface|null'], default: '')]
    #[Immutable]
    public $start;

    /**
     * Current iterator value.
     * @var DateTimeInterface|null
     */
    #[LanguageLevelTypeAware(['8.2' => 'DateTimeInterface|null'], default: '')]
    public $current;

    /**
     * End date.
     * @var DateTimeInterface|null
     */
    #[LanguageLevelTypeAware(['8.2' => 'DateTimeInterface|null'], default: '')]
    #[Immutable]
    public $end;

    /**
     * The interval
     * @var DateInterval
     */
    #[LanguageLevelTypeAware(['8.2' => 'DateInterval|null'], default: '')]
    #[Immutable]
    public $interval;

    /**
     * Number of recurrences.
     * @var int
     */
    #[LanguageLevelTypeAware(['8.2' => 'int'], default: '')]
    #[Immutable]
    public $recurrences;

    /**
     * Start of period.
     * @var bool
     */
    #[LanguageLevelTypeAware(['8.2' => 'bool'], default: '')]
    #[Immutable]
    public $include_start_date;

    /**
     * @since 8.2
     */
    #[Immutable]
    public bool $include_end_date;

    /**
     * @param TDate $start
     * @param DateInterval $interval
     * @param TEnd $end
     * @param int $options Can be set to DatePeriod::EXCLUDE_START_DATE.
     * @link https://php.net/manual/en/dateperiod.construct.php
     */
    public function __construct(DateTimeInterface $start, DateInterval $interval, DateTimeInterface $end, $options = 0) {}

    /**
     * @param TDate $start
     * @param DateInterval $interval
     * @param int $recurrences Number of recurrences
     * @param int $options Can be set to DatePeriod::EXCLUDE_START_DATE.
     * @link https://php.net/manual/en/dateperiod.construct.php
     */
    public function __construct(DateTimeInterface $start, DateInterval $interval, $recurrences, $options = 0) {}

    /**
     * @param string $isostr String containing the ISO interval.
     * @param int $options Can be set to DatePeriod::EXCLUDE_START_DATE.
     * @link https://php.net/manual/en/dateperiod.construct.php
     */
    public function __construct($isostr, $options = 0) {}

    /**
     * Gets the interval
     * @return DateInterval
     * @link https://php.net/manual/en/dateperiod.getdateinterval.php
     * @since 5.6.5
     */
    #[TentativeType]
    public function getDateInterval(): DateInterval {}

    /**
     * Gets the end date
     * @return DateTimeInterface|null
     * @link https://php.net/manual/en/dateperiod.getenddate.php
     * @since 5.6.5
     * @return TEnd
     */
    #[TentativeType]
    public function getEndDate(): ?DateTimeInterface {}

    /**
     * Gets the start date
     * @return DateTimeInterface
     * @link https://php.net/manual/en/dateperiod.getstartdate.php
     * @since 5.6.5
     * @return TDate
     */
    #[TentativeType]
    public function getStartDate(): DateTimeInterface {}

    #[TentativeType]
    public static function __set_state(#[PhpStormStubsElementAvailable(from: '7.3')] array $array): DatePeriod {}

    #[TentativeType]
    public function __wakeup(): void {}

    /**
     * Get the number of recurrences
     * @return int|null
     * @link https://php.net/manual/en/dateperiod.getrecurrences.php
     * @since 7.2.17
     */
    #[TentativeType]
    public function getRecurrences(): ?int {}

    /**
     * @return \Iterator<int, TDate>
     * @since 8.0
     */
    public function getIterator(): Iterator {}

    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function __serialize(): array {}

    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function __unserialize(array $data): void {}
}
