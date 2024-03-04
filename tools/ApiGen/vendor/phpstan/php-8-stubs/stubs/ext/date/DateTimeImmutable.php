<?php 

class DateTimeImmutable implements \DateTimeInterface
{
    public function __construct(string $datetime = "now", ?DateTimeZone $timezone = null)
    {
    }
    #[\Since('8.2')]
    public function __serialize() : array
    {
    }
    #[\Since('8.2')]
    public function __unserialize(array $data) : void
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function __wakeup()
    {
    }
    /**
     * @tentative-return-type
     * @return DateTimeImmutable
     */
    public static function __set_state(array $array)
    {
    }
    /**
     * @tentative-return-type
     * @alias date_create_immutable_from_format
     * @return (DateTimeImmutable | false)
     */
    public static function createFromFormat(string $format, string $datetime, ?DateTimeZone $timezone = null)
    {
    }
    /**
     * @return array|false
     * @alias date_get_last_errors
     */
    public static function getLastErrors()
    {
    }
    /**
     * @tentative-return-type
     * @alias date_format
     * @return string
     */
    public function format(string $format)
    {
    }
    /**
     * @tentative-return-type
     * @alias date_timezone_get
     * @return (DateTimeZone | false)
     */
    public function getTimezone()
    {
    }
    /**
     * @tentative-return-type
     * @alias date_offset_get
     * @return int
     */
    public function getOffset()
    {
    }
    /**
     * @tentative-return-type
     * @alias date_timestamp_get
     * @return int
     */
    public function getTimestamp()
    {
    }
    /**
     * @tentative-return-type
     * @alias date_diff
     * @return DateInterval
     */
    public function diff(DateTimeInterface $targetObject, bool $absolute = false)
    {
    }
    /**
     * @tentative-return-type
     * @return (DateTimeImmutable | false)
     */
    public function modify(string $modifier)
    {
    }
    /**
     * @tentative-return-type
     * @return DateTimeImmutable
     */
    public function add(DateInterval $interval)
    {
    }
    /**
     * @tentative-return-type
     * @return DateTimeImmutable
     */
    public function sub(DateInterval $interval)
    {
    }
    /**
     * @tentative-return-type
     * @return DateTimeImmutable
     */
    public function setTimezone(DateTimeZone $timezone)
    {
    }
    /**
     * @tentative-return-type
     * @return DateTimeImmutable
     */
    public function setTime(int $hour, int $minute, int $second = 0, int $microsecond = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return DateTimeImmutable
     */
    public function setDate(int $year, int $month, int $day)
    {
    }
    /**
     * @tentative-return-type
     * @return DateTimeImmutable
     */
    public function setISODate(int $year, int $week, int $dayOfWeek = 1)
    {
    }
    /**
     * @tentative-return-type
     * @return DateTimeImmutable
     */
    public function setTimestamp(int $timestamp)
    {
    }
    /**
     * @tentative-return-type
     * @return DateTimeImmutable
     */
    public static function createFromMutable(DateTime $object)
    {
    }
    public static function createFromInterface(DateTimeInterface $object) : DateTimeImmutable
    {
    }
}