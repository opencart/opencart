<?php

// Start of standard v.5.3.1-0.dotdeb.1

define('CONNECTION_ABORTED', 1);
define('CONNECTION_NORMAL', 0);
define('CONNECTION_TIMEOUT', 2);
define('INI_USER', 1);
define('INI_PERDIR', 2);
define('INI_SYSTEM', 4);
define('INI_ALL', 7);

/**
 * Normal INI scanner mode
 * @link https://php.net/manual/en/filesystem.constants.php
 */
define('INI_SCANNER_NORMAL', 0);

/**
 * Typed INI scanner mode
 * @since 5.6.1
 * @link https://php.net/manual/en/function.parse-ini-file.php
 */
define('INI_SCANNER_TYPED', 2);

/**
 * Raw INI scanner mode
 * @link https://php.net/manual/en/filesystem.constants.php
 */
define('INI_SCANNER_RAW', 1);

define('PHP_URL_SCHEME', 0);
define('PHP_URL_HOST', 1);
define('PHP_URL_PORT', 2);
define('PHP_URL_USER', 3);
define('PHP_URL_PASS', 4);
define('PHP_URL_PATH', 5);
define('PHP_URL_QUERY', 6);
define('PHP_URL_FRAGMENT', 7);

/**
 * <i>e</i> constant
 */
define('M_E', 2.718281828459);

/**
 * {@link log}<sub>2</sub><i>e</i> constant
 */
define('M_LOG2E', 1.442695040889);

/**
 * {@link log}<sub>10</sub><i>e</i> constant
 */
define('M_LOG10E', 0.43429448190325);

/**
 * {@link log}<sub><i>e</i></sub>2 constant
 */
define('M_LN2', 0.69314718055995);

/**
 * {@link log}<sub><i>e</i></sub>10 constant
 */
define('M_LN10', 2.302585092994);

/**
 * &pi; constant
 */
define('M_PI', 3.1415926535898);

/**
 * &pi;/2 constant
 */
define('M_PI_2', 1.5707963267949);

/**
 * &pi;/4 constant
 */
define('M_PI_4', 0.78539816339745);

/**
 * 1/&pi; constant
 */
define('M_1_PI', 0.31830988618379);

/**
 * 2/&pi; constant
 */
define('M_2_PI', 0.63661977236758);

/**
 * {@link sqrt}(&pi;) constant
 */
define('M_SQRTPI', 1.7724538509055);

/**
 * 2/{@link sqrt}(&pi;) constant
 */
define('M_2_SQRTPI', 1.1283791670955);

/**
 * {@link log}<sub><i>e</i></sub>&pi; constant
 */
define('M_LNPI', 1.1447298858494);

/**
 * Euler constant
 */
define('M_EULER', 0.57721566490153);

/**
 * {@link sqrt}(2) constant
 */
define('M_SQRT2', 1.4142135623731);

/**
 * 1/{@link sqrt}(2) constant
 */
define('M_SQRT1_2', 0.70710678118655);

/**
 * {@link sqrt}(3) constant
 */
define('M_SQRT3', 1.7320508075689);

/**
 * The infinite
 */
define('INF', (float)INF);

/**
 * Not A Number
 */
define('NAN', (float)NAN);

/**
 * Round halves up
 * @link https://php.net/manual/en/math.constants.php
 */
define('PHP_ROUND_HALF_UP', 1);

/**
 * Round halves down
 * @link https://php.net/manual/en/math.constants.php
 */
define('PHP_ROUND_HALF_DOWN', 2);

/**
 * Round halves to even numbers
 * @link https://php.net/manual/en/math.constants.php
 */
define('PHP_ROUND_HALF_EVEN', 3);

/**
 * Round halves to odd numbers
 * @link https://php.net/manual/en/math.constants.php
 */
define('PHP_ROUND_HALF_ODD', 4);
define('INFO_GENERAL', 1);

/**
 * PHP Credits. See also phpcredits.
 * @link https://php.net/manual/en/info.constants.php
 */
define('INFO_CREDITS', 2);

/**
 * Current Local and Main values for PHP directives. See
 * also ini_get.
 * @link https://php.net/manual/en/info.constants.php
 */
define('INFO_CONFIGURATION', 4);

/**
 * Loaded modules and their respective settings.
 * @link https://php.net/manual/en/info.constants.php
 */
define('INFO_MODULES', 8);

/**
 * Environment Variable information that's also available in
 * $_ENV.
 * @link https://php.net/manual/en/info.constants.php
 */
define('INFO_ENVIRONMENT', 16);

