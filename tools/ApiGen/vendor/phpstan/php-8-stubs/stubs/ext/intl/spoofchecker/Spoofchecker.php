<?php 

/** @generate-function-entries */
class Spoofchecker
{
    public function __construct()
    {
    }
    /**
     * @param int $errorCode
     * @tentative-return-type
     * @return bool
     */
    public function isSuspicious(string $string, &$errorCode = null)
    {
    }
    /**
     * @param int $errorCode
     * @tentative-return-type
     * @return bool
     */
    public function areConfusable(string $string1, string $string2, &$errorCode = null)
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function setAllowedLocales(string $locales)
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function setChecks(int $checks)
    {
    }
    #if U_ICU_VERSION_MAJOR_NUM >= 58
    /**
     * @tentative-return-type
     * @return void
     */
    public function setRestrictionLevel(int $level)
    {
    }
}