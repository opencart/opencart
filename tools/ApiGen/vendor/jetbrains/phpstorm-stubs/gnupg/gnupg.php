<?php

use JetBrains\PhpStorm\ExpectedValues;

/** GNUPG Constants
 * @link https://php.net/manual/en/gnupg.constants.php
 */
define('GNUPG_SIG_MODE_NORMAL', 0);
define('GNUPG_SIG_MODE_DETACH', 1);
define('GNUPG_SIG_MODE_CLEAR', 2);
define('GNUPG_VALIDITY_UNKNOWN', 0);
define('GNUPG_VALIDITY_UNDEFINED', 1);
define('GNUPG_VALIDITY_NEVER', 2);
define('GNUPG_VALIDITY_MARGINAL', 3);
define('GNUPG_VALIDITY_FULL', 4);
define('GNUPG_VALIDITY_ULTIMATE', 5);
define('GNUPG_PROTOCOL_OpenPGP', 0);
define('GNUPG_PROTOCOL_CMS', 1);
define('GNUPG_SIGSUM_VALID', 1);
define('GNUPG_SIGSUM_GREEN', 2);
define('GNUPG_SIGSUM_RED', 4);
define('GNUPG_SIGSUM_KEY_REVOKED', 16);
define('GNUPG_SIGSUM_KEY_EXPIRED', 32);
define('GNUPG_SIGSUM_SIG_EXPIRED', 64);
define('GNUPG_SIGSUM_KEY_MISSING', 128);
define('GNUPG_SIGSUM_CRL_MISSING', 256);
define('GNUPG_SIGSUM_CRL_TOO_OLD', 512);
define('GNUPG_SIGSUM_BAD_POLICY', 1024);
define('GNUPG_SIGSUM_SYS_ERROR', 2048);
define('GNUPG_ERROR_WARNING', 1);
define('GNUPG_ERROR_EXCEPTION', 2);
define('GNUPG_ERROR_SILENT', 3);
define('GNUPG_PK_RSA', 1);
define('GNUPG_PK_RSA_E', 2);
define('GNUPG_PK_RSA_S', 3);
define('GNUPG_PK_DSA', 17);
define('GNUPG_PK_ELG', 20);
define('GNUPG_PK_ELG_E', 16);
define('GNUPG_PK_ECC', 18);
define('GNUPG_PK_ECDSA', 301);
define('GNUPG_PK_ECDH', 302);
define('GNUPG_PK_EDDSA', 303);
define('GNUPG_GPGME_VERSION', '1.15.1');

/**
 * GNUPG Encryption Class
 * @link https://php.net/manual/en/book.gnupg.php
 * Class gnupg
 */
class gnupg
{
    public const SIG_MODE_NORMAL = 0;
    public const SIG_MODE_DETACH = 1;
    public const SIG_MODE_CLEAR = 2;
    public const VALIDITY_UNKNOWN = 0;
    public const VALIDITY_UNDEFINED = 1;
    public const VALIDITY_NEVER = 2;
    public const VALIDITY_MARGINAL = 3;
    public const VALIDITY_FULL = 4;
    public const VALIDITY_ULTIMATE = 5;
    public const PROTOCOL_OpenPGP = 0;
    public const PROTOCOL_CMS = 1;
    public const SIGSUM_VALID = 1;
    public const SIGSUM_GREEN = 2;
    public const SIGSUM_RED = 4;
    public const SIGSUM_KEY_REVOKED = 16;
    public const SIGSUM_KEY_EXPIRED = 32;
    public const SIGSUM_SIG_EXPIRED = 64;
    public const SIGSUM_KEY_MISSING = 128;
    public const SIGSUM_CRL_MISSING = 256;
    public const SIGSUM_CRL_TOO_OLD = 512;
    public const SIGSUM_BAD_POLICY = 1024;
    public const SIGSUM_SYS_ERROR = 2048;
    public const ERROR_WARNING = 1;
    public const ERROR_EXCEPTION = 2;
    public const ERROR_SILENT = 3;
    public const PK_RSA = 1;
    public const PK_RSA_E = 2;
    public const PK_RSA_S = 3;
    public const PK_DSA = 17;
    public const PK_ELG = 20;
    public const PK_ELG_E = 16;
    public const PK_ECC = 18;
    public const PK_ECDSA = 301;
    public const PK_ECDH = 302;
    public const PK_EDDSA = 303;

    public function __construct($options = null) {}

