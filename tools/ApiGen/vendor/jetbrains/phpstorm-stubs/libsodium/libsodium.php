<?php
declare(strict_types=1);

namespace Sodium;

/**
 * To silence the phpstorm "unknown namespace" errors.
 */
const CRYPTO_AEAD_AES256GCM_KEYBYTES = 32;
const CRYPTO_AEAD_AES256GCM_NSECBYTES = 0;
const CRYPTO_AEAD_AES256GCM_NPUBBYTES = 12;
const CRYPTO_AEAD_AES256GCM_ABYTES = 16;
const CRYPTO_AEAD_CHACHA20POLY1305_KEYBYTES = 32;
const CRYPTO_AEAD_CHACHA20POLY1305_NSECBYTES = 0;
const CRYPTO_AEAD_CHACHA20POLY1305_NPUBBYTES = 8;
const CRYPTO_AEAD_CHACHA20POLY1305_ABYTES = 16;
const CRYPTO_AUTH_BYTES = 32;
const CRYPTO_AUTH_KEYBYTES = 32;
const CRYPTO_BOX_SEALBYTES = 16;
const CRYPTO_BOX_SECRETKEYBYTES = 32;
const CRYPTO_BOX_PUBLICKEYBYTES = 32;
const CRYPTO_BOX_KEYPAIRBYTES = 64;
const CRYPTO_BOX_MACBYTES = 16;
const CRYPTO_BOX_NONCEBYTES = 24;
const CRYPTO_BOX_SEEDBYTES = 32;
const CRYPTO_KX_BYTES = 32;
const CRYPTO_KX_PUBLICKEYBYTES = 32;
const CRYPTO_KX_SECRETKEYBYTES = 32;
const CRYPTO_GENERICHASH_BYTES = 32;
const CRYPTO_GENERICHASH_BYTES_MIN = 16;
const CRYPTO_GENERICHASH_BYTES_MAX = 64;
const CRYPTO_GENERICHASH_KEYBYTES = 32;
const CRYPTO_GENERICHASH_KEYBYTES_MIN = 16;
const CRYPTO_GENERICHASH_KEYBYTES_MAX = 64;
const CRYPTO_PWHASH_SCRYPTSALSA208SHA256_SALTBYTES = 32;
const CRYPTO_PWHASH_SCRYPTSALSA208SHA256_STRPREFIX = '$7$';
const CRYPTO_PWHASH_SCRYPTSALSA208SHA256_OPSLIMIT_INTERACTIVE = 534288;
const CRYPTO_PWHASH_SCRYPTSALSA208SHA256_MEMLIMIT_INTERACTIVE = 16777216;
const CRYPTO_PWHASH_SCRYPTSALSA208SHA256_OPSLIMIT_SENSITIVE = 33554432;
const CRYPTO_PWHASH_SCRYPTSALSA208SHA256_MEMLIMIT_SENSITIVE = 1073741824;
const CRYPTO_SCALARMULT_BYTES = 32;
const CRYPTO_SCALARMULT_SCALARBYTES = 32;
const CRYPTO_SHORTHASH_BYTES = 8;
const CRYPTO_SHORTHASH_KEYBYTES = 16;
const CRYPTO_SECRETBOX_KEYBYTES = 32;
const CRYPTO_SECRETBOX_MACBYTES = 16;
const CRYPTO_SECRETBOX_NONCEBYTES = 24;
const CRYPTO_SIGN_BYTES = 64;
const CRYPTO_SIGN_SEEDBYTES = 32;
const CRYPTO_SIGN_PUBLICKEYBYTES = 32;
const CRYPTO_SIGN_SECRETKEYBYTES = 64;
const CRYPTO_SIGN_KEYPAIRBYTES = 96;
const CRYPTO_STREAM_KEYBYTES = 32;
const CRYPTO_STREAM_NONCEBYTES = 24;
const CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE = 4;
const CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE = 33554432;
const CRYPTO_PWHASH_OPSLIMIT_MODERATE = 6;
const CRYPTO_PWHASH_MEMLIMIT_MODERATE = 134217728;
const CRYPTO_PWHASH_OPSLIMIT_SENSITIVE = 8;
const CRYPTO_PWHASH_MEMLIMIT_SENSITIVE = 536870912;

/**
 * Can you access AES-256-GCM? This is only available if you have supported
 * hardware.
 *
 * @return bool
 */
function crypto_aead_aes256gcm_is_available(): bool {}

