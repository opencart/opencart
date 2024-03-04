<?php 

/** @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key */
function openssl_pkcs12_export_to_file(\OpenSSLCertificate|string $certificate, string $output_filename, $private_key, string $passphrase, array $options = []) : bool
{
}