/**
 * Shows all
 * predefined variables from EGPCS (Environment, GET,
 * POST, Cookie, Server).
 * @link https://php.net/manual/en/info.constants.php
 */
define('INFO_VARIABLES', 32);

/**
 * PHP License information. See also the license faq.
 * @link https://php.net/manual/en/info.constants.php
 */
define('INFO_LICENSE', 64);
define('INFO_ALL', 4294967295);

/**
 * A list of the core developers
 * @link https://php.net/manual/en/info.constants.php
 */
define('CREDITS_GROUP', 1);

/**
 * General credits: Language design and concept, PHP
 * authors and SAPI module.
 * @link https://php.net/manual/en/info.constants.php
 */
define('CREDITS_GENERAL', 2);

/**
 * A list of the server API modules for PHP, and their authors.
 * @link https://php.net/manual/en/info.constants.php
 */
define('CREDITS_SAPI', 4);

/**
 * A list of the extension modules for PHP, and their authors.
 * @link https://php.net/manual/en/info.constants.php
 */
define('CREDITS_MODULES', 8);

/**
 * The credits for the documentation team.
 * @link https://php.net/manual/en/info.constants.php
 */
define('CREDITS_DOCS', 16);

/**
 * Usually used in combination with the other flags. Indicates
 * that a complete stand-alone HTML page needs to be
 * printed including the information indicated by the other
 * flags.
 * @link https://php.net/manual/en/info.constants.php
 */
define('CREDITS_FULLPAGE', 32);

/**
 * The credits for the quality assurance team.
 * @link https://php.net/manual/en/info.constants.php
 */
define('CREDITS_QA', 64);

/**
 * The configuration line, "php.ini" location, build date, Web
 * Server, System and more.
 * @link https://php.net/manual/en/info.constants.php
 */
define('CREDITS_ALL', 4294967295);
define('HTML_SPECIALCHARS', 0);
define('HTML_ENTITIES', 1);

/**
 * Will convert double-quotes and leave single-quotes alone.
 * @link https://php.net/manual/en/function.htmlspecialchars.php
 */
define('ENT_COMPAT', 2);

/**
 * Will convert both double and single quotes.
 * @link https://php.net/manual/en/function.htmlspecialchars.php
 */
define('ENT_QUOTES', 3);

/**
 * Will leave both double and single quotes unconverted.
 * @link https://php.net/manual/en/function.htmlspecialchars.php
 */
define('ENT_NOQUOTES', 0);

/**
 * Silently discard invalid code unit sequences instead of returning an empty string.
 * Using this flag is discouraged as it may have security implications.
 * @link https://php.net/manual/en/function.htmlspecialchars.php
 */
define('ENT_IGNORE', 4);
define('STR_PAD_LEFT', 0);
define('STR_PAD_RIGHT', 1);
define('STR_PAD_BOTH', 2);
define('PATHINFO_DIRNAME', 1);
define('PATHINFO_BASENAME', 2);
define('PATHINFO_EXTENSION', 4);

/**
 * @link https://php.net/manual/en/filesystem.constants.php
 */
define('PATHINFO_FILENAME', 8);
define('PATHINFO_ALL', 15);
define('CHAR_MAX', 127);
define('LC_CTYPE', 0);
define('LC_NUMERIC', 1);
define('LC_TIME', 2);
define('LC_COLLATE', 3);
define('LC_MONETARY', 4);
define('LC_ALL', 6);
define('LC_MESSAGES', 5);
define('SEEK_SET', 0);
define('SEEK_CUR', 1);
define('SEEK_END', 2);

/**
 * Acquire a shared lock (reader).
 * @link https://www.php.net/manual/en/function.flock.php
 */
define('LOCK_SH', 1);

/**
 * Acquire an exclusive lock (writer).
 * @link https://www.php.net/manual/en/function.flock.php
 */
define('LOCK_EX', 2);

/**
 * Release lock (shared or exclusive).
 * @link https://www.php.net/manual/en/function.flock.php
 */
define('LOCK_UN', 3);

/**
 * Non-blocking operation while locking.
 * @link https://www.php.net/manual/en/function.flock.php
 */
define('LOCK_NB', 4);

