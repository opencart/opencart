<?php 

/**
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key
 * @alias openssl_pkey_get_private
 */
function openssl_get_privatekey($private_key, ?string $passphrase = null) : \OpenSSLAsymmetricKey|false
{
}