<?php

/**
 * A bitmask consisting of one or more of
 * <b>CURLSSH_AUTH_PUBLICKEY</b>,
 * <b>CURLSSH_AUTH_PASSWORD</b>,
 * <b>CURLSSH_AUTH_HOST</b>,
 * <b>CURLSSH_AUTH_KEYBOARD</b>. Set to
 * <b>CURLSSH_AUTH_ANY</b> to let libcurl pick one.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSH_AUTH_TYPES', 151);

/**
 * <b>TRUE</b> tells the library to perform all the required proxy authentication
 * and connection setup, but no data transfer. This option is implemented for
 * HTTP, SMTP and POP3.
 * @since 5.5
 * @link https://php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_CONNECT_ONLY', 141);

/**
 * With the <b>CURLOPT_FOLLOWLOCATION</b> option disabled:
 *   redirect URL found in the last transaction, that should be requested manually next.
 * With the <b>CURLOPT_FOLLOWLOCATION</b> option enabled:
 *   this is empty. The redirect URL in this case is available in <b>CURLINFO_EFFECTIVE_URL</b>
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 5.3.7
 */
define('CURLINFO_REDIRECT_URL', 1048607);

/**
 * IP address of the most recent connection
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 5.4.7
 */
define('CURLINFO_PRIMARY_IP', 1048608);
/**
 * Destination port of the most recent connection
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 5.4.7
 */
define('CURLINFO_PRIMARY_PORT', 2097192);
/**
 * Local (source) IP address of the most recent connection
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 5.4.7
 */
define('CURLINFO_LOCAL_IP', 1048617);
/**
 * Local (source) port of the most recent connection
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 5.4.7
 */
define('CURLINFO_LOCAL_PORT', 2097194);
/**
 * A result of {@see curl_share_init()}. Makes the cURL handle to use the data from the shared handle.
 * @link https://php.net/manual/en/function.curl-setopt.php
 * @since 5.5
 */
define('CURLOPT_SHARE', 10100);
/**
 * Allows an application to select what kind of IP addresses to use when resolving host names.
 * This is only interesting when using host names that resolve addresses using more than one version of IP,
 * possible values are <b>CURL_IPRESOLVE_WHATEVER</b>, <b>CURL_IPRESOLVE_V4</b>, <b>CURL_IPRESOLVE_V6</b>, by default <b>CURL_IPRESOLVE_WHATEVER</b>.
 * @link https://php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_IPRESOLVE', 113);
/**
 * Value for the <b>CURLOPT_IPRESOLVE</b> option.
 * Default, resolves addresses to all IP versions that your system allows.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_IPRESOLVE.html
 */
define('CURL_IPRESOLVE_WHATEVER', 0);
/**
 * Value for the <b>CURLOPT_IPRESOLVE</b> option.
 * Resolve to IPv4 addresses.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_IPRESOLVE.html
 */
define('CURL_IPRESOLVE_V4', 1);
/**
 * Value for the <b>CURLOPT_IPRESOLVE</b> option.
 * Resolve to IPv6 addresses.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_IPRESOLVE.html
 */
define('CURL_IPRESOLVE_V6', 2);
/**
 * <b>TRUE</b> to use a global DNS cache. This option is not thread-safe.
 * It is conditionally enabled by default if PHP is built for non-threaded use (CLI, FCGI, Apache2-Prefork, etc.).
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_DNS_USE_GLOBAL_CACHE', 91);

/**
 * The number of seconds to keep DNS entries in memory.
 * This option is set to 120 (2 minutes) by default.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_DNS_CACHE_TIMEOUT', 92);
/**
 * An alternative port number to connect to.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_PORT', 3);
/**
 * The file that the transfer should be written to. The default is STDOUT (the browser window).
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_FILE', 10001);
/**
 * Custom pointer passed to the read callback.
 * If you use the CURLOPT_READFUNCTION option, this is the pointer you'll get as input in the 4th argument to the callback.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_READDATA.html
 */
define('CURLOPT_READDATA', 10009);
/**
 * The file that the transfer should be read from when uploading.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_INFILE', 10009);
/**
 * The expected size, in bytes, of the file when uploading a file to a remote site.
 * Note that using this option will not stop libcurl from sending more data, as exactly what is sent depends on <b>CURLOPT_READFUNCTION</b>.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_INFILESIZE', 14);
/**
 * The URL to fetch. This can also be set when initializing a session with {@see curl_init()}.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_URL', 10002);
/**
 * The HTTP proxy to tunnel requests through.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_PROXY', 10004);
/**
 * <b>TRUE</b> to output verbose information.
 * Writes output to STDERR, or the file specified using <b>CURLOPT_STDERR</b>.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_VERBOSE', 41);
/**
 * <b>TRUE</b> to include the header in the output.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_HEADER', 42);
/**
 * An array of HTTP header fields to set, in the format array('<em>Content-type: text/plain</em>', '<em>Content-length: 100</em>')
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_HTTPHEADER', 10023);
/**
 * <b>TRUE</b> to disable the progress meter for cURL transfers.
 * (PHP automatically sets this option to TRUE, this should only be changed for debugging purposes.)
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_NOPROGRESS', 43);

/**
 * A callback accepting five parameters.
 * The first is the cURL resource,
 * the second is the total number of bytes expected to be downloaded in this transfer,
 * the third is the number of bytes downloaded so far,
 * the fourth is the total number of bytes expected to be uploaded in this transfer,
 * and the fifth is the number of bytes uploaded so far.
 * (The callback is only called when the <b>CURLOPT_NOPROGRESS</b> option is set to <b>FALSE</b>.)
 * Return a non-zero value to abort the transfer. In which case, the transfer will set a <b>CURLE_ABORTED_BY_CALLBACK</b> error.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.3
 */
define('CURLOPT_PROGRESSFUNCTION', 20056);
/**
 * <b>TRUE</b> to exclude the body from the output. Request method is then set to HEAD. Changing this to <b>FALSE</b> does not change it to GET.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_NOBODY', 44);
/**
 * <b>TRUE</b> to fail verbosely if the HTTP code returned is greater than or equal to 400.
 * The default behavior is to return the page normally, ignoring the code.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_FAILONERROR', 45);
/**
 * <b>TRUE</b> to prepare for an upload.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_UPLOAD', 46);
/**
 * <b>TRUE</b> to do a regular HTTP POST.
 * This POST is the normal application/x-www-form-urlencoded kind, most commonly used by HTML forms.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_POST', 47);
/**
 * <b>TRUE</b> to only list the names of an FTP directory.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_FTPLISTONLY', 48);
/**
 * <b>TRUE</b> to append to the remote file instead of overwriting it.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_FTPAPPEND', 50);
/**
 * <b>TRUE</b> to scan the ~/.netrc file to find a username and password for the remote site that a connection is being established with.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_NETRC', 51);
/**
 * A bitmask of 1 (301 Moved Permanently), 2 (302 Found) and 4 (303 See Other) if the HTTP POST method should be maintained
 * when <b>CURLOPT_FOLLOWLOCATION</b> is set and a specific type of redirect occurs.
 * @link https://secure.php.net/manual/en/function.curl-setopt.php
 * @since 5.3.2
 */
define('CURLOPT_POSTREDIR', 161);
/**
 * <b>TRUE</b> to output SSL certification information to STDERR on secure transfers.
 * Requires <b>CURLOPT_VERBOSE</b> to be on to have an effect.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.3.2
 */
define('CURLOPT_CERTINFO', 172);
/**
 * An alias of <b>CURLOPT_TRANSFERTEXT</b>. Use that instead.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_FTPASCII', -1);
/**
 * <b>TRUE</b> to be completely silent with regards to the cURL functions.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @deprecated use <b>CURLOPT_RETURNTRANSFER</b> instead since cURL 7.15.5
 */
define('CURLOPT_MUTE', -1);
/**
 * Bitmask of CURLPROTO_* values. If used, this bitmask limits what protocols libcurl may use in the transfer.
 * This allows you to have a libcurl built to support a wide range of protocols but still limit specific transfers
 * to only be allowed to use a subset of them.
 * By default libcurl will accept all protocols it supports. See also <b>CURLOPT_REDIR_PROTOCOLS</b>.
 * Valid protocol options are:
 * <b>CURLPROTO_HTTP</b>, <b>CURLPROTO_HTTPS</b>, <b>CURLPROTO_FTP</b>, <b>CURLPROTO_FTPS</b>, <b>CURLPROTO_SCP</b>, <b>CURLPROTO_SFTP</b>,
 * <b>CURLPROTO_TELNET</b>, <b>CURLPROTO_LDAP</b>, <b>CURLPROTO_LDAPS</b>, <b>CURLPROTO_DICT</b>, <b>CURLPROTO_FILE</b>, <b>CURLPROTO_TFTP</b>,
 * <b>CURLPROTO_ALL</b>
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.2.10
 */
define('CURLOPT_PROTOCOLS', 181);
/**
 * Bitmask of CURLPROTO_* values. If used, this bitmask limits what protocols libcurl may use in a transfer
 * that it follows to in a redirect when <b>CURLOPT_FOLLOWLOCATION</b> is enabled.
 * This allows you to limit specific transfers to only be allowed to use a subset of protocols in redirections.
 * By default libcurl will allow all protocols except for FILE and SCP.
 * This is a difference compared to pre-7.19.4 versions which unconditionally would follow to all protocols supported.
 * See also <b>CURLOPT_PROTOCOLS</b> for protocol constant values.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.2.10
 */
define('CURLOPT_REDIR_PROTOCOLS', 182);
/**
 * If a download exceeds this speed (counted in bytes per second) on cumulative average during the transfer,
 * the transfer will pause to keep the average rate less than or equal to the parameter value.
 * Defaults to unlimited speed.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.4
 */
define('CURLOPT_MAX_RECV_SPEED_LARGE', 30146);
/**
 * If an upload exceeds this speed (counted in bytes per second) on cumulative average during the transfer,
 * the transfer will pause to keep the average rate less than or equal to the parameter value.
 * Defaults to unlimited speed.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.4
 */
define('CURLOPT_MAX_SEND_SPEED_LARGE', 30145);
/**
 * A callback accepting three parameters.
 * The first is the cURL resource, the second is a string containing a password prompt, and the third is the maximum password length.
 * Return the string containing the password.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_PASSWDFUNCTION', -1);

/**
 * <b>TRUE</b> to follow any "<em>Location: </em>" header that the server sends as part of the HTTP header
 * (note this is recursive, PHP will follow as many "Location: " headers that it is sent, unless <b>CURLOPT_MAXREDIRS</b> is set).
 * This constant is not available when open_basedir
 * or safe_mode are enabled.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_FOLLOWLOCATION', 52);
/**
 * <b>TRUE</b> to HTTP PUT a file. The file to PUT must be set with <b>CURLOPT_INFILE</b> and <b>CURLOPT_INFILESIZE</b>.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_PUT', 54);
/**
 * A username and password formatted as "<em>[username]:[password]</em>" to use for the connection.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_USERPWD', 10005);
/**
 * A username and password formatted as "<em>[username]:[password]</em>" to use for the connection to the proxy.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_PROXYUSERPWD', 10006);
/**
 * Range(s) of data to retrieve in the format "<em>X-Y</em>" where X or Y are optional.
 * HTTP transfers also support several intervals, separated with commas in the format "<em>X-Y,N-M</em>".
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_RANGE', 10007);
/**
 * The maximum number of seconds to allow cURL functions to execute.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_TIMEOUT', 13);
/**
 * The maximum number of milliseconds to allow cURL functions to execute.
 * If libcurl is built to use the standard system name resolver,
 * that portion of the connect will still use full-second resolution for timeouts with a minimum timeout allowed of one second.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.2
 */
define('CURLOPT_TIMEOUT_MS', 155);
/**
 * The full data to post in a HTTP "POST" operation.
 * To post a file, prepend a filename with @ and use the full path.
 * The filetype can be explicitly specified by following the filename with the type in the format '<em>;type=mimetype</em>'.
 * This parameter can either be passed
 * as a urlencoded string like '<em>para1=val1&para2=val2&...</em>'
 * or as an array with the field name as key and field data as value.
 * If value is an array, the <em>Content-Type</em> header will be set to <em>multipart/form-data</em>.
 * As of PHP 5.2.0, value must be an array if files are passed to this option with the @ prefix.
 * As of PHP 5.5.0, the @ prefix is deprecated and files can be sent using <b>CURLFile</b>.
 * The @ prefix can be disabled for safe passing of values beginning with @ by setting the <b>CURLOPT_SAFE_UPLOAD</b> option to TRUE.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_POSTFIELDS', 10015);
/**
 * The contents of the "<em>Referer: </em>" header to be used in a HTTP request.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_REFERER', 10016);
/**
 * A string containing 32 hexadecimal digits.
 * The string should be the MD5 checksum of the remote host's public key, and libcurl will reject the connection to the host unless the md5sums match.
 * This option is only for <b>SCP</b> and <b>SFTP</b> transfers.
 * @link https://php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSH_HOST_PUBLIC_KEY_MD5', 10162);
/**
 * The file name for your public key. If not used, libcurl defaults to $HOME/.ssh/id_dsa.pub
 * if the HOME environment variable is set, and just "id_dsa.pub" in the current directory if HOME is not set.
 * @link https://php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSH_PUBLIC_KEYFILE', 10152);
/**
 * The file name for your private key. If not used, libcurl defaults to $HOME/.ssh/id_dsa
 * if the HOME environment variable is set, and just "id_dsa" in the current directory if HOME is not set.
 * If the file is password-protected, set the password with <b>CURLOPT_KEYPASSWD</b>.
 * @link https://php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSH_PRIVATE_KEYFILE', 10153);
/**
 * The contents of the "<em>User-Agent: </em>" header to be used in a HTTP request.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_USERAGENT', 10018);
/**
 * The value which will be used to get the IP address to use for the FTP "PORT" instruction.
 * The "PORT" instruction tells the remote server to connect to our specified IP address.
 * The string may be a plain IP address, a hostname, a network interface name (under Unix),
 * or just a plain '-' to use the systems default IP address.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_FTPPORT', 10017);
/**
 * <b>TRUE</b> to first try an EPSV command for FTP transfers before reverting back to PASV. Set to <b>FALSE</b> to disable EPSV.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_FTP_USE_EPSV', 85);
/**
 * The transfer speed, in bytes per second, that the transfer should be below during the count of <b>CURLOPT_LOW_SPEED_TIME</b> seconds
 * before PHP considers the transfer too slow and aborts.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_LOW_SPEED_LIMIT', 19);
/**
 * The number of seconds the transfer speed should be below <b>CURLOPT_LOW_SPEED_LIMIT</b>
 * before PHP considers the transfer too slow and aborts.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_LOW_SPEED_TIME', 20);
/**
 * The offset, in bytes, to resume a transfer from.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_RESUME_FROM', 21);
/**
 * The contents of the "<em>Cookie: </em>" header to be used in the HTTP request.
 * Note that multiple cookies are separated with a semicolon followed by a space (e.g., "<em>fruit=apple; colour=red</em>")
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_COOKIE', 10022);

/**
 * <b>TRUE</b> to mark this as a new cookie "session".
 * It will force libcurl to ignore all cookies it is about to load that are "session cookies" from the previous session.
 * By default, libcurl always stores and loads all cookies, independent if they are session cookies or not.
 * Session cookies are cookies without expiry date and they are meant to be alive and existing for this "session" only.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_COOKIESESSION', 96);

/**
 * <b>TRUE</b> to automatically set the Referer: field in requests where it follows a Location: redirect.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_AUTOREFERER', 58);
/**
 * The name of a file containing a PEM formatted certificate.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSLCERT', 10025);
/**
 * The password required to use the <b>CURLOPT_SSLCERT</b> certificate.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSLCERTPASSWD', 10026);
/**
 * The file that the header part of the transfer is written to.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_WRITEHEADER', 10029);
/**
 * 1 to check the existence of a common name in the SSL peer certificate. (Deprecated)
 * 2 to check the existence of a common name and also verify that it matches the hostname provided.
 * 0 to not check the names. In production environments the value of this option should be kept at 2 (default value).
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSL_VERIFYHOST', 81);
/**
 * The name of the file containing the cookie data.
 * The cookie file can be in Netscape format, or just plain HTTP-style headers dumped into a file.
 * If the name is an empty string, no cookies are loaded, but cookie handling is still enabled.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_COOKIEFILE', 10031);
/**
 * One of <b>CURL_SSLVERSION_DEFAULT</b> (0), <b>CURL_SSLVERSION_TLSv1</b> (1), <b>CURL_SSLVERSION_SSLv2</b> (2), <b>CURL_SSLVERSION_SSLv3</b> (3),
 * <b>CURL_SSLVERSION_TLSv1_0</b> (4), <b>CURL_SSLVERSION_TLSv1_1</b> (5) or <b>CURL_SSLVERSION_TLSv1_2</b> (6).
 * The maximum TLS version can be set by using one of the <b>CURL_SSLVERSION_MAX_*</b> constants.
 * It is also possible to OR one of the <b>CURL_SSLVERSION_*</b> constants with one of the <b>CURL_SSLVERSION_MAX_*</b> constants.
 * <b>CURL_SSLVERSION_MAX_DEFAULT</b> (the maximum version supported by the library), <b>CURL_SSLVERSION_MAX_TLSv1_0</b>, <b>CURL_SSLVERSION_MAX_TLSv1_1</b>,
 * <b>CURL_SSLVERSION_MAX_TLSv1_2</b>, or <b>CURL_SSLVERSION_MAX_TLSv1_3</b>.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSLVERSION', 32);
/**
 * Value for the <b>CURLOPT_SSLVERSION</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define('CURL_SSLVERSION_DEFAULT', 0);
/**
 * Value for the <b>CURLOPT_SSLVERSION</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define('CURL_SSLVERSION_TLSv1', 1);
/**
 * Value for the <b>CURLOPT_SSLVERSION</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define('CURL_SSLVERSION_SSLv2', 2);
/**
 * Value for the <b>CURLOPT_SSLVERSION</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define('CURL_SSLVERSION_SSLv3', 3);

/**
 * Value for the <b>CURLOPT_SSLVERSION</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 5.6.3
 * @since 5.5.19
 */
