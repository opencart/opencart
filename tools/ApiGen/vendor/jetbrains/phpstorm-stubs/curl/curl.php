<?php

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;
use JetBrains\PhpStorm\Pure;

class CURLFile
{
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $name;

    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $mime;

    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $postname;

    /**
     * Create a CURLFile object
     * @link https://secure.php.net/manual/en/curlfile.construct.php
     * @param string $filename <p>Path to the file which will be uploaded.</p>
     * @param string $mime_type [optional] <p>Mimetype of the file.</p>
     * @param string $posted_filename [optional] <p>Name of the file.</p>
     * @since 5.5
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $filename,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $mime_type = '',
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $posted_filename = ''
    ) {}

    /**
     * Get file name
     * @link https://secure.php.net/manual/en/curlfile.getfilename.php
     * @return string Returns file name.
     * @since 5.5
     */
    #[Pure]
    #[TentativeType]
    public function getFilename(): string {}

    /**
     * Get MIME type
     * @link https://secure.php.net/manual/en/curlfile.getmimetype.php
     * @return string Returns MIME type.
     * @since 5.5
     */
    #[Pure]
    #[TentativeType]
    public function getMimeType(): string {}

    /**
     * Get file name for POST
     * @link https://secure.php.net/manual/en/curlfile.getpostfilename.php
     * @return string Returns file name for POST.
     * @since 5.5
     */
    #[Pure]
    #[TentativeType]
    public function getPostFilename(): string {}

    /**
     * Set MIME type
     * @link https://secure.php.net/manual/en/curlfile.setmimetype.php
     * @param string $mime_type
     * @since 5.5
     */
    #[TentativeType]
    public function setMimeType(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $mime_type): void {}

    /**
     * Set file name for POST
     * https://secure.php.net/manual/en/curlfile.setpostfilename.php
     * @param string $posted_filename
     * @since 5.5
     */
    #[TentativeType]
    public function setPostFilename(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $posted_filename): void {}

    /**
     * @link https://secure.php.net/manual/en/curlfile.wakeup.php
     * Unserialization handler
     * @since 5.5
     */
    public function __wakeup() {}
}

/**
 * Initialize a cURL session
 * @link https://php.net/manual/en/function.curl-init.php
 * @param string|null $url [optional] <p>
 * If provided, the CURLOPT_URL option will be set
 * to its value. You can manually set this using the
 * curl_setopt function.
 * </p>
 * @return resource|false|CurlHandle a cURL handle on success, false on errors.
 */
#[LanguageLevelTypeAware(['8.0' => 'CurlHandle|false'], default: 'resource|false')]
function curl_init(?string $url) {}

/**
 * Copy a cURL handle along with all of its preferences
 * @link https://php.net/manual/en/function.curl-copy-handle.php
 * @param CurlHandle|resource $handle
 * @return CurlHandle|resource|false a new cURL handle.
 */
