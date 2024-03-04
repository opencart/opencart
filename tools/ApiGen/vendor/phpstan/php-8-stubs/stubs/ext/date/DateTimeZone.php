<?php 

class DateTimeZone
{
    public function __construct(string $timezone)
    {
    }
    /**
     * @tentative-return-type
     * @alias timezone_name_get
     * @return string
     */
    public function getName()
    {
    }
    /**
     * @tentative-return-type
     * @alias timezone_offset_get
     * @return int
     */
    public function getOffset(DateTimeInterface $datetime)
    {
    }
    /**
     * @return array|false
     * @alias timezone_transitions_get
     */
    public function getTransitions(int $timestampBegin = PHP_INT_MIN, int $timestampEnd = PHP_INT_MAX)
    {
    }
    /**
     * @return array|false
     * @alias timezone_location_get
     */
    public function getLocation()
    {
    }
    /**
     * @return array
     * @alias timezone_abbreviations_list
     */
    public static function listAbbreviations()
    {
    }
    /**
     * @return array
     * @alias timezone_identifiers_list
     */
    public static function listIdentifiers(int $timezoneGroup = DateTimeZone::ALL, ?string $countryCode = null)
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
     * @return DateTimeZone
     */
    public static function __set_state(array $array)
    {
    }
}