<?php

namespace Crypto;

/**
 * Class providing cipher algorithms
 */
class Cipher
{
    public const MODE_ECB = 1;
    public const MODE_CBC = 2;
    public const MODE_CFB = 3;
    public const MODE_OFB = 4;
    public const MODE_CTR = 5;
    public const MODE_GCM = 6;
    public const MODE_CCM = 7;
    public const MODE_XTS = 65537;

    /**
     * Returns cipher algorithms
     * @param bool $aliases
     * @param string $prefix
     * @return string
     */
    public static function getAlgorithms($aliases = false, $prefix = null) {}

    /**
     * Finds out whether algorithm exists
     * @param string $algorithm
     * @return bool
     */
    public static function hasAlgorithm($algorithm) {}

    /**
     * Finds out whether the cipher mode is defined in the used OpenSSL library
     * @param int $mode
     * @return bool
     */
    public static function hasMode($mode) {}

    /**
     * Cipher magic method for calling static methods
     * @param string $name
     * @param array $arguments
     */
    public static function __callStatic($name, $arguments) {}

    /**
     * Cipher constructor
     * @param string $algorithm
     * @param int $mode
     * @param string $key_size
     */
    public function __construct($algorithm, $mode = null, $key_size = null) {}

    /**
     * Returns cipher algorithm string
     * @return string
     */
    public function getAlgorithmName() {}

    /**
     * Initializes cipher encryption
     * @param string $key
     * @param string $iv
     * @return bool
     */
    public function encryptInit($key, $iv = null) {}

    /**
     * Updates cipher encryption
     * @param string $data
     * @return string
     */
    public function encryptUpdate($data) {}

    /**
     * Finalizes cipher encryption
     * @return string
     */
    public function encryptFinish() {}

    /**
     * Encrypts text to ciphertext
     * @param string $data
     * @param string $key
     * @param string $iv
     * @return string
     */
    public function encrypt($data, $key, $iv = null) {}

    /**
     * Initializes cipher decryption
     * @param string $key
     * @param string $iv
     * @return null
     */
    public function decryptInit($key, $iv = null) {}

    /**
     * Updates cipher decryption
     * @param string $data
     * @return string
     */
    public function decryptUpdate($data) {}

    /**
     * Finalizes cipher decryption
     * @return string
     */
    public function decryptFinish() {}

    /**
     * Decrypts ciphertext to decrypted text
     * @param string $data
     * @param string $key
     * @param string $iv
     * @return string
     */
    public function decrypt($data, $key, $iv = null) {}

    /**
     * Returns cipher block size
     * @return int
     */
    public function getBlockSize() {}

    /**
     * Returns cipher key length
     * @return int
     */
    public function getKeyLength() {}

    /**
     * Returns cipher IV length
     * @return int
     */
    public function getIVLength() {}

    /**
     * Returns cipher mode
     * @return int
     */
    public function getMode() {}

    /**
     * Returns authentication tag
     * @return string
     */
    public function getTag() {}

    /**
     * Sets authentication tag
     * @param string $tag
     * @return bool
     */
    public function setTag($tag) {}

    /**
     * Set authentication tag length
     * @param int $tag_length
     * @return bool
     */
    public function setTagLength($tag_length) {}

    /**
     * Sets additional application data for authenticated encryption
     * @param string $aad
     * @return bool
     */
    public function setAAD($aad) {}
}

/**
 * Exception class for cipher errors
 */
class CipherException extends \Exception
{
    /**
     * Cipher '%s' algorithm not found
     */
    public const ALGORITHM_NOT_FOUND = 1;

    /**
     * Cipher static method '%s' not found
     */
    public const STATIC_METHOD_NOT_FOUND = 2;

    /**
     * Cipher static method %s can accept max two arguments
     */
    public const STATIC_METHOD_TOO_MANY_ARGS = 3;

    /**
     * Cipher mode not found
     */
    public const MODE_NOT_FOUND = 4;

