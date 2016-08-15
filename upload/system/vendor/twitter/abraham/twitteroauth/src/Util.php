<?php
/**
 * The MIT License
 * Copyright (c) 2007 Andy Smith
 */
namespace Abraham\TwitterOAuth;

class Util
{
    /**
     * @param $input
     *
     * @return array|mixed|string
     */
    public static function urlencodeRfc3986($input)
    {
        $output = '';
        if (is_array($input)) {
            $output = array_map([__NAMESPACE__ . '\Util', 'urlencodeRfc3986'], $input);
        } elseif (is_scalar($input)) {
            $output = rawurlencode($input);
        }
        return $output;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function urldecodeRfc3986($string)
    {
        return urldecode($string);
    }

    /**
     * This function takes a input like a=b&a=c&d=e and returns the parsed
     * parameters like this
     * array('a' => array('b','c'), 'd' => 'e')
     *
     * @param mixed $input
     *
     * @return array
     */
    public static function parseParameters($input)
    {
        if (!isset($input) || !$input) {
            return [];
        }

        $pairs = explode('&', $input);

        $parameters = [];
        foreach ($pairs as $pair) {
            $split = explode('=', $pair, 2);
            $parameter = Util::urldecodeRfc3986($split[0]);
            $value = isset($split[1]) ? Util::urldecodeRfc3986($split[1]) : '';

            if (isset($parameters[$parameter])) {
                // We have already recieved parameter(s) with this name, so add to the list
                // of parameters with this name

                if (is_scalar($parameters[$parameter])) {
                    // This is the first duplicate, so transform scalar (string) into an array
                    // so we can add the duplicates
                    $parameters[$parameter] = [$parameters[$parameter]];
                }

                $parameters[$parameter][] = $value;
            } else {
                $parameters[$parameter] = $value;
            }
        }
        return $parameters;
    }

    /**
     * @param $params
     *
     * @return string
     */
    public static function buildHttpQuery($params)
    {
        if (!$params) {
            return '';
        }

        // Urlencode both keys and values
        $keys = Util::urlencodeRfc3986(array_keys($params));
        $values = Util::urlencodeRfc3986(array_values($params));
        $params = array_combine($keys, $values);

        // Parameters are sorted by name, using lexicographical byte value ordering.
        // Ref: Spec: 9.1.1 (1)
        uksort($params, 'strcmp');

        $pairs = [];
        foreach ($params as $parameter => $value) {
            if (is_array($value)) {
                // If two or more parameters share the same name, they are sorted by their value
                // Ref: Spec: 9.1.1 (1)
                // June 12th, 2010 - changed to sort because of issue 164 by hidetaka
                sort($value, SORT_STRING);
                foreach ($value as $duplicateValue) {
                    $pairs[] = $parameter . '=' . $duplicateValue;
                }
            } else {
                $pairs[] = $parameter . '=' . $value;
            }
        }
        // For each parameter, the name is separated from the corresponding value by an '=' character (ASCII code 61)
        // Each name-value pair is separated by an '&' character (ASCII code 38)
        return implode('&', $pairs);
    }
}
