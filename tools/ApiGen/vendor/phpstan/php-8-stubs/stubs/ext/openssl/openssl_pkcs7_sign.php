<?php 

/** @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key */
function openssl_pkcs7_sign(string $input_filename, string $output_filename, \OpenSSLCertificate|string $certificate, $private_key, ?array $headers, int $flags = PKCS7_DETACHED, ?string $untrusted_certificates_filename = null) : bool
{
}