/**
 * A connection with an external resource has been established.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_NOTIFY_CONNECT', 2);

/**
 * Additional authorization is required to access the specified resource.
 * Typical issued with severity level of
 * STREAM_NOTIFY_SEVERITY_ERR.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_NOTIFY_AUTH_REQUIRED', 3);

/**
 * Authorization has been completed (with or without success).
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_NOTIFY_AUTH_RESULT', 10);

/**
 * The mime-type of resource has been identified,
 * refer to message for a description of the
 * discovered type.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_NOTIFY_MIME_TYPE_IS', 4);

/**
 * The size of the resource has been discovered.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_NOTIFY_FILE_SIZE_IS', 5);

/**
 * The external resource has redirected the stream to an alternate
 * location. Refer to message.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_NOTIFY_REDIRECTED', 6);

/**
 * Indicates current progress of the stream transfer in
 * bytes_transferred and possibly
 * bytes_max as well.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_NOTIFY_PROGRESS', 7);

/**
 * A generic error occurred on the stream, consult
 * message and message_code
 * for details.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_NOTIFY_FAILURE', 9);

/**
 * There is no more data available on the stream.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_NOTIFY_COMPLETED', 8);

/**
 * A remote address required for this stream has been resolved, or the resolution
 * failed. See severity for an indication of which happened.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_NOTIFY_RESOLVE', 1);

/**
 * Normal, non-error related, notification.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_NOTIFY_SEVERITY_INFO', 0);

/**
 * Non critical error condition. Processing may continue.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_NOTIFY_SEVERITY_WARN', 1);

/**
 * A critical error occurred. Processing cannot continue.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_NOTIFY_SEVERITY_ERR', 2);

/**
 * Used with stream_filter_append and
 * stream_filter_prepend to indicate
 * that the specified filter should only be applied when
 * reading
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_FILTER_READ', 1);

/**
 * Used with stream_filter_append and
 * stream_filter_prepend to indicate
 * that the specified filter should only be applied when
 * writing
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_FILTER_WRITE', 2);

/**
 * This constant is equivalent to
 * STREAM_FILTER_READ | STREAM_FILTER_WRITE
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_FILTER_ALL', 3);

/**
 * Client socket opened with stream_socket_client
 * should remain persistent between page loads.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_CLIENT_PERSISTENT', 1);

/**
 * Open client socket asynchronously. This option must be used
 * together with the STREAM_CLIENT_CONNECT flag.
 * Used with stream_socket_client.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_CLIENT_ASYNC_CONNECT', 2);

/**
 * Open client socket connection. Client sockets should always
 * include this flag. Used with stream_socket_client.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_CLIENT_CONNECT', 4);

/**
 * Used with stream_socket_shutdown to disable
 * further receptions.
 * @since 5.2.1
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_SHUT_RD', 0);

/**
 * Used with stream_socket_shutdown to disable
 * further transmissions.
 * @since 5.2.1
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_SHUT_WR', 1);

/**
 * Used with stream_socket_shutdown to disable
 * further receptions and transmissions.
 * @since 5.2.1
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_SHUT_RDWR', 2);

/**
 * Internet Protocol Version 4 (IPv4).
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_PF_INET', 2);

/**
 * Internet Protocol Version 6 (IPv6).
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_PF_INET6', 10);

/**
 * Unix system internal protocols.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_PF_UNIX', 1);

/**
 * Provides a IP socket.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_IPPROTO_IP', 0);

/**
 * Provides a TCP socket.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_IPPROTO_TCP', 6);

/**
 * Provides a UDP socket.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_IPPROTO_UDP', 17);

/**
 * Provides a ICMP socket.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_IPPROTO_ICMP', 1);

/**
 * Provides a RAW socket.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_IPPROTO_RAW', 255);

/**
 * Provides sequenced, two-way byte streams with a transmission mechanism
 * for out-of-band data (TCP, for example).
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_SOCK_STREAM', 1);

/**
 * Provides datagrams, which are connectionless messages (UDP, for
 * example).
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_SOCK_DGRAM', 2);

/**
 * Provides a raw socket, which provides access to internal network
 * protocols and interfaces. Usually this type of socket is just available
 * to the root user.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_SOCK_RAW', 3);

/**
 * Provides a sequenced packet stream socket.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_SOCK_SEQPACKET', 5);

/**
 * Provides a RDM (Reliably-delivered messages) socket.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_SOCK_RDM', 4);
define('STREAM_PEEK', 2);
define('STREAM_OOB', 1);

/**
 * Tells a stream created with stream_socket_server
 * to bind to the specified target. Server sockets should always include this flag.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_SERVER_BIND', 4);

/**
 * Tells a stream created with stream_socket_server
 * and bound using the STREAM_SERVER_BIND flag to start
 * listening on the socket. Connection-orientated transports (such as TCP)
 * must use this flag, otherwise the server socket will not be enabled.
 * Using this flag for connect-less transports (such as UDP) is an error.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_SERVER_LISTEN', 8);

/**
 * Search for filename in include_path
 * @link https://php.net/manual/en/filesystem.constants.php
 */
define('FILE_USE_INCLUDE_PATH', 1);