    /**
     * Add a key for decryption
     * @link https://php.net/manual/en/function.gnupg-adddecryptkey.php
     *
     * @param string $kye
     * @param string $passphrase
     *
     * @return bool
     */
    public function adddecryptkey($kye, $passphrase) {}

    /**
     * Verifies a signed text
     * @link https://php.net/manual/en/function.gnupg-verify.php
     *
     * @param string $text
     * @param string $signature
     * @param string &$plaintext
     *
     * @return array|false On success, this function returns information about the signature.
     *               On failure, this function returns false.
     */
    public function verify($text, $signature, &$plaintext = null) {}

    /**
     * Add a key for encryption
     * @link https://php.net/manual/en/function.gnupg-addencryptkey.php
     *
     * @param string $kye
     *
     * @return bool
     */
    public function addencryptkey($kye) {}

    /**
     * Add a key for signing
     * @link https://php.net/manual/en/function.gnupg-addsignkey.php
     *
     * @param string $kye
     * @param string $passphrase
     *
     * @return bool
     */
    public function addsignkey($kye, $passphrase = null) {}

    public function deletekey($kye, $allow_secret) {}

    public function gettrustlist($pattern) {}

    public function listsignatures($kyeid) {}

    /**
     * Removes all keys which were set for decryption before
     * @link https://php.net/manual/en/function.gnupg-cleardecryptkeys.php
     *
     * @return bool
     */
    public function cleardecryptkeys() {}

    /**
     * Removes all keys which were set for encryption before
     * @link https://php.net/manual/en/function.gnupg-clearencryptkeys.php
     *
     * @return bool
     */
    public function clearencryptkeys() {}

    /**
     * Removes all keys which were set for signing before
     * @link https://php.net/manual/en/function.gnupg-clearsignkeys.php
     *
     * @return bool
     */
    public function clearsignkeys() {}

    /**
     * Decrypts a given text
     * @link https://php.net/manual/en/function.gnupg-decrypt.php
     *
     * @param string $enctext
     *
     * @return string|false On success, this function returns the decrypted text.
     *                On failure, this function returns false.
     */
    public function decrypt($enctext) {}

    /**
     * Decrypts and verifies a given text
     * @link https://php.net/manual/en/function.gnupg-decryptverify.php
     *
     * @param string $enctext
     * @param string &$plaintext
     *
     * @return array|false On success, this function returns information about the signature and
     *               fills the  parameter with the decrypted text.
     *               On failure, this function returns false.
     */
    public function decryptverify($enctext, &$plaintext) {}

    /**
     * Encrypts a given text
     * @link https://php.net/manual/en/function.gnupg-encrypt.php
     *
     * @param string $text
     *
     * @return string|false On success, this function returns the encrypted text.
     *                On failure, this function returns false.
     */
    public function encrypt($text) {}

    /**
     * Encrypts and signs a given text
     * @link https://php.net/manual/en/function.gnupg-encryptsign.php
     *
     * @param string $text
     *
     * @return string|false On success, this function returns the encrypted and signed text.
     *                On failure, this function returns false.
     */
    public function encryptsign($text) {}

    /**
     * Exports a key
     * @link https://php.net/manual/en/function.gnupg-export.php
     *
     * @param string $pattern
     *
     * @return string|false On success, this function returns the keydata.
     *                On failure, this function returns false.
     */
    public function export($pattern) {}

    /**
     * Returns the errortext, if a function fails
     * @link https://php.net/manual/en/function.gnupg-geterror.php
     *
     * @return string|false Returns an errortext, if an error has occurred, otherwise false.
     */
    public function geterror() {}

    /**
     * Returns the currently active protocol for all operations
     * @link https://php.net/manual/en/function.gnupg-getprotocol.php
     *
     * @return int Returns the currently active protocol, which can be one of
     *             or
     *             .
     */
    public function getprotocol() {}

    /**
     * Imports a key
     * @link https://php.net/manual/en/function.gnupg-import.php
     *
     * @param string $kye
     *
     * @return array|false On success, this function returns and info-array about the importprocess.
     *               On failure, this function returns false.
     */
    public function import($kye) {}

    /**
     * Initialize a connection
     * @link https://php.net/manual/en/function.gnupg-init.php
     *
     * @return resource A GnuPG ``resource`` connection used by other GnuPG functions.
     */
    public function init() {}

