<?php 

/**
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $key
 * @param string $output
 */
function openssl_pkey_export($key, &$output, ?string $passphrase = null, ?array $options = null) : bool
{
}