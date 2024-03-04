<?php 

/** @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key */
function openssl_csr_sign(\OpenSSLCertificateSigningRequest|string $csr, \OpenSSLCertificate|string|null $ca_certificate, $private_key, int $days, ?array $options = null, int $serial = 0) : \OpenSSLCertificate|false
{
}