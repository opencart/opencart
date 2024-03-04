<?php 

/** @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key */
function openssl_cms_sign(string $input_filename, string $output_filename, \OpenSSLCertificate|string $certificate, $private_key, ?array $headers, int $flags = 0, int $encoding = OPENSSL_ENCODING_SMIME, ?string $untrusted_certificates_filename = null) : bool
{
}