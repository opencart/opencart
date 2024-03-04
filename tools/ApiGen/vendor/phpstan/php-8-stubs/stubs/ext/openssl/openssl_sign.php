<?php 

/**
 * @param string $signature
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key
 */
function openssl_sign(string $data, &$signature, $private_key, string|int $algorithm = OPENSSL_ALGO_SHA1) : bool
{
}