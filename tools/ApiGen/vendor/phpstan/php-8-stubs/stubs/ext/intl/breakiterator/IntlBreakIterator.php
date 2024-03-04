<?php 

/** @generate-function-entries */
class IntlBreakIterator implements \IteratorAggregate
{
    /**
     * @tentative-return-type
     * @return (IntlBreakIterator | null)
     */
    public static function createCharacterInstance(?string $locale = null)
    {
    }
    /**
     * @tentative-return-type
     * @return IntlCodePointBreakIterator
     */
    public static function createCodePointInstance()
    {
    }
    /**
     * @tentative-return-type
     * @return (IntlBreakIterator | null)
     */
    public static function createLineInstance(?string $locale = null)
    {
    }
    /**
     * @tentative-return-type
     * @return (IntlBreakIterator | null)
     */
    public static function createSentenceInstance(?string $locale = null)
    {
    }
    /**
     * @tentative-return-type
     * @return (IntlBreakIterator | null)
     */
    public static function createTitleInstance(?string $locale = null)
    {
    }
    /**
     * @tentative-return-type
     * @return (IntlBreakIterator | null)
     */
    public static function createWordInstance(?string $locale = null)
    {
    }
    private function __construct()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function current()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function first()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function following(int $offset)
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function getErrorCode()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function getErrorMessage()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getLocale(int $type)
    {
    }
    /**
     * @tentative-return-type
     * @return IntlPartsIterator
     */
    public function getPartsIterator(string $type = IntlPartsIterator::KEY_SEQUENTIAL)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | null)
     */
    public function getText()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isBoundary(int $offset)
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function last()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function next(?int $offset = null)
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function preceding(int $offset)
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function previous()
    {
    }
    /**
     * @tentative-return-type
     * @return (bool | null)
     */
    public function setText(string $text)
    {
    }
    public function getIterator() : Iterator
    {
    }
}