#[Pure]
#[LanguageLevelTypeAware(['8.0' => 'CurlHandle|false'], default: 'resource|false')]
function curl_copy_handle(#[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle) {}

/**
 * Gets cURL version information
 * @link https://php.net/manual/en/function.curl-version.php
 * @param int $age [optional] Removed since version PHP 8.0.
 * @return array|false an associative array with the following elements:
 * <tr valign="top">
 * <td>Indice</td>
 * <td>Value description</td>
 * </tr>
 * <tr valign="top">
 * <td>version_number</td>
 * <td>cURL 24 bit version number</td>
 * </tr>
 * <tr valign="top">
 * <td>version</td>
 * <td>cURL version number, as a string</td>
 * </tr>
 * <tr valign="top">
 * <td>ssl_version_number</td>
 * <td>OpenSSL 24 bit version number</td>
 * </tr>
 * <tr valign="top">
 * <td>ssl_version</td>
 * <td>OpenSSL version number, as a string</td>
 * </tr>
 * <tr valign="top">
 * <td>libz_version</td>
 * <td>zlib version number, as a string</td>
 * </tr>
 * <tr valign="top">
 * <td>host</td>
 * <td>Information about the host where cURL was built</td>
 * </tr>
 * <tr valign="top">
 * <td>age</td>
 * <td></td>
 * </tr>
 * <tr valign="top">
 * <td>features</td>
 * <td>A bitmask of the CURL_VERSION_XXX constants</td>
 * </tr>
 * <tr valign="top">
 * <td>protocols</td>
 * <td>An array of protocols names supported by cURL</td>
 * </tr>
 */
#[ArrayShape(["version_number" => "string", "version" => "string", "ssl_version_number" => "int", "ssl_version" => "string", "libz_version" => "string", "host" => "string", "age" => "int", "features" => "int", "protocols" => "array"])]
#[Pure]
function curl_version(#[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $age = null): array|false {}

/**
 * Set an option for a cURL transfer
 * @link https://php.net/manual/en/function.curl-setopt.php
 * @param CurlHandle|resource $handle
 * @param int $option <p>
 * The CURLOPT_XXX option to set.
 * </p>
 * @param mixed|callable $value <p>
 * The value to be set on option.
 * </p>
 * <p>
 * value should be a bool for the
 * following values of the option parameter:</p>
 * <table class="doctable informaltable">
 *
 * <thead>
 * <tr>
 * <th>Option</th>
 * <th>Set <em>value</em> to</th>
 * <th>Notes</th>
 * </tr>
 * </thead>
 *
 * <tbody class="tbody">
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_AUTOREFERER</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to automatically set the <em>Referer:</em> field in
 * requests where it follows a <em>Location:</em> redirect.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_BINARYTRANSFER</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to return the raw output when
 * <b>CURLOPT_RETURNTRANSFER</b> is used.
 * </td>
 * <td style="vertical-align: top;">
 * From PHP 5.1.3, this option has no effect: the raw output will
 * always be returned when
 * <b>CURLOPT_RETURNTRANSFER</b> is used.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_COOKIESESSION</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to mark this as a new cookie "session". It will force libcurl
 * to ignore all cookies it is about to load that are "session cookies"
 * from the previous session. By default, libcurl always stores and
 * loads all cookies, independent if they are session cookies or not.
 * Session cookies are cookies without expiry date and they are meant
 * to be alive and existing for this "session" only.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_CERTINFO</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to output SSL certification information to <em>STDERR</em>
 * on secure transfers.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.19.1.
 * Available since PHP 5.3.2.
 * Requires <b>CURLOPT_VERBOSE</b> to be on to have an effect.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_CONNECT_ONLY</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> tells the library to perform all the required proxy authentication
 * and connection setup, but no data transfer. This option is implemented for
 * HTTP, SMTP and POP3.
 * </td>
 * <td style="vertical-align: top;">
 * Added in 7.15.2.
 * Available since PHP 5.5.0.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_CRLF</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to convert Unix newlines to CRLF newlines
 * on transfers.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_DNS_USE_GLOBAL_CACHE</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to use a global DNS cache. This option is
 * not thread-safe and is enabled by default.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FAILONERROR</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to fail verbosely if the HTTP code returned
 * is greater than or equal to 400. The default behavior is to return
 * the page normally, ignoring the code.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FILETIME</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to attempt to retrieve the modification
 * date of the remote document. This value can be retrieved using
 * the <b>CURLINFO_FILETIME</b> option with
 * <span class="function">{@see curl_getinfo()}</span>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FOLLOWLOCATION</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to follow any
 * <em>"Location: "</em> header that the server sends as
 * part of the HTTP header (note this is recursive, PHP will follow as
 * many <em>"Location: "</em> headers that it is sent,
 * unless <b>CURLOPT_MAXREDIRS</b> is set).
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FORBID_REUSE</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to force the connection to explicitly
 * close when it has finished processing, and not be pooled for reuse.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FRESH_CONNECT</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to force the use of a new connection
 * instead of a cached one.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FTP_USE_EPRT</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to use EPRT (and LPRT) when doing active
 * FTP downloads. Use <b>FALSE</b> to disable EPRT and LPRT and use PORT
 * only.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FTP_USE_EPSV</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to first try an EPSV command for FTP
 * transfers before reverting back to PASV. Set to <b>FALSE</b>
 * to disable EPSV.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FTP_CREATE_MISSING_DIRS</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to create missing directories when an FTP operation
 * encounters a path that currently doesn't exist.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FTPAPPEND</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to append to the remote file instead of
 * overwriting it.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_TCP_NODELAY</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to disable TCP's Nagle algorithm, which tries to minimize
 * the number of small packets on the network.
 * </td>
 * <td style="vertical-align: top;">
 * Available since PHP 5.2.1 for versions compiled with libcurl 7.11.2 or
 * greater.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FTPASCII</b></td>
 * <td style="vertical-align: top;">
 * An alias of
 * <b>CURLOPT_TRANSFERTEXT</b>. Use that instead.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FTPLISTONLY</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to only list the names of an FTP
 * directory.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_HEADER</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to include the header in the output.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td><b>CURLINFO_HEADER_OUT</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to track the handle's request string.
 * </td>
 * <td style="vertical-align: top;">
 * Available since PHP 5.1.3. The <b>CURLINFO_</b>
 * prefix is intentional.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_HTTPGET</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to reset the HTTP request method to GET.
 * Since GET is the default, this is only necessary if the request
 * method has been changed.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_HTTPPROXYTUNNEL</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to tunnel through a given HTTP proxy.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_MUTE</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to be completely silent with regards to
 * the cURL functions.
 * </td>
 * <td style="vertical-align: top;">
 * Removed in cURL 7.15.5 (You can use CURLOPT_RETURNTRANSFER instead)
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_NETRC</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to scan the <var class="filename">~/.netrc</var>
 * file to find a username and password for the remote site that
 * a connection is being established with.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_NOBODY</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to exclude the body from the output.
 * Request method is then set to HEAD. Changing this to <b>FALSE</b> does
 * not change it to GET.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_NOPROGRESS</b></td>
 * <td style="vertical-align: top;"><p>
 * <b>TRUE</b> to disable the progress meter for cURL transfers.
 * </p><blockquote class="note"><p><strong class="note">Note</strong>:
 * </p><p>
 * PHP automatically sets this option to <b>TRUE</b>, this should only be
 * changed for debugging purposes.
 * </p>
 * </blockquote>
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_NOSIGNAL</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to ignore any cURL function that causes a
 * signal to be sent to the PHP process. This is turned on by default
 * in multi-threaded SAPIs so timeout options can still be used.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.10.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_POST</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to do a regular HTTP POST. This POST is the
 * normal <em>application/x-www-form-urlencoded</em> kind,
 * most commonly used by HTML forms.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_PUT</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to HTTP PUT a file. The file to PUT must
 * be set with <b>CURLOPT_INFILE</b> and
 * <b>CURLOPT_INFILESIZE</b>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_RETURNTRANSFER</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to return the transfer as a string of the
 * return value of <span class="function">{@see curl_exec()}</span> instead of outputting
 * it out directly.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SAFE_UPLOAD</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to disable support for the <em>@</em> prefix for
 * uploading files in <b>CURLOPT_POSTFIELDS</b>, which
 * means that values starting with <em>@</em> can be safely
 * passed as fields. {@see CURLFile} may be used for
 * uploads instead.
 * </td>
 * <td style="vertical-align: top;">
 * Added in PHP 5.5.0 with <b>FALSE</b> as the default value. PHP 5.6.0
 * changes the default value to <b>TRUE</b>.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSL_VERIFYPEER</b></td>
 * <td style="vertical-align: top;">
 * <b>FALSE</b> to stop cURL from verifying the peer's
 * certificate. Alternate certificates to verify against can be
 * specified with the <b>CURLOPT_CAINFO</b> option
 * or a certificate directory can be specified with the
 * <b>CURLOPT_CAPATH</b> option.
 * </td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> by default as of cURL 7.10. Default bundle installed as of
 * cURL 7.10.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_TRANSFERTEXT</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to use ASCII mode for FTP transfers.
 * For LDAP, it retrieves data in plain text instead of HTML. On
 * Windows systems, it will not set <em>STDOUT</em> to binary
 * mode.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_UNRESTRICTED_AUTH</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to keep sending the username and password
 * when following locations (using
 * <b>CURLOPT_FOLLOWLOCATION</b>), even when the
 * hostname has changed.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_UPLOAD</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to prepare for an upload.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_VERBOSE</b></td>
 * <td style="vertical-align: top;">
 * <b>TRUE</b> to output verbose information. Writes
 * output to <em>STDERR</em>, or the file specified using
 * <b>CURLOPT_STDERR</B>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * </tbody>
 *
 * </table>
 *
 * value should be an integer for the following values of the option parameter:
 * <table>
 *
 * <thead>
 * <tr>
 * <th>Option</th>
 * <th>Set <em>value</em> to</th>
 * <th>Notes</th>
 * </tr>
 * </thead>
 *
 * <tbody class="tbody">
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_BUFFERSIZE</b></td>
 * <td style="vertical-align: top;">
 * The size of the buffer to use for each read. There is no guarantee
 * this request will be fulfilled, however.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.10.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_CLOSEPOLICY</b></td>
 * <td style="vertical-align: top;">
 * One of the <b>CURLCLOSEPOLICY_*</b> values.
 * <blockquote class="note"><p><b>Note</b>:
 * </p><p>
 * This option is deprecated, as it was never implemented in cURL and
 * never had any effect.
 * </p>
 * </blockquote>
 * </td>
 * <td style="vertical-align: top;">
 * Removed in PHP 5.6.0.
 * </td>
 * </tr>
 *
 * <tr>
 * <td><b>CURLOPT_CONNECTTIMEOUT</b></td>
 * <td style="vertical-align: top;">
 * The number of seconds to wait while trying to connect. Use 0 to
 * wait indefinitely.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_CONNECTTIMEOUT_MS</b></td>
 * <td style="vertical-align: top;">
 * The number of milliseconds to wait while trying to connect. Use 0 to
 * wait indefinitely.
 *
 * If libcurl is built to use the standard system name resolver, that
 * portion of the connect will still use full-second resolution for
 * timeouts with a minimum timeout allowed of one second.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.16.2. Available since PHP 5.2.3.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_DNS_CACHE_TIMEOUT</b></td>
 * <td style="vertical-align: top;">
 * The number of seconds to keep DNS entries in memory. This
 * option is set to 120 (2 minutes) by default.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FTPSSLAUTH</b></td>
 * <td style="vertical-align: top;">
 * The FTP authentication method (when is activated):
 * <em>CURLFTPAUTH_SSL</em> (try SSL first),
 * <em>CURLFTPAUTH_TLS</em> (try TLS first), or
 * <em>CURLFTPAUTH_DEFAULT</em> (let cURL decide).
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.12.2.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_HTTP_VERSION</b></td>
 * <td style="vertical-align: top;">
 * <code class="parameter">CURL_HTTP_VERSION_NONE</code> (default, lets CURL
 * decide which version to use),
 * <code class="parameter">CURL_HTTP_VERSION_1_0</code> (forces HTTP/1.0),
 * or <code class="parameter">CURL_HTTP_VERSION_1_1</code> (forces HTTP/1.1).
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_HTTPAUTH</b></td>
 * <td style="vertical-align: top;">
 * <p>
 * The HTTP authentication method(s) to use. The options are:
 * <code class="parameter">CURLAUTH_BASIC</code>,
 * <code class="parameter">CURLAUTH_DIGEST</code>,
 * <code class="parameter">CURLAUTH_GSSNEGOTIATE</code>,
 * <code class="parameter">CURLAUTH_NTLM</code>,
 * <code class="parameter">CURLAUTH_ANY</code>, and
 * <code class="parameter">CURLAUTH_ANYSAFE</code>.
 * </p>
 * <p>
 * The bitwise <em>|</em> (or) operator can be used to combine
 * more than one method. If this is done, cURL will poll the server to see
 * what methods it supports and pick the best one.
 * </p>
 * <p>
 * <code class="parameter">CURLAUTH_ANY</code> is an alias for
 * <em>CURLAUTH_BASIC | CURLAUTH_DIGEST | CURLAUTH_GSSNEGOTIATE | CURLAUTH_NTLM</em>.
 * </p>
 * <p>
 * <code class="parameter">CURLAUTH_ANYSAFE</code> is an alias for
 * <em>CURLAUTH_DIGEST | CURLAUTH_GSSNEGOTIATE | CURLAUTH_NTLM</em>.
 * </p>
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_INFILESIZE</b></td>
 * <td style="vertical-align: top;">
 * The expected size, in bytes, of the file when uploading a file to
 * a remote site. Note that using this option will not stop libcurl
 * from sending more data, as exactly what is sent depends on
 * <b>CURLOPT_READFUNCTION</b>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_LOW_SPEED_LIMIT</b></td>
 * <td style="vertical-align: top;">
 * The transfer speed, in bytes per second, that the transfer should be
 * below during the count of <b>CURLOPT_LOW_SPEED_TIME</b>
 * seconds before PHP considers the transfer too slow and aborts.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_LOW_SPEED_TIME</b></td>
 * <td style="vertical-align: top;">
 * The number of seconds the transfer speed should be below
 * <b>CURLOPT_LOW_SPEED_LIMIT</b> before PHP considers
 * the transfer too slow and aborts.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_MAXCONNECTS</b></td>
 * <td style="vertical-align: top;">
 * The maximum amount of persistent connections that are allowed.
 * When the limit is reached,
 * <b>CURLOPT_CLOSEPOLICY</b> is used to determine
 * which connection to close.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_MAXREDIRS</b></td>
 * <td style="vertical-align: top;">
 * The maximum amount of HTTP redirections to follow. Use this option
 * alongside <b>CURLOPT_FOLLOWLOCATION</b>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_PORT</b></td>
 * <td style="vertical-align: top;">
 * An alternative port number to connect to.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_POSTREDIR</b></td>
 * <td style="vertical-align: top;">
 * A bitmask of 1 (301 Moved Permanently), 2 (302 Found)
 * vand 4 (303 See Other) if the HTTP POST method should be maintained
 * when <b>CURLOPT_FOLLOWLOCATION</b> is set and a
 * specific type of redirect occurs.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.19.1. Available since PHP 5.3.2.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_PROTOCOLS</b></td>
 * <td style="vertical-align: top;">
 * <p>
 * Bitmask of <b>CURLPROTO_*</b> values. If used, this bitmask
 * limits what protocols libcurl may use in the transfer. This allows you to have
 * a libcurl built to support a wide range of protocols but still limit specific
 * transfers to only be allowed to use a subset of them. By default libcurl will
 * accept all protocols it supports.
 * See also <b>CURLOPT_REDIR_PROTOCOLS</b>.
 * </p>
 * <p>
 * Valid protocol options are:
 * <code class="parameter">CURLPROTO_HTTP</code>,
 * <code class="parameter">CURLPROTO_HTTPS</code>,
 * <code class="parameter">CURLPROTO_FTP</code>,
 * <code class="parameter">CURLPROTO_FTPS</code>,
 * <code class="parameter">CURLPROTO_SCP</code>,
 * <code class="parameter">CURLPROTO_SFTP</code>,
 * <code class="parameter">CURLPROTO_TELNET</code>,
 * <code class="parameter">CURLPROTO_LDAP</code>,
 * <code class="parameter">CURLPROTO_LDAPS</code>,
 * <code class="parameter">CURLPROTO_DICT</code>,
 * <code class="parameter">CURLPROTO_FILE</code>,
 * <code class="parameter">CURLPROTO_TFTP</code>,
 * <code class="parameter">CURLPROTO_ALL</code>
 * </p>
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.19.4.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_PROXYAUTH</b></td>
 * <td style="vertical-align: top;">
 * The HTTP authentication method(s) to use for the proxy connection.
 * Use the same bitmasks as described in
 * <b>CURLOPT_HTTPAUTH</b>. For proxy authentication,
 * only <code class="parameter">CURLAUTH_BASIC</code> and
 * <code class="parameter">CURLAUTH_NTLM</code> are currently supported.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.10.7.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_PROXYPORT</b></td>
 * <td style="vertical-align: top;">
 * The port number of the proxy to connect to. This port number can
 * also be set in <b>CURLOPT_PROXY</b>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_PROXYTYPE</b></td>
 * <td style="vertical-align: top;">
 * Either <b>CURLPROXY_HTTP</b> (default),
 * <b>CURLPROXY_SOCKS4</b>,
 * <b>CURLPROXY_SOCKS5</b>,
 * <b>CURLPROXY_SOCKS4A</b> or
 * <b>CURLPROXY_SOCKS5_HOSTNAME</b>.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.10.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_REDIR_PROTOCOLS</b></td>
 * <td style="vertical-align: top;">
 * Bitmask of <b>CURLPROTO_*</b> values. If used, this bitmask
 * limits what protocols libcurl may use in a transfer that it follows to in
 * a redirect when <b>CURLOPT_FOLLOWLOCATION</b> is enabled.
 * This allows you to limit specific transfers to only be allowed to use a subset
 * of protocols in redirections. By default libcurl will allow all protocols
 * except for FILE and SCP. This is a difference compared to pre-7.19.4 versions
 * which unconditionally would follow to all protocols supported.
 * See also <b>CURLOPT_PROTOCOLS</b> for protocol constant values.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.19.4.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_RESUME_FROM</b></td>
 * <td style="vertical-align: top;">
 * The offset, in bytes, to resume a transfer from.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSL_VERIFYHOST</b></td>
 * <td style="vertical-align: top;">
 * 1 to check the existence of a common name in the
 * SSL peer certificate. 2 to check the existence of
 * a common name and also verify that it matches the hostname
 * provided. In production environments the value of this option
 * should be kept at 2 (default value).
 * </td>
 * <td style="vertical-align: top;">
 * Support for value 1 removed in cURL 7.28.1
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLVERSION</b></td>
 * <td style="vertical-align: top;">
 * One of <b>CURL_SSLVERSION_DEFAULT</b> (0),
 * <b>CURL_SSLVERSION_TLSv1</b> (1),
 * <b>CURL_SSLVERSION_SSLv2</b> (2),
 * <b>CURL_SSLVERSION_SSLv3</b> (3),
 * <b>CURL_SSLVERSION_TLSv1_0</b> (4),
 * <b>CURL_SSLVERSION_TLSv1_1</b> (5) or
 * <b>CURL_SSLVERSION_TLSv1_2</b> (6).
 * <blockquote class="note"><p><strong class="note">Note</strong>:
 * </p><p class="para">
 * Your best bet is to not set this and let it use the default.
 * Setting it to 2 or 3 is very dangerous given the known
 * vulnerabilities in SSLv2 and SSLv3.
 * </p>
 * </blockquote>
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_TIMECONDITION</b></td>
 * <td style="vertical-align: top;">
 * How <b>CURLOPT_TIMEVALUE</b> is treated.
 * Use <b>CURL_TIMECOND_IFMODSINCE</b> to return the
 * page only if it has been modified since the time specified in
 * <b>CURLOPT_TIMEVALUE</b>. If it hasn't been modified,
 * a <em>"304 Not Modified"</em> header will be returned
 * assuming <b>CURLOPT_HEADER</code></strong> is <strong><code>TRUE</b>.
 * Use <code class="parameter">CURL_TIMECOND_IFUNMODSINCE</code> for the reverse
 * effect. <code class="parameter">CURL_TIMECOND_IFMODSINCE</code> is the
 * default.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_TIMEOUT</b></td>
 * <td style="vertical-align: top;">
 * The maximum number of seconds to allow cURL functions to execute.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_TIMEOUT_MS</b></td>
 * <td style="vertical-align: top;">
 * The maximum number of milliseconds to allow cURL functions to
 * execute.
 *
 * If libcurl is built to use the standard system name resolver, that
 * portion of the connect will still use full-second resolution for
 * timeouts with a minimum timeout allowed of one second.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.16.2. Available since PHP 5.2.3.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_TIMEVALUE</b></td>
 * <td style="vertical-align: top;">
 * The time in seconds since January 1st, 1970. The time will be used
 * by <b>CURLOPT_TIMECONDITION</b>. By default,
 * <code class="parameter">CURL_TIMECOND_IFMODSINCE</code> is used.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_MAX_RECV_SPEED_LARGE</b></td>
 * <td style="vertical-align: top;">
 * If a download exceeds this speed (counted in bytes per second) on
 * cumulative average during the transfer, the transfer will pause to
 * keep the average rate less than or equal to the parameter value.
 * Defaults to unlimited speed.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.15.5. Available since PHP 5.4.0.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_MAX_SEND_SPEED_LARGE</b></td>
 * <td style="vertical-align: top;">
 * If an upload exceeds this speed (counted in bytes per second) on
 * cumulative average during the transfer, the transfer will pause to
 * keep the average rate less than or equal to the parameter value.
 * Defaults to unlimited speed.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.15.5. Available since PHP 5.4.0.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSH_AUTH_TYPES</b></td>
 * <td style="vertical-align: top;">
 * A bitmask consisting of one or more of
 * <b>CURLSSH_AUTH_PUBLICKEY</b>,
 * <b>CURLSSH_AUTH_PASSWORD</b>,
 * <b>CURLSSH_AUTH_HOST</b>,
 * <b>CURLSSH_AUTH_KEYBOARD</b>. Set to
 * <b>CURLSSH_AUTH_ANY</b> to let libcurl pick one.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.16.1.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_IPRESOLVE</b></td>
 * <td style="vertical-align: top;">
 * Allows an application to select what kind of IP addresses to use when
 * resolving host names. This is only interesting when using host names that
 * resolve addresses using more than one version of IP, possible values are
 * <b>CURL_IPRESOLVE_WHATEVER</b>,
 * <b>CURL_IPRESOLVE_V4</b>,
 * <b>CURL_IPRESOLVE_V6</b>, by default
 * <b>CURL_IPRESOLVE_WHATEVER</b>.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.10.8.
 * </td>
 * </tr>
 *
 * </tbody>
 *
 * </table>
 *
 * value should be a string for the following values of the option parameter:
 * <table class="doctable informaltable">
 *
 * <thead>
 * <tr>
 * <th>Option</th>
 * <th>Set <code class="parameter">value</code> to</th>
 * <th>Notes</th>
 * </tr>
 * </thead>
 *
 * <tbody class="tbody">
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_CAINFO</b></td>
 * <td style="vertical-align: top;">
 * The name of a file holding one or more certificates to verify the
 * peer with. This only makes sense when used in combination with
 * <b>CURLOPT_SSL_VERIFYPEER</b>.
 * </td>
 * <td style="vertical-align: top;">
 * Might require an absolute path.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_CAPATH</b></td>
 * <td style="vertical-align: top;">
 * A directory that holds multiple CA certificates. Use this option
 * alongside <b>CURLOPT_SSL_VERIFYPEER</b>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_COOKIE</b></td>
 * <td style="vertical-align: top;">
 * The contents of the <em>"Cookie: "</em> header to be
 * used in the HTTP request.
 * Note that multiple cookies are separated with a semicolon followed
 * by a space (e.g., "<em>fruit=apple; colour=red</em>")
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_COOKIEFILE</b></td>
 * <td style="vertical-align: top;">
 * The name of the file containing the cookie data. The cookie file can
 * be in Netscape format, or just plain HTTP-style headers dumped into
 * a file.
 * If the name is an empty string, no cookies are loaded, but cookie
 * handling is still enabled.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_COOKIEJAR</b></td>
 * <td style="vertical-align: top;">
 * The name of a file to save all internal cookies to when the handle is closed,
 * e.g. after a call to curl_close.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_CUSTOMREQUEST</b></td>
 * <td style="vertical-align: top;"><p class="para">
 * A custom request method to use instead of
 * <em>"GET"</em> or <em>"HEAD"</em> when doing
 * a HTTP request. This is useful for doing
 * <em>"DELETE"</em> or other, more obscure HTTP requests.
 * Valid values are things like <em>"GET"</em>,
 * <em>"POST"</em>, <em>"CONNECT"</em> and so on;
 * i.e. Do not enter a whole HTTP request line here. For instance,
 * entering <em>"GET /index.html HTTP/1.0\r\n\r\n"</em>
 * would be incorrect.
 * </p><blockquote class="note"><p><strong class="note">Note</strong>:
 * </p><p class="para">
 * Don't do this without making sure the server supports the custom
 * request method first.
 * </p>
 * </blockquote>
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_EGDSOCKET</b></td>
 * <td style="vertical-align: top;">
 * Like <b>CURLOPT_RANDOM_FILE</b>, except a filename
 * to an Entropy Gathering Daemon socket.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_ENCODING</b></td>
 * <td style="vertical-align: top;">
 * The contents of the <em>"Accept-Encoding: "</em> header.
 * This enables decoding of the response. Supported encodings are
 * <em>"identity"</em>, <em>"deflate"</em>, and
 * <em>"gzip"</em>. If an empty string, <em>""</em>,
 * is set, a header containing all supported encoding types is sent.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.10.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FTPPORT</b></td>
 * <td style="vertical-align: top;">
 * The value which will be used to get the IP address to use
 * for the FTP "PORT" instruction. The "PORT" instruction tells
 * the remote server to connect to our specified IP address.  The
 * string may be a plain IP address, a hostname, a network
 * interface name (under Unix), or just a plain '-' to use the
 * systems default IP address.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_INTERFACE</b></td>
 * <td style="vertical-align: top;">
 * The name of the outgoing network interface to use. This can be an
 * interface name, an IP address or a host name.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_KEYPASSWD</b></td>
 * <td style="vertical-align: top;">
 * The password required to use the <b>CURLOPT_SSLKEY</b>
 * or <b>CURLOPT_SSH_PRIVATE_KEYFILE</b> private key.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.16.1.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_KRB4LEVEL</b></td>
 * <td style="vertical-align: top;">
 * The KRB4 (Kerberos 4) security level. Any of the following values
 * (in order from least to most powerful) are valid:
 * <em>"clear"</em>,
 * <em>"safe"</em>,
 * <em>"confidential"</em>,
 * <em>"private".</em>.
 * If the string does not match one of these,
 * <em>"private"</em> is used. Setting this option to <b>NULL</b>
 * will disable KRB4 security. Currently KRB4 security only works
 * with FTP transactions.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_POSTFIELDS</b></td>
 * <td style="vertical-align: top;">
 * <span class="simpara">
 * The full data to post in a HTTP "POST" operation.
 * To post a file, prepend a filename with <em>@</em> and
 * use the full path. The filetype can be explicitly specified by
 * following the filename with the type in the format
 * '<em>;type=mimetype</em>'. This parameter can either be
 * passed as a urlencoded string like '<em>para1=val1&amp;para2=val2&amp;...</em>'
 * or as an array with the field name as key and field data as value.
 * If <code class="parameter">value</code> is an array, the
 * <em>Content-Type</em> header will be set to
 * <em>multipart/form-data</em>.
 * </span>
 * <span class="simpara">
 * As of PHP 5.2.0, <code class="parameter">value</code> must be an array if
 * files are passed to this option with the <em>@</em> prefix.
 * </span>
 * <span class="simpara">
 * As of PHP 5.5.0, the <em>@</em> prefix is deprecated and
 * files can be sent using <a href="class.curlfile.php" class="classname">CURLFile</a>. The
 * <em>@</em> prefix can be disabled for safe passing of
 * values beginning with <em>@</em> by setting the
 * <b>CURLOPT_SAFE_UPLOAD</code></strong> option to <strong><code>TRUE</b>.
 * </span>
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_PROXY</b></td>
 * <td style="vertical-align: top;">
 * The HTTP proxy to tunnel requests through.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_PROXYUSERPWD</b></td>
 * <td style="vertical-align: top;">
 * A username and password formatted as
 * <em>"[username]:[password]"</em> to use for the
 * connection to the proxy.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_RANDOM_FILE</b></td>
 * <td style="vertical-align: top;">
 * A filename to be used to seed the random number generator for SSL.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_RANGE</b></td>
 * <td style="vertical-align: top;">
 * Range(s) of data to retrieve in the format
 * <em>"X-Y"</em> where X or Y are optional. HTTP transfers
 * also support several intervals, separated with commas in the format
 * <em>"X-Y,N-M"</em>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_REFERER</b></td>
 * <td style="vertical-align: top;">
 * The contents of the <em>"Referer: "</em> header to be used
 * in a HTTP request.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSH_HOST_PUBLIC_KEY_MD5</b></td>
 * <td style="vertical-align: top;">
 * A string containing 32 hexadecimal digits. The string should be the
 * MD5 checksum of the remote host's public key, and libcurl will reject
 * the connection to the host unless the md5sums match.
 * This option is only for SCP and SFTP transfers.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.17.1.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSH_PUBLIC_KEYFILE</b></td>
 * <td style="vertical-align: top;">
 * The file name for your public key. If not used, libcurl defaults to
 * $HOME/.ssh/id_dsa.pub if the HOME environment variable is set,
 * and just "id_dsa.pub" in the current directory if HOME is not set.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.16.1.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSH_PRIVATE_KEYFILE</b></td>
 * <td style="vertical-align: top;">
 * The file name for your private key. If not used, libcurl defaults to
 * $HOME/.ssh/id_dsa if the HOME environment variable is set,
 * and just "id_dsa" in the current directory if HOME is not set.
 * If the file is password-protected, set the password with
 * <b>CURLOPT_KEYPASSWD</b>.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.16.1.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSL_CIPHER_LIST</b></td>
 * <td style="vertical-align: top;">
 * A list of ciphers to use for SSL. For example,
 * <em>RC4-SHA</em> and <em>TLSv1</em> are valid
 * cipher lists.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLCERT</b></td>
 * <td style="vertical-align: top;">
 * The name of a file containing a PEM formatted certificate.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLCERTPASSWD</b></td>
 * <td style="vertical-align: top;">
 * The password required to use the
 * <b>CURLOPT_SSLCERT</b> certificate.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLCERTTYPE</b></td>
 * <td style="vertical-align: top;">
 * The format of the certificate. Supported formats are
 * <em>"PEM"</em> (default), <em>"DER"</em>,
 * and <em>"ENG"</em>.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.9.3.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLENGINE</b></td>
 * <td style="vertical-align: top;">
 * The identifier for the crypto engine of the private SSL key
 * specified in <b>CURLOPT_SSLKEY</b>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLENGINE_DEFAULT</b></td>
 * <td style="vertical-align: top;">
 * The identifier for the crypto engine used for asymmetric crypto
 * operations.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLKEY</b></td>
 * <td style="vertical-align: top;">
 * The name of a file containing a private SSL key.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLKEYPASSWD</b></td>
 * <td style="vertical-align: top;"><p class="para">
 * The secret password needed to use the private SSL key specified in
 * <b>CURLOPT_SSLKEY</b>.
 * </p><blockquote class="note"><p><strong class="note">Note</strong>:
 * </p><p class="para">
 * Since this option contains a sensitive password, remember to keep
 * the PHP script it is contained within safe.
 * </p>
 * </blockquote>
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLKEYTYPE</b></td>
 * <td style="vertical-align: top;">
 * The key type of the private SSL key specified in
 * <b>CURLOPT_SSLKEY</b>. Supported key types are
 * <em>"PEM"</em> (default), <em>"DER"</em>,
 * and <em>"ENG"</em>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_URL</b></td>
 * <td style="vertical-align: top;">
 * The URL to fetch. This can also be set when initializing a
 * session with {@see curl_init()}</span>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_USERAGENT</b></td>
 * <td style="vertical-align: top;">
 * The contents of the <em>"User-Agent: "</em> header to be
 * used in a HTTP request.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_USERPWD</b></td>
 * <td style="vertical-align: top;">
 * A username and password formatted as
 * <em>"[username]:[password]"</em> to use for the
 * connection.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * </tbody>
 *
 * </table>
 * <table class="doctable informaltable">
 *
 * <thead>
 * <tr>
 * <th>Option</th>
 * <th>Set <code class="parameter">value</code> to</th>
 * <th>Notes</th>
 * </tr>
 * </thead>
 *
 * <tbody class="tbody">
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_CAINFO</b></td>
 * <td style="vertical-align: top;">
 * The name of a file holding one or more certificates to verify the
 * peer with. This only makes sense when used in combination with
 * <b>CURLOPT_SSL_VERIFYPEER</b>.
 * </td>
 * <td style="vertical-align: top;">
 * Might require an absolute path.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_CAPATH</b></td>
 * <td style="vertical-align: top;">
 * A directory that holds multiple CA certificates. Use this option
 * alongside <b>CURLOPT_SSL_VERIFYPEER</b>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_COOKIE</b></td>
 * <td style="vertical-align: top;">
 * The contents of the <em>"Cookie: "</em> header to be
 * used in the HTTP request.
 * Note that multiple cookies are separated with a semicolon followed
 * by a space (e.g., "<em>fruit=apple; colour=red</em>")
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_COOKIEFILE</b></td>
 * <td style="vertical-align: top;">
 * The name of the file containing the cookie data. The cookie file can
 * be in Netscape format, or just plain HTTP-style headers dumped into
 * a file.
 * If the name is an empty string, no cookies are loaded, but cookie
 * handling is still enabled.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_COOKIEJAR</b></td>
 * <td style="vertical-align: top;">
 * The name of a file to save all internal cookies to when the handle is closed,
 * e.g. after a call to curl_close.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_CUSTOMREQUEST</b></td>
 * <td style="vertical-align: top;"><p class="para">
 * A custom request method to use instead of
 * <em>"GET"</em> or <em>"HEAD"</em> when doing
 * a HTTP request. This is useful for doing
 * <em>"DELETE"</em> or other, more obscure HTTP requests.
 * Valid values are things like <em>"GET"</em>,
 * <em>"POST"</em>, <em>"CONNECT"</em> and so on;
 * i.e. Do not enter a whole HTTP request line here. For instance,
 * entering <em>"GET /index.html HTTP/1.0\r\n\r\n"</em>
 * would be incorrect.
 * </p><blockquote class="note"><p><strong class="note">Note</strong>:
 * </p><p class="para">
 * Don't do this without making sure the server supports the custom
 * request method first.
 * </p>
 * </blockquote>
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_EGDSOCKET</b></td>
 * <td style="vertical-align: top;">
 * Like <b>CURLOPT_RANDOM_FILE</b>, except a filename
 * to an Entropy Gathering Daemon socket.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_ENCODING</b></td>
 * <td style="vertical-align: top;">
 * The contents of the <em>"Accept-Encoding: "</em> header.
 * This enables decoding of the response. Supported encodings are
 * <em>"identity"</em>, <em>"deflate"</em>, and
 * <em>"gzip"</em>. If an empty string, <em>""</em>,
 * is set, a header containing all supported encoding types is sent.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.10.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FTPPORT</b></td>
 * <td style="vertical-align: top;">
 * The value which will be used to get the IP address to use
 * for the FTP "PORT" instruction. The "PORT" instruction tells
 * the remote server to connect to our specified IP address.  The
 * string may be a plain IP address, a hostname, a network
 * interface name (under Unix), or just a plain '-' to use the
 * systems default IP address.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_INTERFACE</b></td>
 * <td style="vertical-align: top;">
 * The name of the outgoing network interface to use. This can be an
 * interface name, an IP address or a host name.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_KEYPASSWD</b></td>
 * <td style="vertical-align: top;">
 * The password required to use the <b>CURLOPT_SSLKEY</b>
 * or <b>CURLOPT_SSH_PRIVATE_KEYFILE</b> private key.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.16.1.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_KRB4LEVEL</b></td>
 * <td style="vertical-align: top;">
 * The KRB4 (Kerberos 4) security level. Any of the following values
 * (in order from least to most powerful) are valid:
 * <em>"clear"</em>,
 * <em>"safe"</em>,
 * <em>"confidential"</em>,
 * <em>"private".</em>.
 * If the string does not match one of these,
 * <em>"private"</em> is used. Setting this option to <b>NULL</b>
 * will disable KRB4 security. Currently KRB4 security only works
 * with FTP transactions.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_POSTFIELDS</b></td>
 * <td style="vertical-align: top;">
 * <span class="simpara">
 * The full data to post in a HTTP "POST" operation.
 * To post a file, prepend a filename with <em>@</em> and
 * use the full path. The filetype can be explicitly specified by
 * following the filename with the type in the format
 * '<em>;type=mimetype</em>'. This parameter can either be
 * passed as a urlencoded string like '<em>para1=val1&amp;para2=val2&amp;...</em>'
 * or as an array with the field name as key and field data as value.
 * If <code class="parameter">value</code> is an array, the
 * <em>Content-Type</em> header will be set to
 * <em>multipart/form-data</em>.
 * </span>
 * <span class="simpara">
 * As of PHP 5.2.0, <code class="parameter">value</code> must be an array if
 * files are passed to this option with the <em>@</em> prefix.
 * </span>
 * <span class="simpara">
 * As of PHP 5.5.0, the <em>@</em> prefix is deprecated and
 * files can be sent using <a href="class.curlfile.php" class="classname">CURLFile</a>. The
 * <em>@</em> prefix can be disabled for safe passing of
 * values beginning with <em>@</em> by setting the
 * <b>CURLOPT_SAFE_UPLOAD</code></strong> option to <strong><code>TRUE</b>.
 * </span>
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_PROXY</b></td>
 * <td style="vertical-align: top;">
 * The HTTP proxy to tunnel requests through.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_PROXYUSERPWD</b></td>
 * <td style="vertical-align: top;">
 * A username and password formatted as
 * <em>"[username]:[password]"</em> to use for the
 * connection to the proxy.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_RANDOM_FILE</b></td>
 * <td style="vertical-align: top;">
 * A filename to be used to seed the random number generator for SSL.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_RANGE</b></td>
 * <td style="vertical-align: top;">
 * Range(s) of data to retrieve in the format
 * <em>"X-Y"</em> where X or Y are optional. HTTP transfers
 * also support several intervals, separated with commas in the format
 * <em>"X-Y,N-M"</em>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_REFERER</b></td>
 * <td style="vertical-align: top;">
 * The contents of the <em>"Referer: "</em> header to be used
 * in a HTTP request.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSH_HOST_PUBLIC_KEY_MD5</b></td>
 * <td style="vertical-align: top;">
 * A string containing 32 hexadecimal digits. The string should be the
 * MD5 checksum of the remote host's public key, and libcurl will reject
 * the connection to the host unless the md5sums match.
 * This option is only for SCP and SFTP transfers.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.17.1.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSH_PUBLIC_KEYFILE</b></td>
 * <td style="vertical-align: top;">
 * The file name for your public key. If not used, libcurl defaults to
 * $HOME/.ssh/id_dsa.pub if the HOME environment variable is set,
 * and just "id_dsa.pub" in the current directory if HOME is not set.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.16.1.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSH_PRIVATE_KEYFILE</b></td>
 * <td style="vertical-align: top;">
 * The file name for your private key. If not used, libcurl defaults to
 * $HOME/.ssh/id_dsa if the HOME environment variable is set,
 * and just "id_dsa" in the current directory if HOME is not set.
 * If the file is password-protected, set the password with
 * <b>CURLOPT_KEYPASSWD</b>.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.16.1.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSL_CIPHER_LIST</b></td>
 * <td style="vertical-align: top;">
 * A list of ciphers to use for SSL. For example,
 * <em>RC4-SHA</em> and <em>TLSv1</em> are valid
 * cipher lists.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLCERT</b></td>
 * <td style="vertical-align: top;">
 * The name of a file containing a PEM formatted certificate.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLCERTPASSWD</b></td>
 * <td style="vertical-align: top;">
 * The password required to use the
 * <b>CURLOPT_SSLCERT</b> certificate.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLCERTTYPE</b></td>
 * <td style="vertical-align: top;">
 * The format of the certificate. Supported formats are
 * <em>"PEM"</em> (default), <em>"DER"</em>,
 * and <em>"ENG"</em>.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.9.3.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLENGINE</b></td>
 * <td style="vertical-align: top;">
 * The identifier for the crypto engine of the private SSL key
 * specified in <b>CURLOPT_SSLKEY</b>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLENGINE_DEFAULT</b></td>
 * <td style="vertical-align: top;">
 * The identifier for the crypto engine used for asymmetric crypto
 * operations.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLKEY</b></td>
 * <td style="vertical-align: top;">
 * The name of a file containing a private SSL key.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLKEYPASSWD</b></td>
 * <td style="vertical-align: top;"><p class="para">
 * The secret password needed to use the private SSL key specified in
 * <b>CURLOPT_SSLKEY</b>.
 * </p><blockquote class="note"><p><strong class="note">Note</strong>:
 * </p><p class="para">
 * Since this option contains a sensitive password, remember to keep
 * the PHP script it is contained within safe.
 * </p>
 * </blockquote>
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SSLKEYTYPE</b></td>
 * <td style="vertical-align: top;">
 * The key type of the private SSL key specified in
 * <b>CURLOPT_SSLKEY</b>. Supported key types are
 * <em>"PEM"</em> (default), <em>"DER"</em>,
 * and <em>"ENG"</em>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_URL</b></td>
 * <td style="vertical-align: top;">
 * The URL to fetch. This can also be set when initializing a
 * session with <span class="function"><a href="function.curl-init.php" class="function">curl_init()</a></span>.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_USERAGENT</b></td>
 * <td style="vertical-align: top;">
 * The contents of the <em>"User-Agent: "</em> header to be
 * used in a HTTP request.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_USERPWD</b></td>
 * <td style="vertical-align: top;">
 * A username and password formatted as
 * <em>"[username]:[password]"</em> to use for the
 * connection.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 * </tbody>
 *
 * </table>
 * <p>
 * value should be an array for the following values of the option parameter:</p>
 * <table class="doctable informaltable">
 *
 * <thead>
 * <tr>
 * <th>Option</th>
 * <th>Set <code class="parameter">value</code> to</th>
 * <th>Notes</th>
 * </tr>
 * </thead>
 *
 * <tbody class="tbody">
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_HTTP200ALIASES</b></td>
 * <td style="vertical-align: top;">
 * An array of HTTP 200 responses that will be treated as valid
 * responses and not as errors.
 * </td>
 * <td style="vertical-align: top;">
 * Added in cURL 7.10.3.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_HTTPHEADER</b></td>
 * <td style="vertical-align: top;">
 * An array of HTTP header fields to set, in the format
 * <code class="code">
 * array('Content-type: text/plain', 'Content-length: 100')
 * </code>
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_POSTQUOTE</b></td>
 * <td style="vertical-align: top;">
 * An array of FTP commands to execute on the server after the FTP
 * request has been performed.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_QUOTE</b></td>
 * <td style="vertical-align: top;">
 * An array of FTP commands to execute on the server prior to the FTP
 * request.
 * </td>
 * <td style="vertical-align: top;">
 * </td>
 * </tr>
 *
 * </tbody>
 *
 * </table>
 * value should be a stream resource (using {@see fopen()}, for example) for the following values of the option parameter:
 * <table class="doctable informaltable">
 *
 * <thead>
 * <tr>
 * <th>Option</th>
 * <th>Set <code class="parameter">value</code> to</th>
 * </tr>
 * </thead>
 *
 * <tbody class="tbody">
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_FILE</b></td>
 * <td style="vertical-align: top;">
 * The file that the transfer should be written to. The default
 * is <em>STDOUT</em> (the browser window).
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_INFILE</b></td>
 * <td style="vertical-align: top;">
 * The file that the transfer should be read from when uploading.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_STDERR</b></td>
 * <td style="vertical-align: top;">
 * An alternative location to output errors to instead of
 * <em>STDERR</em>.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_WRITEHEADER</b></td>
 * <td style="vertical-align: top;">
 * The file that the header part of the transfer is written to.
 * </td>
 * </tr>
 *
 * </tbody>
 *
 * </table>
 *  value should be the name of a valid function or a Closure for the following values of the option parameter:
 * <table class="doctable informaltable">
 *
 * <thead>
 * <tr>
 * <th>Option</th>
 * <th>Set <code class="parameter">value</code> to</th>
 * </tr>
 * </thead>
 *
 * <tbody class="tbody">
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_HEADERFUNCTION</b></td>
 * <td style="vertical-align: top;">
 * A callback accepting two parameters.
 * The first is the cURL resource, the second is a
 * string with the header data to be written. The header data must
 * be written by this callback. Return the number of
 * bytes written.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_PASSWDFUNCTION</b></td>
 * <td style="vertical-align: top;">
 * A callback accepting three parameters.
 * The first is the cURL resource, the second is a
 * string containing a password prompt, and the third is the maximum
 * password length. Return the string containing the password.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_PROGRESSFUNCTION</b></td>
 * <td style="vertical-align: top;">
 * <p>
 * A callback accepting five parameters.
 * The first is the cURL resource, the second is the total number of
 * bytes expected to be downloaded in this transfer, the third is
 * the number of bytes downloaded so far, the fourth is the total
 * number of bytes expected to be uploaded in this transfer, and the
 * fifth is the number of bytes uploaded so far.
 * </p>
 * <blockquote class="note"><p><strong class="note">Note</strong>:
 * </p><p class="para">
 * The callback is only called when the <b>CURLOPT_NOPROGRESS</b>
 * option is set to <b>FALSE</b>.
 * </p>
 * </blockquote>
 * <p>
 * Return a non-zero value to abort the transfer. In which case, the
 * transfer will set a <b>CURLE_ABORTED_BY_CALLBACK</b>
 * error.
 * </p>
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_READFUNCTION</b></td>
 * <td style="vertical-align: top;">
 * A callback accepting three parameters.
 * The first is the cURL resource, the second is a
 * stream resource provided to cURL through the option
 * <b>CURLOPT_INFILE</b>, and the third is the maximum
 * amount of data to be read. The callback must return a string
 * with a length equal or smaller than the amount of data requested,
 * typically by reading it from the passed stream resource. It should
 * return an empty string to signal <em>EOF</em>.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_WRITEFUNCTION</b></td>
 * <td style="vertical-align: top;">
 * A callback accepting two parameters.
 * The first is the cURL resource, and the second is a
 * string with the data to be written. The data must be saved by
 * this callback. It must return the exact number of bytes written
 * or the transfer will be aborted with an error.
 * </td>
 * </tr>
 *
 * </tbody>
 *
 * </table>
 * Other values:
 * <table class="doctable informaltable">
 *
 * <thead>
 * <tr>
 * <th>Option</th>
 * <th>Set <code class="parameter">value</code> to</th>
 * </tr>
 * </thead>
 *
 * <tbody class="tbody">
 * <tr>
 * <td style="vertical-align: top;"><b>CURLOPT_SHARE</b></td>
 * <td style="vertical-align: top;">
 * A result of <span class="function">{@see curl_share_init()}</span>. Makes the cURL
 * handle to use the data from the shared handle.
 * </td>
 * </tr>
 * </tbody>
 *
 * </table>
 * @return bool true on success or false on failure.
 */
function curl_setopt(#[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle, int $option, mixed $value): bool {}

/**
 * Set multiple options for a cURL transfer
 * @link https://php.net/manual/en/function.curl-setopt-array.php
 * @param CurlHandle|resource $handle
 * @param array $options <p>
 * An array specifying which options to set and their values.
 * The keys should be valid curl_setopt constants or
 * their integer equivalents.
 * </p>
 * @return bool true if all options were successfully set. If an option could
 * not be successfully set, false is immediately returned, ignoring any
 * future options in the options array.
 * @since 5.1.3
 */
function curl_setopt_array(#[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle, array $options): bool {}

/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Close a cURL share handle
 * @link https://secure.php.net/manual/en/function.curl-share-close.php
 * @param CurlShareHandle|resource $share_handle <p>
 * A cURL share handle returned by  {@link https://secure.php.net/manual/en/function.curl-share-init.php curl_share_init()}
 * </p>
 * @return void
 * @since 5.5
 */
function curl_share_close(#[LanguageLevelTypeAware(['8.0' => 'CurlShareHandle'], default: 'resource')] $share_handle): void {}

/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Initialize a cURL share handle
 * @link https://secure.php.net/manual/en/function.curl-share-init.php
 * @return resource|CurlShareHandle Returns resource of type "cURL Share Handle".
 * @since 5.5
 */
#[LanguageLevelTypeAware(['8.0' => 'CurlShareHandle'], default: 'resource')]
function curl_share_init() {}

/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Set an option for a cURL share handle.
 * @link https://secure.php.net/manual/en/function.curl-share-setopt.php
 * @param CurlShareHandle|resource $share_handle <p>
 * A cURL share handle returned by  {@link https://secure.php.net/manual/en/function.curl-share-init.php curl_share_init()}.
 * </p>
 * @param int $option <table>
 *
 * <thead>
 * <tr>
 * <th>Option</th>
 * <th>Description</th>
 * </tr>
 * </thead>
 *
 * <tbody>
 * <tr>
 * <td style="vertical-align: top;"><b>CURLSHOPT_SHARE</b></td>
 * <td style="vertical-align: top;">
 * Specifies a type of data that should be shared.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLSHOPT_UNSHARE</b></td>
 * <td style="vertical-align: top;">
 * Specifies a type of data that will be no longer shared.
 * </td>
 * </tr>
 *
 * </tbody>
 *
 * </table>
 * @param string $value <p><table>
 *
 * <thead>
 * <tr>
 * <th>Value</th>
 * <th>Description</th>
 * </tr>
 * </thead>
 *
 * <tbody class="tbody">
 * <tr>
 * <td style="vertical-align: top;"><b>CURL_LOCK_DATA_COOKIE</b></td>
 * <td style="vertical-align: top;">
 * Shares cookie data.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURL_LOCK_DATA_DNS</b></td>
 * <td style="vertical-align: top;">
 * Shares DNS cache. Note that when you use cURL multi handles,
 * all handles added to the same multi handle will share DNS cache
 * by default.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURL_LOCK_DATA_SSL_SESSION</b></td>
 * <td style="vertical-align: top;">
 * Shares SSL session IDs, reducing the time spent on the SSL
 * handshake when reconnecting to the same server. Note that SSL
 * session IDs are reused within the same handle by default.
 * </td>
 * </tr>
 *
 * </tbody>
 *
 * </table>
 * </p>
 * @return bool
 * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * @since 5.5
 */
function curl_share_setopt(#[LanguageLevelTypeAware(['8.0' => 'CurlShareHandle'], default: 'resource')] $share_handle, int $option, mixed $value): bool {}

/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Return string describing the given error code
 * @link https://secure.php.net/manual/en/function.curl-strerror.php
 * @param int $error_code <p>
 * One of the {@link https://curl.haxx.se/libcurl/c/libcurl-errors.html &nbsp;cURL error codes} constants.
 * </p>
 * @return string|null Returns error description or <b>NULL</b> for invalid error code.
 * @since 5.5
 */
#[Pure]
function curl_strerror(int $error_code): ?string {}

/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Decodes the given URL encoded string
 * @link https://secure.php.net/manual/en/function.curl-unescape.php
 * @param CurlHandle|resource $handle <p>A cURL handle returned by
 * {@link https://secure.php.net/manual/en/function.curl-init.php curl_init()}.</p>
 * @param string $string <p>
 * The URL encoded string to be decoded.
 * </p>
 * @return string|false Returns decoded string or FALSE on failure.
 * @since 5.5
 */
#[Pure]
function curl_unescape(#[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle, string $string): string|false {}

/**
 * Perform a cURL session
 * @link https://php.net/manual/en/function.curl-exec.php
 * @param CurlHandle|resource $handle
 * @return string|bool true on success or false on failure. However, if the CURLOPT_RETURNTRANSFER
 * option is set, it will return the result on success, false on failure.
 */
function curl_exec(#[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle): string|bool {}

/**
 * Get information regarding a specific transfer
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @param CurlHandle|resource $handle
 * @param int|null $option [optional] <p>
 * This may be one of the following constants:
 * <ul>
 *   <li>
 *     <strong><code class="code">CURLINFO_EFFECTIVE_URL</code></strong> - Last effective URL
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_HTTP_CODE</code></strong> - The last response code. As of cURL 7.10.8, this is a legacy alias of
 *     <strong><code class="code">CURLINFO_RESPONSE_CODE</code>
 *     </strong>
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_FILETIME</code></strong> - Remote time of the retrieved document, with the
 *     <strong><code class="code">CURLOPT_FILETIME</code>
 *     </strong> enabled; if -1 is returned the time of the document is unknown
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_TOTAL_TIME</code></strong> - Total transaction time in seconds for last transfer
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_NAMELOOKUP_TIME</code></strong> - Time in seconds until name resolving was complete
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_CONNECT_TIME</code></strong> - Time in seconds it took to establish the connection
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_PRETRANSFER_TIME</code></strong> - Time in seconds from start until just before file transfer begins
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_STARTTRANSFER_TIME</code></strong> - Time in seconds until the first byte is about to be transferred
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_REDIRECT_COUNT</code></strong> - Number of redirects, with the
 *     <strong><code class="code">CURLOPT_FOLLOWLOCATION</code>
 *     </strong> option enabled
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_REDIRECT_TIME</code></strong> - Time in seconds of all redirection steps before final transaction was started, with the
 *     <strong><code class="code">CURLOPT_FOLLOWLOCATION</code>
 *     </strong> option enabled
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_REDIRECT_URL</code></strong> - With the
 *     <strong><code class="code">CURLOPT_FOLLOWLOCATION</code>
 *     </strong> option disabled: redirect URL found in the last transaction, that should be requested manually next. With the
 *     <strong><code class="code">CURLOPT_FOLLOWLOCATION</code>
 *     </strong> option enabled: this is empty. The redirect URL in this case is available in
 *     <strong><code class="code">CURLINFO_EFFECTIVE_URL</code>
 *     </strong>
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_PRIMARY_IP</code></strong> - IP address of the most recent connection
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_PRIMARY_PORT</code></strong> - Destination port of the most recent connection
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_LOCAL_IP</code></strong> - Local (source) IP address of the most recent connection
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_LOCAL_PORT</code></strong> - Local (source) port of the most recent connection
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_SIZE_UPLOAD</code></strong> - Total number of bytes uploaded
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_SIZE_DOWNLOAD</code></strong> - Total number of bytes downloaded
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_SPEED_DOWNLOAD</code></strong> - Average download speed
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_SPEED_UPLOAD</code></strong> - Average upload speed
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_HEADER_SIZE</code></strong> - Total size of all headers received
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_HEADER_OUT</code></strong> - The request string sent. For this to work, add the
 *     <strong><code class="code">CURLINFO_HEADER_OUT</code>
 *     </strong> option to the handle by calling curl_setopt()
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_REQUEST_SIZE</code></strong> - Total size of issued requests, currently only for HTTP requests
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_SSL_VERIFYRESULT</code></strong> - Result of SSL certification verification requested by setting
 *     <strong><code class="code">CURLOPT_SSL_VERIFYPEER</code>
 *     </strong>
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_CONTENT_LENGTH_DOWNLOAD</code></strong> - Content length of download, read from
 *     <code class="code">Content-Length:</code> field
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_CONTENT_LENGTH_UPLOAD</code></strong> - Specified size of upload
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_CONTENT_TYPE</code>
 *     </strong> -
 *     <code class="code">Content-Type:</code> of the requested document. NULL indicates server did not send valid
 *     <code class="code">Content-Type:</code> header
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_PRIVATE</code></strong> - Private data associated with this cURL handle, previously set with the
 *     <strong><code class="code">CURLOPT_PRIVATE</code>
 *     </strong> option of curl_setopt()
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_RESPONSE_CODE</code></strong> - The last response code
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_HTTP_CONNECTCODE</code></strong> - The CONNECT response code
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_HTTPAUTH_AVAIL</code></strong> - Bitmask indicating the authentication method(s) available according to the previous response
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_PROXYAUTH_AVAIL</code></strong> - Bitmask indicating the proxy authentication method(s) available according to the previous response
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_OS_ERRNO</code></strong> - Errno from a connect failure. The number is OS and system specific.
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_NUM_CONNECTS</code></strong> - Number of connections curl had to create to achieve the previous transfer
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_SSL_ENGINES</code></strong> - OpenSSL crypto-engines supported
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_COOKIELIST</code></strong> - All known cookies
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_FTP_ENTRY_PATH</code></strong> - Entry path in FTP server
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_APPCONNECT_TIME</code></strong> - Time in seconds it took from the start until the SSL/SSH connect/handshake to the remote host was completed
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_CERTINFO</code></strong> - TLS certificate chain
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_CONDITION_UNMET</code></strong> - Info on unmet time conditional
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_RTSP_CLIENT_CSEQ</code></strong> - Next RTSP client CSeq
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_RTSP_CSEQ_RECV</code></strong> - Recently received CSeq
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_RTSP_SERVER_CSEQ</code></strong> - Next RTSP server CSeq
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_RTSP_SESSION_ID</code></strong> - RTSP session ID
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_CONTENT_LENGTH_DOWNLOAD_T</code></strong> - The content-length of the download. This is the value read from the
 *     <code class="code">Content-Type:</code> field. -1 if the size isn't known
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_CONTENT_LENGTH_UPLOAD_T</code></strong> - The specified size of the upload. -1 if the size isn't known
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_HTTP_VERSION</code></strong> - The version used in the last HTTP connection. The return value will be one of the defined
 *     <strong><code class="code">CURL_HTTP_VERSION_*</code>
 *     </strong> constants or 0 if the version can't be determined
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_PROTOCOL</code></strong> - The protocol used in the last HTTP connection. The returned value will be exactly one of the
 *     <strong><code class="code">CURLPROTO_*</code>
 *     </strong> values
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_PROXY_SSL_VERIFYRESULT</code></strong> - The result of the certificate verification that was requested (using the
 *     <strong><code class="code">CURLOPT_PROXY_SSL_VERIFYPEER</code>
 *     </strong> option). Only used for HTTPS proxies
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_SCHEME</code></strong> - The URL scheme used for the most recent connection
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_SIZE_DOWNLOAD_T</code></strong> - Total number of bytes that were downloaded. The number is only for the latest transfer and will be reset again for each new transfer
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_SIZE_UPLOAD_T</code></strong> - Total number of bytes that were uploaded
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_SPEED_DOWNLOAD_T</code></strong> - The average download speed in bytes/second that curl measured for the complete download
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_SPEED_UPLOAD_T</code></strong> - The average upload speed in bytes/second that curl measured for the complete upload
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_APPCONNECT_TIME_T</code></strong> - Time, in microseconds, it took from the start until the SSL/SSH connect/handshake to the remote host was completed
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_CONNECT_TIME_T</code></strong> - Total time taken, in microseconds, from the start until the connection to the remote host (or proxy) was completed
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_FILETIME_T</code></strong> - Remote time of the retrieved document (as Unix timestamp), an alternative to
 *     <strong><code class="code">CURLINFO_FILETIME</code>
 *     </strong> to allow systems with 32 bit long variables to extract dates outside of the 32bit timestamp range
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_NAMELOOKUP_TIME_T</code></strong> - Time in microseconds from the start until the name resolving was completed
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_PRETRANSFER_TIME_T</code></strong> - Time taken from the start until the file transfer is just about to begin, in microseconds
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_REDIRECT_TIME_T</code></strong> - Total time, in microseconds, it took for all redirection steps include name lookup, connect, pretransfer and transfer before final transaction was started
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_STARTTRANSFER_TIME_T</code></strong> - Time, in microseconds, it took from the start until the first byte is received
 *   </li>
 *   <li>
 *     <strong><code class="code">CURLINFO_TOTAL_TIME_T</code></strong> - Total time in microseconds for the previous transfer, including name resolving, TCP connect etc.
 * </ul>
 *
 * @return mixed If <em><code class="parameter">$option</code></em> is given, returns its value as a string.
 * Otherwise, returns an associative array with the following elements
 * (which correspond to <em><code class="parameter">$option</code></em>), or false on failure:
 * <ul>
 *   <li>url</li>
 *   <li>content_type</li>
 *   <li>http_code</li>
 *   <li>header_size</li>
 *   <li>request_size</li>
 *   <li>filetime</li>
 *   <li>ssl_verify_result</li>
 *   <li>redirect_count</li>
 *   <li>total_time</li>
 *   <li>namelookup_time</li>
 *   <li>connect_time</li>
 *   <li>pretransfer_time</li>
 *   <li>size_upload</li>
 *   <li>size_download</li>
 *   <li>speed_download</li>
 *   <li>speed_upload</li>
 *   <li>download_content_length</li>
 *   <li>upload_content_length</li>
 *   <li>starttransfer_time</li>
 *   <li>redirect_time</li>
 *   <li>certinfo</li>
 *   <li>primary_ip</li>
 *   <li>primary_port</li>
 *   <li>local_ip</li>
 *   <li>local_port</li>
 *   <li>redirect_url</li>
 *   <li>request_header (This is only set if the
 *     <strong><code>CURLINFO_HEADER_OUT</code>
 *     </strong> is set by a previous call to curl_setopt()
 *   </li>
 * </ul>
 */
#[Pure(true)]
function curl_getinfo(#[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle, ?int $option): mixed {}

/**
 * Return a string containing the last error for the current session
 * @link https://php.net/manual/en/function.curl-error.php
 * @param CurlHandle|resource $handle
 * @return string the error message or '' (the empty string) if no
 * error occurred.
 */
#[Pure(true)]
function curl_error(#[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle): string {}

/**
 * Return the last error number
 * @link https://php.net/manual/en/function.curl-errno.php
 * @param CurlHandle|resource $handle
 * @return int the error number or 0 (zero) if no error
 * occurred.
 */
#[Pure(true)]
function curl_errno(#[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle): int {}

/**
 * URL encodes the given string
 * @link https://secure.php.net/manual/en/function.curl-escape.php
 * @param CurlHandle|resource $handle <p>
 * A cURL handle returned by
 * {@link https://secure.php.net/manual/en/function.curl-init.php curl_init()}.</p>
 * @param string $string <p>
 * The string to be encoded.</p>
 * @return string|false Returns escaped string or FALSE on failure.
 * @since 5.5
 */
#[Pure]
function curl_escape(#[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle, string $string): string|false {}

/**
 * (PHP 5 >= 5.5.0) <br/>
 * Create a CURLFile object
 * @link https://secure.php.net/manual/en/curlfile.construct.php
 * @param string $filename <p> Path to the file which will be uploaded.</p>
 * @param string|null $mime_type <p>Mimetype of the file.</p>
 * @param string|null $posted_filename <p>Name of the file.</p>
 * @return CURLFile
 * Returns a {@link https://secure.php.net/manual/en/class.curlfile.php CURLFile} object.
 * @since 5.5
 */
#[Pure]
function curl_file_create(string $filename, ?string $mime_type = null, ?string $posted_filename = null): CURLFile {}

/**
 * Close a cURL session
 * @link https://php.net/manual/en/function.curl-close.php
 * @param CurlHandle|resource $handle
 * @return void
 */
function curl_close(#[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle): void {}

/**
 * Returns a new cURL multi handle
 * @link https://php.net/manual/en/function.curl-multi-init.php
 * @return resource|CurlMultiHandle|false a cURL multi handle resource on success, false on failure.
 */
#[LanguageLevelTypeAware(['8.0' => 'CurlMultiHandle'], default: 'resource')]
function curl_multi_init(): CurlMultiHandle {}

/**
 * Add a normal cURL handle to a cURL multi handle
 * @link https://php.net/manual/en/function.curl-multi-add-handle.php
 * @param CurlMultiHandle|resource $multi_handle
 * @param CurlHandle|resource $handle
 * @return int 0 on success, or one of the CURLM_XXX errors
 * code.
 */
function curl_multi_add_handle(#[LanguageLevelTypeAware(['8.0' => 'CurlMultiHandle'], default: 'resource')] $multi_handle, #[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle): int {}

/**
 * Remove a multi handle from a set of cURL handles
 * @link https://php.net/manual/en/function.curl-multi-remove-handle.php
 * @param CurlMultiHandle|resource $multi_handle
 * @param CurlHandle|resource $handle
 * @return int|false On success, returns one of the CURLM_XXX error codes, false on failure.
 */
#[LanguageLevelTypeAware(['8.0' => 'int'], default: 'int|false')]
function curl_multi_remove_handle(#[LanguageLevelTypeAware(['8.0' => 'CurlMultiHandle'], default: 'resource')] $multi_handle, #[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle) {}

/**
 * Wait for activity on any curl_multi connection
 * @link https://php.net/manual/en/function.curl-multi-select.php
 * @param CurlMultiHandle|resource $multi_handle
 * @param float $timeout [optional] <p>
 * Time, in seconds, to wait for a response.
 * </p>
 * @return int On success, returns the number of descriptors contained in,
 * the descriptor sets. On failure, this function will return -1 on a select failure or timeout (from the underlying select system call).
 */
function curl_multi_select(#[LanguageLevelTypeAware(['8.0' => 'CurlMultiHandle'], default: 'resource')] $multi_handle, float $timeout = 1.0): int {}

/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Set an option for the cURL multi handle
 * @link https://secure.php.net/manual/en/function.curl-multi-setopt.php
 * @param CurlMultiHandle|resource $multi_handle
 * @param int $option <p>
 * One of the <b>CURLMOPT_*</b> constants.
 * </p>
 * @param mixed $value <p>
 * The value to be set on <em>option</em>.
 * </p>
 * <p>
 * <em>value</em> should be an {@link https://php.net/manual/en/language.types.integer.php int} for the
 * following values of the <em>option</em> parameter:
 * </p><table>
 *
 * <thead>
 * <tr>
 * <th>Option</th>
 * <th>Set <em><code class="parameter">value</code></em> to</th>
 * </tr>
 * </thead>
 *
 * <tbody>
 * <tr>
 * <td><b>CURLMOPT_PIPELINING</b></td>
 * <td style="vertical-align: top;">
 * Pass 1 to enable or 0 to disable. Enabling pipelining on a multi
 * handle will make it attempt to perform HTTP Pipelining as far as
 * possible for transfers using this handle. This means that if you add
 * a second request that can use an already existing connection, the
 * second request will be "piped" on the same connection rather than
 * being executed in parallel.
 * </td>
 * </tr>
 *
 * <tr>
 * <td style="vertical-align: top;"><b>CURLMOPT_MAXCONNECTS</b></td>
 * <td style="vertical-align: top;">
 * Pass a number that will be used as the maximum amount of
 * simultaneously open connections that libcurl may cache. Default is
 * 10. When the cache is full, curl closes the oldest one in the cache
 * to prevent the number of open connections from increasing.
 * </td>
 * </tr>
 * </tbody>
 * </table>
 * @return bool Returns TRUE on success or FALSE on failure.
 * @since 5.5
 */
function curl_multi_setopt(#[LanguageLevelTypeAware(['8.0' => 'CurlMultiHandle'], default: 'resource')] $multi_handle, int $option, mixed $value): bool {}

/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Return string describing error code
 * @link https://secure.php.net/manual/en/function.curl-multi-strerror.php
 * @param int $error_code <p>
 * One of the {@link https://curl.haxx.se/libcurl/c/libcurl-errors.html CURLM error codes} constants.
 * </p>
 * @return string|null Returns error string for valid error code, NULL otherwise.
 * @since 5.5
 */
function curl_multi_strerror(int $error_code): ?string {}

/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Pause and unpause a connection
 * @link https://secure.php.net/manual/en/function.curl-pause.php
 * @param CurlHandle|resource $handle
 * <p>A cURL handle returned by {@link https://secure.php.net/manual/en/function.curl-init.php curl_init()}.</p>
 * @param int $flags <p>One of <b>CURLPAUSE_*</b> constants.</p>
 * @return int Returns an error code (<b>CURLE_OK</b> for no error).
 * @since 5.5
 */
function curl_pause(#[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle, int $flags): int {}

/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Reset all options of a libcurl session handle
 * @link https://secure.php.net/manual/en/function.curl-reset.php
 * @param CurlHandle|resource $handle <p>A cURL handle returned by
 * {@link https://secure.php.net/manual/en/function.curl-init.php curl_init()}.</p>
 * @return void
 * @since 5.5
 */
function curl_reset(#[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle): void {}

/**
 * Run the sub-connections of the current cURL handle
 * @link https://php.net/manual/en/function.curl-multi-exec.php
 * @param CurlMultiHandle|resource $multi_handle
 * @param int &$still_running <p>
 * A reference to a flag to tell whether the operations are still running.
 * </p>
 * @return int A cURL code defined in the cURL Predefined Constants.
 * <p>
 * This only returns errors regarding the whole multi stack. There might still have
 * occurred problems on individual transfers even when this function returns
 * CURLM_OK.
 * </p>
 */
function curl_multi_exec(
    #[LanguageLevelTypeAware(['8.0' => 'CurlMultiHandle'], default: 'resource')] $multi_handle,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] &$still_running = 0,
    #[PhpStormStubsElementAvailable(from: '8.0')] &$still_running
): int {}

/**
 * Return the content of a cURL handle if <constant>CURLOPT_RETURNTRANSFER</constant> is set
 * @link https://php.net/manual/en/function.curl-multi-getcontent.php
 * @param CurlHandle|resource $handle
 * @return null|string Return the content of a cURL handle if CURLOPT_RETURNTRANSFER is set.
 */
#[Pure]
function curl_multi_getcontent(#[LanguageLevelTypeAware(['8.0' => 'CurlHandle'], default: 'resource')] $handle): ?string {}

/**
 * Get information about the current transfers
 * @link https://php.net/manual/en/function.curl-multi-info-read.php
 * @param CurlMultiHandle|resource $multi_handle
 * @param int &$queued_messages [optional] <p>
 * Number of messages that are still in the queue
 * </p>
 * @return array|false On success, returns an associative array for the message, false on failure.
 */
#[Pure]
#[ArrayShape(["msg" => "int", "result" => "int", "handle" => "resource"])]
function curl_multi_info_read(#[LanguageLevelTypeAware(['8.0' => 'CurlMultiHandle'], default: 'resource')] $multi_handle, &$queued_messages): array|false {}

/**
 * Close a set of cURL handles
 * @link https://php.net/manual/en/function.curl-multi-close.php
 * @param CurlMultiHandle|resource $multi_handle
 * @return void
 */
function curl_multi_close(#[LanguageLevelTypeAware(['8.0' => 'CurlMultiHandle'], default: 'resource')] $multi_handle): void {}

/**
 * Return the last multi curl error number
 * @param CurlMultiHandle|resource $multi_handle
 * @return int
 * @since 7.1
 */
#[Pure(true)]
function curl_multi_errno(#[LanguageLevelTypeAware(['8.0' => 'CurlMultiHandle'], default: 'resource')] $multi_handle): int {}

/**
 * Return the last share curl error number
 * @param CurlMultiHandle|resource $share_handle
 * @return int
 * @since 7.1
 */
#[Pure(true)]
function curl_share_errno(#[LanguageLevelTypeAware(['8.0' => 'CurlShareHandle'], default: 'resource')] $share_handle): int {}

/**
 * Return string describing the given error code
 * @param int $error_code
 * @return string|null
 * @since 7.1
 */
#[Pure]
function curl_share_strerror(int $error_code): ?string {}

/**
 * @since 8.2
 */
function curl_upkeep(CurlHandle $handle): bool {}
/**
 * @since 8.0
 */
final class CurlHandle
{
    /**
     * Cannot directly construct CurlHandle, use curl_init() instead
     * @see curl_init()
     */
    private function __construct() {}
}

/**
 * @since 8.0
 */
final class CurlMultiHandle
{
    /**
     * Cannot directly construct CurlMultiHandle, use curl_multi_init() instead
     * @see curl_multi_init()
     */
    private function __construct() {}
}

/**
 * @since 8.0
 */
final class CurlShareHandle
{
    /**
     * Cannot directly construct CurlShareHandle, use curl_share_init() instead
     * @see  curl_share_init()
     */
    private function __construct() {}
}