define('CURL_SSLVERSION_TLSv1_0', 4);

/**
 * Value for the <b>CURLOPT_SSLVERSION</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 5.6.3
 * @since 5.5.19
 */
define('CURL_SSLVERSION_TLSv1_1', 5);

/**
 * Value for the <b>CURLOPT_SSLVERSION</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 5.6.3
 * @since 5.5.19
 */
define('CURL_SSLVERSION_TLSv1_2', 6);
/**
 * How <b>CURLOPT_TIMEVALUE</b> is treated.
 * Use <b>CURL_TIMECOND_IFMODSINCE</b> to return the page only if it has been modified since the time specified in <b>CURLOPT_TIMEVALUE</b>.
 * If it hasn't been modified, a "304 Not Modified" header will be returned assuming <b>CURLOPT_HEADER</b> is <b>TRUE</b>.
 * Use <b>CURL_TIMECOND_IFUNMODSINCE</b> for the reverse effect.
 * <b>CURL_TIMECOND_IFMODSINCE</b> is the default.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_TIMECONDITION', 33);
/**
 * The time in seconds since January 1st, 1970.
 * The time will be used by <b>CURLOPT_TIMECONDITION</b>. By default, <b>CURL_TIMECOND_IFMODSINCE</b> is used.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_TIMEVALUE', 34);
/**
 * A custom request method to use instead of "GET" or "HEAD" when doing a HTTP request.
 * This is useful for doing "DELETE" or other, more obscure HTTP requests.
 * Valid values are things like "GET", "POST", "CONNECT" and so on; i.e. Do not enter a whole HTTP request line here.
 * For instance, entering "GET /index.html HTTP/1.0\r\n\r\n" would be incorrect.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_CUSTOMREQUEST', 10036);
/**
 * An alternative location to output errors to instead of STDERR.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_STDERR', 10037);
/**
 * <b>TRUE</b> to use ASCII mode for FTP transfers.
 * For LDAP, it retrieves data in plain text instead of HTML.
 * On Windows systems, it will not set STDOUT to binary mode.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_TRANSFERTEXT', 53);
/**
 * <b>TRUE</b> to return the transfer as a string of the return value of {@see curl_exec()} instead of outputting it directly.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_RETURNTRANSFER', 19913);
/**
 * An array of FTP commands to execute on the server prior to the FTP request.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_QUOTE', 10028);
/**
 * An array of FTP commands to execute on the server after the FTP request has been performed.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_POSTQUOTE', 10039);
/**
 * The name of the outgoing network interface to use. This can be an interface name, an IP address or a host name.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_INTERFACE', 10062);
/**
 * The KRB4 (Kerberos 4) security level.
 * Any of the following values (in order from least to most powerful) are valid: "clear", "safe", "confidential", "private".
 * If the string does not match one of these, "private" is used.
 * Setting this option to <b>NULL</b> will disable KRB4 security. Currently KRB4 security only works with FTP transactions.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_KRB4LEVEL', 10063);
/**
 * <b>TRUE</b> to tunnel through a given HTTP proxy.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_HTTPPROXYTUNNEL', 61);
/**
 * <b>TRUE</b> to attempt to retrieve the modification date of the remote document.
 * This value can be retrieved using the <b>CURLINFO_FILETIME</b> option with {@see curl_getinfo()}.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_FILETIME', 69);
/**
 * A callback accepting two parameters. The first is the cURL resource, and the second is a string with the data to be written.
 * The data must be saved by this callback. It must return the exact number of bytes written or the transfer will be aborted with an error.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_WRITEFUNCTION', 20011);
/**
 * A callback accepting three parameters.
 * The first is the cURL resource,
 * the second is a stream resource provided to cURL through the option <b>CURLOPT_INFILE</b>,
 * and the third is the maximum amount of data to be read.
 * The callback must return a string with a length equal or smaller than the amount of data requested, typically by reading it from the passed stream resource.
 * It should return an empty string to signal EOF.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_READFUNCTION', 20012);
/**
 * A callback accepting two parameters. The first is the cURL resource, the second is a string with the header data to be written.
 * The header data must be written by this callback. Return the number of bytes written.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_HEADERFUNCTION', 20079);
/**
 * The maximum amount of HTTP redirections to follow. Use this option alongside <b>CURLOPT_FOLLOWLOCATION</b>.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_MAXREDIRS', 68);
/**
 * The maximum amount of persistent connections that are allowed.
 * When the limit is reached, <b>CURLOPT_CLOSEPOLICY</b> is used to determine which connection to close.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_MAXCONNECTS', 71);
/**
 * This option is deprecated, as it was never implemented in cURL and never had any effect.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @removed 5.6
 */
define('CURLOPT_CLOSEPOLICY', 72);
/**
 * <b>TRUE</b> to force the use of a new connection instead of a cached one.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_FRESH_CONNECT', 74);
/**
 * <b>TRUE</b> to force the connection to explicitly close when it has finished processing, and not be pooled for reuse.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_FORBID_REUSE', 75);
/**
 * A filename to be used to seed the random number generator for SSL.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_RANDOM_FILE', 10076);
/**
 * Like <b>CURLOPT_RANDOM_FILE</b>, except a filename to an Entropy Gathering Daemon socket.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_EGDSOCKET', 10077);

/**
 * The number of seconds to wait while trying to connect. Use 0 to wait indefinitely.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_CONNECTTIMEOUT', 78);

/**
 * The number of milliseconds to wait while trying to connect. Use 0 to wait indefinitely.
 * If libcurl is built to use the standard system name resolver, that portion of the connect
 * will still use full-second resolution for timeouts with a minimum timeout allowed of one second.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.2.3
 */
define('CURLOPT_CONNECTTIMEOUT_MS', 156);
/**
 * <b>FALSE</b> to stop cURL from verifying the peer's certificate.
 * Alternate certificates to verify against can be specified with the <b>CURLOPT_CAINFO</b> option or
 * a certificate directory can be specified with the <b>CURLOPT_CAPATH</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSL_VERIFYPEER', 64);
/**
 * The name of a file holding one or more certificates to verify the peer with.
 * This only makes sense when used in combination with <b>CURLOPT_SSL_VERIFYPEER</b>.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_CAINFO', 10065);
/**
 * A directory that holds multiple CA certificates. Use this option alongside <b>CURLOPT_SSL_VERIFYPEER</b>.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_CAPATH', 10097);
/**
 * The name of a file to save all internal cookies to when the handle is closed, e.g. after a call to curl_close.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_COOKIEJAR', 10082);
/**
 * A list of ciphers to use for SSL. For example, RC4-SHA and TLSv1 are valid cipher lists.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSL_CIPHER_LIST', 10083);
/**
 * <b>TRUE</b> to return the raw output when CURLOPT_RETURNTRANSFER is used.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @deprecated 5.1.3
 */
define('CURLOPT_BINARYTRANSFER', 19914);
/**
 * <b>TRUE</b> to ignore any cURL function that causes a signal to be sent to the PHP process.
 * This is turned on by default in multi-threaded SAPIs so timeout options can still be used.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_NOSIGNAL', 99);
/**
 * Either <b>CURLPROXY_HTTP</b> (default), <b>CURLPROXY_SOCKS4</b>, <b>CURLPROXY_SOCKS5</b>, <b>CURLPROXY_SOCKS4A</b> or <b>CURLPROXY_SOCKS5_HOSTNAME</b>.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_PROXYTYPE', 101);
/**
 * The size of the buffer to use for each read. There is no guarantee this request will be fulfilled, however.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_BUFFERSIZE', 98);
/**
 * <b>TRUE</b> to reset the HTTP request method to GET. Since GET is the default, this is only necessary if the request method has been changed.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_HTTPGET', 80);
/**
 * <b>CURL_HTTP_VERSION_NONE</b> (default, lets CURL decide which version to use),
 * <b>CURL_HTTP_VERSION_1_0</b> (forces HTTP/1.0), <b>CURL_HTTP_VERSION_1_1</b> (forces HTTP/1.1), <b>CURL_HTTP_VERSION_2_0</b> (attempts HTTP 2),
 * <b>CURL_HTTP_VERSION_2</b> (alias of CURL_HTTP_VERSION_2_0), <b>CURL_HTTP_VERSION_2TLS</b> (attempts HTTP 2 over TLS (HTTPS) only) or
 * <b>CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE</b> (issues non-TLS HTTP requests using HTTP/2 without HTTP/1.1 Upgrade).
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_HTTP_VERSION', 84);
/**
 * The name of a file containing a private SSL key.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSLKEY', 10087);
/**
 * The key type of the private SSL key specified in <b>CURLOPT_SSLKEY</b>.
 * Supported key types are "<em>PEM</em>" (default), "<em>DER</em>", and "<em>ENG</em>".
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSLKEYTYPE', 10088);
/**
 * The secret password needed to use the private SSL key specified in <b>CURLOPT_SSLKEY</b>.
 * (Since this option contains a sensitive password, remember to keep the PHP script it is contained within safe)
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSLKEYPASSWD', 10026);
/**
 * The identifier for the crypto engine of the private SSL key specified in <b>CURLOPT_SSLKEY</b>.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSLENGINE', 10089);
/**
 * The identifier for the crypto engine used for asymmetric crypto operations.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSLENGINE_DEFAULT', 90);
/**
 * The format of the certificate.
 * Supported formats are "<em>PEM</em>" (default), "<em>DER</em>", and "<em>ENG</em>". As of OpenSSL 0.9.3, "<em>P12</em>" (for PKCS#12-encoded files) is also supported.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_SSLCERTTYPE', 10086);
/**
 * <b>TRUE</b> to convert Unix newlines to CRLF newlines on transfers.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_CRLF', 27);
/**
 * The contents of the "<em>Accept-Encoding: </em>" header. This enables decoding of the response.
 * Supported encodings are "identity", "deflate", and "gzip".
 * If an empty string, "", is set, a header containing all supported encoding types is sent.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_ENCODING', 10102);
/**
 * The port number of the proxy to connect to. This port number can also be set in <b>CURLOPT_PROXY</b>.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_PROXYPORT', 59);
/**
 * <b>TRUE</b> to keep sending the username and password when following locations
 * (using <b>CURLOPT_FOLLOWLOCATION</b>), even when the hostname has changed.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_UNRESTRICTED_AUTH', 105);
/**
 * <b>TRUE</b> to use EPRT (and LPRT) when doing active FTP downloads. Use <b>FALSE</b> to disable EPRT and LPRT and use PORT only.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_FTP_USE_EPRT', 106);

/**
 * <b>TRUE</b> to disable TCP's Nagle algorithm, which tries to minimize the number of small packets on the network.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 5.2.1
 */
define('CURLOPT_TCP_NODELAY', 121);
/**
 * An array of HTTP 200 responses that will be treated as valid responses and not as errors.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_HTTP200ALIASES', 10104);
/**
 * Value for the <b>CURLOPT_TIMECONDITION</b> option.
 * Return the page only if it has been modified since the time specified in <b>CURLOPT_TIMEVALUE</b>.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_TIMECONDITION.html
 */
define('CURL_TIMECOND_IFMODSINCE', 1);
/**
 * Value for the <b>CURLOPT_TIMECONDITION</b> option.
 * Return the page if it hasn't been modified since the time specified in <b>CURLOPT_TIMEVALUE</b>.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_TIMECONDITION.html
 */
define('CURL_TIMECOND_IFUNMODSINCE', 2);
/**
 * Value for the <b>CURLOPT_TIMECONDITION</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define('CURL_TIMECOND_LASTMOD', 3);
/**
 * The HTTP authentication method(s) to use.
 * The options are: <b>CURLAUTH_BASIC</b>, <b>CURLAUTH_DIGEST</b>, <b>CURLAUTH_GSSNEGOTIATE</b>, <b>CURLAUTH_NTLM</b>, <b>CURLAUTH_ANY</b>, and <b>CURLAUTH_ANYSAFE</b>.
 * The bitwise | (or) operator can be used to combine more than one method.
 * If this is done, cURL will poll the server to see what methods it supports and pick the best one.
 * <b>CURLAUTH_ANY</b> is an alias for <b>CURLAUTH_BASIC</b> | <b>CURLAUTH_DIGEST</b> | <b>CURLAUTH_GSSNEGOTIATE</b> | <b>CURLAUTH_NTLM</b>.
 * <b>CURLAUTH_ANYSAFE</b> is an alias for <b>CURLAUTH_DIGEST</b> | <b>CURLAUTH_GSSNEGOTIATE</b> | <b>CURLAUTH_NTLM</b>.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_HTTPAUTH', 107);
/**
 * Value for the <b>CURLOPT_HTTPAUTH</b> option.
 * Allows username/password authentication.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_HTTPAUTH.html
 */
define('CURLAUTH_BASIC', 1);
/**
 * Value for the <b>CURLOPT_HTTPAUTH</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_HTTPAUTH.html
 */
define('CURLAUTH_DIGEST', 2);
/**
 * Value for the <b>CURLOPT_HTTPAUTH</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_HTTPAUTH.html
 */
define('CURLAUTH_GSSNEGOTIATE', 4);
/**
 * Value for the <b>CURLOPT_HTTPAUTH</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_HTTPAUTH.html
 */
