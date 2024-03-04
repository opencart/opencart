<?php 

/** @generate-function-entries */
class Transliterator
{
    private final function __construct()
    {
    }
    /**
     * @tentative-return-type
     * @alias transliterator_create
     * @return (Transliterator | null)
     */
    public static function create(string $id, int $direction = Transliterator::FORWARD)
    {
    }
    /**
     * @tentative-return-type
     * @alias transliterator_create_from_rules
     * @return (Transliterator | null)
     */
    public static function createFromRules(string $rules, int $direction = Transliterator::FORWARD)
    {
    }
    /**
     * @tentative-return-type
     * @alias transliterator_create_inverse
     * @return (Transliterator | null)
     */
    public function createInverse()
    {
    }
    /**
     * @return array|false
     * @alias transliterator_list_ids
     */
    public static function listIDs()
    {
    }
    /**
     * @tentative-return-type
     * @alias transliterator_transliterate
     * @return (string | false)
     */
    public function transliterate(string $string, int $start = 0, int $end = -1)
    {
    }
    /**
     * @tentative-return-type
     * @alias transliterator_get_error_code
     * @return (int | false)
     */
    public function getErrorCode()
    {
    }
    /**
     * @tentative-return-type
     * @alias transliterator_get_error_message
     * @return (string | false)
     */
    public function getErrorMessage()
    {
    }
}