/**
 * Strip EOL characters
 * @link https://php.net/manual/en/filesystem.constants.php
 */
define('FILE_IGNORE_NEW_LINES', 2);

/**
 * Skip empty lines
 * @link https://php.net/manual/en/filesystem.constants.php
 */
define('FILE_SKIP_EMPTY_LINES', 4);

/**
 * Append content to existing file.
 * @link https://php.net/manual/en/filesystem.constants.php
 */
define('FILE_APPEND', 8);
define('FILE_NO_DEFAULT_CONTEXT', 16);

/**
 * <p>
 * This constant has no effect prior to PHP 6. It is only available for
 * forward compatibility.
 * </p>
 * @since 5.2.7
 * @link https://php.net/manual/en/filesystem.constants.php
 * @deprecated 8.1
 */
define('FILE_TEXT', 0);

/**
 * <p>
 * This constant has no effect prior to PHP 6. It is only available for
 * forward compatibility.
 * </p>
 * @since 5.2.7
 * @link https://php.net/manual/en/filesystem.constants.php
 * @deprecated 8.1
 */
define('FILE_BINARY', 0);

/**
 * Disable backslash escaping.
 * @link https://php.net/manual/en/filesystem.constants.php
 */
define('FNM_NOESCAPE', 2);

/**
 * Slash in string only matches slash in the given pattern.
 * @link https://php.net/manual/en/filesystem.constants.php
 */
define('FNM_PATHNAME', 1);

/**
 * Leading period in string must be exactly matched by period in the given pattern.
 * @link https://php.net/manual/en/filesystem.constants.php
 */
define('FNM_PERIOD', 4);

/**
 * Caseless match. Part of the GNU extension.
 * @link https://php.net/manual/en/filesystem.constants.php
 */
define('FNM_CASEFOLD', 16);

/**
 * Return Code indicating that the
 * userspace filter returned buckets in $out.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('PSFS_PASS_ON', 2);

/**
 * Return Code indicating that the
 * userspace filter did not return buckets in $out
 * (i.e. No data available).
 * @link https://php.net/manual/en/stream.constants.php
 */
define('PSFS_FEED_ME', 1);

/**
 * Return Code indicating that the
 * userspace filter encountered an unrecoverable error
 * (i.e. Invalid data received).
 * @link https://php.net/manual/en/stream.constants.php
 */
define('PSFS_ERR_FATAL', 0);

/**
 * Regular read/write.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('PSFS_FLAG_NORMAL', 0);

/**
 * An incremental flush.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('PSFS_FLAG_FLUSH_INC', 1);

/**
 * Final flush prior to closing.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('PSFS_FLAG_FLUSH_CLOSE', 2);
define('ABDAY_1', 131072);
define('ABDAY_2', 131073);
define('ABDAY_3', 131074);
define('ABDAY_4', 131075);
define('ABDAY_5', 131076);
define('ABDAY_6', 131077);
define('ABDAY_7', 131078);
define('DAY_1', 131079);
define('DAY_2', 131080);
define('DAY_3', 131081);
define('DAY_4', 131082);
define('DAY_5', 131083);
define('DAY_6', 131084);
define('DAY_7', 131085);
define('ABMON_1', 131086);
define('ABMON_2', 131087);
define('ABMON_3', 131088);
define('ABMON_4', 131089);
define('ABMON_5', 131090);
define('ABMON_6', 131091);
define('ABMON_7', 131092);
define('ABMON_8', 131093);
define('ABMON_9', 131094);
define('ABMON_10', 131095);
define('ABMON_11', 131096);
define('ABMON_12', 131097);
define('MON_1', 131098);
define('MON_2', 131099);
define('MON_3', 131100);
define('MON_4', 131101);
define('MON_5', 131102);
define('MON_6', 131103);
define('MON_7', 131104);
define('MON_8', 131105);
define('MON_9', 131106);
define('MON_10', 131107);
define('MON_11', 131108);
define('MON_12', 131109);
define('AM_STR', 131110);
define('PM_STR', 131111);
define('D_T_FMT', 131112);
define('D_FMT', 131113);
define('T_FMT', 131114);
define('T_FMT_AMPM', 131115);
define('ERA', 131116);
define('ERA_D_T_FMT', 131120);
define('ERA_D_FMT', 131118);
define('ERA_T_FMT', 131121);
define('ALT_DIGITS', 131119);
define('CRNCYSTR', 262159);
define('RADIXCHAR', 65536);
define('THOUSEP', 65537);
define('YESEXPR', 327680);
define('NOEXPR', 327681);
define('YESSTR', 327682);
define('NOSTR', 327683);
define('CODESET', 14);
define('CRYPT_SALT_LENGTH', 123);
define('CRYPT_STD_DES', 1);
define('CRYPT_EXT_DES', 1);
define('CRYPT_MD5', 1);
define('CRYPT_BLOWFISH', 1);
define('CRYPT_SHA256', 1);
define('CRYPT_SHA512', 1);
define('DIRECTORY_SEPARATOR', "/");
define('PATH_SEPARATOR', ":");
define('GLOB_BRACE', 1024);
define('GLOB_MARK', 2);
define('GLOB_NOSORT', 4);
define('GLOB_NOCHECK', 16);
define('GLOB_NOESCAPE', 64);
define('GLOB_ERR', 1);
define('GLOB_ONLYDIR', 1073741824);
define('GLOB_AVAILABLE_FLAGS', 1073741911);
define('EXTR_OVERWRITE', 0);
define('EXTR_SKIP', 1);
define('EXTR_PREFIX_SAME', 2);
define('EXTR_PREFIX_ALL', 3);
define('EXTR_PREFIX_INVALID', 4);
define('EXTR_PREFIX_IF_EXISTS', 5);
define('EXTR_IF_EXISTS', 6);
define('EXTR_REFS', 256);

/**
 * SORT_ASC is used with
 * array_multisort to sort in ascending order.
 * @link https://php.net/manual/en/array.constants.php
 */
