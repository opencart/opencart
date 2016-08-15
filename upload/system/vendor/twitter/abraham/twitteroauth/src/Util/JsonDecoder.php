<?php

namespace Abraham\TwitterOAuth\Util;

/**
 * @author louis <louis@systemli.org>
 */
class JsonDecoder
{
    /**
     * Decodes a JSON string to stdObject or associative array
     *
     * @param string $string
     * @param bool   $asArray
     *
     * @return array|object
     */
    public static function decode($string, $asArray)
    {
        if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
            return json_decode($string, $asArray, 512, JSON_BIGINT_AS_STRING);
        }

        return json_decode($string, $asArray);
    }
}
