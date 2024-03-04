<?php 

/** @param OpenSSLCertificate|array|string $certificate */
#[\Until('8.1')]
function openssl_cms_encrypt(string $input_filename, string $output_filename, $certificate, ?array $headers, int $flags = 0, int $encoding = OPENSSL_ENCODING_SMIME, int $cipher_algo = OPENSSL_CIPHER_RC2_40) : bool
{
}
/** @param OpenSSLCertificate|array|string $certificate */
#[\Since('8.1')]
function openssl_cms_encrypt(string $input_filename, string $output_filename, $certificate, ?array $headers, int $flags = 0, int $encoding = OPENSSL_ENCODING_SMIME, int $cipher_algo = OPENSSL_CIPHER_AES_128_CBC) : bool
{
}