/**
 * Authenticated Encryption with Associated Data (decrypt)
 * AES-256-GCM
 *
 * @param string $msg encrypted message
 * @param string $nonce
 * @param string $key
 * @param string $ad additional data (optional)
 * @return string
 */
function crypto_aead_aes256gcm_decrypt(
    string $msg,
    string $nonce,
    string $key,
    string $ad = ''
): string {}

/**
 * Authenticated Encryption with Associated Data (encrypt)
 * AES-256-GCM
 *
 * @param string $msg plaintext message
 * @param string $nonce
 * @param string $key
 * @param string $ad additional data (optional)
 * @return string
 */
function crypto_aead_aes256gcm_encrypt(
    string $msg,
    string $nonce,
    string $key,
    string $ad = ''
): string {}

/**
 * Authenticated Encryption with Associated Data (decrypt)
 * ChaCha20 + Poly1305
 *
 * @param string $msg encrypted message
 * @param string $nonce
 * @param string $key
 * @param string $ad additional data (optional)
 * @return string
 */
function crypto_aead_chacha20poly1305_decrypt(
    string $msg,
    string $nonce,
    string $key,
    string $ad = ''
): string {}

/**
 * Authenticated Encryption with Associated Data (encrypt)
 * ChaCha20 + Poly1305
 *
 * @param string $msg plaintext message
 * @param string $nonce
 * @param string $key
 * @param string $ad additional data (optional)
 * @return string
 */
function crypto_aead_chacha20poly1305_encrypt(
    string $msg,
    string $nonce,
    string $key,
    string $ad = ''
): string {}

/**
 * Secret-key message authentication
 * HMAC SHA-512/256
 *
 * @param string $msg
 * @param string $key
 * @return string
 */
function crypto_auth(
    string $msg,
    string $key
): string {}

/**
 * Secret-key message verification
 * HMAC SHA-512/256
 *
 * @param string $mac
 * @param string $msg
 * @param string $key
 * @return bool
 */
function crypto_auth_verify(
    string $mac,
    string $msg,
    string $key
): bool {}

/**
 * Public-key authenticated encryption (encrypt)
 * X25519 + Xsalsa20 + Poly1305
 *
 * @param string $msg
 * @param string $nonce
 * @param string $keypair
 * @return string
 */
function crypto_box(
    string $msg,
    string $nonce,
    string $keypair
): string {}

/**
 * Generate an X25519 keypair for use with the crypto_box API
 *
 * @return string
 */
function crypto_box_keypair(): string {}

/**
 * Derive an X25519 keypair for use with the crypto_box API from a seed
 *
 * @param string $seed
 * @return string
 */
function crypto_box_seed_keypair(
    string $seed
): string {}

/**
 * Create an X25519 keypair from an X25519 secret key and X25519 public key
 *
 * @param string $secretkey
 * @param string $publickey
 * @return string
 */
function crypto_box_keypair_from_secretkey_and_publickey(
    string $secretkey,
    string $publickey
): string {}

/**
 * Public-key authenticated encryption (decrypt)
 * X25519 + Xsalsa20 + Poly1305
 *
 * @param string $msg
 * @param string $nonce
 * @param string $keypair
 * @return string
 */
function crypto_box_open(
    string $msg,
    string $nonce,
    string $keypair
): string {}

/**
 * Get an X25519 public key from an X25519 keypair
 *
 * @param string $keypair
 * @return string
 */
function crypto_box_publickey(
    string $keypair
): string {}

/**
 * Derive an X25519 public key from an X25519 secret key
 *
 * @param string $secretkey
 * @return string
 */
function crypto_box_publickey_from_secretkey(
    string $secretkey
): string {}

/**
 * Anonymous public-key encryption (encrypt)
 * X25519 + Xsalsa20 + Poly1305 + BLAKE2b
 *
 * @param string $message
 * @param string $publickey
 * @return string
 */
function crypto_box_seal(
    string $message,
    string $publickey
): string {}

/**
 * Anonymous public-key encryption (decrypt)
 * X25519 + Xsalsa20 + Poly1305 + BLAKE2b
 *
 * @param string $encrypted
 * @param string $keypair
 * @return string
 */
function crypto_box_seal_open(
    string $encrypted,
    string $keypair
): string {}

/**
 * Extract the X25519 secret key from an X25519 keypair
 *
 * @param string $keypair
 * @return string
 */
function crypto_box_secretkey(
    string $keypair
): string {}

/**
 * Elliptic Curve Diffie Hellman Key Exchange
 * X25519
 *
 * @param string $secretkey
 * @param string $publickey
 * @param string $client_publickey
 * @param string $server_publickey
 * @return string
 */
