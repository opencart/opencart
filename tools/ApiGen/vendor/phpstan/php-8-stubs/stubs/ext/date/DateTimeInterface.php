<?php 

// NB: Adding return types to methods is a BC break!
// For now only using @return annotations here.
interface DateTimeInterface
{
    /**
     * @tentative-return-type
     * @return string
     */
    public function format(string $format);
    /**
     * @tentative-return-type
     * @return (DateTimeZone | false)
     */
    public function getTimezone();
    /**
     * @tentative-return-type
     * @return int
     */
    public function getOffset();
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function getTimestamp();
    /**
     * @tentative-return-type
     * @return (DateInterval | false)
     */
    public function diff(DateTimeInterface $targetObject, bool $absolute = false);
    /**
     * @tentative-return-type
     * @return void
     */
    public function __wakeup();
    #[\Since('8.2')]
    public function __serialize() : array;
    #[\Since('8.2')]
    public function __unserialize(array $data) : void;
}