    /**
     * Cipher mode %s is not available in installed OpenSSL library
     */
    public const MODE_NOT_AVAILABLE = 5;

    /**
     * The authentication is not supported for %s cipher mode
     */
    public const AUTHENTICATION_NOT_SUPPORTED = 6;

    /**
     * Invalid length of key for cipher '%s' algorithm (required length: %d)
     */
    public const KEY_LENGTH_INVALID = 7;

    /**
     * Invalid length of initial vector for cipher '%s' algorithm (required length: %d)
     */
    public const IV_LENGTH_INVALID = 8;

    /**
     * AAD setter has to be called before encryption or decryption
     */
    public const AAD_SETTER_FORBIDDEN = 9;

    /**
     * AAD setter failed
     */
    public const AAD_SETTER_FAILED = 10;

    /**
     * AAD length can't exceed max integer length
     */
    public const AAD_LENGTH_HIGH = 11;

    /**
     * Tag getter has to be called after encryption
     */
    public const TAG_GETTER_FORBIDDEN = 12;

    /**
     * Tag setter has to be called before decryption
     */
    public const TAG_SETTER_FORBIDDEN = 13;

    /**
     * Tag getter failed
     */
    public const TAG_GETTER_FAILED = 14;

    /**
     * Tag setter failed
     */
    public const TAG_SETTER_FAILED = 15;

    /**
     * Tag length setter has to be called before encryption
     */
    public const TAG_LENGTH_SETTER_FORBIDDEN = 16;

    /**
     * Tag length can't be lower than 32 bits (4 characters)
     */
    public const TAG_LENGTH_LOW = 17;

    /**
     * Tag length can't exceed 128 bits (16 characters)
     */
    public const TAG_LENGTH_HIGH = 18;

    /**
     * Tag verification failed
     */
    public const TAG_VERIFY_FAILED = 19;

    /**
     * Initialization of cipher algorithm failed
     */
    public const INIT_ALG_FAILED = 20;

    /**
     * Initialization of cipher context failed
     */
    public const INIT_CTX_FAILED = 21;

    /**
     * Cipher object is already used for decryption
     */
    public const INIT_ENCRYPT_FORBIDDEN = 22;

    /**
     * Cipher object is already used for encryption
     */
    public const INIT_DECRYPT_FORBIDDEN = 23;

    /**
     * Updating of cipher failed
     */
    public const UPDATE_FAILED = 24;

    /**
     * Cipher object is not initialized for encryption
     */
    public const UPDATE_ENCRYPT_FORBIDDEN = 25;

    /**
     * Cipher object is not initialized for decryption
     */
    public const UPDATE_DECRYPT_FORBIDDEN = 26;

    /**
     * Finalizing of cipher failed
     */
    public const FINISH_FAILED = 27;

    /**
     * Cipher object is not initialized for encryption
     */
    public const FINISH_ENCRYPT_FORBIDDEN = 28;

    /**
     * Cipher object is not initialized for decryption
     */
    public const FINISH_DECRYPT_FORBIDDEN = 29;

    /**
     * Input data length can't exceed max integer length
     */
    public const INPUT_DATA_LENGTH_HIGH = 30;
}

/**
 * Class providing hash algorithms
 */
class Hash
{
    /**
     * Returns hash algorithms
     * @param bool $aliases
     * @param string $prefix
     * @return string
     */
    public static function getAlgorithms($aliases = false, $prefix = null) {}

    /**
     * Finds out whether algorithm exists
     * @param string $algorithm
     * @return bool
     */
    public static function hasAlgorithm($algorithm) {}

    /**
     * Hash magic method for calling static methods
     * @param string $name
     * @param array $arguments
     */
    public static function __callStatic($name, $arguments) {}

    /**
     * Hash constructor
     * @param string $algorithm
     */
    public function __construct($algorithm) {}

    /**
     * Returns hash algorithm string
     * @return string
     */
    public function getAlgorithmName() {}

    /**
     * Updates hash
     * @param string $data
     * @return null
     */
    public function update($data) {}