define('SORT_ASC', 4);

/**
 * SORT_DESC is used with
 * array_multisort to sort in descending order.
 * @link https://php.net/manual/en/array.constants.php
 */
define('SORT_DESC', 3);

/**
 * SORT_REGULAR is used to compare items normally.
 * @link https://php.net/manual/en/array.constants.php
 */
define('SORT_REGULAR', 0);

/**
 * SORT_NUMERIC is used to compare items numerically.
 * @link https://php.net/manual/en/array.constants.php
 */
define('SORT_NUMERIC', 1);

/**
 * SORT_STRING is used to compare items as strings.
 * @link https://php.net/manual/en/array.constants.php
 */
define('SORT_STRING', 2);

/**
 * SORT_LOCALE_STRING is used to compare items as
 * strings, based on the current locale.
 * @since 5.0.2
 * @link https://php.net/manual/en/array.constants.php
 */
define('SORT_LOCALE_STRING', 5);

/**
 * CASE_LOWER is used with
 * array_change_key_case and is used to convert array
 * keys to lower case. This is also the default case for
 * array_change_key_case.
 * @link https://php.net/manual/en/array.constants.php
 */
define('CASE_LOWER', 0);

/**
 * CASE_UPPER is used with
 * array_change_key_case and is used to convert array
 * keys to upper case.
 * @link https://php.net/manual/en/array.constants.php
 */
define('CASE_UPPER', 1);
define('COUNT_NORMAL', 0);
define('COUNT_RECURSIVE', 1);
define('ASSERT_ACTIVE', 1);
define('ASSERT_CALLBACK', 2);
define('ASSERT_BAIL', 3);
define('ASSERT_WARNING', 4);

/**
 * @removed 8.0
 */
define('ASSERT_QUIET_EVAL', 5);
define('ASSERT_EXCEPTION', 5);

/**
 * Flag indicating if the stream used the include path.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_USE_PATH', 1);
define('STREAM_IGNORE_URL', 2);
define('STREAM_ENFORCE_SAFE_MODE', 4);

/**
 * Flag indicating if the wrapper
 * is responsible for raising errors using trigger_error
 * during opening of the stream. If this flag is not set, you
 * should not raise any errors.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_REPORT_ERRORS', 8);

/**
 * This flag is useful when your extension really must be able to randomly
 * seek around in a stream. Some streams may not be seekable in their
 * native form, so this flag asks the streams API to check to see if the
 * stream does support seeking. If it does not, it will copy the stream
 * into temporary storage (which may be a temporary file or a memory
 * stream) which does support seeking.
 * Please note that this flag is not useful when you want to seek the
 * stream and write to it, because the stream you are accessing might
 * not be bound to the actual resource you requested.
 * If the requested resource is network based, this flag will cause the
 * opener to block until the whole contents have been downloaded.
 * @link https://php.net/manual/en/internals2.ze1.streams.constants.php
 */
define('STREAM_MUST_SEEK', 16);
define('STREAM_URL_STAT_LINK', 1);
define('STREAM_URL_STAT_QUIET', 2);
define('STREAM_MKDIR_RECURSIVE', 1);
define('STREAM_IS_URL', 1);
define('STREAM_OPTION_BLOCKING', 1);
define('STREAM_OPTION_READ_TIMEOUT', 4);
define('STREAM_OPTION_READ_BUFFER', 2);
define('STREAM_OPTION_WRITE_BUFFER', 3);
define('STREAM_BUFFER_NONE', 0);
define('STREAM_BUFFER_LINE', 1);
define('STREAM_BUFFER_FULL', 2);