define('CURLAUTH_NTLM', 8);
/**
 * Value for the <b>CURLOPT_HTTPAUTH</b> option.
 * Is an alias for <b>CURLAUTH_BASIC</b> | <b>CURLAUTH_DIGEST</b> | <b>CURLAUTH_GSSNEGOTIATE</b> | <b>CURLAUTH_NTLM</b>.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_HTTPAUTH.html
 */
define('CURLAUTH_ANY', -17);
/**
 * Value for the <b>CURLOPT_HTTPAUTH</b> option.
 * Is an alias for <b>CURLAUTH_DIGEST</b> | <b>CURLAUTH_GSSNEGOTIATE</b> | <b>CURLAUTH_NTLM</b>.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_HTTPAUTH.html
 */
define('CURLAUTH_ANYSAFE', -18);
/**
 * The HTTP authentication method(s) to use for the proxy connection.
 * Use the same bitmasks as described in <b>CURLOPT_HTTPAUTH</b>.
 * For proxy authentication, only <b>CURLAUTH_BASIC</b> and <b>CURLAUTH_NTLM</b> are currently supported.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_PROXYAUTH', 111);
/**
 * <b>TRUE</b> to create missing directories when an FTP operation encounters a path that currently doesn't exist.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_FTP_CREATE_MISSING_DIRS', 110);

/**
 * Any data that should be associated with this cURL handle.
 * This data can subsequently be retrieved with the <b>CURLINFO_PRIVATE</b> option of {@see curl_getinfo()}. cURL does nothing with this data.
 * When using a cURL multi handle, this private data is typically a unique key to identify a standard cURL handle.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 5.2.4
 */
define('CURLOPT_PRIVATE', 10103);

/**
 * The last response code
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_RESPONSE_CODE', 2097154);
/**
 * The CONNECT response code
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_HTTP_CONNECTCODE', 2097174);
/**
 * Bitmask indicating the authentication method(s) available according to the previous response
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_HTTPAUTH_AVAIL', 2097175);
/**
 * Bitmask indicating the proxy authentication method(s) available according to the previous response
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_PROXYAUTH_AVAIL', 2097176);
/**
 * Errno from a connect failure. The number is OS and system specific.
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_OS_ERRNO', 2097177);
/**
 * Number of connections curl had to create to achieve the previous transfer
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_NUM_CONNECTS', 2097178);
/**
 * OpenSSL crypto-engines supported
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_SSL_ENGINES', 4194331);
/**
 * All known cookies
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_COOKIELIST', 4194332);
/**
 * Entry path in FTP server
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_FTP_ENTRY_PATH', 1048606);
/**
 * Time in seconds it took from the start until the SSL/SSH connect/handshake to the remote host was completed
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_APPCONNECT_TIME', 3145761);
/**
 * TLS certificate chain
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_CERTINFO', 4194338);
/**
 * Info on unmet time conditional
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_CONDITION_UNMET', 2097187);
/**
 * Next RTSP client CSeq
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_RTSP_CLIENT_CSEQ', 2097189);
/**
 * Recently received CSeq
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_RTSP_CSEQ_RECV', 2097191);
/**
 * Next RTSP server CSeq
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_RTSP_SERVER_CSEQ', 2097190);
/**
 * RTSP session ID
 * @link https://php.net/manual/en/function.curl-getinfo.php
 * @since 5.5
 */
define('CURLINFO_RTSP_SESSION_ID', 1048612);
/**
 * Value for the <b>CURLOPT_CLOSEPOLICY</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @removed 5.6
 */
define('CURLCLOSEPOLICY_LEAST_RECENTLY_USED', 2);
/**
 * Value for the <b>CURLOPT_CLOSEPOLICY</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @removed 5.6
 */
define('CURLCLOSEPOLICY_LEAST_TRAFFIC', 3);
/**
 * Value for the <b>CURLOPT_CLOSEPOLICY</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @removed 5.6
 */
define('CURLCLOSEPOLICY_SLOWEST', 4);
/**
 * Value for the <b>CURLOPT_CLOSEPOLICY</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @removed 5.6
 */
define('CURLCLOSEPOLICY_CALLBACK', 5);
/**
 * Value for the <b>CURLOPT_CLOSEPOLICY</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @removed 5.6
 */
define('CURLCLOSEPOLICY_OLDEST', 1);
/**
 * Last effective URL
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_EFFECTIVE_URL', 1048577);
/**
 * As of PHP 5.5.0 and cURL 7.10.8, this is a legacy alias of <b>CURLINFO_RESPONSE_CODE</b>.
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_HTTP_CODE', 2097154);
/**
 * Total size of all headers received
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_HEADER_SIZE', 2097163);
/**
 * Total size of issued requests, currently only for HTTP requests
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_REQUEST_SIZE', 2097164);
/**
 * Total transaction time in seconds for last transfer
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_TOTAL_TIME', 3145731);
/**
 * Time in seconds until name resolving was complete
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_NAMELOOKUP_TIME', 3145732);
/**
 * Time in seconds it took to establish the connection
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_CONNECT_TIME', 3145733);
/**
 * Time in seconds from start until just before file transfer begins
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_PRETRANSFER_TIME', 3145734);
/**
 * Total number of bytes uploaded
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_SIZE_UPLOAD', 3145735);
/**
 * Total number of bytes downloaded
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_SIZE_DOWNLOAD', 3145736);
/**
 * Average download speed
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_SPEED_DOWNLOAD', 3145737);
/**
 * Average upload speed
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_SPEED_UPLOAD', 3145738);
/**
 * Remote time of the retrieved document, with the <b>CURLOPT_FILETIME</b> enabled;
 * if -1 is returned the time of the document is unknown
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_FILETIME', 2097166);
/**
 * Result of SSL certification verification requested by setting <b>CURLOPT_SSL_VERIFYPEER</b>
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_SSL_VERIFYRESULT', 2097165);
/**
 * Content length of download, read from <em>Content-Length: field</em>
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_CONTENT_LENGTH_DOWNLOAD', 3145743);
/**
 * Specified size of upload
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_CONTENT_LENGTH_UPLOAD', 3145744);
/**
 * Time in seconds until the first byte is about to be transferred
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_STARTTRANSFER_TIME', 3145745);
/**
 * Content-Type: of the requested document. <b>NULL</b> indicates server did not send valid Content-Type: header
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_CONTENT_TYPE', 1048594);
/**
 * Time in seconds of all redirection steps before final transaction was started,
 * with the <b>CURLOPT_FOLLOWLOCATION</b> option enabled
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_REDIRECT_TIME', 3145747);
/**
 * Number of redirects, with the <b>CURLOPT_FOLLOWLOCATION</b> option enabled
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 */
define('CURLINFO_REDIRECT_COUNT', 2097172);

/**
 * <b>TRUE</b> to track the handle's request string
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 5.1.3
 */
define('CURLINFO_HEADER_OUT', 2);

/**
 * Private data associated with this cURL handle, previously set with the <b>CURLOPT_PRIVATE</b> option of {@see curl_getinfo()}
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 5.2.4
 */
define('CURLINFO_PRIVATE', 1048597);
/**
 * Supports IPv6
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURL_VERSION_IPV6', 1);
/**
 * Supports Kerberos V4 (when using FTP)
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURL_VERSION_KERBEROS4', 2);
/**
 * Supports SSL (HTTPS/FTPS)
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURL_VERSION_SSL', 4);
/**
 * Supports HTTP deflate using libz
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURL_VERSION_LIBZ', 8);
/**
 * Will be the most recent age value for the libcurl.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLVERSION_NOW', 9);
/**
 * All fine. Proceed as usual.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_OK', 0);
/**
 * The URL you passed to libcurl used a protocol that this libcurl does not support.
 * The support might be a compile-time option that you didn't use,
 * it can be a misspelled protocol string or just a protocol libcurl has no code for.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_UNSUPPORTED_PROTOCOL', 1);
/**
 * Very early initialization code failed.
 * This is likely to be an internal error or problem,
 * or a resource problem where something fundamental couldn't get done at init time.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FAILED_INIT', 2);
/**
 * The URL was not properly formatted.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_URL_MALFORMAT', 3);
/**
 * A requested feature, protocol or option was not found built-in in this libcurl due to a build-time decision.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_URL_MALFORMAT_USER', 4);
/**
 * Couldn't resolve proxy. The given proxy host could not be resolved.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_COULDNT_RESOLVE_PROXY', 5);
/**
 * Couldn't resolve host. The given remote host was not resolved.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_COULDNT_RESOLVE_HOST', 6);
/**
 * Failed to connect to host or proxy.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_COULDNT_CONNECT', 7);
/**
 * The server sent data libcurl couldn't parse.
 * This error code was known as as <b>CURLE_FTP_WEIRD_SERVER_REPLY</b> before 7.51.0.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_WEIRD_SERVER_REPLY', 8);
/**
 * We were denied access to the resource given in the URL.
 * For FTP, this occurs while trying to change to the remote directory.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_ACCESS_DENIED', 9);
/**
 * While waiting for the server to connect back when an active FTP session is used,
 * an error code was sent over the control connection or similar.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_USER_PASSWORD_INCORRECT', 10);
/**
 * After having sent the FTP password to the server, libcurl expects a proper reply.
 * This error code indicates that an unexpected code was returned.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_WEIRD_PASS_REPLY', 11);
/**
 * During an active FTP session while waiting for the server to connect,
 * the <b>CURLOPT_ACCEPTTIMEOUT_MS</b> (or the internal default) timeout expired.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_WEIRD_USER_REPLY', 12);
/**
 * Libcurl failed to get a sensible result back from the server as a response to either a PASV or a EPSV command.
 * The server is flawed.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_WEIRD_PASV_REPLY', 13);
/**
 * FTP servers return a 227-line as a response to a PASV command.
 * If libcurl fails to parse that line, this return code is passed back.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_WEIRD_227_FORMAT', 14);
/**
 * An internal failure to lookup the host used for the new connection.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_CANT_GET_HOST', 15);
/**
 * A problem was detected in the HTTP2 framing layer.
 * This is somewhat generic and can be one out of several problems, see the error buffer for details.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_CANT_RECONNECT', 16);
/**
 * Received an error when trying to set the transfer mode to binary or ASCII.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_COULDNT_SET_BINARY', 17);
/**
 * A file transfer was shorter or larger than expected.
 * This happens when the server first reports an expected transfer size, and then delivers data
 * that doesn't match the previously given size.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_PARTIAL_FILE', 18);
/**
 * This was either a weird reply to a 'RETR' command or a zero byte transfer complete.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_COULDNT_RETR_FILE', 19);
/**
 * After a completed file transfer, the FTP server did not respond a proper
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLE_FTP_WRITE_ERROR', 20);
/**
 * When sending custom "QUOTE" commands to the remote server,
 * one of the commands returned an error code that was 400 or higher (for FTP) or otherwise indicated unsuccessful completion of the command.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_QUOTE_ERROR', 21);
/**
 * This is returned if <b>CURLOPT_FAILONERROR</b> is set <b>TRUE</b> and the HTTP server returns an error code that is >= 400.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_HTTP_NOT_FOUND', 22);
/**
 * An error occurred when writing received data to a local file, or an error was returned to libcurl from a write callback.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_WRITE_ERROR', 23);
/**
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLE_MALFORMAT_USER', 24);
/**
 * Failed starting the upload. For FTP, the server typically denied the STOR command.
 * The error buffer usually contains the server's explanation for this.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_COULDNT_STOR_FILE', 25);
/**
 * There was a problem reading a local file or an error returned by the read callback.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_READ_ERROR', 26);
/**
 * A memory allocation request failed. This is serious badness and things are severely screwed up if this ever occurs.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_OUT_OF_MEMORY', 27);
/**
 * Operation timeout. The specified time-out period was reached according to the conditions.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_OPERATION_TIMEOUTED', 28);
/**
 * libcurl failed to set ASCII transfer type (TYPE A).
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLE_FTP_COULDNT_SET_ASCII', 29);
/**
 * The FTP PORT command returned error.
 * This mostly happens when you haven't specified a good enough address for libcurl to use. See <b>CURLOPT_FTPPORT</b>.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_PORT_FAILED', 30);
/**
 * The FTP REST command returned error. This should never happen if the server is sane.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_COULDNT_USE_REST', 31);
/**
 * The FTP SIZE command returned error. SIZE is not a kosher FTP command,
 * it is an extension and not all servers support it.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLE_FTP_COULDNT_GET_SIZE', 32);
/**
 * The server does not support or accept range requests.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_HTTP_RANGE_ERROR', 33);
/**
 * This is an odd error that mainly occurs due to internal confusion.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_HTTP_POST_ERROR', 34);
/**
 * A problem occurred somewhere in the SSL/TLS handshake.
 * You really want the error buffer and read the message there as it pinpoints the problem slightly more.
 * Could be certificates (file formats, paths, permissions), passwords, and others.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_SSL_CONNECT_ERROR', 35);
/**
 * The download could not be resumed because the specified offset was out of the file boundary.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_BAD_DOWNLOAD_RESUME', 36);
/**
 * A file given with <em>FILE://</em> couldn't be opened.
 * Most likely because the file path doesn't identify an existing file. Did you check file permissions?
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FILE_COULDNT_READ_FILE', 37);
/**
 * LDAP cannot bind. LDAP bind operation failed.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_LDAP_CANNOT_BIND', 38);
/**
 * LDAP search failed.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_LDAP_SEARCH_FAILED', 39);
/**
 * Library not found. The LDAP library was not found.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLE_LIBRARY_NOT_FOUND', 40);
/**
 * Function not found. A required zlib function was not found.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FUNCTION_NOT_FOUND', 41);
/**
 * Aborted by callback. A callback returned "abort" to libcurl.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_ABORTED_BY_CALLBACK', 42);
/**
 * A function was called with a bad parameter.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_BAD_FUNCTION_ARGUMENT', 43);
/**
 * This is never returned
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLE_BAD_CALLING_ORDER', 44);
/**
 * Interface error. A specified outgoing interface could not be used.
 * Set which interface to use for outgoing connections' source IP address with <b>CURLOPT_INTERFACE</b>.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_HTTP_PORT_FAILED', 45);
/**
 * This is never returned
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLE_BAD_PASSWORD_ENTERED', 46);
/**
 * Too many redirects. When following redirects, libcurl hit the maximum amount.
 * Set your limit with <b>CURLOPT_MAXREDIRS</b>.
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_TOO_MANY_REDIRECTS', 47);
/**
 * An option passed to libcurl is not recognized/known. Refer to the appropriate documentation.
 * This is most likely a problem in the program that uses libcurl.
 * The error buffer might contain more specific information about which exact option it concerns.
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_UNKNOWN_TELNET_OPTION', 48);
/**
 * A telnet option string was Illegally formatted.
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_TELNET_OPTION_SYNTAX', 49);
/**
 * Currently unused.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLE_OBSOLETE', 50);
/**
 * The remote server's SSL certificate or SSH md5 fingerprint was deemed not OK.
 * This error code has been unified with <b>CURLE_SSL_CACERT</b> since 7.62.0. Its previous value was 51.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_SSL_PEER_CERTIFICATE', 60);
/**
 * Nothing was returned from the server, and under the circumstances, getting nothing is considered an error.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_GOT_NOTHING', 52);
/**
 * The specified crypto engine wasn't found.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_SSL_ENGINE_NOTFOUND', 53);
/**
 * Failed setting the selected SSL crypto engine as default!
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_SSL_ENGINE_SETFAILED', 54);
/**
 * Failed sending network data.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_SEND_ERROR', 55);
/**
 * Failure with receiving network data.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_RECV_ERROR', 56);
/**
 * The share object is currently in use.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_SHARE_IN_USE', 57);
/**
 * Problem with the local client certificate.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_SSL_CERTPROBLEM', 58);
/**
 * Couldn't use specified cipher.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_SSL_CIPHER', 59);
/**
 * The remote server's SSL certificate or SSH md5 fingerprint was deemed not OK.
 * This error code has been unified with <b>CURLE_SSL_PEER_CERTIFICATE</b> since 7.62.0.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_SSL_CACERT', 60);
/**
 * Unrecognized transfer encoding.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_BAD_CONTENT_ENCODING', 61);
/**
 * Invalid LDAP URL.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_LDAP_INVALID_URL', 62);
/**
 * Maximum file size exceeded.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FILESIZE_EXCEEDED', 63);
/**
 * Requested FTP SSL level failed.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLE_FTP_SSL_FAILED', 64);
/**
 * Value for the <b>CURLOPT_PROXYTYPE</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLPROXY_HTTP', 0);
/**
 * Value for the <b>CURLOPT_PROXYTYPE</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLPROXY_SOCKS4', 4);
/**
 * Value for the <b>CURLOPT_PROXYTYPE</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLPROXY_SOCKS5', 5);
/**
 * Value for the <b>CURLOPT_NETRC</b> option.
 * The use of the ~/.netrc file is optional, and information in the URL is to be preferred.
 * The file will be scanned for the host and user name (to find the password only) or for the host only,
 * to find the first user name and password after that machine, which ever information is not specified.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_NETRC.html
 */
