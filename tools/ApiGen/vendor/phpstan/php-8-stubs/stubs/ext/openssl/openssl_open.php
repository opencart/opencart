<?php 

/**
 * @param string $output
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key
 */
function openssl_open(string $data, &$output, string $encrypted_key, $private_key, string $cipher_algo, ?string $iv = null) : bool
{
}