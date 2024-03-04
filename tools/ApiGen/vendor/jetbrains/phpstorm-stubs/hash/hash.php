<?php

// Start of hash v.1.0
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Pure;

/**
 * (PHP 5 &gt;= 5.1.2, PECL hash &gt;= 1.1)<br/>
 * Generate a hash value (message digest)
 * @link https://php.net/manual/en/function.hash.php
 * @param string $algo <p>
 * Name of selected hashing algorithm (i.e. "md5", "sha256", "haval160,4", etc..)
 * </p>
 * @param string $data <p>
 * Message to be hashed.
 * </p>
 * @param bool $binary [optional] <p>
 * When set to <b>TRUE</b>, outputs raw binary data.
 * <b>FALSE</b> outputs lowercase hexits.
 * </p>
 * @return string a string containing the calculated message digest as lowercase hexits
 * unless <i>raw_output</i> is set to true in which case the raw
 * binary representation of the message digest is returned.
 */
#[Pure]
function hash(string $algo, string $data, bool $binary = false, #[PhpStormStubsElementAvailable('8.1')] array $options = []): string {}

/**
 * Timing attack safe string comparison
 * @link https://php.net/manual/en/function.hash-equals.php
 * @param string $known_string <p>The string of known length to compare against</p>
 * @param string $user_string <p>The user-supplied string</p>
 * @return bool <p>Returns <b>TRUE</b> when the two strings are equal, <b>FALSE</b> otherwise.</p>
 * @since 5.6
 */
#[Pure]
function hash_equals(string $known_string, string $user_string): bool {}

/**
 * (PHP 5 &gt;= 5.1.2, PECL hash &gt;= 1.1)<br/>
 * Generate a hash value using the contents of a given file
 * @link https://php.net/manual/en/function.hash-file.php
 * @param string $algo <p>
 * Name of selected hashing algorithm (i.e. "md5", "sha256", "haval160,4", etc..)
 * </p>
 * @param string $filename <p>
 * URL describing location of file to be hashed; Supports fopen wrappers.
 * </p>
 * @param bool $binary [optional] <p>
 * When set to <b>TRUE</b>, outputs raw binary data.
 * <b>FALSE</b> outputs lowercase hexits.
 * </p>
 * @return string|false a string containing the calculated message digest as lowercase hexits
 * unless <i>raw_output</i> is set to true in which case the raw
 * binary representation of the message digest is returned.
 */
#[Pure]
function hash_file(string $algo, string $filename, bool $binary = false, #[PhpStormStubsElementAvailable('8.1')] array $options = []): string|false {}

/**
 * (PHP 5 &gt;= 5.1.2, PECL hash &gt;= 1.1)<br/>
 * Generate a keyed hash value using the HMAC method
 * @link https://php.net/manual/en/function.hash-hmac.php
 * @param string $algo <p>
 * Name of selected hashing algorithm (i.e. "md5", "sha256", "haval160,4", etc..) See <b>hash_algos</b> for a list of supported algorithms.<br/>
 * Since 7.2.0 usage of non-cryptographic hash functions (adler32, crc32, crc32b, fnv132, fnv1a32, fnv164, fnv1a64, joaat) was disabled.
 * </p>
 * @param string $data <p>
 * Message to be hashed.
 * </p>
 * @param string $key <p>
 * Shared secret key used for generating the HMAC variant of the message digest.
 * </p>
 * @param bool $binary [optional] <p>
 * When set to <b>TRUE</b>, outputs raw binary data.
 * <b>FALSE</b> outputs lowercase hexits.
 * </p>
 * @return string a string containing the calculated message digest as lowercase hexits
 * unless <i>raw_output</i> is set to true in which case the raw
 * binary representation of the message digest is returned.
 */
#[Pure]
function hash_hmac(string $algo, string $data, string $key, bool $binary = false): string {}

/**
 * (PHP 5 &gt;= 5.1.2, PECL hash &gt;= 1.1)<br/>
 * Generate a keyed hash value using the HMAC method and the contents of a given file
 * @link https://php.net/manual/en/function.hash-hmac-file.php
 * @param string $algo <p>
 * Name of selected hashing algorithm (i.e. "md5", "sha256", "haval160,4", etc..) See <b>hash_algos</b> for a list of supported algorithms.<br/>
 * Since 7.2.0 usage of non-cryptographic hash functions (adler32, crc32, crc32b, fnv132, fnv1a32, fnv164, fnv1a64, joaat) was disabled.
 * </p>
 * @param string $filename <p>
 * URL describing location of file to be hashed; Supports fopen wrappers.
 * </p>
 * @param string $key <p>
 * Shared secret key used for generating the HMAC variant of the message digest.
 * </p>
 * @param bool $binary [optional] <p>
 * When set to <b>TRUE</b>, outputs raw binary data.
 * <b>FALSE</b> outputs lowercase hexits.
 * </p>
 * @return string|false a string containing the calculated message digest as lowercase hexits
 * unless <i>raw_output</i> is set to true in which case the raw
 * binary representation of the message digest is returned.
 */
#[Pure]
function hash_hmac_file(string $algo, string $filename, string $key, bool $binary = false): string|false {}

/**
 * (PHP 5 &gt;= 5.1.2, PECL hash &gt;= 1.1)<br/>
 * Initialize an incremental hashing context
 * @link https://php.net/manual/en/function.hash-init.php
 * @param string $algo <p>
 * Name of selected hashing algorithm (i.e. "md5", "sha256", "haval160,4", etc..). For a list of supported algorithms see <b>hash_algos</b>.<br/>
 * Since 7.2.0 usage of non-cryptographic hash functions (adler32, crc32, crc32b, fnv132, fnv1a32, fnv164, fnv1a64, joaat) was disabled.
 * </p>
 * @param int $flags [optional] <p>
 * Optional settings for hash generation, currently supports only one option:
 * <b>HASH_HMAC</b>. When specified, the <i>key</i>
 * must be specified.
 * </p>
 * @param string $key <p>
 * When <b>HASH_HMAC</b> is specified for <i>options</i>,
 * a shared secret key to be used with the HMAC hashing method must be supplied in this
 * parameter.
 * </p>
 * @return HashContext|resource a Hashing Context resource for use with <b>hash_update</b>,
 * <b>hash_update_stream</b>, <b>hash_update_file</b>,
 * and <b>hash_final</b>.
 */
#[Pure]
#[LanguageLevelTypeAware(["7.2" => "HashContext"], default: "resource")]
function hash_init(string $algo, int $flags = 0, string $key = "", #[PhpStormStubsElementAvailable('8.1')] array $options = []) {}

/**
 * (PHP 5 &gt;= 5.1.2, PECL hash &gt;= 1.1)<br/>
 * Pump data into an active hashing context
 * @link https://php.net/manual/en/function.hash-update.php
 * @param HashContext|resource $context <p>
 * Hashing context returned by {@see hash_init}.
 * </p>
 * @param string $data <p>
 * Message to be included in the hash digest.
 * </p>
 * @return bool <b>TRUE</b>.
 */
function hash_update(#[LanguageLevelTypeAware(["7.2" => "HashContext"], default: "resource")] $context, string $data): bool {}

/**
 * (PHP 5 &gt;= 5.1.2, PECL hash &gt;= 1.1)<br/>
 * Pump data into an active hashing context from an open stream
 * @link https://php.net/manual/en/function.hash-update-stream.php
 * @param HashContext|resource $context <p>
 * Hashing context returned by {@see hash_init}.
 * </p>
 * @param resource $stream <p>
 * Open file handle as returned by any stream creation function.
 * </p>
 * @param int $length [optional] <p>
 * Maximum number of characters to copy from <i>handle</i>
 * into the hashing context.
 * </p>
 * @return int Actual number of bytes added to the hashing context from <i>handle</i>.
 */
function hash_update_stream(#[LanguageLevelTypeAware(["7.2" => "HashContext"], default: "resource")] $context, $stream, int $length = -1): int {}

/**
 * (PHP 5 &gt;= 5.1.2, PECL hash &gt;= 1.1)<br/>
 * Pump data into an active hashing context from a file
 * @link https://php.net/manual/en/function.hash-update-file.php
 * @param HashContext|resource $context <p>
 * Hashing context returned by <b>hash_init</b>.
 * </p>
 * @param string $filename <p>
 * URL describing location of file to be hashed; Supports fopen wrappers.
 * </p>
 * @param resource $stream_context [optional] <p>
 * Stream context as returned by <b>stream_context_create</b>.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function hash_update_file(#[LanguageLevelTypeAware(["7.2" => "HashContext"], default: "resource")] $context, string $filename, $stream_context): bool {}

/**
 * (PHP 5 &gt;= 5.1.2, PECL hash &gt;= 1.1)<br/>
 * Finalize an incremental hash and return resulting digest
 * @link https://php.net/manual/en/function.hash-final.php
 * @param HashContext|resource $context <p>
 * Hashing context returned by {@see hash_init}.
 * </p>
 * @param bool $binary [optional] <p>
 * When set to <b>TRUE</b>, outputs raw binary data.
 * <b>FALSE</b> outputs lowercase hexits.
 * </p>
 * @return string a string containing the calculated message digest as lowercase hexits
 * unless <i>raw_output</i> is set to true in which case the raw
 * binary representation of the message digest is returned.
 */
function hash_final(#[LanguageLevelTypeAware(["7.2" => "HashContext"], default: "resource")] $context, bool $binary = false): string {}

/**
 * Copy hashing context
 * @link https://php.net/manual/en/function.hash-copy.php
 * @param HashContext|resource $context <p>
 * Hashing context returned by {@see hash_init}.
 * </p>
 * @return HashContext|resource a copy of Hashing Context resource.
 */
#[Pure]
#[LanguageLevelTypeAware(["7.2" => "HashContext"], default: "resource")]
function hash_copy(#[LanguageLevelTypeAware(["7.2" => "HashContext"], default: "resource")] $context) {}

/**
 * (PHP 5 &gt;= 5.1.2, PECL hash &gt;= 1.1)<br/>
 * Return a list of registered hashing algorithms
 * @link https://php.net/manual/en/function.hash-algos.php
 * @return array a numerically indexed array containing the list of supported
 * hashing algorithms.
 */
#[Pure]
function hash_algos(): array {}

/**
 * Generate a hkdf key derivation of a supplied key input
 * @param string $algo Name of selected hashing algorithm (i.e. "sha256", "sha512", "haval160,4", etc..)
 * See {@see hash_algos()} for a list of supported algorithms.
 * <blockquote>
 * <p><strong>Note</strong></p>
 * <p>
 * Non-cryptographic hash functions are not allowed.
 * </p>
 * </blockquote>
 * @param string $key <p>Input keying material (raw binary). Cannot be empty.</p>
 * @param int $length [optional] <p>Desired output length in bytes. Cannot be greater than 255 times the chosen hash function size.
 * If <b>length</b> is 0, the output length will default to the chosen hash function size.</p>
 * @param string $info [optional] <p>Application/context-specific info string.</p>
 * @param string $salt [optional] <p>Salt to use during derivation. While optional, adding random salt significantly improves the strength of HKDF.</p>
 * @return string|false <p>Returns a string containing a raw binary representation of the derived key (also known as output keying material - OKM); or <b>FALSE</b> on failure.</p>
 * @since 7.1.2
 * Generate a HKDF key derivation of a supplied key input
 * @link https://php.net/manual/en/function.hash-hkdf.php
 */
#[Pure]
#[LanguageLevelTypeAware(["8.0" => "string"], default: "string|false")]
function hash_hkdf(string $algo, string $key, int $length = 0, string $info = '', string $salt = '') {}

/**
 * Return a list of registered hashing algorithms suitable for hash_hmac
 * @since 7.2
 * Return a list of registered hashing algorithms suitable for hash_hmac
 * @return string[] Returns a numerically indexed array containing the list of supported hashing algorithms suitable for {@see hash_hmac()}.
 */
#[Pure]
function hash_hmac_algos(): array {}

/**
 * Generate a PBKDF2 key derivation of a supplied password
 * @link https://php.net/manual/en/function.hash-pbkdf2.php
 * @param string $algo <p>
 * Name of selected hashing algorithm (i.e. "md5", "sha256", "haval160,4", etc..) See <b>hash_algos</b> for a list of supported algorithms.<br/>
 * Since 7.2.0 usage of non-cryptographic hash functions (adler32, crc32, crc32b, fnv132, fnv1a32, fnv164, fnv1a64, joaat) was disabled.
 * </p>
 * @param string $password <p>
 * The password to use for the derivation.
 * </p>
 * @param string $salt <p>
 * The salt to use for the derivation. This value should be generated randomly.
 * </p>
 * @param int $iterations <p>
 * The number of internal iterations to perform for the derivation.
 * </p>
 * @param int $length [optional] <p>
 * The length of the output string. If raw_output is TRUE this corresponds to the byte-length of the derived key,
 * if raw_output is FALSE this corresponds to twice the byte-length of the derived key (as every byte of the key is returned as two hexits). <br/>
 * If 0 is passed, the entire output of the supplied algorithm is used.
 * </p>
 * @param bool $binary [optional] <p>
 * When set to TRUE, outputs raw binary data. FALSE outputs lowercase hexits.
 * </p>
 * @return string a string containing the derived key as lowercase hexits unless
 * <i>raw_output</i> is set to <b>TRUE</b> in which case the raw
 * binary representation of the derived key is returned.
 * @since 5.5
 */
#[Pure]
function hash_pbkdf2(string $algo, string $password, string $salt, int $iterations, int $length = 0, bool $binary = false): string {}

/**
 * Generates a key
 * @link https://php.net/manual/en/function.mhash-keygen-s2k.php
 * @param int $algo <p>
 * The hash ID used to create the key.
 * One of the <b>MHASH_hashname</b> constants.
 * </p>
 * @param string $password <p>
 * An user supplied password.
 * </p>
 * @param string $salt <p>
 * Must be different and random enough for every key you generate in
 * order to create different keys. Because <i>salt</i>
 * must be known when you check the keys, it is a good idea to append
 * the key to it. Salt has a fixed length of 8 bytes and will be padded
 * with zeros if you supply less bytes.
 * </p>
 * @param int $length <p>
 * The key length, in bytes.
 * </p>
 * @return string|false the generated key as a string, or <b>FALSE</b> on error.
 * @deprecated 8.1
 */
#[Pure]
#[Deprecated(since: '8.1')]
function mhash_keygen_s2k(int $algo, string $password, string $salt, int $length): string|false {}

/**
 * Gets the block size of the specified hash
 * @link https://php.net/manual/en/function.mhash-get-block-size.php
 * @param int $algo <p>
 * The hash ID. One of the <b>MHASH_hashname</b> constants.
 * </p>
 * @return int|false the size in bytes or <b>FALSE</b>, if the <i>hash</i>
 * does not exist.
 * @deprecated 8.1
 */
#[Pure]
#[Deprecated(since: '8.1')]
function mhash_get_block_size(int $algo): int|false {}

/**
 * Gets the name of the specified hash
 * @link https://php.net/manual/en/function.mhash-get-hash-name.php
 * @param int $algo <p>
 * The hash ID. One of the <b>MHASH_hashname</b> constants.
 * </p>
 * @return string|false the name of the hash or <b>FALSE</b>, if the hash does not exist.
 * @deprecated 8.1
 */
#[Pure]
#[Deprecated(since: '8.1')]
function mhash_get_hash_name(int $algo): string|false {}

/**
 * Gets the highest available hash ID
 * @link https://php.net/manual/en/function.mhash-count.php
 * @return int<0, max> the highest available hash ID. Hashes are numbered from 0 to this
 * hash ID.
 * @deprecated 8.1
 */
#[Pure]
#[Deprecated(since: '8.1')]
function mhash_count(): int {}

/**
 * Computes hash
 * @link https://php.net/manual/en/function.mhash.php
 * @param int $algo <p>
 * The hash ID. One of the <b>MHASH_hashname</b> constants.
 * </p>
 * @param string $data <p>
 * The user input, as a string.
 * </p>
 * @param string|null $key [optional] <p>
 * If specified, the function will return the resulting HMAC instead.
 * HMAC is keyed hashing for message authentication, or simply a message
 * digest that depends on the specified key. Not all algorithms
 * supported in mhash can be used in HMAC mode.
 * </p>
 * @return string|false the resulting hash (also called digest) or HMAC as a string, or
 * <b>FALSE</b> on error.
 * @deprecated 8.1
 */
#[Pure]
#[Deprecated(since: '8.1')]
function mhash(int $algo, string $data, ?string $key): string|false {}

/**
 * Optional flag for <b>hash_init</b>.
 * Indicates that the HMAC digest-keying algorithm should be
 * applied to the current hashing context.
 * @link https://php.net/manual/en/hash.constants.php
 */
define('HASH_HMAC', 1);
define('MHASH_CRC32', 0);
/**
 * @since 7.4
 */
define('MHASH_CRC32C', 34);
define('MHASH_MD5', 1);
define('MHASH_SHA1', 2);
define('MHASH_HAVAL256', 3);
define('MHASH_RIPEMD160', 5);
define('MHASH_TIGER', 7);
define('MHASH_GOST', 8);
define('MHASH_CRC32B', 9);
define('MHASH_HAVAL224', 10);
define('MHASH_HAVAL192', 11);
define('MHASH_HAVAL160', 12);
define('MHASH_HAVAL128', 13);
define('MHASH_TIGER128', 14);
define('MHASH_TIGER160', 15);
define('MHASH_MD4', 16);
define('MHASH_SHA256', 17);
define('MHASH_ADLER32', 18);
define('MHASH_SHA224', 19);
define('MHASH_SHA512', 20);
define('MHASH_SHA384', 21);
define('MHASH_WHIRLPOOL', 22);
define('MHASH_RIPEMD128', 23);
define('MHASH_RIPEMD256', 24);
define('MHASH_RIPEMD320', 25);
define('MHASH_SNEFRU256', 27);
define('MHASH_MD2', 28);
define('MHASH_FNV132', 29);
define('MHASH_FNV1A32', 30);
define('MHASH_FNV164', 31);
define('MHASH_FNV1A64', 32);
define('MHASH_JOAAT', 33);
/**
 * @since 8.1
 */
define('MHASH_MURMUR3A', 35);
/**
 * @since 8.1
 */
define('MHASH_MURMUR3C', 36);
/**
 * @since 8.1
 */
define('MHASH_MURMUR3F', 37);
/**
 * @since 8.1
 */
define('MHASH_XXH32', 38);
/**
 * @since 8.1
 */
define('MHASH_XXH64', 39);
/**
 * @since 8.1
 */
define('MHASH_XXH3', 40);
/**
 * @since 8.1
 */
define('MHASH_XXH128', 41);

/**
 * @since 7.2
 */
final class HashContext
{
    private function __construct() {}

    public function __serialize(): array {}

    /**
     * @param array $data
     */
    public function __unserialize(#[LanguageLevelTypeAware(['8.0' => 'array'], default: '')] $data): void {}
}
// End of hash v.1.0
