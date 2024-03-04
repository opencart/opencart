<?php 

/**
 * @param string $decrypted_data
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $public_key
 */
function openssl_public_decrypt(string $data, &$decrypted_data, $public_key, int $padding = OPENSSL_PKCS1_PADDING) : bool
{
}