/**
 * Stream casting, when stream_cast is called
 * otherwise (see above).
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_CAST_AS_STREAM', 0);

/**
 * Stream casting, for when stream_select is
 * calling stream_cast.
 * @link https://php.net/manual/en/stream.constants.php
 */
define('STREAM_CAST_FOR_SELECT', 3);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_GIF', 1);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_JPEG', 2);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_PNG', 3);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_SWF', 4);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_PSD', 5);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_BMP', 6);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_TIFF_II', 7);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_TIFF_MM', 8);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_JPC', 9);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_JP2', 10);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_JPX', 11);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_JB2', 12);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_SWC', 13);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_IFF', 14);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_WBMP', 15);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_JPEG2000', 9);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_XBM', 16);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 */
define('IMAGETYPE_ICO', 17);

/**
 * Image type constant used by the {@link image_type_to_mime_type()} and {@link image_type_to_extension()} functions.
 * @link https://php.net/manual/en/image.constants.php
 * @since 7.1
 */
define('IMAGETYPE_WEBP', 18);
define('IMAGETYPE_UNKNOWN', 0);
define('IMAGETYPE_COUNT', 20);

/**
 * @since 8.1
 */
define('IMAGETYPE_AVIF', 19);
/**
 * IPv4 Address Resource
 * @link https://php.net/manual/en/network.constants.php
 */
define('DNS_A', 1);

define('DNS_CAA', 8192);

/**
 * Authoritative Name Server Resource
 * @link https://php.net/manual/en/network.constants.php
 */
define('DNS_NS', 2);

/**
 * Alias (Canonical Name) Resource
 * @link https://php.net/manual/en/network.constants.php
 */
define('DNS_CNAME', 16);

/**
 * Start of Authority Resource
 * @link https://php.net/manual/en/network.constants.php
 */
define('DNS_SOA', 32);

/**
 * Pointer Resource
 * @link https://php.net/manual/en/network.constants.php
 */
define('DNS_PTR', 2048);

/**
 * Host Info Resource (See IANA's
 * Operating System Names
 * for the meaning of these values)
 * @link https://php.net/manual/en/network.constants.php
 */
define('DNS_HINFO', 4096);

/**
 * Mail Exchanger Resource
 * @link https://php.net/manual/en/network.constants.php
 */
define('DNS_MX', 16384);

/**
 * Text Resource
 * @link https://php.net/manual/en/network.constants.php
 */
define('DNS_TXT', 32768);
define('DNS_SRV', 33554432);
define('DNS_NAPTR', 67108864);

/**
 * IPv6 Address Resource
 * @link https://php.net/manual/en/network.constants.php
 */
define('DNS_AAAA', 134217728);
define('DNS_A6', 16777216);

/**
 * Any Resource Record. On most systems
 * this returns all resource records, however
 * it should not be counted upon for critical
 * uses. Try DNS_ALL instead.
 * @link https://php.net/manual/en/network.constants.php
 */
define('DNS_ANY', 268435456);

/**
 * Iteratively query the name server for
 * each available record type.
 * @link https://php.net/manual/en/network.constants.php
 */
define('DNS_ALL', 251721779);

// End of standard v.5.3.1-0.dotdeb.1

//WI-11084 Constant not defined PHP_QUERY_RFC3986

/**
 * Encoding is performed per RFC 1738 and the application/x-www-form-urlencoded media type,
 * which implies that spaces are encoded as plus (+) signs.
 * @link https://php.net/manual/en/function.http-build-query.php
 */
define('PHP_QUERY_RFC1738', 1);
/**
 * Encoding is performed according to RFC 3986, and spaces will be percent encoded (%20).
 * @link https://php.net/manual/en/function.http-build-query.php
 */
define('PHP_QUERY_RFC3986', 2);

//WI-11254 Stubs for missing constants from PHP 5.4

/**
 * (PHP4, PHP5)
 * <p>Constant containing either the session name and session ID in the form of "name=ID" or
 * empty string if session ID was set in an appropriate session cookie.
 * This is the same id as the one returned by session_id().</p>
 * @see session_id()
 * @link https://php.net/manual/en/session.constants.php
 */
define('SID', "name=ID");
/**
 * Return value of session_status() if sessions are disabled.
 * @since 5.4
 * @link https://php.net/manual/en/function.session-status.php
 */
