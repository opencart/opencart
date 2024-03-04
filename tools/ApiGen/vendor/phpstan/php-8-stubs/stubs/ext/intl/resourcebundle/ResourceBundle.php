<?php 

/** @generate-function-entries */
class ResourceBundle implements \IteratorAggregate, \Countable
{
    public function __construct(?string $locale, ?string $bundle, bool $fallback = true)
    {
    }
    /**
     * @tentative-return-type
     * @alias resourcebundle_create
     * @return (ResourceBundle | null)
     */
    public static function create(?string $locale, ?string $bundle, bool $fallback = true)
    {
    }
    /**
     * @param (string | int) $index
     * @tentative-return-type
     * @alias resourcebundle_get
     * @return mixed
     */
    public function get($index, bool $fallback = true)
    {
    }
    /**
     * @tentative-return-type
     * @alias resourcebundle_count
     * @return int
     */
    public function count()
    {
    }
    /**
     * @return array|false
     * @alias resourcebundle_locales
     */
    public static function getLocales(string $bundle)
    {
    }
    /**
     * @tentative-return-type
     * @alias resourcebundle_get_error_code
     * @return int
     */
    public function getErrorCode()
    {
    }
    /**
     * @tentative-return-type
     * @alias resourcebundle_get_error_message
     * @return string
     */
    public function getErrorMessage()
    {
    }
    public function getIterator() : Iterator
    {
    }
}