    /**
     * Returns an array with information about all keys that matches the given pattern
     * @link https://php.net/manual/en/function.gnupg-keyinfo.php
     *
     * @param string $pattern
     *
     * @return array Returns an array with information about all keys that matches the given
     *               pattern or false, if an error has occurred.
     */
    public function keyinfo($pattern, $secret_only = false) {}

    /**
     * Toggle armored output
     * @link https://php.net/manual/en/function.gnupg-setarmor.php
     *
     * @param int $armor
     *
     * @return bool
     */
    public function setarmor($armor) {}

    /**
     * Sets the mode for error_reporting
     * @link https://php.net/manual/en/function.gnupg-seterrormode.php
     *
     * @param int $errnmode
     *
     * @return void
     */
    public function seterrormode($errnmode) {}

    /**
     * Sets the mode for signing
     * @link https://php.net/manual/en/function.gnupg-setsignmode.php
     *
     * @param int $signmode
     *
     * @return bool
     */
    public function setsignmode($signmode) {}

    /**
     * Signs a given text
     * @link https://php.net/manual/en/function.gnupg-sign.php
     *
     * @param string $text
     *
     * @return string|false On success, this function returns the signed text or the signature.
     *                On failure, this function returns false.
     */
    public function sign($text) {}

    public function getengineinfo() {}

    public function geterrorinfo() {}
}

class gnupg_keylistiterator implements Iterator
{
    public function __construct() {}

    public function current() {}

    public function key() {}

    public function next() {}

    public function rewind() {}

    public function valid() {}
}

/**
 * Initialize a connection
 * @link https://www.php.net/manual/en/function.gnupg-init.php
 * @param $options
 * @return resource
 */
function gnupg_init($options = null) {}

/**
 * Returns an array with information about all keys that matches the given pattern
 * @link https://www.php.net/manual/en/function.gnupg-keyinfo.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param string $pattern The pattern being checked against the keys.
 * @return array|false Returns an array with information about all keys that matches the given pattern
 * or false, if an error has occurred.
 */
function gnupg_keyinfo($res, $pattern, $secret_only = false) {}

/**
 * Signs a given text
 * @link https://www.php.net/manual/en/function.gnupg-sign.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param string $text The plain text being signed.
 * @return string|false On success, this function returns the signed text or the signature.
 * On failure, this function returns false.
 */
function gnupg_sign($res, $text) {}

/**
 * Removes all keys which were set for signing before
 * @link https://www.php.net/manual/en/function.gnupg-clearsignkeys.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @return bool Returns true on success or false on failure.
 */
function gnupg_clearsignkeys($res) {}

/**
 * Verifies a signed text
 * @link https://www.php.net/manual/en/function.gnupg-verify.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param string $text The signed text.
 * @param string|false $signature The signature. To verify a clearsigned text, set signature to false.
 * @param string &$plaintext The plain text. If this optional parameter is passed, it is filled with the plain text.
 * @return array|false On success, this function returns information about the signature.
 * On failure, this function returns false.
 */
function gnupg_verify($res, $text, $signature, &$plaintext = '') {}

/**
 * Removes all keys which were set for encryption before
 * @link https://www.php.net/manual/en/function.gnupg-clearencryptkeys.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @return bool Returns true on success or false on failure.
 */
function gnupg_clearencryptkeys($res) {}

/**
 * Removes all keys which were set for decryption before
 * @link https://www.php.net/manual/en/function.gnupg-cleardecryptkeys.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @return bool Returns true on success or false on failure.
 */
function gnupg_cleardecryptkeys($res) {}

/**
 * Add a key for decryption
 * @link https://www.php.net/manual/en/function.gnupg-adddecryptkey.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param string $kye The fingerprint key.
 * @param string $passphrase The pass phrase.
 * @return bool Returns true on success or false on failure.
 */
function gnupg_adddecryptkey($res, $kye, $passphrase) {}

/**
 * Add a key for encryption
 * @link https://www.php.net/manual/en/function.gnupg-addencryptkey.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param string $kye The fingerprint key.
 * @return bool Returns true on success or false on failure.
 */
function gnupg_addencryptkey($res, $kye) {}

/**
 * Toggle armored output
 * @link https://www.php.net/manual/en/function.gnupg-setarmor.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param int $armor Pass a non-zero integer-value to this function to enable armored-output (default).
 * Pass 0 to disable armored output.
 * @return bool Returns true on success or false on failure.
 */
function gnupg_setarmor($res, $armor) {}

