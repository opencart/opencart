<?php 

/** @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $public_key */
function openssl_verify(string $data, string $signature, $public_key, string|int $algorithm = OPENSSL_ALGO_SHA1) : int|false
{
}