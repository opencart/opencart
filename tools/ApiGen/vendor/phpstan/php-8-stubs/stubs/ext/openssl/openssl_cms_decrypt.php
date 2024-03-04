<?php 

/**
 * @param OpenSSLCertificate|string $certificate
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string|null $private_key
 */
function openssl_cms_decrypt(string $input_filename, string $output_filename, $certificate, $private_key = null, int $encoding = OPENSSL_ENCODING_SMIME) : bool
{
}