define('PHP_SESSION_DISABLED', 0);
/**
 * Return value of session_status() if sessions are enabled, but no session exists.
 * @since 5.4
 * @link https://php.net/manual/en/function.session-status.php
 */
define('PHP_SESSION_NONE', 1);
/**
 * Return value of session_status() if sessions are enabled, and a session exists.
 * @since 5.4
 * @link https://php.net/manual/en/function.session-status.php
 */
define('PHP_SESSION_ACTIVE', 2);

/**
 * Replace invalid code unit sequences with a Unicode Replacement Character
 * U+FFFD (UTF-8) or &#FFFD; (otherwise) instead of returning an empty string.
 * @since 5.4
 * @link https://php.net/manual/en/function.htmlspecialchars.php
 */
define('ENT_SUBSTITUTE', 8);
/**
 * Replace invalid code points for the given document type with
 * a Unicode Replacement Character U+FFFD (UTF-8) or &#FFFD;
 * (otherwise) instead of leaving them as is. This may be useful,
 * for instance, to ensure the well-formedness of XML documents
 * with embedded external content.
 * @since 5.4
 * @link https://php.net/manual/en/function.htmlspecialchars.php
 */
define('ENT_DISALLOWED', 128);
/**
 * Handle code as HTML 4.01.
 * @since 5.4
 * @link https://php.net/manual/en/function.htmlspecialchars.php
 */
define('ENT_HTML401', 0);
/**
 * Handle code as XML 1.
 * @since 5.4
 * @link https://php.net/manual/en/function.htmlspecialchars.php
 */
define('ENT_XML1', 16);
/**
 * Handle code as XHTML.
 * @since 5.4
 * @link https://php.net/manual/en/function.htmlspecialchars.php
 */
define('ENT_XHTML', 32);
/**
 * Handle code as HTML 5.
 * @since 5.4
 * @link https://php.net/manual/en/function.htmlspecialchars.php
 */
define('ENT_HTML5', 48);

/** @link https://php.net/manual/en/function.scandir.php */
define('SCANDIR_SORT_ASCENDING', 0);
/** @link https://php.net/manual/en/function.scandir.php */
define('SCANDIR_SORT_DESCENDING', 1);
/** @link https://php.net/manual/en/function.scandir.php */
define('SCANDIR_SORT_NONE', 2);

/**
 * SORT_NATURAL is used to compare items as strings using "natural ordering" like natsort().
 * @since 5.4
 * @link https://php.net/manual/en/array.constants.php
 */
define('SORT_NATURAL', 6);
/**
 * SORT_FLAG_CASE can be combined (bitwise OR) with SORT_STRING or SORT_NATURAL to sort strings case-insensitively.
 * @since 5.4
 * @link https://php.net/manual/en/array.constants.php
 */
define('SORT_FLAG_CASE', 8);

/** @link https://php.net/manual/en/streamwrapper.stream-metadata.php */
define('STREAM_META_TOUCH', 1);
/** @link https://php.net/manual/en/streamwrapper.stream-metadata.php */
define('STREAM_META_OWNER', 3);
/** @link https://php.net/manual/en/streamwrapper.stream-metadata.php */
define('STREAM_META_OWNER_NAME', 2);
/** @link https://php.net/manual/en/streamwrapper.stream-metadata.php */
define('STREAM_META_GROUP', 5);
/** @link https://php.net/manual/en/streamwrapper.stream-metadata.php */
define('STREAM_META_GROUP_NAME', 4);
/** @link https://php.net/manual/en/streamwrapper.stream-metadata.php */
define('STREAM_META_ACCESS', 6);

define('STREAM_CRYPTO_METHOD_SSLv2_CLIENT', 3);
define('STREAM_CRYPTO_METHOD_SSLv3_CLIENT', 5);
define('STREAM_CRYPTO_METHOD_SSLv23_CLIENT', 57);
define('STREAM_CRYPTO_METHOD_TLS_CLIENT', 121);
define('STREAM_CRYPTO_METHOD_SSLv2_SERVER', 2);
define('STREAM_CRYPTO_METHOD_SSLv3_SERVER', 4);
define('STREAM_CRYPTO_METHOD_SSLv23_SERVER', 120);
define('STREAM_CRYPTO_METHOD_TLS_SERVER', 120);

define("STREAM_CRYPTO_METHOD_ANY_CLIENT", 127);
define("STREAM_CRYPTO_METHOD_ANY_SERVER", 126);
define("STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT", 9);
define("STREAM_CRYPTO_METHOD_TLSv1_0_SERVER", 8);
define("STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT", 17);
define("STREAM_CRYPTO_METHOD_TLSv1_1_SERVER", 16);
define("STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT", 33);
define("STREAM_CRYPTO_METHOD_TLSv1_2_SERVER", 32);
/**
 * @since 7.4
 */
