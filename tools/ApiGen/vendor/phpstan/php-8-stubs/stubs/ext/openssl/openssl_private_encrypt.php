<?php 

/**
 * @param string $encrypted_data
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key
 */
function openssl_private_encrypt(string $data, &$encrypted_data, $private_key, int $padding = OPENSSL_PKCS1_PADDING) : bool
{
}