define('CURL_NETRC_OPTIONAL', 1);
/**
 * Value for the <b>CURLOPT_NETRC</b> option.
 * The library will ignore the ~/.netrc file. This is the default.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_NETRC.html
 */
define('CURL_NETRC_IGNORED', 0);
/**
 * Value for the <b>CURLOPT_NETRC</b> option.
 * The use of the ~/.netrc file is required, and information in the URL is to be ignored.
 * The file will be scanned for the host and user name (to find the password only) or for the host only,
 * to find the first user name and password after that machine, which ever information is not specified.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_NETRC.html
 */
define('CURL_NETRC_REQUIRED', 2);
/**
 * Value for the <b>CURLOPT_HTTP_VERSION</b> option.
 * Let's CURL decide which version to use.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURL_HTTP_VERSION_NONE', 0);
/**
 * Value for the <b>CURLOPT_HTTP_VERSION</b> option.
 * Forces HTTP/1.0.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURL_HTTP_VERSION_1_0', 1);
/**
 * Value for the <b>CURLOPT_HTTP_VERSION</b> option.
 * Forces HTTP/1.1.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURL_HTTP_VERSION_1_1', 2);
/**
 * Value for the <b>CURLOPT_HTTP_VERSION</b> option.
 * Attempts HTTP 2.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURL_HTTP_VERSION_2_0', 3);
/**
 * This is not really an error. It means you should call {@see curl_multi_exec()} again without doing select() or similar in between.
 * Before version 7.20.0 this could be returned by {@see curl_multi_exec()}, but in later versions this return code is never used.
 * @link https://www.php.net/manual/en/function.curl-multi-exec.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLM_CALL_MULTI_PERFORM', -1);
/**
 * Things are fine.
 * @link https://www.php.net/manual/en/function.curl-multi-exec.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLM_OK', 0);
/**
 * The passed-in handle is not a valid CURLM handle.
 * @link https://www.php.net/manual/en/function.curl-multi-exec.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLM_BAD_HANDLE', 1);
/**
 * An easy handle was not good/valid. It could mean that it isn't an easy handle at all,
 * or possibly that the handle already is in use by this or another multi handle.
 * @link https://www.php.net/manual/en/function.curl-multi-exec.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLM_BAD_EASY_HANDLE', 2);
/**
 * Out of memory error.
 * @link https://www.php.net/manual/en/function.curl-multi-exec.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLM_OUT_OF_MEMORY', 3);
/**
 * libcurl' internal error.
 * @link https://www.php.net/manual/en/function.curl-multi-exec.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define('CURLM_INTERNAL_ERROR', 4);
/**
 * The message identifies a transfer that is done, and then result contains the return code for the easy handle that just completed.
 * Other return values are currently not available.
 * @link https://www.php.net/manual/en/function.curl-multi-info-read.php
 * @link https://curl.haxx.se/libcurl/c/curl_multi_info_read.html
 */
define('CURLMSG_DONE', 1);

/**
 * The FTP authentication method (when is activated):
 * <b>CURLFTPAUTH_SSL</b> (try SSL first), <b>CURLFTPAUTH_TLS</b> (try TLS first), or <b>CURLFTPAUTH_DEFAULT</b> (let cURL decide).
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLOPT_FTPSSLAUTH', 129);

/**
 * Value for the <b>CURLOPT_FTPSSLAUTH</b> option.
 * Let cURL decide FTP authentication method.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLFTPAUTH_DEFAULT', 0);

/**
 * Value for the <b>CURLOPT_FTPSSLAUTH</b> option.
 * Try SSL first as FTP authentication method.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLFTPAUTH_SSL', 1);

/**
 * Value for the <b>CURLOPT_FTPSSLAUTH</b> option.
 * Try TLS first as FTP authentication method.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLFTPAUTH_TLS', 2);

/**
 * @link https://php.net/manual/en/curl.constants.php
 * @deprecated use <b>CURLOPT_USE_SSL</b> instead.
 */
define('CURLOPT_FTP_SSL', 119);

/**
 * Value for the <b>CURLOPT_FTP_SSL</b> option.
 * Don't attempt to use SSL.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLFTPSSL_NONE', 0);

/**
 * Value for the <b>CURLOPT_FTP_SSL</b> option.
 * Try using SSL, proceed as normal otherwise.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLFTPSSL_TRY', 1);

/**
 * Value for the <b>CURLOPT_FTP_SSL</b> option.
 * Require SSL for the control connection or fail.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLFTPSSL_CONTROL', 2);

/**
 * Value for the <b>CURLOPT_FTP_SSL</b> option.
 * Require SSL for all communication or fail.
 * @link https://php.net/manual/en/curl.constants.php
 */
define('CURLFTPSSL_ALL', 3);
/**
 * Tell curl which method to use to reach a file on a FTP(S) server.
 * Possible values are <b>CURLFTPMETHOD_MULTICWD</b>, <b>CURLFTPMETHOD_NOCWD</b> and <b>CURLFTPMETHOD_SINGLECWD</b>.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.3
 */
define('CURLOPT_FTP_FILEMETHOD', 138);
/**
 * Ignore the IP address in the PASV response
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FTP_SKIP_PASV_IP.html
 */
define('CURLOPT_FTP_SKIP_PASV_IP', 137);
/**
 * <b>TRUE</b> to disable support for the @ prefix for uploading files in <b>CURLOPT_POSTFIELDS</b>,
 * which means that values starting with @ can be safely passed as fields.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.5
 * @deprecated 7.0 Use <b>CURLFile</b> for uploads instead.
 */
define('CURLOPT_SAFE_UPLOAD', -1);
/**
 * Value for the <b>CURLOPT_FTP_FILEMETHOD</b> option.
 * libcurl does a single CWD operation for each path part in the given URL.
 * For deep hierarchies this means many commands. This is how RFC 1738 says it should be done. This is the default but the slowest behavior.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define('CURLFTPMETHOD_MULTICWD', 1);
/**
 * Value for the <b>CURLOPT_FTP_FILEMETHOD</b> option.
 * libcurl does no CWD at all.
 * libcurl will do SIZE, RETR, STOR etc and give a full path to the server for all these commands. This is the fastest behavior.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define('CURLFTPMETHOD_NOCWD', 2);
/**
 * Value for the <b>CURLOPT_FTP_FILEMETHOD</b> option.
 * libcurl does one CWD with the full target directory and then operates on the file "normally" (like in the multicwd case).
 * This is somewhat more standards compliant than 'nocwd' but without the full penalty of 'multicwd'.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define('CURLFTPMETHOD_SINGLECWD', 3);

 /**
  * Value for the <b>CURLOPT_PROTOCOLS</b> option.
  * @link https://www.php.net/manual/en/function.curl-setopt.php
  */
 define('CURLPROTO_HTTP', 1);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLPROTO_HTTPS', 2);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLPROTO_FTP', 4);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLPROTO_FTPS', 8);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLPROTO_SCP', 16);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLPROTO_SFTP', 32);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLPROTO_TELNET', 64);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLPROTO_LDAP', 128);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLPROTO_LDAPS', 256);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLPROTO_DICT', 512);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLPROTO_FILE', 1024);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLPROTO_TFTP', 2048);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLPROTO_ALL', -1);

/**
 * As of cURL 7.43.0, the value is a bitmask.
 * Pass 1 to enable or 0 to disable.
 * Enabling pipelining on a multi handle will make it attempt to perform HTTP Pipelining as far as possible for transfers
 * using this handle. This means that if you add a second request that can use an already existing connection,
 * the second request will be "piped" on the same connection.
 * Pass 2 to try to multiplex the new transfer over an existing HTTP/2 connection if possible.
 * Pass 3 instructs cURL to ask for pipelining and multiplexing independently of each other.
 * As of cURL 7.62.0, setting the pipelining bit has no effect.
 * Instead of integer literals, you can also use the <b>CURLPIPE_*</b> constants if available.
 * @link https://www.php.net/manual/en/function.curl-multi-setopt.php
 * @since 5.5
 */
define('CURLMOPT_PIPELINING', 3);

/**
 * Pass a number that will be used as the maximum amount of simultaneously open connections that libcurl may cache.
 * By default the size will be enlarged to fit four times the number of handles added via {@see curl_multi_add_handle()}.
 * When the cache is full, curl closes the oldest one in the cache to prevent the number of open connections from increasing.
 * @link https://www.php.net/manual/en/function.curl-multi-setopt.php
 * @since 5.5
 */
define('CURLMOPT_MAXCONNECTS', 6);
/**
 * Specifies a type of data that should be shared.
 * @link https://www.php.net/manual/en/function.curl-share-setopt.php
 */
define('CURLSHOPT_SHARE', 1);
/**
 * Specifies a type of data that will be no longer shared.
 * @link https://www.php.net/manual/en/function.curl-share-setopt.php
 */
define('CURLSHOPT_UNSHARE', 2);
/**
 * Value for the <b>CURLSHOPT_SHARE</b> option.
 * Shares cookie data.
 * @link https://www.php.net/manual/en/function.curl-share-setopt.php
 */
define('CURL_LOCK_DATA_COOKIE', 2);
/**
 * Value for the <b>CURLSHOPT_SHARE</b> option.
 * Shares DNS cache. Note that when you use cURL multi handles,
 * all handles added to the same multi handle will share DNS cache by default.
 * @link https://www.php.net/manual/en/function.curl-share-setopt.php
 */
define('CURL_LOCK_DATA_DNS', 3);
/**
 * Value for the <b>CURLSHOPT_SHARE</b> option.
 * Shares SSL session IDs, reducing the time spent on the SSL handshake when reconnecting to the same server.
 * Note that SSL session IDs are reused within the same handle by default.
 * @link https://www.php.net/manual/en/function.curl-share-setopt.php
 */
define('CURL_LOCK_DATA_SSL_SESSION', 4);
/**
 * The password required to use the <b>CURLOPT_SSLKEY</b> or <b>CURLOPT_SSH_PRIVATE_KEYFILE</b> private key.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define('CURLOPT_KEYPASSWD', 10026);

/**
 * Value for the <b>CURLOPT_FTP_CREATE_MISSING_DIRS</b> option.
 * libcurl will attempt to create any remote directory that it fails to "move" into.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FTP_CREATE_MISSING_DIRS.html
 * @since 7.0.7
 */
define('CURLFTP_CREATE_DIR', 1);

/**
 * Value for the <b>CURLOPT_FTP_CREATE_MISSING_DIRS</b> option.
 * libcurl will not attempt to create any remote directory that it fails to "move" into.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FTP_CREATE_MISSING_DIRS.html
 * @since 7.0.7
 */
define('CURLFTP_CREATE_DIR_NONE', 0);

/**
 * Value for the <b>CURLOPT_HTTPAUTH</b> option.
 * NTLM delegating to winbind helper.
 * Authentication is performed by a separate binary application that is executed when needed.
 * The name of the application is specified at compile time but is typically <em>/usr/bin/ntlm_auth</em>.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURLAUTH_NTLM_WB', 32);

/**
 * Value for the <b>CURLOPT_HTTP_VERSION</b> option.
 * Alias of <b>CURL_HTTP_VERSION_2_0</b>
 * Attempts HTTP 2
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURL_HTTP_VERSION_2', 3);

/**
 * Value for the <b>CURLOPT_HTTP_VERSION</b> option.
 * Attempts HTTP 2 over TLS (HTTPS) only
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURL_HTTP_VERSION_2TLS', 4);

/**
 * Value for the <b>CURLOPT_HTTP_VERSION</b> option.
 * Issues non-TLS HTTP requests using HTTP/2 without HTTP/1.1 Upgrade
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE', 5);

/**
 * <b>TRUE</b> to enable sending the initial response in the first packet.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_SASL_IR', 218);

/**
 * Set the name of the network interface that the DNS resolver should bind to. This must be an interface name (not an address).
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_DNS_INTERFACE', 10221);

/**
 * Set the local IPv4 address that the resolver should bind to. The argument should contain a single numerical IPv4 address as a string.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_DNS_LOCAL_IP4', 10222);

/**
 * Set the local IPv6 address that the resolver should bind to. The argument should contain a single numerical IPv6 address as a string.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_DNS_LOCAL_IP6', 10223);

/**
 * Specifies the OAuth 2.0 access token.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_XOAUTH2_BEARER', 10220);

/**
 * Can be used to set protocol specific login options, such as the preferred authentication mechanism via "<em>AUTH=NTLM</em>" or "<em>AUTH=*</em>",
 * and should be used in conjunction with the <b>CURLOPT_USERNAME</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_LOGIN_OPTIONS', 10224);

/**
 * The timeout for Expect: 100-continue responses in milliseconds. Defaults to 1000 milliseconds.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_EXPECT_100_TIMEOUT_MS', 227);

/**
 * <b>FALSE</b> to disable ALPN in the SSL handshake (if the SSL backend libcurl is built to use supports it),
 * which can be used to negotiate http2.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_SSL_ENABLE_ALPN', 226);

/**
 * <b>FALSE</b> to disable NPN in the SSL handshake (if the SSL backend libcurl is built to use supports it),
 * which can be used to negotiate http2.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_SSL_ENABLE_NPN', 225);

/**
 * Set the pinned public key. The string can be the file name of your pinned public key. The file format expected is "<em>PEM</em>" or "<em>DER</em>".
 * The string can also be any number of base64 encoded sha256 hashes preceded by "sha256//" and separated by ";".
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_PINNEDPUBLICKEY', 10230);

/**
 * Enables the use of Unix domain sockets as connection endpoint and sets the path to the given string.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_UNIX_SOCKET_PATH', 10231);

/**
 * <b>TRUE</b> to verify the certificate's status.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_SSL_VERIFYSTATUS', 232);

/**
 * <b>TRUE</b> to not handle dot dot sequences.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_PATH_AS_IS', 234);

/**
 * <b>TRUE</b> to enable TLS false start.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_SSL_FALSESTART', 233);

/**
 * <b>TRUE</b> to wait for pipelining/multiplexing.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_PIPEWAIT', 237);

/**
 * The proxy authentication service name.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_PROXY_SERVICE_NAME', 10235);

/**
 * The authentication service name.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_SERVICE_NAME', 10236);

/**
 * Value for the <b>CURLOPT_SSH_AUTH_TYPES</b> option.
 * libcurl attempts to connect to ssh-agent or pageant and let the agent attempt the authentication.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURLSSH_AUTH_AGENT', 16);

/**
 * Value for the <b>CURLMOPT_PIPELINING</b> option.
 * Default, which means doing no attempts at pipelining or multiplexing.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/CURLMOPT_PIPELINING.html
 * @since 7.0.7
 */
