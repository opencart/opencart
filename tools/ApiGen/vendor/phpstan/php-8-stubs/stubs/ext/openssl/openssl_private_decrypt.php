<?php 

/**
 * @param string $decrypted_data
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key
 */
function openssl_private_decrypt(string $data, &$decrypted_data, $private_key, int $padding = OPENSSL_PKCS1_PADDING) : bool
{
}