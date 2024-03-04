<?php 

/** @param OpenSSLAsymmetricKey $private_key */
function openssl_csr_new(array $distinguished_names, &$private_key, ?array $options = null, ?array $extra_attributes = null) : \OpenSSLCertificateSigningRequest|false
{
}