define('CURLPIPE_NOTHING', 0);

/**
 * Value for the <b>CURLMOPT_PIPELINING</b> option.
 * If this bit is set, libcurl will try to pipeline HTTP/1.1 requests on connections that are already established and in use to hosts.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/CURLMOPT_PIPELINING.html
 * @deprecated 7.4
 * @since 7.0.7
 */
define('CURLPIPE_HTTP1', 1);

/**
 * Value for the <b>CURLMOPT_PIPELINING</b> option.
 * If this bit is set, libcurl will try to multiplex the new transfer over an existing connection if possible. This requires HTTP/2.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/CURLMOPT_PIPELINING.html
 * @since 7.0.7
 */
define('CURLPIPE_MULTIPLEX', 2);

/**
 * Value for the <b>CURLOPT_HEADEROPT</b> option.
 * Makes <b>CURLOPT_HTTPHEADER</b> headers only get sent to a server and not to a proxy.
 * Proxy headers must be set with <b>CURLOPT_PROXYHEADER</b> to get used.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURLHEADER_SEPARATE', 1);

/**
 * Value for the <b>CURLOPT_HEADEROPT</b> option.
 * The headers specified in <b>CURLOPT_HTTPHEADER</b> will be used in requests both to servers and proxies.
 * With this option enabled, <b>CURLOPT_PROXYHEADER</b> will not have any effect.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURLHEADER_UNIFIED', 0);

/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURLPROTO_SMB', 67108864);

/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURLPROTO_SMBS', 134217728);

/**
 * How to deal with headers.
 * One of the following constants:
 *  <b>CURLHEADER_UNIFIED</b>: the headers specified in <b>CURLOPT_HTTPHEADER</b> will be used in requests both to servers and proxies.
 *   With this option enabled, <b>CURLOPT_PROXYHEADER</b> will not have any effect.
 *  <b>CURLHEADER_SEPARATE</b>: makes <b>CURLOPT_HTTPHEADER</b> headers only get sent to a server and not to a proxy.
 *   Proxy headers must be set with <b>CURLOPT_PROXYHEADER</b> to get used.
 *   Note that if a non-CONNECT request is sent to a proxy, libcurl will send both server headers and proxy headers.
 *   When doing CONNECT, libcurl will send <b>CURLOPT_PROXYHEADER</b> headers only to the proxy and then <b>CURLOPT_HTTPHEADER</b> headers only to the server.
 *  Defaults to <b>CURLHEADER_SEPARATE</b> as of cURL 7.42.1, and <b>CURLHEADER_UNIFIED</b> before.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_HEADEROPT', 229);

/**
 * An array of custom HTTP headers to pass to proxies.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define('CURLOPT_PROXYHEADER', 10228);

/**
 * Value for the <b>CURLOPT_POSTREDIR</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURL_REDIR_POST_301', 1);

/**
 * Value for the <b>CURLOPT_POSTREDIR</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURL_REDIR_POST_302', 2);

/**
 * Value for the <b>CURLOPT_POSTREDIR</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURL_REDIR_POST_303', 4);

/**
 * Value for the <b>CURLOPT_PROXYTYPE</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURLPROXY_HTTP_1_0', 1);
/**
 * Value for the <b>CURLOPT_POSTREDIR</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURL_REDIR_POST_ALL', 7);

/**
 * Pass a number that specifies the chunk length threshold for pipelining in bytes.
 * @link https://www.php.net/manual/en/function.curl-multi-setopt.php
 * @since 7.0.7
 */
define('CURLMOPT_CHUNK_LENGTH_PENALTY_SIZE', 30010);

/**
 * Pass a number that specifies the size threshold for pipelining penalty in bytes.
 * @link https://www.php.net/manual/en/function.curl-multi-setopt.php
 * @since 7.0.7
 */
define('CURLMOPT_CONTENT_LENGTH_PENALTY_SIZE', 30009);

/**
 * Pass a number that specifies the maximum number of connections to a single host.
 * @link https://www.php.net/manual/en/function.curl-multi-setopt.php
 * @since 7.0.7
 */
define('CURLMOPT_MAX_HOST_CONNECTIONS', 7);

/**
 * Pass a number that specifies the maximum number of requests in a pipeline.
 * @link https://www.php.net/manual/en/function.curl-multi-setopt.php
 * @since 7.0.7
 */
define('CURLMOPT_MAX_PIPELINE_LENGTH', 8);

/**
 * Pass a number that specifies the maximum number of simultaneously open connections.
 * @link https://www.php.net/manual/en/function.curl-multi-setopt.php
 * @since 7.0.7
 */
define('CURLMOPT_MAX_TOTAL_CONNECTIONS', 13);

/**
 * Value for the <b>CURLOPT_FTP_CREATE_MISSING_DIRS</b> option.
 * libcurl will not attempt to create any remote directory that it fails to "move" into.
 * Tells libcurl to retry the CWD command again if the subsequent MKD command fails.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FTP_CREATE_MISSING_DIRS.html
 * @since 7.0.7
 */
define('CURLFTP_CREATE_DIR_RETRY', 2);

/**
 * Value for the <b>CURLOPT_HTTPAUTH</b> option.
 * HTTP Negotiate (SPNEGO) authentication
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURLAUTH_NEGOTIATE', 4);

/**
 * Pass a <em>callable</em> that will be registered to handle server pushes and should have the following signature:
 *  <b>parent_ch</b>
 *   The parent cURL handle (the request the client made).
 *  <b>pushed_ch</b>
 *   A new cURL handle for the pushed request.
 *  <b>headers</b>
 *   The push promise headers.
 *   The push function is supposed to return either <b>CURL_PUSH_OK</b> if it can handle the push,
 *   or <b>CURL_PUSH_DENY</b> to reject it.
 * @link https://www.php.net/manual/en/function.curl-multi-setopt.php
 * @since 7.1
 */
define('CURLMOPT_PUSHFUNCTION', 20014);

/**
 * Returned value from the push function - can handle the push.
 * @link https://www.php.net/manual/en/function.curl-multi-setopt.php
 * @since 7.1
 */
define('CURL_PUSH_OK', 0);

/**
 * Returned value from the push function - can't handle the push.
 * @link https://www.php.net/manual/en/function.curl-multi-setopt.php
 * @since 7.1
 */
define('CURL_PUSH_DENY', 1);

/**
 * The default buffer size for <b>CURLOPT_BUFFERSIZE</b>
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURL_MAX_READ_SIZE', 524288);

/**
 * Enables the use of an abstract Unix domain socket instead of establishing a TCP connection to a host and sets the path to the given string.
 * This option shares the same semantics as <b>CURLOPT_UNIX_SOCKET_PATH</b>.
 * These two options share the same storage and therefore only one of them can be set per handle.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_ABSTRACT_UNIX_SOCKET', 10264);

/**
 * Value for the <b>CURLOPT_SSLVERSION</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_SSLVERSION_MAX_DEFAULT', 65536);

/**
 * Value for the <b>CURLOPT_SSLVERSION</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_SSLVERSION_MAX_NONE', 0);

/**
 * Value for the <b>CURLOPT_SSLVERSION</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_SSLVERSION_MAX_TLSv1_0', 262144);

/**
 * Value for the <b>CURLOPT_SSLVERSION</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_SSLVERSION_MAX_TLSv1_1', 327680);

/**
 * Value for the <b>CURLOPT_SSLVERSION</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_SSLVERSION_MAX_TLSv1_2', 393216);

/**
 * Value for the <b>CURLOPT_SSLVERSION</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_SSLVERSION_MAX_TLSv1_3', 458752);

/**
 * <b>TRUE</b> to suppress proxy CONNECT response headers from the user callback functions
 * <b>CURLOPT_HEADERFUNCTION</b> and <b>CURLOPT_WRITEFUNCTION</b>,
 * when <b>CURLOPT_HTTPPROXYTUNNEL</b> is used and a <em>CONNECT</em> request is made.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_SUPPRESS_CONNECT_HEADERS', 265);

/**
 * Value for the <b>CURLOPT_HTTPAUTH</b> option.
 * Allows GSS-API authentication.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURLAUTH_GSSAPI', 4);

/**
 * The content-length of the download. This is the value read from the Content-Type: field. -1 if the size isn't known
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_CONTENT_LENGTH_DOWNLOAD_T', 6291471);

/**
 * The specified size of the upload. -1 if the size isn't known
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_CONTENT_LENGTH_UPLOAD_T', 6291472);

/**
 * Total number of bytes that were downloaded.
 * The number is only for the latest transfer and will be reset again for each new transfer
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_SIZE_DOWNLOAD_T', 6291464);

/**
 * Total number of bytes that were uploaded
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_SIZE_UPLOAD_T', 6291463);

/**
 * The average download speed in bytes/second that curl measured for the complete download
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_SPEED_DOWNLOAD_T', 6291465);

/**
 * The average upload speed in bytes/second that curl measured for the complete upload
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_SPEED_UPLOAD_T', 6291466);

/**
 * Specify an alternative target for this request
 * @link https://www.php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_REQUEST_TARGET.html
 * @since 7.3
 */
define('CURLOPT_REQUEST_TARGET', 10266);

/**
 * The SOCKS5 authentication method(s) to use. The options are: <b>CURLAUTH_BASIC</b>, <b>CURLAUTH_GSSAPI</b>, <b>CURLAUTH_NONE</b>.
 * The bitwise | (or) operator can be used to combine more than one method. If this is done,
 * cURL will poll the server to see what methods it supports and pick the best one.
 * <b>CURLAUTH_BASIC</b> allows username/password authentication.
 * <b>CURLAUTH_GSSAPI</b> allows GSS-API authentication.
 * <b>CURLAUTH_NONE</b> allows no authentication.
 * Defaults to <b>CURLAUTH_BASIC</b>|<b>CURLAUTH_GSSAPI</b>.
 * Set the actual username and password with the <b>CURLOPT_PROXYUSERPWD</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_SOCKS5_AUTH', 267);

/**
 * <b>TRUE</b> to enable built-in SSH compression. This is a request, not an order; the server may or may not do it.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_SSH_COMPRESSION', 268);

/**
 * libcurl was build with multiple ssh backends.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_MULTI_SSL', 4194304);

/**
 * Supports HTTP Brotli content encoding using libbrotlidec
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_BROTLI', 8388608);

/**
 * Value for the <b>CURLSHOPT_SHARE</b> option.
 * Put the connection cache in the share object and make all easy handles using this share object share the connection cache.
 * Using this, you can for example do multi-threaded libcurl use with one handle in each thread, and yet
 * have a shared pool of unused connections and this way get way better connection re-use
 * than if you use one separate pool in each thread.
 * Connections that are used for HTTP/1.1 Pipelining or HTTP/2 multiplexing only get additional transfers
 * added to them if the existing connection is held by the same multi or easy handle.
 * libcurl does not support doing HTTP/2 streams in different threads using a shared connection.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/curl_share_setopt.html
 * @since 7.3
 */
define('CURL_LOCK_DATA_CONNECT', 5);

/**
 * Value for the <b>CURLOPT_SSH_AUTH_TYPES</b> option.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURLSSH_AUTH_GSSAPI', 32);

/**
 * Remote time of the retrieved document (as Unix timestamp),
 * an alternative to <b>CURLINFO_FILETIME</b> to allow systems with 32 bit long variables to extract dates
 * outside of the 32bit timestamp range
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_FILETIME_T', 6291470);

/**
 * Head start for ipv6 for the happy eyeballs algorithm.
 * Happy eyeballs attempts to connect to both IPv4 and IPv6 addresses for dual-stack hosts,
 * preferring IPv6 first for timeout milliseconds.
 * Defaults to <b>CURL_HET_DEFAULT</b>, which is currently 200 milliseconds.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_HAPPY_EYEBALLS_TIMEOUT_MS', 271);

/**
 * The time in seconds since January 1st, 1970.
 * The time will be used by <b>CURLOPT_TIMECONDITION</b>. Defaults to zero.
 * The difference between this option and <b>CURLOPT_TIMEVALUE</b> is the type of the argument.
 * On systems where 'long' is only 32 bit wide, this option has to be used to set dates beyond the year 2038.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_TIMEVALUE_LARGE', 30270);

/**
 * <b>TRUE</b> to shuffle the order of all returned addresses so that they will be used in a random order,
 * when a name is resolved and more than one IP address is returned.
 * This may cause IPv4 to be used before IPv6 or vice versa.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_DNS_SHUFFLE_ADDRESSES', 275);

/**
 * <b>TRUE</b> to send an HAProxy PROXY protocol v1 header at the start of the connection.
 * The default action is not to send this header.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURLOPT_HAPROXYPROTOCOL', 274);

/**
 * Value for the <b>CURLSHOPT_SHARE</b> option.
 * The Public Suffix List stored in the share object is made available to all easy handle bound to the later.
 * Since the Public Suffix List is periodically refreshed, this avoids updates in too many different contexts.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/curl_share_setopt.html
 * @since 7.3
 */
define('CURL_LOCK_DATA_PSL', 6);

/**
 * Value for the <b>CURLOPT_HTTPAUTH</b> option.
 * HTTP Bearer token authentication, used primarily in OAuth 2.0 protocol.
 * @link https://php.net/manual/en/curl.constants.php
 * https://curl.haxx.se/libcurl/c/CURLOPT_HTTPAUTH.html
 * @since 7.3
 */
define('CURLAUTH_BEARER', 64);

/**
 * Time, in microseconds, it took from the start until the SSL/SSH connect/handshake to the remote host was completed
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_APPCONNECT_TIME_T', 6291512);

/**
 * Total time taken, in microseconds, from the start until the connection to the remote host (or proxy) was completed
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_CONNECT_TIME_T', 6291508);

/**
 * Time in microseconds from the start until the name resolving was completed
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_NAMELOOKUP_TIME_T', 6291507);

/**
 * Time taken from the start until the file transfer is just about to begin, in microseconds
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_PRETRANSFER_TIME_T', 6291509);

/**
 * Total time, in microseconds,
 * it took for all redirection steps include name lookup, connect, pretransfer and transfer before final transaction was started
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_REDIRECT_TIME_T', 6291511);

/**
 * Time, in microseconds, it took from the start until the first byte is received
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_STARTTRANSFER_TIME_T', 6291510);

/**
 * Total time in microseconds for the previous transfer, including name resolving, TCP connect etc.
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_TOTAL_TIME_T', 6291506);

/**
 * <b>TRUE</b> to not allow URLs that include a username. Usernames are allowed by default (0).
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_DISALLOW_USERNAME_IN_URL', 278);

/**
 * The list of cipher suites to use for the TLS 1.3 connection to a proxy.
 * The list must be syntactically correct, it consists of one or more cipher suite strings separated by colons.
 * This option is currently used only when curl is built to use OpenSSL 1.1.1 or later.
 * If you are using a different SSL backend you can try setting TLS 1.3 cipher suites by using the <b>CURLOPT_PROXY_SSL_CIPHER_LIST</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_TLS13_CIPHERS', 10277);

/**
 * The list of cipher suites to use for the TLS 1.3 connection.
 * The list must be syntactically correct, it consists of one or more cipher suite strings separated by colons.
 * This option is currently used only when curl is built to use OpenSSL 1.1.1 or later.
 * If you are using a different SSL backend you can try setting TLS 1.3 cipher suites by using the <b>CURLOPT_SSL_CIPHER_LIST</b> option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_TLS13_CIPHERS', 10276);

/**
 * Time allowed to wait for FTP response.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FTP_RESPONSE_TIMEOUT.html
 * @since 5.5
 */
define('CURLOPT_FTP_RESPONSE_TIMEOUT', 112);

/**
 * Provide a custom address for a specific host and port pair.
 * An array of hostname, port, and IP address strings, each element separated by a colon.
 * In the format: array("<em>example.com:80:127.0.0.1</em>")
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.5
 */