    /**
     * Return hash digest in raw foramt
     * @return string
     */
    public function digest() {}

    /**
     * Return hash digest in hex format
     * @return string
     */
    public function hexdigest() {}

    /**
     * Returns hash block size
     * @return int
     */
    public function getBlockSize() {}

    /**
     * Returns hash size
     * @return int
     */
    public function getSize() {}
}

/**
 * Exception class for hash errors
 */
class HashException extends \Exception
{
    /**
     * Hash algorithm '%s' not found
     */
    public const HASH_ALGORITHM_NOT_FOUND = 1;

    /**
     * Hash static method '%s' not found
     */
    public const STATIC_METHOD_NOT_FOUND = 2;

    /**
     * Hash static method %s can accept max one argument
     */
    public const STATIC_METHOD_TOO_MANY_ARGS = 3;

    /**
     * Initialization of hash failed
     */
    public const INIT_FAILED = 4;

    /**
     * Updating of hash context failed
     */
    public const UPDATE_FAILED = 5;

    /**
     * Creating of hash digest failed
     */
    public const DIGEST_FAILED = 6;

    /**
     * Input data length can't exceed max integer length
     */
    public const INPUT_DATA_LENGTH_HIGH = 7;
}

/**
 * Abstract class for MAC subclasses
 */
abstract class MAC extends Hash
{
    /**
     * Create a MAC (used by MAC subclasses - HMAC and CMAC)
     * @param string $algorithm
     * @param string $key
     */
    public function __construct($algorithm, $key) {}
}

/**
 * Exception class for MAC errors
 */
class MACException extends HashException
{
    /**
     * MAC algorithm '%s' not found
     */
    public const MAC_ALGORITHM_NOT_FOUND = 1;

    /**
     * The key length for MAC is invalid
     */
    public const KEY_LENGTH_INVALID = 2;
}

/**
 * Class providing HMAC functionality
 */
class HMAC extends MAC {}

/**
 * Class providing CMAC functionality
 */
class CMAC extends MAC {}

/**
 * Abstract class for KDF subclasses
 */
abstract class KDF
{
    /**
     * KDF constructor
     * @param int $length
     * @param string $salt
     */
    public function __construct($length, $salt = null) {}

    /**
     * Get key length
     * @return int
     */
    public function getLength() {}

    /**
     * Set key length
     * @param int $length
     * @return bool
     */
    public function setLength($length) {}

    /**
     * Get salt
     * @return string
     */
    public function getSalt() {}

    /**
     * Set salt
     * @param string $salt
     * @return bool
     */
    public function setSalt($salt) {}
}

/**
 * Exception class for KDF errors
 */
class KDFException
{
    /**
     * The key length is too low
     */
    public const KEY_LENGTH_LOW = 1;

    /**
     * The key length is too high
     */
    public const KEY_LENGTH_HIGH = 2;

    /**
     * The salt is too long
     */
    public const SALT_LENGTH_HIGH = 3;

    /**
     * The password is too long
     */
    public const PASSWORD_LENGTH_INVALID = 4;

    /**
     * KDF derivation failed
     */
    public const DERIVATION_FAILED = 5;
}

/**
 * Class providing PBKDF2 functionality
 */
class PBKDF2 extends KDF
{
    /**
     * KDF constructor
     * @param string $hashAlgorithm
     * @param int $length
     * @param string $salt
     * @param int $iterations
     */
    public function __construct($hashAlgorithm, $length, $salt = null, $iterations = 1000) {}

    /**
     * Deriver hash for password
     * @param string $password
     * @return string
     */
    public function derive($password) {}

    /**
     * Get iterations
     * @return int
     */
    public function getIterations() {}

    /**
     * Set iterations
     * @param int $iterations
     * @return bool
     */
    public function setIterations($iterations) {}

    /**
     * Get hash algorithm
     * @return string
     */
    public function getHashAlgorithm() {}

