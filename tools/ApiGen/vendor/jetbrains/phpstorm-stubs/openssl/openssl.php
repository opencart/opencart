<?php

// Start of openssl v.
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;

/**
 * Frees a private key
 * @link https://php.net/manual/en/function.openssl-pkey-free.php
 * @param OpenSSLAsymmetricKey|resource $key <p>
 * Resource holding the key.
 * </p>
 * @return void
 */
#[Deprecated(since: '8.0')]
function openssl_pkey_free(#[LanguageLevelTypeAware(["8.0" => "OpenSSLAsymmetricKey"], default: "resource")] $key): void {}

/**
 * Generates a new private key
 * @link https://php.net/manual/en/function.openssl-pkey-new.php
 * @param array|null $options [optional] <p>
 * You can finetune the key generation (such as specifying the number of
 * bits) using <i>configargs</i>. See
 * <b>openssl_csr_new</b> for more information about
 * <i>configargs</i>.
 * </p>
 * @return OpenSSLAsymmetricKey|resource|false a resource identifier for the pkey on success, or false on
 * error.
 */
#[LanguageLevelTypeAware(["8.0" => "OpenSSLAsymmetricKey|false"], default: "resource|false")]
function openssl_pkey_new(?array $options) {}

/**
 * Gets an exportable representation of a key into a string
 * @link https://php.net/manual/en/function.openssl-pkey-export.php
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $key
 * @param string &$output
 * @param string|null $passphrase [optional] <p>
 * The key is optionally protected by <i>passphrase</i>.
 * </p>
 * @param array|null $options [optional] <p>
 * <i>configargs</i> can be used to fine-tune the export
 * process by specifying and/or overriding options for the openssl
 * configuration file. See <b>openssl_csr_new</b> for more
 * information about <i>configargs</i>.
 * </p>
 * @return bool true on success or false on failure.
 */
function openssl_pkey_export(
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $key,
    &$output,
    ?string $passphrase,
    ?array $options
): bool {}

/**
 * Gets an exportable representation of a key into a file
 * @link https://php.net/manual/en/function.openssl-pkey-export-to-file.php
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $key
 * @param string $output_filename <p>
 * Path to the output file.
 * </p>
 * @param string|null $passphrase [optional] <p>
 * The key can be optionally protected by a
 * <i>passphrase</i>.
 * </p>
 * @param array|null $options [optional] <p>
 * <i>configargs</i> can be used to fine-tune the export
 * process by specifying and/or overriding options for the openssl
 * configuration file. See <b>openssl_csr_new</b> for more
 * information about <i>configargs</i>.
 * </p>
 * @return bool true on success or false on failure.
 */
function openssl_pkey_export_to_file(
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $key,
    string $output_filename,
    ?string $passphrase,
    ?array $options
): bool {}

/**
 * Get a private key
 * @link https://php.net/manual/en/function.openssl-pkey-get-private.php
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key
 * <p>
 * <b><em>key</em></b> can be one of the following:
 * <ol>
 * <li>a string having the format
 * <var>file://path/to/file.pem</var>. The named file must
 * contain a PEM encoded certificate/private key (it may contain both).
 * </li>
 * <li>A PEM formatted private key.</li>
 * </ol></p>
 * @param string|null $passphrase <p>
 * The optional parameter <b><em>passphrase</em></b> must be used
 * if the specified key is encrypted (protected by a passphrase).
 * </p>
 * @return OpenSSLAsymmetricKey|resource|false Returns a positive key resource identifier on success, or <b>FALSE</b> on error.
 */
#[LanguageLevelTypeAware(["8.0" => "OpenSSLAsymmetricKey|false"], default: "resource|false")]
function openssl_pkey_get_private(
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $private_key,
    ?string $passphrase = null
) {}

/**
 * Extract public key from certificate and prepare it for use
 * @link https://php.net/manual/en/function.openssl-pkey-get-public.php
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $public_key <p><em><b>certificate</b></em> can be one of the following:
 * <ol>
 * <li>an X.509 certificate resource</li>
 * <li>a string having the format
 * <var>file://path/to/file.pem</var>. The named file must
 * contain a PEM encoded certificate/public key (it may contain both).
 * </li>
 * <li>A PEM formatted public key.</li>
 * </ol></p>
 * @return OpenSSLAsymmetricKey|resource|false a positive key resource identifier on success, or false on error.
 */
#[LanguageLevelTypeAware(["8.0" => "OpenSSLAsymmetricKey|false"], default: "resource|false")]
function openssl_pkey_get_public(#[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $public_key) {}

/**
 * Returns an array with the key details
 * @link https://php.net/manual/en/function.openssl-pkey-get-details.php
 * @param OpenSSLAsymmetricKey|resource $key <p>
 * Resource holding the key.
 * </p>
 * @return array|false an array with the key details in success or false in failure.
 * Returned array has indexes bits (number of bits),
 * key (string representation of the public key) and
 * type (type of the key which is one of
 * <b>OPENSSL_KEYTYPE_RSA</b>,
 * <b>OPENSSL_KEYTYPE_DSA</b>,
 * <b>OPENSSL_KEYTYPE_DH</b>,
 * <b>OPENSSL_KEYTYPE_EC</b> or -1 meaning unknown).
 * </p>
 * <p>
 * Depending on the key type used, additional details may be returned. Note that
 * some elements may not always be available.
 */
#[ArrayShape(["bits" => "int", "key" => "string", "rsa" => "array", "dsa" => "array", "dh" => "array", "type" => "int"])]
function openssl_pkey_get_details(#[LanguageLevelTypeAware(["8.0" => "OpenSSLAsymmetricKey"], default: "resource")] $key): array|false {}

/**
 * Free key resource
 * @link https://php.net/manual/en/function.openssl-free-key.php
 * @param OpenSSLAsymmetricKey|resource $key
 * @return void
 */
#[Deprecated(since: '8.0')]
function openssl_free_key(#[LanguageLevelTypeAware(["8.0" => "OpenSSLAsymmetricKey"], default: "resource")] $key): void {}

/**
 * Alias of <b>openssl_pkey_get_private</b>
 * @link https://php.net/manual/en/function.openssl-get-privatekey.php
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key
 * <p>
 * <b><em>key</em></b> can be one of the following:
 * <ol>
 * <li>a string having the format
 * <var>file://path/to/file.pem</var>. The named file must
 * contain a PEM encoded certificate/private key (it may contain both).
 * </li>
 * <li>A PEM formatted private key.</li>
 * </ol></p>
 * @param string|null $passphrase [optional] <p>
 * The optional parameter <b><em>passphrase</em></b> must be used
 * if the specified key is encrypted (protected by a passphrase).
 * </p>
 * @return OpenSSLAsymmetricKey|resource|false Returns a positive key resource identifier on success, or <b>FALSE</b> on error.
 */
#[LanguageLevelTypeAware(["8.0" => "OpenSSLAsymmetricKey|false"], default: "resource|false")]
function openssl_get_privatekey(
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $private_key,
    ?string $passphrase
) {}

/**
 * Alias of <b>openssl_pkey_get_public</b>
 * @link https://php.net/manual/en/function.openssl-get-publickey.php
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $public_key <p>
 * <em><b>certificate</b></em> can be one of the following:
 * <ol>
 * <li>an X.509 certificate resource</li>
 * <li>a string having the format
 * <var>file://path/to/file.pem</var>. The named file must
 * contain a PEM encoded certificate/public key (it may contain both).
 * </li>
 * <li>A PEM formatted public key.</li>
 * </ol></p>
 * @return OpenSSLAsymmetricKey|false a positive key resource identifier on success, or FALSE on error.
 */
#[LanguageLevelTypeAware(["8.0" => "OpenSSLAsymmetricKey|false"], default: "resource|false")]
function openssl_get_publickey(#[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $public_key) {}

/**
 * Generate a new signed public key and challenge
 * @link https://php.net/manual/en/function.openssl-spki-new.php
 * @param OpenSSLAsymmetricKey|resource $private_key <p>
 * <b>privkey</b> should be set to a private key that was
 * previously generated by {@link https://php.net/en/manual/function.openssl-pkey-new.php openssl_pkey_new()} (or
 * otherwise obtained from the other openssl_pkey family of functions).
 * The corresponding public portion of the key will be used to sign the
 * CSR.
 * </p>
 * @param string $challenge <p>The challenge associated to associate with the SPKAC</p>
 * @param int $digest_algo <p>The digest algorithm. See openssl_get_md_method().</p>
 * @return string|false Returns a signed public key and challenge string or NULL on failure.
 * @since 5.6
 */
function openssl_spki_new(#[LanguageLevelTypeAware(["8.0" => "OpenSSLAsymmetricKey"], default: "resource")] $private_key, string $challenge, int $digest_algo = 2): string|false {}

/**
 * Verifies a signed public key and challenge
 * @link https://php.net/manual/en/function.openssl-spki-verify.php
 * @param string $spki <p>Expects a valid signed public key and challenge</p>
 * @return bool Returns a boolean on success or failure.
 * @since 5.6
 */
function openssl_spki_verify(string $spki): bool {}

/**
 * Exports the challenge associated with a signed public key and challenge
 * @link https://php.net/manual/en/function.openssl-spki-export-challenge.php
 * @param string $spki <p>Expects a valid signed public key and challenge</p>
 * @return string|false Returns the associated challenge string or NULL on failure.
 * @since 5.6
 */
function openssl_spki_export_challenge(string $spki): string|false {}

/**
 * Exports a valid PEM formatted public key signed public key and challenge
 * @link https://php.net/manual/en/function.openssl-spki-export.php
 * @param string $spki <p>Expects a valid signed public key and challenge</p>
 * @return string|false Returns the associated PEM formatted public key or NULL on failure.
 * @since 5.6
 */
function openssl_spki_export(string $spki): string|false {}
/**
 * Parse an X.509 certificate and return a resource identifier for
 * it
 * @link https://php.net/manual/en/function.openssl-x509-read.php
 * @param OpenSSLCertificate|string|resource $certificate
 * @return OpenSSLCertificate|resource|false a resource identifier on success or false on failure.
 */
#[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate|false"], default: "resource|false")]
function openssl_x509_read(#[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate|string"], default: "resource|string")] $certificate) {}

/**
 * @param string $certificate
 * @param string $digest_algo [optional] hash method
 * @param bool $binary [optional]
 * @return string|false <b>FALSE</b> on failure
 * @since 5.6
 */
function openssl_x509_fingerprint(#[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate|string"], default: "resource|string")] $certificate, string $digest_algo = 'sha1', bool $binary = false): string|false {}
/**
 * Free certificate resource
 * @link https://php.net/manual/en/function.openssl-x509-free.php
 * @param OpenSSLCertificate|resource|string $certificate
 * @return void
 */
#[Deprecated(since: '8.0')]
function openssl_x509_free(#[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate"], default: "resource|string")] $certificate): void {}

/**
 * Parse an X509 certificate and return the information as an array
 * @link https://php.net/manual/en/function.openssl-x509-parse.php
 * @param OpenSSLCertificate|string|resource $certificate
 * @param bool $short_names [optional] <p>
 * <i>shortnames</i> controls how the data is indexed in the
 * array - if <i>shortnames</i> is true (the default) then
 * fields will be indexed with the short name form, otherwise, the long name
 * form will be used - e.g.: CN is the shortname form of commonName.
 * </p>
 * @return array|false The structure of the returned data is (deliberately) not
 * yet documented, as it is still subject to change.
 */
#[ArrayShape([
    'name' => 'string',
    'subject' => 'string',
    'hash' => 'string',
    'issuer' => 'string',
    'version' => 'int',
    'serialNumber' => 'string',
    'serialNumberHex' => 'string',
    'validFrom' => 'string',
    'validTo' => 'string',
    'validFrom_time_t' => 'int',
    'validTo_time_t' => 'int',
    'alias' => 'string',
    'signatureTypeSN' => 'string',
    'signatureTypeLN' => 'string',
    'signatureTypeNID' => 'int',
    'purposes' => 'array',
    'extensions' => 'array'
])]
function openssl_x509_parse(
    #[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate|string"], default: "resource|string")] $certificate,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] bool $shortname,
    #[PhpStormStubsElementAvailable(from: '7.1')] bool $short_names = true
): array|false {}

/**
 * Verifies if a certificate can be used for a particular purpose
 * @link https://php.net/manual/en/function.openssl-x509-checkpurpose.php
 * @param OpenSSLCertificate|string|resource $certificate <p>
 * The examined certificate.
 * </p>
 * @param int $purpose <p>
 * <table>
 * <b>openssl_x509_checkpurpose</b> purposes
 * <tr valign="top">
 * <td>Constant</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>X509_PURPOSE_SSL_CLIENT</td>
 * <td>Can the certificate be used for the client side of an SSL
 * connection?</td>
 * </tr>
 * <tr valign="top">
 * <td>X509_PURPOSE_SSL_SERVER</td>
 * <td>Can the certificate be used for the server side of an SSL
 * connection?</td>
 * </tr>
 * <tr valign="top">
 * <td>X509_PURPOSE_NS_SSL_SERVER</td>
 * <td>Can the cert be used for Netscape SSL server?</td>
 * </tr>
 * <tr valign="top">
 * <td>X509_PURPOSE_SMIME_SIGN</td>
 * <td>Can the cert be used to sign S/MIME email?</td>
 * </tr>
 * <tr valign="top">
 * <td>X509_PURPOSE_SMIME_ENCRYPT</td>
 * <td>Can the cert be used to encrypt S/MIME email?</td>
 * </tr>
 * <tr valign="top">
 * <td>X509_PURPOSE_CRL_SIGN</td>
 * <td>Can the cert be used to sign a certificate revocation list
 * (CRL)?</td>
 * </tr>
 * <tr valign="top">
 * <td>X509_PURPOSE_ANY</td>
 * <td>Can the cert be used for Any/All purposes?</td>
 * </tr>
 * </table>
 * These options are not bitfields - you may specify one only!
 * </p>
 * @param array $ca_info <p>
 * <i>cainfo</i> should be an array of trusted CA files/dirs
 * as described in Certificate
 * Verification.
 * </p>
 * @param string|null $untrusted_certificates_file [optional] <p>
 * If specified, this should be the name of a PEM encoded file holding
 * certificates that can be used to help verify the certificate, although
 * no trust is placed in the certificates that come from that file.
 * </p>
 * @return int|bool true if the certificate can be used for the intended purpose,
 * false if it cannot, or -1 on error.
 */
function openssl_x509_checkpurpose(
    #[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate|string"], default: "resource|string")] $certificate,
    int $purpose,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] array $ca_info,
    #[PhpStormStubsElementAvailable(from: '7.1')] array $ca_info = [],
    ?string $untrusted_certificates_file
): int|bool {}

/**
 * Checks if a private key corresponds to a certificate
 * @link https://php.net/manual/en/function.openssl-x509-check-private-key.php
 * @param OpenSSLCertificate|string|resource $certificate <p>
 * The certificate.
 * </p>
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key <p>
 * The private key.
 * </p>
 * @return bool true if <i>key</i> is the private key that
 * corresponds to <i>cert</i>, or false otherwise.
 */
function openssl_x509_check_private_key(
    #[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate|string"], default: "resource|string")] $certificate,
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $private_key
): bool {}

/**
 * Exports a certificate as a string
 * @link https://php.net/manual/en/function.openssl-x509-export.php
 * @param OpenSSLCertificate|string|resource $certificate
 * @param string &$output <p>
 * On success, this will hold the PEM.
 * </p>
 * @param bool $no_text [optional]
 * @return bool true on success or false on failure.
 */
function openssl_x509_export(#[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate|string"], default: "resource|string")] $certificate, &$output, bool $no_text = true): bool {}

/**
 * Exports a certificate to file
 * @link https://php.net/manual/en/function.openssl-x509-export-to-file.php
 * @param OpenSSLCertificate|string|resource $certificate
 * @param string $output_filename <p>
 * Path to the output file.
 * </p>
 * @param bool $no_text [optional]
 * @return bool true on success or false on failure.
 */
function openssl_x509_export_to_file(#[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate|string"], default: "resource|string")] $certificate, string $output_filename, bool $no_text = true): bool {}

/**
 * Verifies digital signature of x509 certificate against a public key
 * @link https://www.php.net/manual/en/function.openssl-x509-verify.php
 * @param OpenSSLCertificate|string|resource $certificate
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $public_key
 * @return int Returns 1 if the signature is correct, 0 if it is incorrect, and -1 on error.
 * @since 7.4
 */
function openssl_x509_verify(
    #[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate|string"], default: "resource|string")] $certificate,
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $public_key
): int {}

/**
 * Exports a PKCS#12 Compatible Certificate Store File to variable.
 * @link https://php.net/manual/en/function.openssl-pkcs12-export.php
 * @param OpenSSLCertificate|string|resource $certificate
 * @param string &$output <p>
 * On success, this will hold the PKCS#12.
 * </p>
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key <p>
 * Private key component of PKCS#12 file.
 * </p>
 * @param string $passphrase <p>
 * Encryption password for unlocking the PKCS#12 file.
 * </p>
 * @param array $options
 * @return bool true on success or false on failure.
 * @since 5.2.2
 */
function openssl_pkcs12_export(
    #[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate|string"], default: "resource|string")] $certificate,
    &$output,
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $private_key,
    string $passphrase,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] $args,
    #[PhpStormStubsElementAvailable(from: '7.1')] array $options = []
): bool {}

/**
 * Exports a PKCS#12 Compatible Certificate Store File
 * @link https://php.net/manual/en/function.openssl-pkcs12-export-to-file.php
 * @param OpenSSLCertificate|string|resource $certificate
 * @param string $output_filename <p>
 * Path to the output file.
 * </p>
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key <p>
 * Private key component of PKCS#12 file.
 * </p>
 * @param string $passphrase <p>
 * Encryption password for unlocking the PKCS#12 file.
 * </p>
 * @param array $options
 * @return bool true on success or false on failure.
 * @since 5.2.2
 */
function openssl_pkcs12_export_to_file(#[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate|string"], default: "resource|string")] $certificate, string $output_filename, $private_key, string $passphrase, array $options = []): bool {}

/**
 * Parse a PKCS#12 Certificate Store into an array
 * @link https://php.net/manual/en/function.openssl-pkcs12-read.php
 * @param string $pkcs12
 * @param array &$certificates <p>
 * On success, this will hold the Certificate Store Data.
 * </p>
 * @param string $passphrase <p>
 * Encryption password for unlocking the PKCS#12 file.
 * </p>
 * @return bool true on success or false on failure.
 * @since 5.2.2
 */
function openssl_pkcs12_read(string $pkcs12, &$certificates, string $passphrase): bool {}

/**
 * Generates a CSR
 * @link https://php.net/manual/en/function.openssl-csr-new.php
 * @param array $distinguished_names <p>
 * The Distinguished Name to be used in the certificate.
 * </p>
 * @param OpenSSLAsymmetricKey &$private_key <p>
 * <i>privkey</i> should be set to a private key that was
 * previously generated by <b>openssl_pkey_new</b> (or
 * otherwise obtained from the other openssl_pkey family of functions).
 * The corresponding public portion of the key will be used to sign the
 * CSR.
 * </p>
 * @param array|null $options [optional] <p>
 * By default, the information in your system openssl.conf
 * is used to initialize the request; you can specify a configuration file
 * section by setting the config_section_section key of
 * <i>configargs</i>. You can also specify an alternative
 * openssl configuration file by setting the value of the
 * config key to the path of the file you want to use.
 * The following keys, if present in <i>configargs</i>
 * behave as their equivalents in the openssl.conf, as
 * listed in the table below.
 * <table>
 * Configuration overrides
 * <tr valign="top">
 * <td><i>configargs</i> key</td>
 * <td>type</td>
 * <td>openssl.conf equivalent</td>
 * <td>description</td>
 * </tr>
 * <tr valign="top">
 * <td>digest_alg</td>
 * <td>string</td>
 * <td>default_md</td>
 * <td>Selects which digest method to use</td>
 * </tr>
 * <tr valign="top">
 * <td>x509_extensions</td>
 * <td>string</td>
 * <td>x509_extensions</td>
 * <td>Selects which extensions should be used when creating an x509
 * certificate</td>
 * </tr>
 * <tr valign="top">
 * <td>req_extensions</td>
 * <td>string</td>
 * <td>req_extensions</td>
 * <td>Selects which extensions should be used when creating a CSR</td>
 * </tr>
 * <tr valign="top">
 * <td>private_key_bits</td>
 * <td>integer</td>
 * <td>default_bits</td>
 * <td>Specifies how many bits should be used to generate a private
 * key</td>
 * </tr>
 * <tr valign="top">
 * <td>private_key_type</td>
 * <td>integer</td>
 * <td>none</td>
 * <td>Specifies the type of private key to create. This can be one
 * of <b>OPENSSL_KEYTYPE_DSA</b>,
 * <b>OPENSSL_KEYTYPE_DH</b> or
 * <b>OPENSSL_KEYTYPE_RSA</b>.
 * The default value is <b>OPENSSL_KEYTYPE_RSA</b> which
 * is currently the only supported key type.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>encrypt_key</td>
 * <td>boolean</td>
 * <td>encrypt_key</td>
 * <td>Should an exported key (with passphrase) be encrypted?</td>
 * </tr>
 * <tr valign="top">
 * <td>encrypt_key_cipher</td>
 * <td>integer</td>
 * <td>none</td>
 * <td>
 * One of cipher constants.
 * </td>
 * </tr>
 * </table>
 * </p>
 * @param array|null $extra_attributes [optional] <p>
 * <i>extraattribs</i> is used to specify additional
 * configuration options for the CSR. Both <i>dn</i> and
 * <i>extraattribs</i> are associative arrays whose keys are
 * converted to OIDs and applied to the relevant part of the request.
 * </p>
 * @return OpenSSLCertificateSigningRequest|resource|false the CSR.
 */
#[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificateSigningRequest|false"], default: "resource|false")]
function openssl_csr_new(
    array $distinguished_names,
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey'], default: 'resource')] &$private_key,
    ?array $options,
    ?array $extra_attributes
) {}

/**
 * Exports a CSR as a string
 * @link https://php.net/manual/en/function.openssl-csr-export.php
 * @param OpenSSLCertificateSigningRequest|string|resource $csr
 * @param string &$output
 * @param bool $no_text [optional]
 * @return bool true on success or false on failure.
 */
function openssl_csr_export(#[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificateSigningRequest|string"], default: "resource|string")] $csr, &$output, bool $no_text = true): bool {}

/**
 * Exports a CSR to a file
 * @link https://php.net/manual/en/function.openssl-csr-export-to-file.php
 * @param OpenSSLCertificateSigningRequest|string|resource $csr
 * @param string $output_filename <p>
 * Path to the output file.
 * </p>
 * @param bool $no_text [optional]
 * @return bool true on success or false on failure.
 */
function openssl_csr_export_to_file(#[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificateSigningRequest|string"], default: "resource|string")] $csr, string $output_filename, bool $no_text = true): bool {}

/**
 * Sign a CSR with another certificate (or itself) and generate a certificate
 * @link https://php.net/manual/en/function.openssl-csr-sign.php
 * @param OpenSSLCertificateSigningRequest|string|resource $csr <p>
 * A CSR previously generated by <b>openssl_csr_new</b>.
 * It can also be the path to a PEM encoded CSR when specified as
 * file://path/to/csr or an exported string generated
 * by <b>openssl_csr_export</b>.
 * </p>
 * @param OpenSSLCertificate|resource|string|null $ca_certificate <p>
 * The generated certificate will be signed by <i>cacert</i>.
 * If <i>cacert</i> is null, the generated certificate
 * will be a self-signed certificate.
 * </p>
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key <p>
 * <i>priv_key</i> is the private key that corresponds to
 * <i>cacert</i>.
 * </p>
 * @param int $days <p>
 * <i>days</i> specifies the length of time for which the
 * generated certificate will be valid, in days.
 * </p>
 * @param array|null $options [optional] <p>
 * You can finetune the CSR signing by <i>configargs</i>.
 * See <b>openssl_csr_new</b> for more information about
 * <i>configargs</i>.
 * </p>
 * @param int $serial [optional] <p>
 * An optional the serial number of issued certificate. If not specified
 * it will default to 0.
 * </p>
 * @return OpenSSLCertificate|resource|false an x509 certificate resource on success, false on failure.
 */
#[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate|false"], default: "resource|false")]
function openssl_csr_sign(
    #[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificateSigningRequest|string"], default: "resource|string")] $csr,
    #[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate|string|null"], default: "resource|string|null")] $ca_certificate,
    #[LanguageLevelTypeAware(["8.0" => "OpenSSLAsymmetricKey|OpenSSLCertificate|array|string"], default: "resource|array|string")] $private_key,
    int $days,
    ?array $options,
    int $serial = 0
) {}

/**
 * Returns the subject of a CERT
 * @link https://php.net/manual/en/function.openssl-csr-get-subject.php
 * @param OpenSSLCertificateSigningRequest|string|resource $csr
 * @param bool $short_names [optional]
 * @return array|false
 */
function openssl_csr_get_subject(
    #[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificateSigningRequest|string"], default: "resource|string")] $csr,
    #[PhpStormStubsElementAvailable(from: '7.1')] bool $short_names = true
): array|false {}

/**
 * Returns the public key of a CERT
 * @link https://php.net/manual/en/function.openssl-csr-get-public-key.php
 * @param OpenSSLCertificateSigningRequest|string|resource $csr
 * @param bool $short_names [optional]
 * @return OpenSSLAsymmetricKey|resource|false
 */
#[LanguageLevelTypeAware(["8.0" => "OpenSSLAsymmetricKey|false"], default: "resource|false")]
function openssl_csr_get_public_key(
    #[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificateSigningRequest|string"], default: "resource|string")] $csr,
    #[PhpStormStubsElementAvailable(from: '7.1')] bool $short_names = true
) {}

/**
 * Computes a digest
 * @link https://php.net/manual/en/function.openssl-digest.php
 * @param string $data <p>
 * The data.
 * </p>
 * @param string $digest_algo <p>
 * The digest method.
 * </p>
 * @param bool $binary [optional] <p>
 * Setting to true will return as raw output data, otherwise the return
 * value is binhex encoded.
 * </p>
 * @return string|false the digested hash value on success or false on failure.
 */
function openssl_digest(string $data, string $digest_algo, bool $binary = false): string|false {}

/**
 * Encrypts data
 * @link https://php.net/manual/en/function.openssl-encrypt.php
 * @param string $data <p>
 * The data.
 * </p>
 * @param string $cipher_algo <p>
 * The cipher method. For a list of available cipher methods, use {@see openssl_get_cipher_methods()}.
 * </p>
 * @param string $passphrase <p>
 * The key.
 * </p>
 * @param int $options [optional] <p>
 * options is a bitwise disjunction of the flags OPENSSL_RAW_DATA and OPENSSL_ZERO_PADDING.
 * </p>
 * @param string $iv [optional] <p>
 * A non-NULL Initialization Vector.
 * </p>
 * @param string &$tag [optional] <p>The authentication tag passed by reference when using AEAD cipher mode (GCM or CCM).</p>
 * @param string $aad [optional] <p>Additional authentication data.</p>
 * @param int $tag_length [optional] <p>
 * The length of the authentication tag. Its value can be between 4 and 16 for GCM mode.
 * </p>
 * @return string|false the encrypted string on success or false on failure.
 */
function openssl_encrypt(
    string $data,
    string $cipher_algo,
    string $passphrase,
    int $options = 0,
    string $iv = "",
    #[PhpStormStubsElementAvailable(from: '7.1')] &$tag,
    #[PhpStormStubsElementAvailable(from: '7.1')] string $aad = "",
    #[PhpStormStubsElementAvailable(from: '7.1')] int $tag_length = 16
): string|false {}

/**
 * Decrypts data
 * @link https://php.net/manual/en/function.openssl-decrypt.php
 * @param string $data <p>
 * The data.
 * </p>
 * @param string $cipher_algo <p>
 * The cipher method.
 * </p>
 * @param string $passphrase <p>
 * The password.
 * </p>
 * @param int $options [optional] <p>
 * Setting to true will take a raw encoded string,
 * otherwise a base64 string is assumed for the
 * <i>data</i> parameter.
 * </p>
 * @param string $iv [optional] <p>
 * A non-NULL Initialization Vector.
 * </p>
 * @param string|null $tag <p>
 * The authentication tag in AEAD cipher mode. If it is incorrect, the authentication fails and the function returns <b>FALSE</b>.
 * </p>
 * @param string $aad [optional] <p>Additional authentication data.</p>
 * @return string|false The decrypted string on success or false on failure.
 */
function openssl_decrypt(
    string $data,
    string $cipher_algo,
    string $passphrase,
    int $options = 0,
    string $iv = "",
    #[PhpStormStubsElementAvailable(from: '7.1')] #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: 'string')] $tag = null,
    #[PhpStormStubsElementAvailable(from: '7.1')] string $aad = ""
): string|false {}

/**
 * (PHP 5 &gt;= PHP 5.3.3)<br/>
 * Gets the cipher iv length
 * @link https://php.net/manual/en/function.openssl-cipher-iv-length.php
 * @param string $cipher_algo <p>
 * The method.
 * </p>
 * @return int|false the cipher length on success, or false on failure.
 */
function openssl_cipher_iv_length(string $cipher_algo): int|false {}

/**
 * This function works in exactly the same way as openssl_cipher_iv_length but for a key length. This is especially
 * useful to make sure that the right key length is provided to openssl_encrypt and openssl_decrypt.
 * @param string $cipher_algo
 * @return int|false
 * @since 8.2
 */
function openssl_cipher_key_length(string $cipher_algo): int|false {}

/**
 * Generate signature
 * @link https://php.net/manual/en/function.openssl-sign.php
 * @param string $data
 * @param string &$signature <p>
 * If the call was successful the signature is returned in
 * <i>signature</i>.
 * </p>
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key
 * @param string|int $algorithm [optional] <p>
 * For more information see the list of Signature Algorithms.
 * </p>
 * @return bool true on success or false on failure.
 */
function openssl_sign(
    string $data,
    &$signature,
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $private_key,
    string|int $algorithm = OPENSSL_ALGO_SHA1
): bool {}

/**
 * Verify signature
 * @link https://php.net/manual/en/function.openssl-verify.php
 * @param string $data
 * @param string $signature
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $public_key
 * @param string|int $algorithm [optional] <p>
 * For more information see the list of Signature Algorithms.
 * </p>
 * @return int|false 1 if the signature is correct, 0 if it is incorrect, and
 * -1 on error.
 */
function openssl_verify(
    string $data,
    string $signature,
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $public_key,
    string|int $algorithm = OPENSSL_ALGO_SHA1
): int|false {}

/**
 * Seal (encrypt) data
 * @link https://php.net/manual/en/function.openssl-seal.php
 * @param string $data
 * @param string &$sealed_data
 * @param array &$encrypted_keys
 * @param array $public_key
 * @param string $cipher_algo
 * @param string &$iv
 * @return int|false the length of the sealed data on success, or false on error.
 * If successful the sealed data is returned in
 * <i>sealed_data</i>, and the envelope keys in
 * <i>env_keys</i>.
 */
function openssl_seal(
    string $data,
    &$sealed_data,
    &$encrypted_keys,
    array $public_key,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] string $cipher_algo = '',
    #[PhpStormStubsElementAvailable(from: '8.0')] string $cipher_algo,
    #[PhpStormStubsElementAvailable(from: '7.0')] &$iv = null
): int|false {}

/**
 * Open sealed data
 * @link https://php.net/manual/en/function.openssl-open.php
 * @param string $data
 * @param string &$output <p>
 * If the call is successful the opened data is returned in this
 * parameter.
 * </p>
 * @param string $encrypted_key
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key
 * @param string $cipher_algo The cipher method.
 * @param string|null $iv [optional] The initialization vector.
 * @return bool true on success or false on failure.
 */
function openssl_open(
    string $data,
    &$output,
    string $encrypted_key,
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $private_key,
    #[PhpStormStubsElementAvailable(from: '7.0', to: '7.4')] string $cipher_algo = '',
    #[PhpStormStubsElementAvailable(from: '8.0')] string $cipher_algo,
    #[PhpStormStubsElementAvailable(from: '7.0')] ?string $iv
): bool {}

/**
 * Generates a PKCS5 v2 PBKDF2 string, defaults to SHA-1
 * @link https://secure.php.net/manual/en/function.openssl-pbkdf2.php
 * @param string $password
 * @param string $salt
 * @param int $key_length
 * @param int $iterations
 * @param string $digest_algo [optional]
 * @return string|false Returns string or FALSE on failure.
 * @since 5.5
 */
function openssl_pbkdf2(string $password, string $salt, int $key_length, int $iterations, string $digest_algo = 'sha1'): string|false {}

/**
 * Verifies the signature of an S/MIME signed message
 * @link https://php.net/manual/en/function.openssl-pkcs7-verify.php
 * @param string $input_filename <p>
 * Path to the message.
 * </p>
 * @param int $flags <p>
 * <i>flags</i> can be used to affect how the signature is
 * verified - see PKCS7 constants
 * for more information.
 * </p>
 * @param string|null $signers_certificates_filename [optional] <p>
 * If the <i>outfilename</i> is specified, it should be a
 * string holding the name of a file into which the certificates of the
 * persons that signed the messages will be stored in PEM format.
 * </p>
 * @param array $ca_info <p>
 * If the <i>cainfo</i> is specified, it should hold
 * information about the trusted CA certificates to use in the verification
 * process - see certificate
 * verification for more information about this parameter.
 * </p>
 * @param string|null $untrusted_certificates_filename [optional] <p>
 * If the <i>extracerts</i> is specified, it is the filename
 * of a file containing a bunch of certificates to use as untrusted CAs.
 * </p>
 * @param string|null $content [optional] <p>
 * You can specify a filename with <i>content</i> that will
 * be filled with the verified data, but with the signature information
 * stripped.
 * @param string|null $output_filename [optional]
 * </p>
 * @return bool|int true if the signature is verified, false if it is not correct
 * (the message has been tampered with, or the signing certificate is invalid),
 * or -1 on error.
 */
function openssl_pkcs7_verify(
    string $input_filename,
    int $flags,
    ?string $signers_certificates_filename,
    array $ca_info = [],
    ?string $untrusted_certificates_filename,
    ?string $content,
    #[PhpStormStubsElementAvailable("7.2")] ?string $output_filename
): int|bool {}

/**
 * Decrypts an S/MIME encrypted message
 * @link https://php.net/manual/en/function.openssl-pkcs7-decrypt.php
 * @param string $input_filename
 * @param string $output_filename <p>
 * The decrypted message is written to the file specified by
 * <i>outfilename</i>.
 * </p>
 * @param OpenSSLCertificate|string|resource $certificate
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string|null $private_key [optional]
 * @return bool true on success or false on failure.
 */
function openssl_pkcs7_decrypt(
    string $input_filename,
    string $output_filename,
    $certificate,
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string|null'], default: 'resource|array|string|null')] $private_key
): bool {}

/**
 * Sign an S/MIME message
 * @link https://php.net/manual/en/function.openssl-pkcs7-sign.php
 * @param string $input_filename
 * @param string $output_filename
 * @param OpenSSLCertificate|string|resource $certificate
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key
 * @param array|null $headers <p>
 * <i>headers</i> is an array of headers that
 * will be prepended to the data after it has been signed (see
 * <b>openssl_pkcs7_encrypt</b> for more information about
 * the format of this parameter).
 * </p>
 * @param int $flags [optional] <p>
 * <i>flags</i> can be used to alter the output - see PKCS7 constants.
 * </p>
 * @param string|null $untrusted_certificates_filename [optional] <p>
 * <i>extracerts</i> specifies the name of a file containing
 * a bunch of extra certificates to include in the signature which can for
 * example be used to help the recipient to verify the certificate that you used.
 * </p>
 * @return bool true on success or false on failure.
 */
function openssl_pkcs7_sign(
    string $input_filename,
    string $output_filename,
    #[LanguageLevelTypeAware(["8.0" => "OpenSSLCertificate|string"], default: "resource|string")] $certificate,
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $private_key,
    ?array $headers,
    int $flags = PKCS7_DETACHED,
    ?string $untrusted_certificates_filename
): bool {}

/**
 * Encrypt an S/MIME message
 * @link https://php.net/manual/en/function.openssl-pkcs7-encrypt.php
 * @param string $input_filename
 * @param string $output_filename
 * @param OpenSSLCertificate|string|resource $certificate <p>
 * Either a lone X.509 certificate, or an array of X.509 certificates.
 * </p>
 * @param array|null $headers <p>
 * <i>headers</i> is an array of headers that
 * will be prepended to the data after it has been encrypted.
 * </p>
 * <p>
 * <i>headers</i> can be either an associative array
 * keyed by header name, or an indexed array, where each element contains
 * a single header line.
 * </p>
 * @param int $flags [optional] <p>
 * <i>flags</i> can be used to specify options that affect
 * the encoding process - see PKCS7
 * constants.
 * </p>
 * @param int $cipher_algo [optional] <p>
 * One of cipher constants.
 * </p>
 * @return bool true on success or false on failure.
 */
function openssl_pkcs7_encrypt(string $input_filename, string $output_filename, $certificate, ?array $headers, int $flags = 0, int $cipher_algo = OPENSSL_CIPHER_AES_128_CBC): bool {}

/**
 * Encrypts data with private key
 * @link https://php.net/manual/en/function.openssl-private-encrypt.php
 * @param string $data
 * @param string &$encrypted_data
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key
 * @param int $padding [optional] <p>
 * <i>padding</i> can be one of
 * <b>OPENSSL_PKCS1_PADDING</b>,
 * <b>OPENSSL_NO_PADDING</b>.
 * </p>
 * @return bool true on success or false on failure.
 */
function openssl_private_encrypt(
    string $data,
    &$encrypted_data,
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $private_key,
    int $padding = OPENSSL_PKCS1_PADDING
): bool {}

/**
 * Decrypts data with private key
 * @link https://php.net/manual/en/function.openssl-private-decrypt.php
 * @param string $data
 * @param string &$decrypted_data
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key <p>
 * <i>key</i> must be the private key corresponding that
 * was used to encrypt the data.
 * </p>
 * @param int $padding [optional] <p>
 * <i>padding</i> can be one of
 * <b>OPENSSL_PKCS1_PADDING</b>,
 * <b>OPENSSL_SSLV23_PADDING</b>,
 * <b>OPENSSL_PKCS1_OAEP_PADDING</b>,
 * <b>OPENSSL_NO_PADDING</b>.
 * </p>
 * @return bool true on success or false on failure.
 */
function openssl_private_decrypt(
    string $data,
    &$decrypted_data,
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $private_key,
    int $padding = OPENSSL_PKCS1_PADDING
): bool {}

/**
 * Encrypts data with public key
 * @link https://php.net/manual/en/function.openssl-public-encrypt.php
 * @param string $data
 * @param string &$encrypted_data <p>
 * This will hold the result of the encryption.
 * </p>
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $public_key <p>
 * The public key.
 * </p>
 * @param int $padding [optional] <p>
 * <i>padding</i> can be one of
 * <b>OPENSSL_PKCS1_PADDING</b>,
 * <b>OPENSSL_SSLV23_PADDING</b>,
 * <b>OPENSSL_PKCS1_OAEP_PADDING</b>,
 * <b>OPENSSL_NO_PADDING</b>.
 * </p>
 * @return bool true on success or false on failure.
 */
function openssl_public_encrypt(
    string $data,
    &$encrypted_data,
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $public_key,
    int $padding = OPENSSL_PKCS1_PADDING
): bool {}

/**
 * Decrypts data with public key
 * @link https://php.net/manual/en/function.openssl-public-decrypt.php
 * @param string $data
 * @param string &$decrypted_data
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $public_key <p>
 * <i>key</i> must be the public key corresponding that
 * was used to encrypt the data.
 * </p>
 * @param int $padding [optional] <p>
 * <i>padding</i> can be one of
 * <b>OPENSSL_PKCS1_PADDING</b>,
 * <b>OPENSSL_NO_PADDING</b>.
 * </p>
 * @return bool true on success or false on failure.
 */
function openssl_public_decrypt(
    string $data,
    &$decrypted_data,
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $public_key,
    int $padding = OPENSSL_PKCS1_PADDING
): bool {}

/**
 * Gets available digest methods
 * @link https://php.net/manual/en/function.openssl-get-md-methods.php
 * @param bool $aliases [optional] <p>
 * Set to true if digest aliases should be included within the
 * returned array.
 * </p>
 * @return array An array of available digest methods.
 */
function openssl_get_md_methods(bool $aliases = false): array {}

/**
 * Gets available cipher methods
 * @link https://php.net/manual/en/function.openssl-get-cipher-methods.php
 * @param bool $aliases [optional] <p>
 * Set to true if cipher aliases should be included within the
 * returned array.
 * </p>
 * @return array An array of available cipher methods.
 */
function openssl_get_cipher_methods(bool $aliases = false): array {}

/**
 * Computes shared secret for public value of remote DH key and local DH key
 * @link https://php.net/manual/en/function.openssl-dh-compute-key.php
 * @param string $public_key <p>
 * Public key
 * </p>
 * @param OpenSSLAsymmetricKey|resource $private_key <p>
 * DH key
 * </p>
 * @return string|false computed key on success or false on failure.
 * @since 5.3
 */
function openssl_dh_compute_key(string $public_key, #[LanguageLevelTypeAware(["8.0" => "OpenSSLAsymmetricKey"], default: "resource")] $private_key): string|false {}

/**
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $public_key
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key
 * @param int $key_length
 * @return string|false
 * @since 7.3
 */
function openssl_pkey_derive(
    $public_key,
    #[LanguageLevelTypeAware(['8.0' => 'OpenSSLAsymmetricKey|OpenSSLCertificate|array|string'], default: 'resource|array|string')] $private_key,
    int $key_length = 0
): string|false {}

/**
 * Generates a string of pseudo-random bytes, with the number of bytes determined by the length parameter.
 * <p>It also indicates if a cryptographically strong algorithm was used to produce the pseudo-random bytes,
 * and does this via the optional crypto_strong parameter. It's rare for this to be FALSE, but some systems may be broken or old.</p>
 * @link https://php.net/manual/en/function.openssl-random-pseudo-bytes.php
 * @param positive-int $length <p>
 * The length of the desired string of bytes. Must be a positive integer. PHP will
 * try to cast this parameter to a non-null integer to use it.
 * </p>
 * @param bool &$strong_result [optional]<p>
 * If passed into the function, this will hold a boolean value that determines
 * if the algorithm used was "cryptographically strong", e.g., safe for usage with GPG,
 * passwords, etc. true if it did, otherwise false
 * </p>
 * @return string|false the generated string of bytes on success, or false on failure.
 */
#[LanguageLevelTypeAware(["8.0" => "string"], default: "string|false")]
function openssl_random_pseudo_bytes(int $length, &$strong_result) {}

/**
 * Return openSSL error message
 * @link https://php.net/manual/en/function.openssl-error-string.php
 * @return string|false an error message string, or false if there are no more error
 * messages to return.
 */
function openssl_error_string(): string|false {}

/**
 * Retrieve the available certificate locations
 * @link https://php.net/manual/en/function.openssl-get-cert-locations.php
 * @return array an array with the available certificate locations
 * @since 5.6
 */
#[ArrayShape([
    'default_cert_file' => 'string',
    'default_cert_file_env' => 'string',
    'default_cert_dir' => 'string',
    'default_cert_dir_env' => 'string',
    'default_private_dir' => 'string',
    'default_default_cert_area' => 'string',
    'ini_cafile' => 'string',
    'ini_capath' => 'string'
])]
function openssl_get_cert_locations(): array {}

function openssl_get_curve_names(): array|false {}

/**
 * @param string $data
 * @param array &$certificates
 * @return bool
 * @since 7.2
 */
function openssl_pkcs7_read(string $data, &$certificates): bool {}

/**
 * Verifies that the data block is intact, the signer is who they say they are, and returns the certs of the signers.
 * @param string $input_filename
 * @param int $flags [optional]
 * @param string|null $certificates [optional]
 * @param array $ca_info
 * @param string|null $untrusted_certificates_filename [optional]
 * @param string|null $content [optional]
 * @param string|null $pk7 [optional]
 * @param string|null $sigfile [optional]
 * @param int $encoding [optional]
 * @return bool
 * @since 8.0
 */
function openssl_cms_verify(string $input_filename, int $flags = 0, ?string $certificates, array $ca_info = [], ?string $untrusted_certificates_filename, ?string $content, ?string $pk7, ?string $sigfile, int $encoding = OPENSSL_ENCODING_SMIME): bool {}

/**
 * Encrypts the message in the file with the certificates and outputs the result to the supplied file.
 * @param string $input_filename
 * @param string $output_filename
 * @param resource|string|array $certificate
 * @param null|array $headers
 * @param int $flags
 * @param int $encoding
 * @param int $cipher_algo
 * @return bool
 * @since 8.0
 */
function openssl_cms_encrypt(string $input_filename, string $output_filename, $certificate, ?array $headers, int $flags = 0, int $encoding = OPENSSL_ENCODING_SMIME, int $cipher_algo = OPENSSL_CIPHER_AES_128_CBC): bool {}

/**
 * Signs the MIME message in the file with a cert and key and output the result to the supplied file.
 * @param string $input_filename
 * @param string $output_filename
 * @param OpenSSLCertificate|string $certificate
 * @param OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $private_key
 * @param array|null $headers
 * @param int $flags [optional]
 * @param int $encoding [optional]
 * @param string|null $untrusted_certificates_filename [optional]
 * @return bool
 * @since 8.0
 */
function openssl_cms_sign(string $input_filename, string $output_filename, OpenSSLCertificate|string $certificate, $private_key, ?array $headers, int $flags = 0, int $encoding = OPENSSL_ENCODING_SMIME, ?string $untrusted_certificates_filename): bool {}

/**
 * Decrypts the S/MIME message in the file and outputs the results to the supplied file.
 * @param string $input_filename
 * @param string $output_filename
 * @param resource|string $certificate
 * @param resource|string|array $private_key
 * @param int $encoding
 * @return bool
 * @since 8.0
 */
function openssl_cms_decrypt(string $input_filename, string $output_filename, $certificate, $private_key = null, int $encoding = OPENSSL_ENCODING_SMIME): bool {}

/**
 * Exports the CMS file to an array of PEM certificates.
 * @param string $input_filename
 * @param array &$certificates
 * @return bool
 * @since 8.0
 */
function openssl_cms_read(string $input_filename, &$certificates): bool {}

define('OPENSSL_VERSION_TEXT', "OpenSSL 1.0.0e 6 Sep 2011");
define('OPENSSL_VERSION_NUMBER', 268435551);
define('X509_PURPOSE_SSL_CLIENT', 1);
define('X509_PURPOSE_SSL_SERVER', 2);
define('X509_PURPOSE_NS_SSL_SERVER', 3);
define('X509_PURPOSE_SMIME_SIGN', 4);
define('X509_PURPOSE_SMIME_ENCRYPT', 5);
define('X509_PURPOSE_CRL_SIGN', 6);
define('X509_PURPOSE_ANY', 7);

/**
 * Used as default algorithm by <b>openssl_sign</b> and
 * <b>openssl_verify</b>.
 * @link https://php.net/manual/en/openssl.constants.php
 */
define('OPENSSL_ALGO_SHA1', 1);
define('OPENSSL_ALGO_MD5', 2);
define('OPENSSL_ALGO_MD4', 3);
define('OPENSSL_ALGO_MD2', 4);
define('OPENSSL_ALGO_DSS1', 5);
define('OPENSSL_ALGO_SHA224', 6);
define('OPENSSL_ALGO_SHA256', 7);
define('OPENSSL_ALGO_SHA384', 8);
define('OPENSSL_ALGO_SHA512', 9);
define('OPENSSL_ALGO_RMD160', 10);
/**
 * When signing a message, use cleartext signing with the MIME
 * type "multipart/signed". This is the default
 * if you do not specify any <i>flags</i> to
 * <b>openssl_pkcs7_sign</b>.
 * If you turn this option off, the message will be signed using
 * opaque signing, which is more resistant to translation by mail relays
 * but cannot be read by mail agents that do not support S/MIME.
 * @link https://php.net/manual/en/openssl.constants.php
 */
define('PKCS7_DETACHED', 64);

/**
 * Adds text/plain content type headers to encrypted/signed
 * message. If decrypting or verifying, it strips those headers from
 * the output - if the decrypted or verified message is not of MIME type
 * text/plain then an error will occur.
 * @link https://php.net/manual/en/openssl.constants.php
 */
define('PKCS7_TEXT', 1);

/**
 * When verifying a message, certificates (if
 * any) included in the message are normally searched for the
 * signing certificate. With this option only the
 * certificates specified in the <i>extracerts</i>
 * parameter of <b>openssl_pkcs7_verify</b> are
 * used. The supplied certificates can still be used as
 * untrusted CAs however.
 * @link https://php.net/manual/en/openssl.constants.php
 */
define('PKCS7_NOINTERN', 16);

/**
 * Do not verify the signers certificate of a signed
 * message.
 * @link https://php.net/manual/en/openssl.constants.php
 */
define('PKCS7_NOVERIFY', 32);

/**
 * Do not chain verification of signers certificates: that is
 * don't use the certificates in the signed message as untrusted CAs.
 * @link https://php.net/manual/en/openssl.constants.php
 */
define('PKCS7_NOCHAIN', 8);

/**
 * When signing a message the signer's certificate is normally
 * included - with this option it is excluded. This will reduce the
 * size of the signed message but the verifier must have a copy of the
 * signers certificate available locally (passed using the
 * <i>extracerts</i> to
 * <b>openssl_pkcs7_verify</b> for example).
 * @link https://php.net/manual/en/openssl.constants.php
 */
define('PKCS7_NOCERTS', 2);

/**
 * Normally when a message is signed, a set of attributes are
 * included which include the signing time and the supported symmetric
 * algorithms. With this option they are not included.
 * @link https://php.net/manual/en/openssl.constants.php
 */
define('PKCS7_NOATTR', 256);

/**
 * Normally the input message is converted to "canonical" format
 * which is effectively using CR and LF
 * as end of line: as required by the S/MIME specification. When this
 * option is present, no translation occurs. This is useful when
 * handling binary data which may not be in MIME format.
 * @link https://php.net/manual/en/openssl.constants.php
 */
define('PKCS7_BINARY', 128);

/**
 * Don't try and verify the signatures on a message
 * @link https://php.net/manual/en/openssl.constants.php
 */
define('PKCS7_NOSIGS', 4);
define('OPENSSL_PKCS1_PADDING', 1);
define('OPENSSL_SSLV23_PADDING', 2);
define('OPENSSL_NO_PADDING', 3);
define('OPENSSL_PKCS1_OAEP_PADDING', 4);
define('OPENSSL_CIPHER_RC2_40', 0);
define('OPENSSL_CIPHER_RC2_128', 1);
define('OPENSSL_CIPHER_RC2_64', 2);
define('OPENSSL_CIPHER_DES', 3);
define('OPENSSL_CIPHER_3DES', 4);
define('OPENSSL_KEYTYPE_RSA', 0);
define('OPENSSL_KEYTYPE_DSA', 1);
define('OPENSSL_KEYTYPE_DH', 2);
define('OPENSSL_KEYTYPE_EC', 3);

/**
 * Whether SNI support is available or not.
 * @link https://php.net/manual/en/openssl.constants.php
 */
define('OPENSSL_TLSEXT_SERVER_NAME', 1);

// End of openssl v.

/** @link https://php.net/manual/en/openssl.ciphers.php */
define('OPENSSL_CIPHER_AES_128_CBC', 5);
/** @link https://php.net/manual/en/openssl.ciphers.php */
define('OPENSSL_CIPHER_AES_192_CBC', 6);
/** @link https://php.net/manual/en/openssl.ciphers.php */
define('OPENSSL_CIPHER_AES_256_CBC', 7);
define('OPENSSL_RAW_DATA', 1);
define('OPENSSL_ZERO_PADDING', 2);
define('OPENSSL_DONT_ZERO_PAD_KEY', 4);

/**
 * @since 8.0
 */
define('OPENSSL_CMS_DETACHED', 64);
/**
 * @since 8.0
 */
define('OPENSSL_CMS_TEXT', 1);
/**
 * @since 8.0
 */
define('OPENSSL_CMS_NOINTERN', 16);
/**
 * @since 8.0
 */
define('OPENSSL_CMS_NOVERIFY', 32);
/**
 * @since 8.0
 */
define('OPENSSL_CMS_NOCERTS', 2);
/**
 * @since 8.0
 */
define('OPENSSL_CMS_NOATTR', 256);
/**
 * @since 8.0
 */
define('OPENSSL_CMS_BINARY', 128);
/**
 * @since 8.0
 */
define('OPENSSL_CMS_NOSIGS', 12);
/**
 * @since 8.0
 */
define('OPENSSL_ENCODING_DER', 0);
/**
 * @since 8.0
 */
define('OPENSSL_ENCODING_SMIME', 1);
/**
 * @since 8.0
 */
define('OPENSSL_ENCODING_PEM', 2);

define('OPENSSL_DEFAULT_STREAM_CIPHERS', "ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES128-GCM-SHA256:" .
"ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:" .
"DHE-DSS-AES128-GCM-SHA256:kEDH+AESGCM:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA256:" .
"ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA384:" .
"ECDHE-RSA-AES256-SHA:ECDHE-ECDSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:" .
"DHE-DSS-AES128-SHA256:DHE-RSA-AES256-SHA256:DHE-DSS-AES256-SHA:DHE-RSA-AES256-SHA:AES128-GCM-SHA256:" .
"AES256-GCM-SHA384:AES128:AES256:HIGH:!SSLv2:!aNULL:!eNULL:!EXPORT:!DES:!MD5:!RC4:!ADH");

/**
 * @since 8.0
 */
final class OpenSSLCertificate
{
    /**
     * Cannot directly construct OpenSSLCertificate, use openssl_x509_read() instead
     * @see openssl_x509_read()
     */
    private function __construct() {}
}

/**
 * @since 8.0
 */
final class OpenSSLCertificateSigningRequest
{
    /**
     * Cannot directly construct OpenSSLCertificateSigningRequest, use openssl_csr_new() instead
     * @see openssl_csr_new()
     */
    private function __construct() {}
}

/**
 * @since 8.0
 */
final class OpenSSLAsymmetricKey
{
    /**
     * Cannot directly construct OpenSSLAsymmetricKey, use openssl_pkey_new() instead
     * @see openssl_pkey_new()
     */
    private function __construct() {}
}