function crypto_kx(
    string $secretkey,
    string $publickey,
    string $client_publickey,
    string $server_publickey
): string {}

/**
 * Fast and secure cryptographic hash
 *
 * @param string $input
 * @param string $key
 * @param int $length
 * @return string
 */
function crypto_generichash(
    string $input,
    string $key = '',
    int $length = 32
): string {}

/**
 * Create a new hash state (e.g. to use for streams)
 * BLAKE2b
 *
 * @param string $key
 * @param int $length
 * @return string
 */
function crypto_generichash_init(
    string $key = '',
    int $length = 32
): string {}

/**
 * Update the hash state with some data
 * BLAKE2b
 *
 * @param string &$hashState
 * @param string $append
 * @return bool
 */
function crypto_generichash_update(
    string &$hashState,
    string $append
): bool {}

/**
 * Get the final hash
 * BLAKE2b
 *
 * @param string $state
 * @param int $length
 * @return string
 */
function crypto_generichash_final(
    string $state,
    int $length = 32
): string {}

/**
 * Secure password-based key derivation function
 * Argon2i
 *
 * @param int $out_len
 * @param string $passwd
 * @param string $salt
 * @param int $opslimit
 * @param int $memlimit
 * @return string
 */
function crypto_pwhash(
    int $out_len,
    string $passwd,
    string $salt,
    int $opslimit,
    int $memlimit
): string {}

/**
 * Get a formatted password hash (for storage)
 * Argon2i
 *
 * @param string $passwd
 * @param int $opslimit
 * @param int $memlimit
 * @return string
 */
function crypto_pwhash_str(
    string $passwd,
    int $opslimit,
    int $memlimit
): string {}

/**
 * Verify a password against a hash
 * Argon2i
 *
 * @param string $hash
 * @param string $passwd
 * @return bool
 */
function crypto_pwhash_str_verify(
    string $hash,
    string $passwd
): bool {}

/**
 * Secure password-based key derivation function
 * Scrypt
 *
 * @param int $out_len
 * @param string $passwd
 * @param string $salt
 * @param int $opslimit
 * @param int $memlimit
 * @return string
 */
function crypto_pwhash_scryptsalsa208sha256(
    int $out_len,
    string $passwd,
    string $salt,
    int $opslimit,
    int $memlimit
): string {}

/**
 * Get a formatted password hash (for storage)
 * Scrypt
 *
 * @param string $passwd
 * @param int $opslimit
 * @param int $memlimit
 * @return string
 */
function crypto_pwhash_scryptsalsa208sha256_str(
    string $passwd,
    int $opslimit,
    int $memlimit
): string {}

/**
 * Verify a password against a hash
 * Scrypt
 *
 * @param string $hash
 * @param string $passwd
 * @return bool
 */
function crypto_pwhash_scryptsalsa208sha256_str_verify(
    string $hash,
    string $passwd
): bool {}

/**
 * Elliptic Curve Diffie Hellman over Curve25519
 * X25519
 *
 * @param string $ecdhA
 * @param string $ecdhB
 * @return string
 */
function crypto_scalarmult(
    string $ecdhA,
    string $ecdhB
): string {}

/**
 * Authenticated secret-key encryption (encrypt)
 * Xsals20 + Poly1305
 *
 * @param string $plaintext
 * @param string $nonce
 * @param string $key
 * @return string
 */
function crypto_secretbox(
    string $plaintext,
    string $nonce,
    string $key
): string {}

/**
 * Authenticated secret-key encryption (decrypt)
 * Xsals20 + Poly1305
 *
 * @param string $ciphertext
 * @param string $nonce
 * @param string $key
 * @return string
 */
function crypto_secretbox_open(
    string $ciphertext,
    string $nonce,
    string $key
): string {}

/**
 * A short keyed hash suitable for data structures
 * SipHash-2-4
 *
 * @param string $message
 * @param string $key
 * @return string
 */
function crypto_shorthash(
    string $message,
    string $key
): string {}

/**
 * Digital Signature
 * Ed25519
 *
 * @param string $message
 * @param string $secretkey
 * @return string
 */
function crypto_sign(
    string $message,
    string $secretkey
): string {}

/**
 * Digital Signature (detached)
 * Ed25519
 *
 * @param string $message
 * @param string $secretkey
 * @return string
 */
function crypto_sign_detached(
    string $message,
    string $secretkey
): string {}

/**
 * Convert an Ed25519 public key to an X25519 public key
 *
 * @param string $sign_pk
 * @return string
 */