define('CURLOPT_RESOLVE', 10203);

/**
 * Enable appending to the remote file
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_APPEND.html
 * @since 5.5
 */
define('CURLOPT_APPEND', 50);

/**
 * Ask for names only in a directory listing
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_DIRLISTONLY.html
 * @since 5.5
 */
define('CURLOPT_DIRLISTONLY', 48);

/**
 * Permissions for remotely created directories
 * Pass a long as a parameter, containing the value of the permissions that will be assigned to newly created directories on the remote server.
 * The default value is 0755, but any valid value can be used.
 * The only protocols that can use this are <em>sftp://</em>, <em>scp://</em>, and <em>file://</em>.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_NEW_DIRECTORY_PERMS.html
 * @since 5.5
 */
define('CURLOPT_NEW_DIRECTORY_PERMS', 160);

/**
 * Permissions for remotely created files.
 * Pass a long as a parameter, containing the value of the permissions that will be assigned to newly created files on the remote server.
 * The default value is 0644, but any valid value can be used.
 * The only protocols that can use this are <em>sftp://</em>, <em>scp://</em>, and <em>file://</em>.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_NEW_FILE_PERMS.html
 * @since 5.5
 */
define('CURLOPT_NEW_FILE_PERMS', 159);

/**
 * <b>TRUE</b> to scan the ~/.netrc file to find a username and password for the remote site that a connection is being established with.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_NETRC_FILE.html
 * @since 5.5
 */
define('CURLOPT_NETRC_FILE', 10118);

/**
 * Commands to run before an FTP transfer
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PREQUOTE.html
 * @since 5.5
 */
define('CURLOPT_PREQUOTE', 10093);

/**
 * Set FTP kerberos security level
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_KRBLEVEL.html
 * @since 5.5
 */
define('CURLOPT_KRBLEVEL', 10063);

/**
 * Maximum file size allowed to download (in bytes)
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_MAXFILESIZE.html
 * @since 5.5
 */
define('CURLOPT_MAXFILESIZE', 114);

/**
 * Set account info for FTP
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FTP_ACCOUNT.html
 * @since 5.5
 */
define('CURLOPT_FTP_ACCOUNT', 10134);

/**
 * A cookie string (i.e. a single line in Netscape/Mozilla format, or a regular HTTP-style Set-Cookie header) adds that single cookie to the internal cookie store.
 * "<b>ALL</b>" erases all cookies held in memory.
 * "<b>SESS</b>" erases all session cookies held in memory.
 * "<b>FLUSH</b>" writes all known cookies to the file specified by CURLOPT_COOKIEJAR.
 * "<b>RELOAD</b>" loads all cookies from the files specified by CURLOPT_COOKIEFILE.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.5
 */
define('CURLOPT_COOKIELIST', 10135);

/**
 * Set local port number to use for socket
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_LOCALPORT.html
 * @since 5.5
 */
define('CURLOPT_LOCALPORT', 139);

/**
 * Number of additional local ports to try.
 * Pass a long. The range argument is the number of attempts libcurl will make to find a working local port number.
 * It starts with the given <b>CURLOPT_LOCALPORT</b> and adds one to the number for each retry.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_LOCALPORTRANGE.html
 * @since 5.5
 */
define('CURLOPT_LOCALPORTRANGE', 140);

/**
 * Command to use instead of USER with FTP.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FTP_ALTERNATIVE_TO_USER.html
 * @since 5.5
 */
define('CURLOPT_FTP_ALTERNATIVE_TO_USER', 10147);

/**
 * Enable/disable use of the SSL session-ID cache.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_SSL_SESSIONID_CACHE.html
 * @since 5.5
 */
define('CURLOPT_SSL_SESSIONID_CACHE', 150);

/**
 * Switch off SSL again with FTP after auth.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FTP_SSL_CCC.html
 * @since 5.5
 */
define('CURLOPT_FTP_SSL_CCC', 154);

/**
 * <b>FALSE</b> to get the raw HTTP response body.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.5
 */
define('CURLOPT_HTTP_CONTENT_DECODING', 158);

/**
 * Enable/disable HTTP transfer decoding.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_HTTP_TRANSFER_DECODING.html
 * @since 5.5
 */
define('CURLOPT_HTTP_TRANSFER_DECODING', 157);

/**
 * Append FTP transfer mode to URL for proxy.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROXY_TRANSFER_MODE.html
 * @since 5.5
 */
define('CURLOPT_PROXY_TRANSFER_MODE', 166);

/**
 * Set scope id for IPv6 addresses.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_ADDRESS_SCOPE.html
 * @since 5.5
 */
define('CURLOPT_ADDRESS_SCOPE', 171);

/**
 * Specify a Certificate Revocation List file.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_CRLFILE.html
 * @since 5.5
 */
define('CURLOPT_CRLFILE', 10169);

/**
 * Issuer SSL certificate filename.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_ISSUERCERT.html
 * @since 5.5
 */
define('CURLOPT_ISSUERCERT', 10170);

/**
 * The user name to use in authentication.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.5
 */
define('CURLOPT_USERNAME', 10173);

/**
 * Password to use in authentication.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PASSWORD.html
 * @since 5.5
 */
define('CURLOPT_PASSWORD', 10174);

/**
 * User name to use for proxy authentication.
 * @since 5.5
 */
define('CURLOPT_PROXYUSERNAME', 10175);

/**
 * Password to use for proxy authentication.
 * @since 5.5
 */
define('CURLOPT_PROXYPASSWORD', 10176);

/**
 * Disable proxy use for specific hosts.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_NOPROXY.html
 * @since 5.5
 */
define('CURLOPT_NOPROXY', 10177);

/**
 * Set socks proxy gssapi negotiation protection.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_SOCKS5_GSSAPI_NEC.html
 * @since 5.5
 */
define('CURLOPT_SOCKS5_GSSAPI_NEC', 180);

/**
 * SOCKS5 proxy authentication service name.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_SOCKS5_GSSAPI_SERVICE.html
 * @deprecated Use <b>CURLOPT_PROXY_SERVICE_NAME</b> instead.
 * @since 5.5
 */
define('CURLOPT_SOCKS5_GSSAPI_SERVICE', 10179);

/**
 * Specify blocksize to use for TFTP data transmission.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_TFTP_BLKSIZE.html
 * @since 5.5
 */
define('CURLOPT_TFTP_BLKSIZE', 178);

/**
 * File name holding the SSH known hosts.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_SSH_KNOWNHOSTS.html
 * @since 5.5
 */
define('CURLOPT_SSH_KNOWNHOSTS', 10183);

/**
 * Enable the PRET command.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FTP_USE_PRET.html
 * @since 5.5
 */
define('CURLOPT_FTP_USE_PRET', 188);

/**
 * SMTP sender address.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_MAIL_FROM.html
 * @since 5.5
 */
define('CURLOPT_MAIL_FROM', 10186);

/**
 * List of SMTP mail recipients.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_MAIL_RCPT.html
 * @since 5.5
 */
define('CURLOPT_MAIL_RCPT', 10187);

/**
 * Set the RTSP client CSEQ number.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_CLIENT_CSEQ.html
 * @since 5.5
 */
define('CURLOPT_RTSP_CLIENT_CSEQ', 193);

/**
 * Set the RTSP server CSEQ number.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_SERVER_CSEQ.html
 * @since 5.5
 */
define('CURLOPT_RTSP_SERVER_CSEQ', 194);

/**
 * Set RTSP session ID.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_SESSION_ID.html
 * @since 5.5
 */
define('CURLOPT_RTSP_SESSION_ID', 10190);

/**
 * Set RTSP stream URI.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_STREAM_URI.html
 * @since 5.5
 */
define('CURLOPT_RTSP_STREAM_URI', 10191);

/**
 * Set RTSP Transport: header.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_TRANSPORT.html
 * @since 5.5
 */
define('CURLOPT_RTSP_TRANSPORT', 10192);

/**
 * Specify RTSP request.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_REQUEST.html
 * @since 5.5
 */
define('CURLOPT_RTSP_REQUEST', 189);

/**
 * Ignore content length.
 * If <b>TRUE</b>, ignore the Content-Length header in the HTTP response and ignore asking for or relying on it for FTP transfers.
 * This is useful for HTTP with Apache 1.x (and similar servers) which will report incorrect content length for files over 2 gigabytes.
 * If this option is used, curl will not be able to accurately report progress, and will simply stop the download when the server ends the connection.
 * It is also useful with FTP when for example the file is growing while the transfer is in progress
 * which otherwise will unconditionally cause libcurl to report error.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_IGNORE_CONTENT_LENGTH.html
 * @since 5.5
 */
define('CURLOPT_IGNORE_CONTENT_LENGTH', 136);
/**
 * Enables automatic decompression of HTTP downloads
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_ACCEPT_ENCODING.html
 * @since 5.5
 */
define('CURLOPT_ACCEPT_ENCODING', 10102);

/**
 * Ask for HTTP Transfer Encoding.
 * Adds a request for compressed Transfer Encoding in the outgoing HTTP request.
 * If the server supports this and so desires, it can respond with the HTTP response sent using a compressed Transfer-Encoding
 * that will be automatically uncompressed by libcurl on reception.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_TRANSFER_ENCODING.html
 * @since 5.5
 */
define('CURLOPT_TRANSFER_ENCODING', 207);

/**
 * Set preferred DNS servers: <em>host[:port][,host[:port]]...</em>
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_DNS_SERVERS.html
 * @since 5.5
 */
define('CURLOPT_DNS_SERVERS', 10211);

/**
 * Request using SSL / TLS for the transfer
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_USE_SSL.html
 * @since 5.5
 */
define('CURLOPT_USE_SSL', 119);
/**
 * Custom telnet options
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_TELNETOPTIONS.html
 */
define("CURLOPT_TELNETOPTIONS", 10070);
/**
 * The download could not be resumed because the specified offset was out of the file boundary.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define("CURLE_BAD_DOWNLOAD_RESUME", 36);
/**
 * A file transfer was shorter or larger than expected.
 * This happens when the server first reports an expected transfer size, and then delivers data
 * that doesn't match the previously given size.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define("CURLE_FTP_PARTIAL_FILE", 18);
/**
 * This is returned if <b>CURLOPT_FAILONERROR</b> is set <b>TRUE</b> and the HTTP server returns an error code that is >= 400.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define("CURLE_HTTP_RETURNED_ERROR", 22);
/**
 * Operation timeout. The specified time-out period was reached according to the conditions.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define("CURLE_OPERATION_TIMEDOUT", 28);
/**
 * Failed to match the pinned key specified with <b>CURLOPT_PINNEDPUBLICKEY</b>.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define("CURLE_SSL_PINNEDPUBKEYNOTMATCH", 90);
/**
 * @link https://php.net/manual/en/curl.constants.php
 */
define("CURLINFO_LASTONE", 60);
/**
 * An easy handle already added to a multi handle was attempted to get added a second time.
 * @link https://www.php.net/manual/en/function.curl-multi-exec.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define("CURLM_ADDED_ALREADY", 7);
/**
 * @link https://curl.haxx.se/libcurl/c/symbols-in-versions.html
 */
define("CURLSHOPT_NONE", 0);
/**
 * Default value for the <b>CURLOPT_TIMECONDITION</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_TIMECONDITION.html
 */
define("CURL_TIMECOND_NONE", 0);
/**
 * Value for the <b>CURLOPT_HTTPAUTH</b> option.
 * Allows no authentication.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 */
define("CURLAUTH_NONE", 0);
/**
 * Problem with reading the SSL CA cert (path? access rights?)
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define("CURLE_SSL_CACERT_BADFILE", 77);
/**
 * An unspecified error occurred during the SSH session.
 * @link https://php.net/manual/en/curl.constants.php
 * @link https://curl.haxx.se/libcurl/c/libcurl-errors.html
 */
define("CURLE_SSH", 79);
/**
 * Value for the <b>CURLOPT_FTP_SSL_CCC</b> option.
 * Initiate the shutdown and wait for a reply.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FTP_SSL_CCC.html
 */
define("CURLFTPSSL_CCC_ACTIVE", 2);
/**
 * Value for the <b>CURLOPT_FTP_SSL_CCC</b> option.
 * Don't attempt to use CCC.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FTP_SSL_CCC.html
 */
define("CURLFTPSSL_CCC_NONE", 0);
/**
 * Value for the <b>CURLOPT_FTP_SSL_CCC</b> option.
 * Do not initiate the shutdown, but wait for the server to do it. Do not send a reply.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FTP_SSL_CCC.html
 */
define("CURLFTPSSL_CCC_PASSIVE", 1);
/**
 * Value for the <b>CURLOPT_USE_SSL</b> option.
 * Require SSL for all communication or fail.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_USE_SSL.html
 */
define("CURLUSESSL_ALL", 3);
/**
 * Value for the <b>CURLOPT_USE_SSL</b> option.
 * Require SSL for the control connection or fail.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_USE_SSL.html
 */
define("CURLUSESSL_CONTROL", 2);
/**
 * Value for the <b>CURLOPT_USE_SSL</b> option.
 * Don't attempt to use SSL.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_USE_SSL.html
 */
define("CURLUSESSL_NONE", 0);
/**
 * Value for the <b>CURLOPT_USE_SSL</b> option.
 * Try using SSL, proceed as normal otherwise.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_USE_SSL.html
 */
define("CURLUSESSL_TRY", 1);
/**
 * Convenience define that pauses both directions.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 5.5
 */
define("CURLPAUSE_ALL", 5);
/**
 * Convenience define that unpauses both directions.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 5.5
 */
define("CURLPAUSE_CONT", 0);
/**
 * Pause receiving data. There will be no data received on this connection until this function is called again without this bit set.
 * Thus, the write callback (<b>CURLOPT_WRITEFUNCTION</b>) won't be called.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 5.5
 */
define("CURLPAUSE_RECV", 1);
/**
 * @link https://php.net/manual/en/curl.constants.php
 * @since 5.5
 */
define("CURLPAUSE_RECV_CONT", 0);
/**
 * Pause sending data. There will be no data sent on this connection until this function is called again without this bit set.
 * Thus, the read callback (CURLOPT_READFUNCTION) won't be called.
 * @link https://php.net/manual/en/curl.constants.php
 * @since 5.5
 */
define("CURLPAUSE_SEND", 4);
/**
 * @link https://php.net/manual/en/curl.constants.php
 * @since 5.5
 */
define("CURLPAUSE_SEND_CONT", 0);
/**
 * Read callback for data uploads.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_READFUNCTION.html
 */
define("CURL_READFUNC_PAUSE", 268435457);
/**
 * Set callback for writing received data.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_WRITEFUNCTION.html
 */
define("CURL_WRITEFUNC_PAUSE", 268435457);
/**
 * Value for the <b>CURLOPT_PROXYTYPE</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 5.5.23
 */
define("CURLPROXY_SOCKS4A", 6);
/**
 * Value for the <b>CURLOPT_PROXYTYPE</b> option.
 * Proxy resolves URL hostname.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 5.5.23
 */
define("CURLPROXY_SOCKS5_HOSTNAME", 7);
/**
 * Value for the <b>CURLOPT_SSH_AUTH_TYPES</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define("CURLSSH_AUTH_ANY", -1);
/**
 * Value for the <b>CURLOPT_SSH_AUTH_TYPES</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define("CURLSSH_AUTH_DEFAULT", -1);
/**
 * Value for the <b>CURLOPT_SSH_AUTH_TYPES</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define("CURLSSH_AUTH_HOST", 4);
/**
 * Value for the <b>CURLOPT_SSH_AUTH_TYPES</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define("CURLSSH_AUTH_KEYBOARD", 8);
/**
 * Value for the <b>CURLOPT_SSH_AUTH_TYPES</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define("CURLSSH_AUTH_NONE", 0);
/**
 * Value for the <b>CURLOPT_SSH_AUTH_TYPES</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define("CURLSSH_AUTH_PASSWORD", 2);
/**
 * Value for the <b>CURLOPT_SSH_AUTH_TYPES</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 */
