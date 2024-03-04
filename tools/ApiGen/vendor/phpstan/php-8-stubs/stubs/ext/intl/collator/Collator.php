<?php 

/** @generate-function-entries */
class Collator
{
    public function __construct(string $locale)
    {
    }
    /**
     * @tentative-return-type
     * @alias collator_create
     * @return (Collator | null)
     */
    public static function create(string $locale)
    {
    }
    /**
     * @tentative-return-type
     * @alias collator_compare
     * @return (int | false)
     */
    public function compare(string $string1, string $string2)
    {
    }
    /**
     * @tentative-return-type
     * @alias collator_sort
     * @return bool
     */
    public function sort(array &$array, int $flags = Collator::SORT_REGULAR)
    {
    }
    /**
     * @tentative-return-type
     * @alias collator_sort_with_sort_keys
     * @return bool
     */
    public function sortWithSortKeys(array &$array)
    {
    }
    /**
     * @tentative-return-type
     * @alias collator_asort
     * @return bool
     */
    public function asort(array &$array, int $flags = Collator::SORT_REGULAR)
    {
    }
    /**
     * @tentative-return-type
     * @alias collator_get_attribute
     * @return (int | false)
     */
    public function getAttribute(int $attribute)
    {
    }
    /**
     * @tentative-return-type
     * @alias collator_set_attribute
     * @return bool
     */
    public function setAttribute(int $attribute, int $value)
    {
    }
    /**
     * @tentative-return-type
     * @alias collator_get_strength
     * @return int
     */
    public function getStrength()
    {
    }
    /**
     * @return bool
     * @alias collator_set_strength
     */
    public function setStrength(int $strength)
    {
    }
    /**
     * @tentative-return-type
     * @alias collator_get_locale
     * @return (string | false)
     */
    public function getLocale(int $type)
    {
    }
    /**
     * @tentative-return-type
     * @alias collator_get_error_code
     * @return (int | false)
     */
    public function getErrorCode()
    {
    }
    /**
     * @tentative-return-type
     * @alias collator_get_error_message
     * @return (string | false)
     */
    public function getErrorMessage()
    {
    }
    /**
     * @tentative-return-type
     * @alias collator_get_sort_key
     * @return (string | false)
     */
    public function getSortKey(string $string)
    {
    }
}