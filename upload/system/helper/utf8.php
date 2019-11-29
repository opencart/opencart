<?php
mb_internal_encoding('UTF-8');

function utf8_strlen($string) {
    return mb_strlen($string);
}

function utf8_strpos($string, $needle, $offset = 0) {
    return mb_strpos($string, $needle, $offset);
}

function utf8_strrpos($string, $needle, $offset = 0) {
    return mb_strrpos($string, $needle, $offset);
}

function utf8_substr($string, $offset, $length = null) {
    if ($length === null) {
        return mb_substr($string, $offset, utf8_strlen($string));
    } else {
        return mb_substr($string, $offset, $length);
    }
}

function utf8_strtoupper($string) {
    return mb_strtoupper($string);
}

function utf8_strtolower($string) {
    return mb_strtolower($string);
}