/**
 * Encrypts a given text
 * @link https://www.php.net/manual/en/function.gnupg-encrypt.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param string $text The text being encrypted.
 * @return string|false On success, this function returns the encrypted text. On failure, this function returns false.
 */
function gnupg_encrypt($res, $text) {}

/**
 * Decrypts a given text
 * @link https://www.php.net/manual/en/function.gnupg-decrypt.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param string $enctext The text being decrypted.
 * @return string|false On success, this function returns the decrypted text. On failure, this function returns false.
 */
function gnupg_decrypt($res, $enctext) {}

/**
 * Exports a key
 * @link https://www.php.net/manual/en/function.gnupg-export.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param string $pattern The fingerprint key.
 * @return string|false On success, this function returns the keydata. On failure, this function returns false.
 */
function gnupg_export($res, $pattern) {}

/**
 * Imports a key
 * @link https://www.php.net/manual/en/function.gnupg-import.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param string $kye The data key that is being imported.
 * @return array|false On success, this function returns and info-array about the importprocess.
 * On failure, this function returns false.
 */
function gnupg_import($res, $kye) {}

function gnupg_getengineinfo($res) {}

/**
 * Returns the currently active protocol for all operations
 * @link https://www.php.net/manual/en/function.gnupg-getprotocol.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @return int Returns the currently active protocol, which can be one of GNUPG_PROTOCOL_OpenPGP or GNUPG_PROTOCOL_CMS.
 */
function gnupg_getprotocol($res) {}

/**
 * Sets the mode for signing
 * @link https://www.php.net/manual/en/function.gnupg-setsignmode.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param int $signmode The mode for signing. Takes a constant indicating what type of signature should be produced.
 * The possible values are GNUPG_SIG_MODE_NORMAL, GNUPG_SIG_MODE_DETACH and GNUPG_SIG_MODE_CLEAR.
 * By default GNUPG_SIG_MODE_CLEAR is used.
 * @return bool Returns true on success or false on failure.
 */
function gnupg_setsignmode($res, #[ExpectedValues([GNUPG_SIG_MODE_NORMAL|GNUPG_SIG_MODE_DETACH|GNUPG_SIG_MODE_CLEAR])] $signmode) {}

/**
 * Encrypts and signs a given text
 * @link https://www.php.net/manual/en/function.gnupg-encryptsign.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param string $text The text being encrypted.
 * @return string|false On success, this function returns the encrypted and signed text.
 * On failure, this function returns false.
 */
function gnupg_encryptsign($res, $text) {}

/**
 * Decrypts and verifies a given text and returns information about the signature.
 * @link https://www.php.net/manual/en/function.gnupg-decryptverify.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param string $enctext The text being decrypted.
 * @param string &$plaintext The parameter plaintext gets filled with the decrypted text.
 * @return array|false On success, this function returns information about the signature and fills the plaintext parameter
 * with the decrypted text. On failure, this function returns false.
 */
function gnupg_decryptverify($res, $enctext, &$plaintext) {}

/**
 * Returns the errortext, if a function fails
 * @link https://www.php.net/manual/en/function.gnupg-geterror.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @return string|false Returns an errortext, if an error has occurred, otherwise false.
 */
function gnupg_geterror($res) {}

function gnupg_geterrorinfo($res) {}

/**
 * Add a key for signing
 * @link https://www.php.net/manual/en/function.gnupg-addsignkey.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param string $kye The fingerprint key.
 * @param string $passphrase The pass phrase.
 * @return bool Returns true on success or false on failure.
 */
function gnupg_addsignkey($res, $kye, $passphrase) {}
function gnupg_deletekey($res, $kye, $allow_secret) {}
function gnupg_gettrustlist($res, $pattern) {}
function gnupg_listsignatures($res, $kyeid) {}

/**
 * Sets the mode for error_reporting
 * @link https://www.php.net/manual/en/function.gnupg-seterrormode.php
 * @param resource $res The gnupg identifier, from a call to gnupg_init() or gnupg.
 * @param int $errnmode The error mode. takes a constant indicating what type of error_reporting should be used.
 * The possible values are GNUPG_ERROR_WARNING, GNUPG_ERROR_EXCEPTION and GNUPG_ERROR_SILENT. By default GNUPG_ERROR_SILENT is used.
 */
function gnupg_seterrormode($res, #[ExpectedValues([GNUPG_ERROR_WARNING|GNUPG_ERROR_EXCEPTION|GNUPG_ERROR_SILENT])] $errnmode) {}
