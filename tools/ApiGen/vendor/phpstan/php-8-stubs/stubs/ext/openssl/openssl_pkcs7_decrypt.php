<?php 

/**
 * @param OpenSSLCertificate|string $certificate
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string|null $private_key
 */
function openssl_pkcs7_decrypt(string $input_filename, string $output_filename, $certificate, $private_key = null) : bool
{
}