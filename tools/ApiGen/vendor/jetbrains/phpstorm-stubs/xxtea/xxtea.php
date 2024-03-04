<?php

/**
 * Stubs for XXTEA
 * https://pecl.php.net/package/xxtea.
 */
class XXTEA
{
    /**
     * Encrypts data.
     *
     * @param string $data Data to be encrypted
     * @param string $key Encryption key
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function encrypt($data, $key) {}

    /**
     * Decrypts data.
     *
     * @param string $data Data to be decrypted
     * @param string $key Encryption key
     *
     * @return string|false
     *
     * @since 1.0.0
     */
    public static function decrypt($data, $key) {}
}

/**
 * Encrypts data.
 *
 * @param string $data Data to be encrypted
 * @param string $key Encryption key
 *
 * @return string
 *
 * @since 1.0.0
 */
function xxtea_encrypt($data, $key) {}

/**
 * Decrypts data.
 *
 * @param string $data Data to be decrypted
 * @param string $key Encryption key
 *
 * @return string|false
 *
 * @since 1.0.0
 */
function xxtea_decrypt($data, $key) {}