define("STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT", 65);
/**
 * @since 7.4
 */
define("STREAM_CRYPTO_METHOD_TLSv1_3_SERVER", 64);

define("STREAM_CRYPTO_PROTO_SSLv3", 4);
define("STREAM_CRYPTO_PROTO_TLSv1_0", 8);
define("STREAM_CRYPTO_PROTO_TLSv1_1", 16);
define("STREAM_CRYPTO_PROTO_TLSv1_2", 32);
/**
 * @since 7.4
 */
define("STREAM_CRYPTO_PROTO_TLSv1_3", 64);

/**
 * @since 7.1
 */
define("MT_RAND_MT19937", 0);
/**
 * @since 7.1
 */
define("MT_RAND_PHP", 1);

/**
 * system is unusable
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_EMERG', 0);

/**
 * action must be taken immediately
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_ALERT', 1);

/**
 * critical conditions
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_CRIT', 2);

/**
 * error conditions
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_ERR', 3);

/**
 * warning conditions
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_WARNING', 4);

/**
 * normal, but significant, condition
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_NOTICE', 5);

/**
 * informational message
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_INFO', 6);

/**
 * debug-level message
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_DEBUG', 7);

/**
 * kernel messages
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_KERN', 0);

/**
 * generic user-level messages
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_USER', 8);

/**
 * mail subsystem
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_MAIL', 16);

/**
 * other system daemons
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_DAEMON', 24);

/**
 * security/authorization messages (use <b>LOG_AUTHPRIV</b> instead
 * in systems where that constant is defined)
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_AUTH', 32);

/**
 * messages generated internally by syslogd
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_SYSLOG', 40);

/**
 * line printer subsystem
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_LPR', 48);

/**
 * USENET news subsystem
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_NEWS', 56);

/**
 * UUCP subsystem
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_UUCP', 64);

/**
 * clock daemon (cron and at)
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_CRON', 72);

/**
 * security/authorization messages (private)
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_AUTHPRIV', 80);
define('LOG_LOCAL0', 128);
define('LOG_LOCAL1', 136);
define('LOG_LOCAL2', 144);
define('LOG_LOCAL3', 152);
define('LOG_LOCAL4', 160);
define('LOG_LOCAL5', 168);
define('LOG_LOCAL6', 176);
define('LOG_LOCAL7', 184);

/**
 * include PID with each message
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_PID', 1);

/**
 * if there is an error while sending data to the system logger,
 * write directly to the system console
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_CONS', 2);

/**
 * (default) delay opening the connection until the first
 * message is logged
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_ODELAY', 4);

/**
 * open the connection to the logger immediately
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_NDELAY', 8);
define('LOG_NOWAIT', 16);

/**
 * print log message also to standard error
 * @link https://php.net/manual/en/network.constants.php
 */
define('LOG_PERROR', 32);

/**
 * @since 8.2
 */
define('DECIMAL_POINT', 65536);
/**
 * @since 8.2
 */
define('THOUSANDS_SEP', 65537);
/**
 * @since 8.2
 */
define('GROUPING', 65538);

/**
 * @since 8.2
 */
define('ERA_YEAR', 131117);

/**
 * @since 8.2
 */
define('INT_CURR_SYMBOL', 262144);
/**
 * @since 8.2
 */
define('CURRENCY_SYMBOL', 262145);
/**
 * @since 8.2
 */
define('MON_DECIMAL_POINT', 262146);
/**
 * @since 8.2
 */
define('MON_THOUSANDS_SEP', 262147);
/**
 * @since 8.2
 */
define('MON_GROUPING', 262148);
/**
 * @since 8.2
 */
define('POSITIVE_SIGN', 262149);
/**
 * @since 8.2
 */
define('NEGATIVE_SIGN', 262150);
/**
 * @since 8.2
 */
define('INT_FRAC_DIGITS', 262151);
/**
 * @since 8.2
 */
define('FRAC_DIGITS', 262152);
/**
 * @since 8.2
 */
define('P_CS_PRECEDES', 262153);
/**
 * @since 8.2
 */
define('P_SEP_BY_SPACE', 262154);
/**
 * @since 8.2
 */
define('N_CS_PRECEDES', 262155);
/**
 * @since 8.2
 */
define('N_SEP_BY_SPACE', 262156);
/**
 * @since 8.2
 */
define('P_SIGN_POSN', 262157);
/**
 * @since 8.2
 */
define('N_SIGN_POSN', 262158);
