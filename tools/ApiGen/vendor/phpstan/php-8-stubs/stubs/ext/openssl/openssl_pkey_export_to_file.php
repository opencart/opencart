<?php 

/** @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $key */
function openssl_pkey_export_to_file($key, string $output_filename, ?string $passphrase = null, ?array $options = null) : bool
{
}