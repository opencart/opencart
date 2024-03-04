<?php 

/**
 * @param string $encrypted_data
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $public_key
 */
function openssl_public_encrypt(string $data, &$encrypted_data, $public_key, int $padding = OPENSSL_PKCS1_PADDING) : bool
{
}