define("CURLSSH_AUTH_PUBLICKEY", 1);
/**
 * Value for the <b>CURLOPT_HTTPAUTH</b> option.
 * HTTP Digest authentication with an IE flavor.
 * Digest authentication is defined in <em>RFC 2617</em> and is a more secure way to do authentication over public networks than
 * the regular old-fashioned Basic method.
 * The IE flavor is simply that libcurl will use a special "quirk" that IE is known to have used before version 7
 * and that some servers require the client to use.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_HTTPAUTH.html
 */
define("CURLAUTH_DIGEST_IE", 16);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROTOCOLS.html
 */
define("CURLPROTO_IMAP", 4096);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROTOCOLS.html
 */
define("CURLPROTO_IMAPS", 8192);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROTOCOLS.html
 */
define("CURLPROTO_POP3", 16384);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROTOCOLS.html
 */
define("CURLPROTO_POP3S", 32768);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROTOCOLS.html
 */
define("CURLPROTO_RTSP", 262144);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROTOCOLS.html
 */
define("CURLPROTO_SMTP", 65536);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROTOCOLS.html
 */
define("CURLPROTO_SMTPS", 131072);
/**
 * Value for the <b>CURLOPT_RTSP_REQUEST</b> option.
 * When sent by a client, this method changes the description of the session.
 * For example, if a client is using the server to record a meeting,
 * the client can use Announce to inform the server of all the meta-information about the session.
 * <b>ANNOUNCE</b> acts like an HTTP PUT or POST
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_REQUEST.html
 */
define("CURL_RTSPREQ_ANNOUNCE", 3);
/**
 * Value for the <b>CURLOPT_RTSP_REQUEST</b> option.
 * Used to get the low level description of a stream.
 * The application should note what formats it understands in the '<em>Accept:</em>' header.
 * Unless set manually, libcurl will automatically fill in '<em>Accept: application/sdp</em>'.
 * Time-condition headers will be added to Describe requests if the <b>CURLOPT_TIMECONDITION</b> option is active.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_REQUEST.html
 */
define("CURL_RTSPREQ_DESCRIBE", 2);
/**
 * Value for the <b>CURLOPT_RTSP_REQUEST</b> option.
 * Retrieve a parameter from the server.
 * By default, libcurl will automatically include a <em>Content-Type: text/parameters</em> header on all non-empty requests
 * unless a custom one is set. <b>GET_PARAMETER</b> acts just like an HTTP PUT or POST
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_REQUEST.html
 */
define("CURL_RTSPREQ_GET_PARAMETER", 8);
/**
 * Value for the <b>CURLOPT_RTSP_REQUEST</b> option.
 * Used to retrieve the available methods of the server.
 * The application is responsible for parsing and obeying the response.
 * The session ID is not needed for this method.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_REQUEST.html
 */
define("CURL_RTSPREQ_OPTIONS", 1);
/**
 * Value for the <b>CURLOPT_RTSP_REQUEST</b> option.
 * Send a Pause command to the server.
 * Use the <b>CURLOPT_RANGE</b> option with a single value to indicate when the stream should be halted. (e.g. npt='25')
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_REQUEST.html
 */
define("CURL_RTSPREQ_PAUSE", 6);
/**
 * Value for the <b>CURLOPT_RTSP_REQUEST</b> option.
 * Send a Play command to the server.
 * Use the <b>CURLOPT_RANGE</b> option to modify the playback time (e.g. 'npt=10-15').
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_REQUEST.html
 */
define("CURL_RTSPREQ_PLAY", 5);
/**
 * Value for the <b>CURLOPT_RTSP_REQUEST</b> option.
 * This is a special request because it does not send any data to the server.
 * The application may call this function in order to receive interleaved RTP data.
 * It will return after processing one read buffer of data in order to give the application a chance to run.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_REQUEST.html
 */
define("CURL_RTSPREQ_RECEIVE", 11);
/**
 * Value for the <b>CURLOPT_RTSP_REQUEST</b> option.
 * Used to tell the server to record a session. Use the <b>CURLOPT_RANGE</b> option to modify the record time.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_REQUEST.html
 */
define("CURL_RTSPREQ_RECORD", 10);
/**
 * Value for the <b>CURLOPT_RTSP_REQUEST</b> option.
 * Set a parameter on the server.
 * By default, libcurl will automatically include a <em>Content-Type: text/parameters</em> header unless a custom one is set.
 * The interaction with SET_PARAMETER is much like an HTTP PUT or POST.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_REQUEST.html
 */
define("CURL_RTSPREQ_SET_PARAMETER", 9);
/**
 * Value for the <b>CURLOPT_RTSP_REQUEST</b> option.
 * Setup is used to initialize the transport layer for the session.
 * The application must set the desired Transport options for a session
 * by using the <b>CURLOPT_RTSP_TRANSPORT</b> option prior to calling setup.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_REQUEST.html
 */
define("CURL_RTSPREQ_SETUP", 4);
/**
 * Value for the <b>CURLOPT_RTSP_REQUEST</b> option.
 * This command terminates an RTSP session.
 * Simply closing a connection does not terminate the RTSP session since it is valid to control an RTSP session over different connections.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_RTSP_REQUEST.html
 */
define("CURL_RTSPREQ_TEARDOWN", 7);
/**
 * Wildcard matching function callback.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FNMATCH_FUNCTION.html
 */
define("CURLOPT_FNMATCH_FUNCTION", 20200);
/**
 * Enable directory wildcard transfers.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_WILDCARDMATCH.html
 */
define("CURLOPT_WILDCARDMATCH", 197);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROTOCOLS.html
 */
define("CURLPROTO_RTMP", 524288);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROTOCOLS.html
 */
define("CURLPROTO_RTMPE", 2097152);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROTOCOLS.html
 */
define("CURLPROTO_RTMPS", 8388608);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROTOCOLS.html
 */
define("CURLPROTO_RTMPT", 1048576);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROTOCOLS.html
 */
define("CURLPROTO_RTMPTE", 4194304);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROTOCOLS.html
 */
define("CURLPROTO_RTMPTS", 16777216);
/**
 * Return value for the <b>CURLOPT_FNMATCH_FUNCTION</b> if an error was occurred.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FNMATCH_FUNCTION.html
 */
define("CURL_FNMATCHFUNC_FAIL", 2);
/**
 * Return value for the <b>CURLOPT_FNMATCH_FUNCTION</b> if pattern matches the string.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FNMATCH_FUNCTION.html
 */
define("CURL_FNMATCHFUNC_MATCH", 0);
/**
 * Return value for the <b>CURLOPT_FNMATCH_FUNCTION</b> if pattern not matches the string.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_FNMATCH_FUNCTION.html
 */
define("CURL_FNMATCHFUNC_NOMATCH", 1);
/**
 * Value for the <b>CURLOPT_PROTOCOLS</b> option.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_PROTOCOLS.html
 */
define("CURLPROTO_GOPHER", 33554432);
/**
 * Value for the <b>CURLOPT_HTTPAUTH</b> option.
 * This is a meta symbol.
 * OR this value together with a single specific auth value to force libcurl to probe for un-restricted auth and if not,
 * only that single auth algorithm is acceptable.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_HTTPAUTH.html
 */
define("CURLAUTH_ONLY", 2147483648);
/**
 * Password to use for TLS authentication.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_TLSAUTH_PASSWORD.html
 */
define("CURLOPT_TLSAUTH_PASSWORD", 10205);
/**
 * Set TLS authentication methods.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_TLSAUTH_TYPE.html
 */
define("CURLOPT_TLSAUTH_TYPE", 10206);
/**
 * User name to use for TLS authentication.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_TLSAUTH_USERNAME.html
 */
define("CURLOPT_TLSAUTH_USERNAME", 10204);
/**
 * Value for the <b>CURLOPT_TLSAUTH_TYPE</b> option.
 * TLS-SRP authentication.
 * Secure Remote Password authentication for TLS is defined in RFC 5054 and provides mutual authentication if both sides have a shared secret.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_TLSAUTH_TYPE.html
 */
define("CURL_TLSAUTH_SRP", 1);
/**
 * Value for the <b>CURLOPT_GSSAPI_DELEGATION</b> option.
 * Allow unconditional GSSAPI credential delegation.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_GSSAPI_DELEGATION.html
 */
define("CURLGSSAPI_DELEGATION_FLAG", 2);
/**
 * Value for the <b>CURLOPT_GSSAPI_DELEGATION</b> option.
 * Delegate only if the OK-AS-DELEGATE flag is set in the service ticket
 * in case this feature is supported by the GSS-API implementation.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_GSSAPI_DELEGATION.html
 */
define("CURLGSSAPI_DELEGATION_POLICY_FLAG", 1);
/**
 * Set allowed GSS-API delegation.
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_GSSAPI_DELEGATION.html
 */
define("CURLOPT_GSSAPI_DELEGATION", 210);
/**
 * Timeout waiting for FTP server to connect back
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_ACCEPTTIMEOUT_MS.html
 */
define("CURLOPT_ACCEPTTIMEOUT_MS", 212);
/**
 * SMTP authentication address
 * @link https://curl.haxx.se/libcurl/c/CURLOPT_MAIL_AUTH.html
 */
define("CURLOPT_MAIL_AUTH", 10217);
/**
 * Set SSL behavior options, which is a bitmask of any of the following constants:
 *  <b>CURLSSLOPT_ALLOW_BEAST</b>: do not attempt to use any workarounds for a security flaw in the SSL3 and TLS1.0 protocols.
 *  <b>CURLSSLOPT_NO_REVOKE</b>: disable certificate revocation checks for those SSL backends where such behavior is present.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.6
 */
define("CURLOPT_SSL_OPTIONS", 216);
/**
 * If set to 1, TCP keepalive probes will be sent.
 * The delay and frequency of these probes can be controlled by the <b>CURLOPT_TCP_KEEPIDLE</b> and <b>CURLOPT_TCP_KEEPINTVL</b> options,
 * provided the operating system supports them.
 * If set to 0 (default) keepalive probes are disabled.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.5
 */
define("CURLOPT_TCP_KEEPALIVE", 213);
/**
 * Sets the delay, in seconds, that the operating system will wait while the connection is idle before sending keepalive probes,
 * if <b>CURLOPT_TCP_KEEPALIVE</b> is enabled. Not all operating systems support this option. The default is 60.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.5
 */
define("CURLOPT_TCP_KEEPIDLE", 214);
/**
 * Sets the interval, in seconds, that the operating system will wait between sending keepalive probes,
 * if <b>CURLOPT_TCP_KEEPALIVE</b> is enabled. Not all operating systems support this option. The default is 60.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.5
 */
define("CURLOPT_TCP_KEEPINTVL", 215);
/**
 * Value for the <b>CURLOPT_SSL_OPTIONS</b> option.
 * Do not attempt to use any workarounds for a security flaw in the SSL3 and TLS1.0 protocols.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 5.6
 */
define("CURLSSLOPT_ALLOW_BEAST", 1);
/**
 * Supports HTTP2.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 5.5.24
 */
define("CURL_VERSION_HTTP2", 65536);
/**
 * Value for the <b>CURLOPT_SSL_OPTIONS</b> option.
 * Disable certificate revocation checks for those SSL backends where such behavior is present.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define("CURLSSLOPT_NO_REVOKE", 2);
/**
 * The default protocol to use if the URL is missing a scheme name.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define("CURLOPT_DEFAULT_PROTOCOL", 10238);
/**
 * Set the numerical stream weight (a number between 1 and 256).
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define("CURLOPT_STREAM_WEIGHT", 239);
/**
 * <b>TRUE</b> to not send TFTP options requests.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define("CURLOPT_TFTP_NO_OPTIONS", 242);
/**
 * Connect to a specific host and port instead of the URL's host and port.
 * Accepts an array of strings with the format HOST:PORT:CONNECT-TO-HOST:CONNECT-TO-PORT.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define("CURLOPT_CONNECT_TO", 10243);
/**
 * <b>TRUE</b> to enable TCP Fast Open.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.0.7
 */
define("CURLOPT_TCP_FASTOPEN", 244);

/**
 * The server sent data libcurl couldn't parse.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURLE_WEIRD_SERVER_REPLY', 8);
/**
 * <b>TRUE</b> to keep sending the request body if the HTTP code returned is equal to or larger than 300.
 * The default action would be to stop sending and close the stream or connection. Suitable for manual NTLM authentication.
 * Most applications do not need this option.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_KEEP_SENDING_ON_ERROR', 245);
/**
 * Value for the <b>CURLOPT_SSLVERSION</b> option.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_SSLVERSION_TLSv1_3', 7);

/**
 * Supports HTTPS proxy.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_HTTPS_PROXY', 2097152);

/**
 * The protocol used in the last HTTP connection. The returned value will be exactly one of the <b>CURLPROTO_*</b> values
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_PROTOCOL', 2097200);

/**
 * Supports asynchronous name lookups.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_ASYNCHDNS', 128);

/**
 * Supports memory tracking debug capabilities.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3.6
 */
define('CURL_VERSION_CURLDEBUG', 8192);

/**
 * Supports character conversions.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_CONV', 4096);

/**
 * libcurl was built with debug capabilities
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_DEBUG', 64);

/**
 * Supports HTTP GSS-Negotiate.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_GSSNEGOTIATE', 32);

/**
 * Supports the IDNA.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_IDN', 1024);

/**
 * Supports large files.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_LARGEFILE', 512);

/**
 * Supports HTTP NTLM.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_NTLM', 16);

/**
 * Supports the Mozilla's Public Suffix List.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURL_VERSION_PSL', 1048576);

/**
 * Supports for SPNEGO authentication (RFC 2478).
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_SPNEGO', 256);

/**
 * Supports SSPI. Windows-specific.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_SSPI', 2048);

/**
 * Supports the TLS-SRP.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_TLSAUTH_SRP', 16384);

/**
 * Supports the NTLM delegation to a winbind helper.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_NTLM_WB', 32768);

/**
 * Supports the GSSAPI. This makes libcurl use provided functions for Kerberos and SPNEGO authentication.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_GSSAPI', 131072);

/**
 * Supports Kerberos V5 authentication for FTP, IMAP, POP3, SMTP and SOCKSv5 proxy.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURL_VERSION_KERBEROS5', 262144);

/**
 * The path to proxy Certificate Authority (CA) bundle.
 * Set the path as a string naming a file holding one or more certificates to verify the HTTPS proxy with.
 * This option is for connecting to an HTTPS proxy, not an HTTPS server.
 * Defaults set to the system path where libcurl's cacert bundle is assumed to be stored.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_CAINFO', 10246);

/**
 * The directory holding multiple CA certificates to verify the HTTPS proxy with.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_CAPATH', 10247);

/**
 * Set the file name with the concatenation of CRL (Certificate Revocation List) in PEM format
 * to use in the certificate validation that occurs during the SSL exchange.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_CRLFILE', 10260);

/**
 * Set the string be used as the password required to use the <b>CURLOPT_PROXY_SSLKEY</b> private key.
 * You never needed a passphrase to load a certificate but you need one to load your private key.
 * This option is for connecting to an HTTPS proxy, not an HTTPS server.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_KEYPASSWD', 10258);

/**
 * The format of your client certificate used when connecting to an HTTPS proxy. Supported formats are "PEM" and "DER", except with Secure Transport.
 * OpenSSL (versions 0.9.3 and later) and Secure Transport (on iOS 5 or later, or OS X 10.7 or later) also support "P12" for PKCS#12-encoded files.
 * Defaults to "PEM".
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_SSLCERTTYPE', 10255);

/**
 * The format of your private key. Supported formats are "PEM", "DER" and "ENG".
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_SSLKEYTYPE', 10257);

/**
 * One of <b>CURL_SSLVERSION_DEFAULT</b>, <b>CURL_SSLVERSION_TLSv1</b>, <b>CURL_SSLVERSION_TLSv1_0</b>, <b>CURL_SSLVERSION_TLSv1_1</b>, <b>CURL_SSLVERSION_TLSv1_2</b>,
 * <b>CURL_SSLVERSION_TLSv1_3</b>, <b>CURL_SSLVERSION_MAX_DEFAULT</b>, <b>CURL_SSLVERSION_MAX_TLSv1_0</b>, <b>CURL_SSLVERSION_MAX_TLSv1_1</b>,
 * <b>CURL_SSLVERSION_MAX_TLSv1_2</b>, <b>CURL_SSLVERSION_MAX_TLSv1_3</b> or <b>CURL_SSLVERSION_SSLv3</b>.
 * See also <b>CURLOPT_SSLVERSION</b>.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_SSLVERSION', 250);

/**
 * Tusername to use for the HTTPS proxy TLS authentication method specified with the <b>CURLOPT_PROXY_TLSAUTH_TYPE</b> option.
 * Requires that the <b>CURLOPT_PROXY_TLSAUTH_PASSWORD</b> option to also be set.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_TLSAUTH_USERNAME', 10251);
/**
 * The password to use for the TLS authentication method specified with the <b>CURLOPT_PROXY_TLSAUTH_TYPE</b> option.
 * Requires that the <b>CURLOPT_PROXY_TLSAUTH_USERNAME</b> option to also be set.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_TLSAUTH_PASSWORD', 10252);

/**
 * The method of the TLS authentication used for the HTTPS connection. Supported method is "SRP".
 * Secure Remote Password (SRP) authentication for TLS provides mutual authentication if both sides have a shared secret.
 * To use TLS-SRP, you must also set the <b>CURLOPT_PROXY_TLSAUTH_USERNAME</b> and <b>CURLOPT_PROXY_TLSAUTH_PASSWORD</b> options.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_TLSAUTH_TYPE', 10253);

/**
 * Value for the <b>CURLOPT_PROXYTYPE</b> option.
 * Use HTTPS Proxy.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3
 */