function crypto_sign_ed25519_pk_to_curve25519(
    string $sign_pk
): string {}

/**
 * Convert an Ed25519 secret key to an X25519 secret key
 *
 * @param string $sign_sk
 * @return string
 */
function crypto_sign_ed25519_sk_to_curve25519(
    string $sign_sk
): string {}

/**
 * Generate an Ed25519 keypair for use with the crypto_sign API
 *
 * @return string
 */
function crypto_sign_keypair(): string {}

/**
 * Create an Ed25519 keypair from an Ed25519 secret key + Ed25519 public key
 *
 * @param string $secretkey
 * @param string $publickey
 * @return string
 */
function crypto_sign_keypair_from_secretkey_and_publickey(
    string $secretkey,
    string $publickey
): string {}

/**
 * Verify a signed message and return the plaintext
 *
 * @param string $signed_message
 * @param string $publickey
 * @return string
 */
function crypto_sign_open(
    string $signed_message,
    string $publickey
): string {}

/**
 * Get the public key from an Ed25519 keypair
 *
 * @param string $keypair
 * @return string
 */
function crypto_sign_publickey(
    string $keypair
): string {}

/**
 * Get the secret key from an Ed25519 keypair
 *
 * @param string $keypair
 * @return string
 */
function crypto_sign_secretkey(
    string $keypair
): string {}

/**
 * Derive an Ed25519 public key from an Ed25519 secret key
 *
 * @param string $secretkey
 * @return string
 */
function crypto_sign_publickey_from_secretkey(
    string $secretkey
): string {}

/**
 * Derive an Ed25519 keypair for use with the crypto_sign API from a seed
 *
 * @param string $seed
 * @return string
 */
function crypto_sign_seed_keypair(
    string $seed
): string {}

/**
 * Verify a detached signature
 *
 * @param string $signature
 * @param string $msg
 * @param string $publickey
 * @return bool
 */
function crypto_sign_verify_detached(
    string $signature,
    string $msg,
    string $publickey
): bool {}

/**
 * Create a keystream from a key and nonce
 * Xsalsa20
 *
 * @param int $length
 * @param string $nonce
 * @param string $key
 * @return string
 */
function crypto_stream(
    int $length,
    string $nonce,
    string $key
): string {}

/**
 * Encrypt a message using a stream cipher
 * Xsalsa20
 *
 * @param string $plaintext
 * @param string $nonce
 * @param string $key
 * @return string
 */
function crypto_stream_xor(
    string $plaintext,
    string $nonce,
    string $key
): string {}

/**
 * Generate a string of random bytes
 * /dev/urandom
 *
 * @param int $length
 * @return string|false
 */
function randombytes_buf(
    int $length
): string {}

/**
 * Generate a 16-bit integer
 * /dev/urandom
 *
 * @return int
 */
function randombytes_random16(): int {}

/**
 * Generate an unbiased random integer between 0 and a specified value
 * /dev/urandom
 *
 * @param int $upperBoundNonInclusive
 * @return int
 */
function randombytes_uniform(
    int $upperBoundNonInclusive
): int {}

/**
 * Convert to hex without side-chanels
 *
 * @param string $binary
 * @return string
 */
function bin2hex(
    string $binary
): string {}

/**
 * Compare two strings in constant time
 *
 * @param string $left
 * @param string $right
 * @return int
 */
function compare(
    string $left,
    string $right
): int {}

/**
 * Convert from hex without side-chanels
 *
 * @param string $hex
 * @return string
 */
function hex2bin(
    string $hex
): string {}

/**
 * Increment a string in little-endian
 *
 * @param string &$nonce
 * @return string
 */
function increment(
    string &$nonce
) {}

/**
 * Add the right operand to the left
 *
 * @param string &$left
 * @param string $right
 */
function add(
    string &$left,
    string $right
) {}

/**
 * Get the true major version of libsodium
 * @return int
 */
function library_version_major(): int {}

/**
 * Get the true minor version of libsodium
 * @return int
 */
function library_version_minor(): int {}

/**
 * Compare two strings in constant time
 *
 * @param string $left
 * @param string $right
 * @return int
 */
function memcmp(
    string $left,
    string $right
): int {}

/**
 * Wipe a buffer
 *
 * @param string &$target
 */
function memzero(string &$target) {}

/**
 * Get the version string
 *
 * @return string
 */
function version_string(): string {}

/**
 * Scalar multiplication of the base point and your key
 *
 * @param string $sk
 * @return string
 */
function crypto_scalarmult_base(string $sk): string {}
