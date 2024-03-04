<?php 

/** @generate-function-entries */
class Normalizer
{
    /**
     * @tentative-return-type
     * @alias normalizer_normalize
     * @return (string | false)
     */
    public static function normalize(string $string, int $form = Normalizer::FORM_C)
    {
    }
    /**
     * @tentative-return-type
     * @alias normalizer_is_normalized
     * @return bool
     */
    public static function isNormalized(string $string, int $form = Normalizer::FORM_C)
    {
    }
    #if U_ICU_VERSION_MAJOR_NUM >= 56
    /**
     * @tentative-return-type
     * @alias normalizer_get_raw_decomposition
     * @return (string | null)
     */
    public static function getRawDecomposition(string $string, int $form = Normalizer::FORM_C)
    {
    }
}