define('CURLPROXY_HTTPS', 2);

/**
 * Set the pinned public key for HTTPS proxy. The string can be the file name of your pinned public key. The file format expected is "PEM" or "DER".
 * The string can also be any number of base64 encoded sha256 hashes preceded by "sha256//" and separated by ";"
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_PINNEDPUBLICKEY', 10263);

/**
 * The file name of your private key used for connecting to the HTTPS proxy.
 * The default format is "PEM" and can be changed with <b>CURLOPT_PROXY_SSLKEYTYPE</b>.
 * (iOS and Mac OS X only) This option is ignored if curl was built against Secure Transport.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_SSLKEY', 10256);

/**
 * The list of ciphers to use for the connection to the HTTPS proxy.
 * The list must be syntactically correct, it consists of one or more cipher strings separated by colons.
 * Commas or spaces are also acceptable separators but colons are normally used, !, - and + can be used as operators.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_SSL_CIPHER_LIST', 10259);

/**
 * Set proxy SSL behavior options, which is a bitmask of any of the following constants:
 *  <b>CURLSSLOPT_ALLOW_BEAST</b>: do not attempt to use any workarounds for a security flaw in the SSL3 and TLS1.0 protocols.
 *  <b>CURLSSLOPT_NO_REVOKE</b>: disable certificate revocation checks for those SSL backends where such behavior is present. (curl >= 7.44.0)
 *  <b>CURLSSLOPT_NO_PARTIALCHAIN</b>: do not accept "partial" certificate chains, which it otherwise does by default. (curl >= 7.68.0)
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_SSL_OPTIONS', 261);

/**
 * Set to 2 to verify in the HTTPS proxy's certificate name fields against the proxy name.
 * When set to 0 the connection succeeds regardless of the names used in the certificate.
 * Use that ability with caution! 1 treated as a debug option in curl 7.28.0 and earlier.
 * From curl 7.28.1 to 7.65.3 CURLE_BAD_FUNCTION_ARGUMENT is returned.
 * From curl 7.66.0 onwards 1 and 2 is treated as the same value.
 * In production environments the value of this option should be kept at 2 (default value).
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_SSL_VERIFYHOST', 249);

/**
 * <b>FALSE</b> to stop cURL from verifying the peer's certificate.
 * Alternate certificates to verify against can be specified with the <b>CURLOPT_CAINFO</b> option or
 * a certificate directory can be specified with the <b>CURLOPT_CAPATH</b> option.
 * When set to false, the peer certificate verification succeeds regardless.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_SSL_VERIFYPEER', 248);

/**
 * The file name of your client certificate used to connect to the HTTPS proxy.
 * The default format is "P12" on Secure Transport and "PEM" on other engines, and can be changed with <b>CURLOPT_PROXY_SSLCERTTYPE</b>.
 * With NSS or Secure Transport, this can also be the nickname of the certificate you wish to authenticate with as it is named in the security database.
 * If you want to use a file from the current directory, please precede it with "./" prefix, in order to avoid confusion with a nickname.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PROXY_SSLCERT', 10254);

/**
 * The URL scheme used for the most recent connection
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_SCHEME', 1048625);

/**
 * Supports UNIX sockets.
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.0.7
 */
define('CURL_VERSION_UNIX_SOCKETS', 524288);

/**
 * The version used in the last HTTP connection. The return value will be one of the defined
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_HTTP_VERSION', 2097198);
/**
 * Set a string holding the host name or dotted numerical IP address to be used as the preproxy that curl connects to before
 * it connects to the HTTP(S) proxy specified in the <b>CURLOPT_PROXY</b> option for the upcoming request.
 * The preproxy can only be a SOCKS proxy and it should be prefixed with [scheme]:// to specify which kind of socks is used.
 * A numerical IPv6 address must be written within [brackets]. Setting the preproxy to an empty string explicitly disables the use of a preproxy.
 * To specify port number in this string, append :[port] to the end of the host name.
 * The proxy's port number may optionally be specified with the separate option <b>CURLOPT_PROXYPORT</b>.
 * Defaults to using port 1080 for proxies if a port is not specified.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_PRE_PROXY', 10262);
/**
 * The result of the certificate verification that was requested (using the <b>CURLOPT_PROXY_SSL_VERIFYPEER</b> option).
 * Only used for HTTPS proxies
 * @link https://www.php.net/manual/en/function.curl-getinfo.php
 * @since 7.3
 */
define('CURLINFO_PROXY_SSL_VERIFYRESULT', 2097199);
/**
 * Whether to allow HTTP/0.9 responses.
 * Defaults to <b>FALSE</b> as of libcurl 7.66.0; formerly it defaulted to <b>TRUE</b>.
 * @link https://www.php.net/manual/en/function.curl-setopt.php
 * @since 7.3
 */
define('CURLOPT_HTTP09_ALLOWED', 285);

/**
 * @link https://www.php.net/manual/en/curl.constants.php
 * @since 7.3.6
 */
define('CURL_VERSION_ALTSVC', 16777216);

/**
 * @since 8.1
 */
define('CURLOPT_DOH_URL', 10279);

/**
 * @since 8.1
 */
define('CURLOPT_ISSUERCERT_BLOB', 40295);

/**
 * @since 8.1
 */
define('CURLOPT_PROXY_ISSUERCERT', 10296);

/**
 * @since 8.1
 */
define('CURLOPT_PROXY_ISSUERCERT_BLOB', 40297);

/**
 * @since 8.1
 */
define('CURLOPT_PROXY_SSLCERT_BLOB', 40293);

/**
 * @since 8.1
 */
define('CURLOPT_PROXY_SSLKEY_BLOB', 40294);

/**
 * @since 8.1
 */
define('CURLOPT_SSLCERT_BLOB', 40291);

/**
 * @since 8.1
 */
define('CURLOPT_SSLKEY_BLOB', 40292);

/**
 * @since 8.2
 */
define('CURLOPT_XFERINFOFUNCTION', 20219);

/**
 * @since 8.2
 */
define('CURLINFO_EFFECTIVE_METHOD', 1048634);

/**
 * @since 8.2
 */
define('CURLOPT_MAXFILESIZE_LARGE', 30117);

/**
 * @since 8.2
 */
define('CURLFTPMETHOD_DEFAULT', 0);

/**
 * @since 8.2
 */
define('CURLOPT_UPKEEP_INTERVAL_MS', 281);

/**
 * @since 8.2
 */
define('CURLOPT_UPLOAD_BUFFERSIZE', 280);

/**
 * @since 8.2
 */
define('CURLALTSVC_H1', 8);

/**
 * @since 8.2
 */
define('CURLALTSVC_H2', 16);

/**
 * @since 8.2
 */
define('CURLALTSVC_H3', 32);

/**
 * @since 8.2
 */
define('CURLALTSVC_READONLYFILE', 4);

/**
 * @since 8.2
 */
define('CURLOPT_ALTSVC', 10287);

/**
 * @since 8.2
 */
define('CURLOPT_ALTSVC_CTRL', 286);

/**
 * @since 8.2
 */
define('CURLOPT_MAXAGE_CONN', 288);

/**
 * @since 8.2
 */
define('CURLOPT_SASL_AUTHZID', 10289);

/**
 * @since 8.2
 */
define('CURL_VERSION_HTTP3', 33554432);

/**
 * @since 8.2
 */
define('CURLINFO_RETRY_AFTER', 6291513);

/**
 * @since 8.2
 */
define('CURLMOPT_MAX_CONCURRENT_STREAMS', 16);

/**
 * @since 8.2
 */
define('CURLSSLOPT_NO_PARTIALCHAIN', 4);

/**
 * @since 8.2
 */
define('CURLOPT_MAIL_RCPT_ALLLOWFAILS', 290);

/**
 * @since 8.2
 */
define('CURLSSLOPT_REVOKE_BEST_EFFORT', 8);

/**
 * @since 8.2
 */
define('CURLPROTO_MQTT', 268435456);

/**
 * @since 8.2
 */
define('CURLSSLOPT_NATIVE_CA', 16);

/**
 * @since 8.2
 */
define('CURL_VERSION_UNICODE', 134217728);

/**
 * @since 8.2
 */
define('CURL_VERSION_ZSTD', 67108864);

/**
 * @since 8.2
 */
define('CURLE_PROXY', 97);

/**
 * @since 8.2
 */
define('CURLINFO_PROXY_ERROR', 2097211);

/**
 * @since 8.2
 */
define('CURLOPT_SSL_EC_CURVES', 10298);

/**
 * @since 8.2
 */
define('CURLPX_BAD_ADDRESS_TYPE', 1);

/**
 * @since 8.2
 */
define('CURLPX_BAD_VERSION', 2);

/**
 * @since 8.2
 */
define('CURLPX_CLOSED', 3);

/**
 * @since 8.2
 */
define('CURLPX_GSSAPI', 4);

/**
 * @since 8.2
 */
define('CURLPX_GSSAPI_PERMSG', 5);

/**
 * @since 8.2
 */
define('CURLPX_GSSAPI_PROTECTION', 6);

/**
 * @since 8.2
 */
define('CURLPX_IDENTD', 7);

/**
 * @since 8.2
 */
define('CURLPX_IDENTD_DIFFER', 8);

/**
 * @since 8.2
 */
define('CURLPX_LONG_HOSTNAME', 9);

/**
 * @since 8.2
 */
define('CURLPX_LONG_PASSWD', 10);

/**
 * @since 8.2
 */
define('CURLPX_LONG_USER', 11);

/**
 * @since 8.2
 */
define('CURLPX_NO_AUTH', 12);

/**
 * @since 8.2
 */
define('CURLPX_OK', 0);

/**
 * @since 8.2
 */
define('CURLPX_RECV_ADDRESS', 13);

/**
 * @since 8.2
 */
define('CURLPX_RECV_AUTH', 14);

/**
 * @since 8.2
 */
define('CURLPX_RECV_CONNECT', 15);

/**
 * @since 8.2
 */
define('CURLPX_RECV_REQACK', 16);

/**
 * @since 8.2
 */
define('CURLPX_REPLY_ADDRESS_TYPE_NOT_SUPPORTED', 17);

/**
 * @since 8.2
 */
define('CURLPX_REPLY_COMMAND_NOT_SUPPORTED', 18);

/**
 * @since 8.2
 */
define('CURLPX_REPLY_CONNECTION_REFUSED', 19);

/**
 * @since 8.2
 */
define('CURLPX_REPLY_GENERAL_SERVER_FAILURE', 20);

/**
 * @since 8.2
 */
define('CURLPX_REPLY_HOST_UNREACHABLE', 21);

/**
 * @since 8.2
 */
define('CURLPX_REPLY_NETWORK_UNREACHABLE', 22);

/**
 * @since 8.2
 */
define('CURLPX_REPLY_NOT_ALLOWED', 23);

/**
 * @since 8.2
 */
define('CURLPX_REPLY_TTL_EXPIRED', 24);

/**
 * @since 8.2
 */
define('CURLPX_REPLY_UNASSIGNED', 25);

/**
 * @since 8.2
 */
define('CURLPX_REQUEST_FAILED', 26);

/**
 * @since 8.2
 */
define('CURLPX_RESOLVE_HOST', 27);

/**
 * @since 8.2
 */
define('CURLPX_SEND_CONNECT', 29);

/**
 * @since 8.2
 */
define('CURLPX_SEND_AUTH', 28);

/**
 * @since 8.2
 */
define('CURLPX_SEND_REQUEST', 30);

/**
 * @since 8.2
 */
define('CURLPX_UNKNOWN_FAIL', 31);

/**
 * @since 8.2
 */
define('CURLPX_UNKNOWN_MODE', 32);

/**
 * @since 8.2
 */
define('CURLPX_USER_REJECTED', 33);

/**
 * @since 8.2
 */
define('CURLHSTS_ENABLE', 1);

/**
 * @since 8.2
 */
define('CURLHSTS_READONLYFILE', 2);

/**
 * @since 8.2
 */
define('CURLOPT_HSTS', 10300);

/**
 * @since 8.2
 */
define('CURLOPT_HSTS_CTRL', 299);

/**
 * @since 8.2
 */
define('CURL_VERSION_HSTS', 268435456);

/**
 * @since 8.2
 */
define('CURLAUTH_AWS_SIGV4', 128);

/**
 * @since 8.2
 */
define('CURLOPT_AWS_SIGV4', 10305);

/**
 * @since 8.2
 */
define('CURLINFO_REFERER', 1048636);

/**
 * @since 8.2
 */
define('CURLOPT_DOH_SSL_VERIFYHOST', 307);

/**
 * @since 8.2
 */
define('CURLOPT_DOH_SSL_VERIFYPEER', 306);

/**
 * @since 8.2
 */
define('CURLOPT_DOH_SSL_VERIFYSTATUS', 308);

/**
 * @since 8.2
 */
define('CURL_VERSION_GSASL', 536870912);

/**
 * @since 8.2
 */
define('CURLOPT_CAINFO_BLOB', 40309);

/**
 * @since 8.2
 */
define('CURLOPT_PROXY_CAINFO_BLOB', 40310);

/**
 * @since 8.2
 */
define('CURLSSLOPT_AUTO_CLIENT_CERT', 32);

/**
 * @since 8.2
 */
define('CURLOPT_MAXLIFETIME_CONN', 314);

/**
 * @since 8.2
 */
define('CURLOPT_SSH_HOST_PUBLIC_KEY_SHA256', 10311);
