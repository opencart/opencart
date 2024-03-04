<?php 

/** @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $public_key */
function openssl_x509_verify(\OpenSSLCertificate|string $certificate, $public_key) : int
{
}