<?php 

/**
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $public_key
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key
 */
function openssl_pkey_derive($public_key, $private_key, int $key_length = 0) : string|false
{
}