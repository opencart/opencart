<?php 

class DatePeriod implements \IteratorAggregate
{
    /**
     * @param DateTimeInterface|string $start
     * @param DateInterval|int $interval
     * @param DateTimeInterface|int $end
     * @param int $options
     */
    public function __construct($start, $interval = UNKNOWN, $end = UNKNOWN, $options = UNKNOWN)
    {
    }
    /**
     * @tentative-return-type
     * @return DateTimeInterface
     */
    public function getStartDate()
    {
    }
    /**
     * @tentative-return-type
     * @return (DateTimeInterface | null)
     */
    public function getEndDate()
    {
    }
    /**
     * @tentative-return-type
     * @return DateInterval
     */
    public function getDateInterval()
    {
    }
    /**
     * @tentative-return-type
     * @return (int | null)
     */
    public function getRecurrences()
    {
    }
    #[\Since('8.2')]
    public function __serialize() : array;
    #[\Since('8.2')]
    public function __unserialize(array $data) : void;
    /**
     * @tentative-return-type
     * @return void
     */
    public function __wakeup()
    {
    }
    /**
     * @tentative-return-type
     * @return DatePeriod
     */
    public static function __set_state(array $array)
    {
    }
    public function getIterator() : Iterator
    {
    }
}