    /**
     * Set hash algorithm
     * @param string $hashAlgorithm
     * @return bool
     */
    public function setHashAlgorithm($hashAlgorithm) {}
}

/**
 * Exception class for PBKDF2 errors
 */
class PBKDF2Exception extends KDFException
{
    /**
     * Hash algorithm '%s' not found
     */
    public const HASH_ALGORITHM_NOT_FOUND = 1;

    /**
     * Iterations count is too high
     */
    public const ITERATIONS_HIGH = 2;
}

/**
 * Class for base64 encoding and docoding
 */
class Base64
{
    /**
     * Encodes string $data to base64 encoding
     * @param string $data
     * @return string
     */
    public function encode($data) {}

    /**
     * Decodes base64 string $data to raw encoding
     * @param string $data
     * @return string
     */
    public function decode($data) {}

    /**
     * Base64 constructor
     */
    public function __construct() {}

    /**
     * Encodes block of characters from $data and saves the reminder of the last block
     * to the encoding context
     * @param string $data
     */
    public function encodeUpdate($data) {}

    /**
     * Encodes characters that left in the encoding context
     */
    public function encodeFinish() {}

    /**
     * Decodes block of characters from $data and saves the reminder of the last block
     * to the encoding context
     * @param string $data
     */
    public function decodeUpdate($data) {}

    /**
     * Decodes characters that left in the encoding context
     */
    public function decodeFinish() {}
}

/**
 * Exception class for base64 errors
 */
class Base64Exception extends \Exception
{
    /**
     * The object is already used for decoding
     */
    public const ENCODE_UPDATE_FORBIDDEN = 1;

    /**
     * The object has not been initialized for encoding
     */
    public const ENCODE_FINISH_FORBIDDEN = 2;

    /**
     * The object is already used for encoding
     */
    public const DECODE_UPDATE_FORBIDDEN = 3;

    /**
     * The object has not been initialized for decoding
     */
    public const DECODE_FINISH_FORBIDDEN = 4;

    /**
     * Base64 decoded string does not contain valid characters
     */
    public const DECODE_UPDATE_FAILED = 5;

    /**
     * Input data length can't exceed max integer length
     */
    public const INPUT_DATA_LENGTH_HIGH = 6;
}

/**
 * Class for generating random numbers
 */
class Rand
{
    /**
     * Generates pseudo random bytes
     * @param int $num
     * @param bool $must_be_strong
     * @param bool &$returned_strong_result
     * @return string
     */
    public static function generate($num, $must_be_strong = true, &$returned_strong_result = true) {}

    /**
     * Mixes bytes in $buf into PRNG state
     * @param string $buf
     * @param float $entropy [optional] The default value is (float) strlen($buf)
     * @return null
     */
    public static function seed($buf, $entropy) {}

    /**
     * Cleans up PRNG state
     * @return null
     */
    public static function cleanup() {}

    /**
     * Reads a number of bytes from file $filename and adds them to the PRNG. If
     * max_bytes is non-negative, up to to max_bytes are read; if $max_bytes is
     * negative, the complete file is read
     * @param string $filename
     * @param int $max_bytes
     * @return int
     */
    public static function loadFile($filename, $max_bytes = -1) {}

    /**
     * Writes a number of random bytes (currently 1024) to file $filename which can be
     * used to initializethe PRNG by calling Crypto\Rand::loadFile() in a later session
     * @param string $filename
     * @return int
     */
    public static function writeFile($filename) {}
}

/**
 * Exception class for rand errors
 */
class RandException extends \Exception
{
    /**
     * The PRNG state is not yet unpredictable
     */
    public const GENERATE_PREDICTABLE = 1;

    /**
     * The bytes written were generated without appropriate seed
     */
    public const FILE_WRITE_PREDICTABLE = 2;

    /**
     * The requested number of bytes is too high
     */
    public const REQUESTED_BYTES_NUMBER_TOO_HIGH = 3;

    /**
     * The supplied seed length is too high
     */
    public const SEED_LENGTH_TOO_HIGH = 4;
}
