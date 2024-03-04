<?php 

/** @generate-function-entries */
class UConverter
{
    public function __construct(?string $destination_encoding = null, ?string $source_encoding = null)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function convert(string $str, bool $reverse = false)
    {
    }
    /**
     * @param int $error
     * @tentative-return-type
     * @return (string | int | array | null)
     */
    public function fromUCallback(int $reason, array $source, int $codePoint, &$error)
    {
    }
    /** @return array|false|null */
    public static function getAliases(string $name)
    {
    }
    /** @return array */
    public static function getAvailable()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false | null)
     */
    public function getDestinationEncoding()
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false | null)
     */
    public function getDestinationType()
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
     * @return (string | null)
     */
    public function getErrorMessage()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false | null)
     */
    public function getSourceEncoding()
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false | null)
     */
    public function getSourceType()
    {
    }
    /**
     * @tentative-return-type
     * @return (array | null)
     */
    public static function getStandards()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false | null)
     */
    public function getSubstChars()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public static function reasonText(int $reason)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setDestinationEncoding(string $encoding)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setSourceEncoding(string $encoding)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setSubstChars(string $chars)
    {
    }
    /**
     * @param int $error
     * @tentative-return-type
     * @return (string | int | array | null)
     */
    public function toUCallback(int $reason, string $source, string $codeUnits, &$error)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public static function transcode(string $str, string $toEncoding, string $fromEncoding, ?array $options = null)
    {
    }
}