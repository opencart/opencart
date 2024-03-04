<?php

namespace PHPSTORM_META {

  /**
   * @param callable $callable Class, Method or function call
   * @param mixed $method one of
   * @see map()
   * @see type()
   * @see elementType()
   * @see sql_injection_subst()
   * @return mixed override pair object
   */
  function override($callable, $override) {
    return "override $callable $override";
  }

  /**
   * map argument with #$argNum Literal value to one of expressions
   * @param mixed $argNum ignored, for now its always 0
   * @param mixed $map Key-value pairs: string_literal|const|class_const => class_name::class|pattern_literal
   * where pattern literal can contain @ char to be replaced with argument literal value.
   *
   * When used with sql_injection_subst(), string literal key-value pairs act as replacement rules: pattern => replacement.
   * These rules are applied in SQL injections in an IDE and enable support for dynamically-prefixed database table names.
   *
   * @see sql_injection_subst()
   * @return mixed overrides map object
   */
  function map($map) {
    return "map $argNum $map";
  }

  /**
   * type of argument #$argNum
   * @param mixed $argNum ignored, for now its always 0
   * @return mixed
   */
  function type($argNum) {
    return "type $argNum";
  }

  /**
   * element type of argument #$argNum
   * @param mixed $argNum
   * @return mixed
   */
  function elementType($argNum) {
    return "elementType $argNum";
  }

  /**
   * Provides an IDE with a set of replacement rules that are applied in SQL injections.
   * Replacement rules are specified as string literal key-value pairs in the map() call.
   * @param mixed $argNum ignored, for now its always 0
   * @see map()
   * @return mixed
   */
  function sql_injection_subst($argNum) {
      return "sql_injection_subst $argNum";
  }

  override(\array_shift(0), elementType(0));
  override(\array_filter(0), type(0));
  override(\array_reverse(0), type(0));
  override(\array_pop(0), elementType(0));
  override(\array_reduce(0), type(2));
  override(\array_slice(0), type(0));
  override(\array_diff(0), type(0));
  override(\array_diff_assoc(0), type(0));
  override(\array_diff_uassoc(0), type(0));
  override(\array_diff_key(0), type(0));
  override(\array_diff_ukey(0), type(0));
  override(\array_udiff(0), type(0));
  override(\array_udiff_assoc(0), type(0));
  override(\array_udiff_uassoc(0), type(0));
  override(\array_merge(0), type(0));

  override(\current(0), elementType(0));
  override(\reset(0), elementType(0));
  override(\end(0), elementType(0));
  override(\prev(0), elementType(0));
  override(\next(0), elementType(0));

  override(\array_change_key_case(0), type(0));
  override(\array_unique(0), type(0));

  override(\array_intersect(0), type(0));
  override(\array_intersect_assoc(0), type(0));
  override(\array_intersect_key(0), type(0));
  override(\array_intersect_uassoc(0), type(0));
  override(\array_intersect_ukey(0), type(0));
  override(\array_uintersect(0), type(0));
  override(\array_uintersect_assoc(0), type(0));
  override(\array_uintersect_uassoc(0), type(0));

//should be changed later to map values when map type is supported
  override(\array_values(0), type(0));
  override(\array_combine(0), type(1));

  override(\str_replace(0), type(2));

  override(\DOMDocument::importNode(0), type(0));
  override(\DOMNode::appendChild(0), type(0));
  override(\DOMNode::insertBefore(0), type(0));
  override(\DOMNode::removeChild(0), type(0));
  override(\DOMNode::replaceChild(0), type(1));
  override(\simplexml_load_file(1), map(["" => "$1"]));
  override(\simplexml_load_string(1), map(["" => "$1"]));
  override(\simplexml_import_dom(1), map(["" => "$1"]));

    function expectedArguments($functionReference, $argumentIndex, ...$values) {
        return "expectedArguments " . $functionReference . "at " . $argumentIndex . ": " . $values;
    }

    function registerArgumentsSet($setName, ...$values) {
        return "registerArgumentsSet " . $setName . ": "  . $values;
    }

    function argumentsSet($setName) {
        return "argumentsSet " . $setName;
    }

    expectedArguments(\array_change_key_case(), 1, CASE_LOWER,CASE_UPPER);
    expectedArguments(\apc_bin_dumpfile(), 3, FILE_USE_INCLUDE_PATH, FILE_APPEND, LOCK_EX);
    expectedArguments(\apc_bin_load(), 3, APC_BIN_VERIFY_CRC32|APC_BIN_VERIFY_MD5);
    expectedArguments(\apc_bin_loadfile(), 3, APC_BIN_VERIFY_CRC32|APC_BIN_VERIFY_MD5);
    expectedArguments(\jdmonthname(), 1, CAL_MONTH_GREGORIAN_SHORT,CAL_MONTH_GREGORIAN_LONG,CAL_MONTH_JULIAN_SHORT,CAL_MONTH_JULIAN_LONG,CAL_MONTH_JEWISH,CAL_MONTH_FRENCH);
    expectedArguments(\variant_cmp(), 0, NORM_IGNORECASE|NORM_IGNORENONSPACE|NORM_IGNORESYMBOLS|NORM_IGNOREWIDTH|NORM_IGNOREKANATYPE|NORM_IGNOREKASHIDA);

    expectedArguments(\DOMDocument::schemaValidateSource(), 1, LIBXML_SCHEMA_CREATE);

    expectedArguments(\curl_setopt(), 1, \CURLINFO_HEADER_OUT, \CURLOPT_AUTOREFERER, \CURLOPT_BINARYTRANSFER, \CURLOPT_CERTINFO, \CURLOPT_CONNECT_ONLY, \CURLOPT_COOKIESESSION, \CURLOPT_CRLF, \CURLOPT_DISALLOW_USERNAME_IN_URL, \CURLOPT_DNS_SHUFFLE_ADDRESSES, \CURLOPT_DNS_USE_GLOBAL_CACHE, \CURLOPT_FAILONERROR, \CURLOPT_FILETIME, \CURLOPT_FOLLOWLOCATION, \CURLOPT_FORBID_REUSE, \CURLOPT_FRESH_CONNECT, \CURLOPT_FTPAPPEND, \CURLOPT_FTPASCII, \CURLOPT_FTPLISTONLY, \CURLOPT_FTP_CREATE_MISSING_DIRS, \CURLOPT_FTP_USE_EPRT, \CURLOPT_FTP_USE_EPSV, \CURLOPT_HAPROXYPROTOCOL, \CURLOPT_HEADER, \CURLOPT_HTTP09_ALLOWED, \CURLOPT_HTTPGET, \CURLOPT_HTTPPROXYTUNNEL, \CURLOPT_HTTP_CONTENT_DECODING, \CURLOPT_KEEP_SENDING_ON_ERROR, \CURLOPT_MUTE, \CURLOPT_NETRC, \CURLOPT_NOBODY, \CURLOPT_NOPROGRESS, \CURLOPT_NOSIGNAL, \CURLOPT_PATH_AS_IS, \CURLOPT_PIPEWAIT, \CURLOPT_POST, \CURLOPT_PROXY_SSL_VERIFYPEER, \CURLOPT_PUT, \CURLOPT_RETURNTRANSFER, \CURLOPT_SAFE_UPLOAD, \CURLOPT_SASL_IR, \CURLOPT_SSH_COMPRESSION, \CURLOPT_SSL_ENABLE_ALPN, \CURLOPT_SSL_ENABLE_NPN, \CURLOPT_SSL_FALSESTART, \CURLOPT_SSL_VERIFYPEER, \CURLOPT_SSL_VERIFYSTATUS, \CURLOPT_SUPPRESS_CONNECT_HEADERS, \CURLOPT_TCP_FASTOPEN, \CURLOPT_TCP_NODELAY, \CURLOPT_TFTP_NO_OPTIONS, \CURLOPT_TRANSFERTEXT, \CURLOPT_UNRESTRICTED_AUTH, \CURLOPT_UPLOAD, \CURLOPT_VERBOSE,
        \CURLOPT_BUFFERSIZE, \CURLOPT_CLOSEPOLICY, \CURLOPT_CONNECTTIMEOUT, \CURLOPT_CONNECTTIMEOUT_MS, \CURLOPT_DNS_CACHE_TIMEOUT, \CURLOPT_EXPECT_100_TIMEOUT_MS, \CURLOPT_FTPSSLAUTH, \CURLOPT_FTP_FILEMETHOD, \CURLOPT_HAPPY_EYEBALLS_TIMEOUT_MS, \CURLOPT_HEADEROPT, \CURLOPT_HTTPAUTH, \CURLOPT_HTTP_VERSION, \CURLOPT_INFILESIZE, \CURLOPT_IPRESOLVE, \CURLOPT_LOW_SPEED_LIMIT, \CURLOPT_LOW_SPEED_TIME, \CURLOPT_MAXCONNECTS, \CURLOPT_MAXREDIRS, \CURLOPT_MAX_RECV_SPEED_LARGE, \CURLOPT_MAX_SEND_SPEED_LARGE, \CURLOPT_PORT, \CURLOPT_POSTREDIR, \CURLOPT_PROTOCOLS, \CURLOPT_PROXYAUTH, \CURLOPT_PROXYPORT, \CURLOPT_PROXYTYPE, \CURLOPT_PROXY_SSLVERSION, \CURLOPT_PROXY_SSL_OPTIONS, \CURLOPT_PROXY_SSL_VERIFYHOST, \CURLOPT_REDIR_PROTOCOLS, \CURLOPT_RESUME_FROM, \CURLOPT_SOCKS5_AUTH, \CURLOPT_SSH_AUTH_TYPES, \CURLOPT_SSLVERSION, \CURLOPT_SSL_OPTIONS, \CURLOPT_SSL_VERIFYHOST, \CURLOPT_STREAM_WEIGHT, \CURLOPT_TCP_KEEPALIVE, \CURLOPT_TCP_KEEPIDLE, \CURLOPT_TCP_KEEPINTVL, \CURLOPT_TIMECONDITION, \CURLOPT_TIMEOUT, \CURLOPT_TIMEOUT_MS, \CURLOPT_TIMEVALUE, \CURLOPT_TIMEVALUE_LARGE,
        \CURLOPT_ABSTRACT_UNIX_SOCKET, \CURLOPT_CAINFO, \CURLOPT_CAPATH, \CURLOPT_COOKIE, \CURLOPT_COOKIEFILE, \CURLOPT_COOKIEJAR, \CURLOPT_COOKIELIST, \CURLOPT_CUSTOMREQUEST, \CURLOPT_DEFAULT_PROTOCOL, \CURLOPT_DNS_INTERFACE, \CURLOPT_DNS_LOCAL_IP4, \CURLOPT_DNS_LOCAL_IP6, \CURLOPT_EGDSOCKET, \CURLOPT_ENCODING, \CURLOPT_FTPPORT, \CURLOPT_INTERFACE, \CURLOPT_KEYPASSWD, \CURLOPT_KRB4LEVEL, \CURLOPT_LOGIN_OPTIONS, \CURLOPT_PINNEDPUBLICKEY, \CURLOPT_POSTFIELDS, \CURLOPT_PRE_PROXY, \CURLOPT_PRIVATE, \CURLOPT_PROXY, \CURLOPT_PROXYUSERPWD, \CURLOPT_PROXY_CAINFO, \CURLOPT_PROXY_CAPATH, \CURLOPT_PROXY_CRLFILE, \CURLOPT_PROXY_KEYPASSWD, \CURLOPT_PROXY_PINNEDPUBLICKEY, \CURLOPT_PROXY_SERVICE_NAME, \CURLOPT_PROXY_SSLCERT, \CURLOPT_PROXY_SSLCERTTYPE, \CURLOPT_PROXY_SSLKEY, \CURLOPT_PROXY_SSLKEYTYPE, \CURLOPT_PROXY_SSL_CIPHER_LIST, \CURLOPT_PROXY_TLS13_CIPHERS, \CURLOPT_PROXY_TLSAUTH_PASSWORD, \CURLOPT_PROXY_TLSAUTH_TYPE, \CURLOPT_PROXY_TLSAUTH_USERNAME, \CURLOPT_RANDOM_FILE, \CURLOPT_RANGE, \CURLOPT_REFERER, \CURLOPT_SERVICE_NAME, \CURLOPT_SSH_HOST_PUBLIC_KEY_MD5, \CURLOPT_SSH_PRIVATE_KEYFILE, \CURLOPT_SSH_PUBLIC_KEYFILE, \CURLOPT_SSLCERT, \CURLOPT_SSLCERTPASSWD, \CURLOPT_SSLCERTTYPE, \CURLOPT_SSLENGINE, \CURLOPT_SSLENGINE_DEFAULT, \CURLOPT_SSLKEY, \CURLOPT_SSLKEYPASSWD, \CURLOPT_SSLKEYTYPE, \CURLOPT_SSL_CIPHER_LIST, \CURLOPT_TLS13_CIPHERS, \CURLOPT_UNIX_SOCKET_PATH, \CURLOPT_URL, \CURLOPT_USERAGENT, \CURLOPT_USERNAME, \CURLOPT_USERPWD, \CURLOPT_XOAUTH2_BEARER,
        \CURLOPT_CONNECT_TO, \CURLOPT_HTTP200ALIASES, \CURLOPT_HTTPHEADER, \CURLOPT_POSTQUOTE, \CURLOPT_PROXYHEADER, \CURLOPT_QUOTE, \CURLOPT_RESOLVE,
        \CURLOPT_HEADERFUNCTION, \CURLOPT_PASSWDFUNCTION, \CURLOPT_PROGRESSFUNCTION, \CURLOPT_READFUNCTION, \CURLOPT_WRITEFUNCTION, \CURLOPT_SHARE,
        \CURLOPT_FILE, \CURLOPT_INFILE, \CURLOPT_STDERR, \CURLOPT_WRITEHEADER,
        \CURLOPT_ACCEPT_ENCODING, \CURLOPT_ADDRESS_SCOPE, \CURLOPT_APPEND, \CURLOPT_CRLFILE, \CURLOPT_DIRLISTONLY, \CURLOPT_DNS_SERVERS, \CURLOPT_FTP_ACCOUNT, \CURLOPT_FTP_ALTERNATIVE_TO_USER, \CURLOPT_FTP_RESPONSE_TIMEOUT, \CURLOPT_FTP_SKIP_PASV_IP, \CURLOPT_FTP_SSL, \CURLOPT_FTP_SSL_CCC, \CURLOPT_FTP_USE_PRET, \CURLOPT_HTTP_TRANSFER_DECODING, \CURLOPT_IGNORE_CONTENT_LENGTH, \CURLOPT_ISSUERCERT, \CURLOPT_KRBLEVEL, \CURLOPT_LOCALPORT, \CURLOPT_LOCALPORTRANGE, \CURLOPT_MAIL_FROM, \CURLOPT_MAIL_RCPT, \CURLOPT_MAXFILESIZE, \CURLOPT_NETRC_FILE, \CURLOPT_NEW_DIRECTORY_PERMS, \CURLOPT_NEW_FILE_PERMS, \CURLOPT_NOPROXY, \CURLOPT_PASSWORD, \CURLOPT_PREQUOTE, \CURLOPT_PROXYPASSWORD, \CURLOPT_PROXYUSERNAME, \CURLOPT_PROXY_TRANSFER_MODE, \CURLOPT_READDATA, \CURLOPT_REQUEST_TARGET, \CURLOPT_RTSP_CLIENT_CSEQ, \CURLOPT_RTSP_REQUEST, \CURLOPT_RTSP_SERVER_CSEQ, \CURLOPT_RTSP_SESSION_ID, \CURLOPT_RTSP_STREAM_URI, \CURLOPT_RTSP_TRANSPORT, \CURLOPT_SOCKS5_GSSAPI_NEC, \CURLOPT_SOCKS5_GSSAPI_SERVICE, \CURLOPT_SSH_KNOWNHOSTS, \CURLOPT_SSL_SESSIONID_CACHE, \CURLOPT_TFTP_BLKSIZE, \CURLOPT_TRANSFER_ENCODING, \CURLOPT_USE_SSL, \CURLOPT_TELNETOPTIONS, \CURLOPT_FNMATCH_FUNCTION, \CURLOPT_WILDCARDMATCH, \CURLOPT_TLSAUTH_PASSWORD, \CURLOPT_TLSAUTH_TYPE, \CURLOPT_TLSAUTH_USERNAME, \CURLOPT_GSSAPI_DELEGATION, \CURLOPT_ACCEPTTIMEOUT_MS, \CURLOPT_MAIL_AUTH);
    expectedArguments(\curl_setopt(), 2, \CURLAUTH_ANY | \CURLAUTH_ANYSAFE | \CURLAUTH_BASIC | \CURLAUTH_BEARER | \CURLAUTH_DIGEST | \CURLAUTH_GSSAPI | \CURLAUTH_GSSNEGOTIATE | \CURLAUTH_NEGOTIATE | \CURLAUTH_NTLM | \CURLAUTH_NTLM_WB | \CURLSSH_AUTH_AGENT | \CURLSSH_AUTH_GSSAPI, \CURL_SSLVERSION_DEFAULT, \CURL_SSLVERSION_MAX_DEFAULT, \CURL_SSLVERSION_MAX_NONE, \CURL_SSLVERSION_MAX_TLSv1_0, \CURL_SSLVERSION_MAX_TLSv1_1, \CURL_SSLVERSION_MAX_TLSv1_2, \CURL_SSLVERSION_MAX_TLSv1_3, \CURL_SSLVERSION_SSLv2, \CURL_SSLVERSION_SSLv3, \CURL_SSLVERSION_TLSv1, \CURL_SSLVERSION_TLSv1_0, \CURL_SSLVERSION_TLSv1_1, \CURL_SSLVERSION_TLSv1_2, \CURL_SSLVERSION_TLSv1_3,
        \CURLFTPAUTH_DEFAULT, \CURLFTPAUTH_SSL, \CURLFTPAUTH_TLS, \CURLFTPMETHOD_MULTICWD, \CURLFTPMETHOD_NOCWD, \CURLFTPMETHOD_SINGLECWD, \CURLFTPSSL_ALL, \CURLFTPSSL_CONTROL, \CURLFTPSSL_NONE, \CURLFTPSSL_TRY, \CURLFTP_CREATE_DIR, \CURLFTP_CREATE_DIR_NONE, \CURLFTP_CREATE_DIR_RETRY,
        \CURLPROTO_ALL | \CURLPROTO_DICT | \CURLPROTO_FILE | \CURLPROTO_FTP | \CURLPROTO_FTPS | \CURLPROTO_HTTPS | \CURLPROTO_LDAP | \CURLPROTO_LDAPS | \CURLPROTO_SCP | \CURLPROTO_SFTP | \CURLPROTO_SMB | \CURLPROTO_SMBS | \CURLPROTO_TELNET | \CURLPROTO_TFTP, \CURLPROXY_HTTP, \CURLPROXY_HTTPS, \CURLPROXY_HTTP_1_0, \CURLPROXY_SOCKS4, \CURLPROXY_SOCKS5,
        \CURL_HTTP_VERSION_1_0, \CURL_HTTP_VERSION_1_1, \CURL_HTTP_VERSION_2, \CURL_HTTP_VERSION_2TLS, \CURL_HTTP_VERSION_2_0, \CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE, \CURL_HTTP_VERSION_NONE,
        \CURL_IPRESOLVE_V4, \CURL_IPRESOLVE_V6, \CURL_IPRESOLVE_WHATEVER, \CURL_NETRC_IGNORED, \CURL_NETRC_OPTIONAL, \CURL_NETRC_REQUIRED, \CURL_REDIR_POST_301 | \CURL_REDIR_POST_302 | \CURL_REDIR_POST_303 | \CURL_REDIR_POST_ALL, \CURL_TIMECOND_IFMODSINCE, \CURL_TIMECOND_IFUNMODSINCE, \CURL_TIMECOND_LASTMOD, \CURLCLOSEPOLICY_CALLBACK, \CURLCLOSEPOLICY_LEAST_RECENTLY_USED, \CURLCLOSEPOLICY_LEAST_TRAFFIC, \CURLCLOSEPOLICY_OLDEST, \CURLCLOSEPOLICY_SLOWEST,
        \CURLHEADER_SEPARATE, \CURLHEADER_UNIFIED);
    expectedArguments(\curl_getinfo(), 1, \CURLINFO_REDIRECT_URL, \CURLINFO_PRIMARY_IP, \CURLINFO_PRIMARY_PORT, \CURLINFO_LOCAL_IP, \CURLINFO_LOCAL_PORT, \CURLINFO_RESPONSE_CODE, \CURLINFO_HTTP_CONNECTCODE, \CURLINFO_HTTPAUTH_AVAIL, \CURLINFO_PROXYAUTH_AVAIL, \CURLINFO_OS_ERRNO, \CURLINFO_NUM_CONNECTS, \CURLINFO_SSL_ENGINES, \CURLINFO_COOKIELIST, \CURLINFO_FTP_ENTRY_PATH, \CURLINFO_APPCONNECT_TIME, \CURLINFO_CERTINFO, \CURLINFO_CONDITION_UNMET, \CURLINFO_RTSP_CLIENT_CSEQ, \CURLINFO_RTSP_CSEQ_RECV, \CURLINFO_RTSP_SERVER_CSEQ, \CURLINFO_RTSP_SESSION_ID, \CURLINFO_EFFECTIVE_URL, \CURLINFO_HTTP_CODE, \CURLINFO_HEADER_SIZE, \CURLINFO_REQUEST_SIZE, \CURLINFO_TOTAL_TIME, \CURLINFO_NAMELOOKUP_TIME, \CURLINFO_CONNECT_TIME, \CURLINFO_PRETRANSFER_TIME, \CURLINFO_SIZE_UPLOAD, \CURLINFO_SIZE_DOWNLOAD, \CURLINFO_SPEED_DOWNLOAD, \CURLINFO_SPEED_UPLOAD, \CURLINFO_FILETIME, \CURLINFO_SSL_VERIFYRESULT, \CURLINFO_CONTENT_LENGTH_DOWNLOAD, \CURLINFO_CONTENT_LENGTH_UPLOAD, \CURLINFO_STARTTRANSFER_TIME, \CURLINFO_CONTENT_TYPE, \CURLINFO_REDIRECT_TIME, \CURLINFO_REDIRECT_COUNT, \CURLINFO_HEADER_OUT, \CURLINFO_PRIVATE, \CURLINFO_CONTENT_LENGTH_DOWNLOAD_T, \CURLINFO_CONTENT_LENGTH_UPLOAD_T, \CURLINFO_SIZE_DOWNLOAD_T, \CURLINFO_SIZE_UPLOAD_T, \CURLINFO_SPEED_DOWNLOAD_T, \CURLINFO_SPEED_UPLOAD_T, \CURLINFO_FILETIME_T, \CURLINFO_APPCONNECT_TIME_T, \CURLINFO_CONNECT_TIME_T, \CURLINFO_NAMELOOKUP_TIME_T, \CURLINFO_PRETRANSFER_TIME_T, \CURLINFO_REDIRECT_TIME_T, \CURLINFO_STARTTRANSFER_TIME_T, \CURLINFO_TOTAL_TIME_T, \CURLINFO_LASTONE, \CURLINFO_PROTOCOL, \CURLINFO_SCHEME, \CURLINFO_HTTP_VERSION, \CURLINFO_PROXY_SSL_VERIFYRESULT, \CURLINFO_EFFECTIVE_METHOD);
    expectedArguments(\curl_pause(), 1, \CURLPAUSE_ALL | \CURLPAUSE_CONT | \CURLPAUSE_RECV | \CURLPAUSE_RECV_CONT | \CURLPAUSE_SEND | \CURLPAUSE_SEND_CONT);
    registerArgumentsSet('curl_errors', \CURLE_OK, \CURLE_UNSUPPORTED_PROTOCOL, \CURLE_FAILED_INIT, \CURLE_URL_MALFORMAT, \CURLE_URL_MALFORMAT_USER, \CURLE_COULDNT_RESOLVE_PROXY, \CURLE_COULDNT_RESOLVE_HOST, \CURLE_COULDNT_CONNECT, \CURLE_FTP_WEIRD_SERVER_REPLY, \CURLE_FTP_ACCESS_DENIED, \CURLE_FTP_USER_PASSWORD_INCORRECT, \CURLE_FTP_WEIRD_PASS_REPLY, \CURLE_FTP_WEIRD_USER_REPLY, \CURLE_FTP_WEIRD_PASV_REPLY, \CURLE_FTP_WEIRD_227_FORMAT, \CURLE_FTP_CANT_GET_HOST, \CURLE_FTP_CANT_RECONNECT, \CURLE_FTP_COULDNT_SET_BINARY, \CURLE_PARTIAL_FILE, \CURLE_FTP_COULDNT_RETR_FILE, \CURLE_FTP_WRITE_ERROR, \CURLE_FTP_QUOTE_ERROR, \CURLE_HTTP_NOT_FOUND, \CURLE_WRITE_ERROR, \CURLE_MALFORMAT_USER, \CURLE_FTP_COULDNT_STOR_FILE, \CURLE_READ_ERROR, \CURLE_OUT_OF_MEMORY, \CURLE_OPERATION_TIMEOUTED, \CURLE_FTP_COULDNT_SET_ASCII, \CURLE_FTP_PORT_FAILED, \CURLE_FTP_COULDNT_USE_REST, \CURLE_FTP_COULDNT_GET_SIZE, \CURLE_HTTP_RANGE_ERROR, \CURLE_HTTP_POST_ERROR, \CURLE_SSL_CONNECT_ERROR, \CURLE_FTP_BAD_DOWNLOAD_RESUME, \CURLE_FILE_COULDNT_READ_FILE, \CURLE_LDAP_CANNOT_BIND, \CURLE_LDAP_SEARCH_FAILED, \CURLE_LIBRARY_NOT_FOUND, \CURLE_FUNCTION_NOT_FOUND, \CURLE_ABORTED_BY_CALLBACK, \CURLE_BAD_FUNCTION_ARGUMENT, \CURLE_BAD_CALLING_ORDER, \CURLE_HTTP_PORT_FAILED, \CURLE_BAD_PASSWORD_ENTERED, \CURLE_TOO_MANY_REDIRECTS, \CURLE_UNKNOWN_TELNET_OPTION, \CURLE_TELNET_OPTION_SYNTAX, \CURLE_OBSOLETE, \CURLE_SSL_PEER_CERTIFICATE, \CURLE_GOT_NOTHING, \CURLE_SSL_ENGINE_NOTFOUND, \CURLE_SSL_ENGINE_SETFAILED, \CURLE_SEND_ERROR, \CURLE_RECV_ERROR, \CURLE_SHARE_IN_USE, \CURLE_SSL_CERTPROBLEM, \CURLE_SSL_CIPHER, \CURLE_SSL_CACERT, \CURLE_BAD_CONTENT_ENCODING, \CURLE_LDAP_INVALID_URL, \CURLE_FILESIZE_EXCEEDED, \CURLE_FTP_SSL_FAILED, \CURLE_BAD_DOWNLOAD_RESUME, \CURLE_FTP_PARTIAL_FILE, \CURLE_HTTP_RETURNED_ERROR, \CURLE_OPERATION_TIMEDOUT, \CURLE_SSL_PINNEDPUBKEYNOTMATCH, \CURLE_SSL_CACERT_BADFILE, \CURLE_SSH, CURLE_WEIRD_SERVER_REPLY);
    expectedReturnValues(\curl_errno(), argumentsSet('curl_errors'));
    expectedArguments(\curl_strerror(), 0, argumentsSet('curl_errors'));
    expectedReturnValues(\curl_pause(), argumentsSet('curl_errors'));
    expectedArguments(\curl_multi_setopt(), 1, \CURLMOPT_PIPELINING, \CURLMOPT_MAXCONNECTS, \CURLMOPT_CHUNK_LENGTH_PENALTY_SIZE, \CURLMOPT_CONTENT_LENGTH_PENALTY_SIZE, \CURLMOPT_MAX_HOST_CONNECTIONS, \CURLMOPT_MAX_PIPELINE_LENGTH, \CURLMOPT_MAX_TOTAL_CONNECTIONS, \CURLMOPT_PUSHFUNCTION);
    expectedArguments(\curl_multi_setopt(), 2, \CURLPIPE_NOTHING, \CURLPIPE_MULTIPLEX, \CURLPIPE_HTTP1);
    registerArgumentsSet('curl_multi_errors', \CURLM_OK, \CURLM_CALL_MULTI_PERFORM, \CURLM_BAD_HANDLE, \CURLM_BAD_EASY_HANDLE, \CURLM_OUT_OF_MEMORY, \CURLM_INTERNAL_ERROR, \CURLM_ADDED_ALREADY);
    expectedArguments(\curl_multi_strerror(), 0, argumentsSet('curl_multi_errors'));
    expectedReturnValues(\curl_multi_errno(), argumentsSet('curl_multi_errors'));
    expectedReturnValues(\curl_multi_exec(), argumentsSet('curl_multi_errors'));
    expectedReturnValues(\curl_multi_add_handle(), argumentsSet('curl_multi_errors'));
    expectedReturnValues(\curl_multi_remove_handle(), argumentsSet('curl_multi_errors'));
    expectedArguments(\curl_share_setopt(), 1, \CURLSHOPT_NONE, \CURLSHOPT_SHARE, \CURLSHOPT_UNSHARE);
    expectedArguments(\curl_share_setopt(), 2, \CURL_LOCK_DATA_COOKIE, \CURL_LOCK_DATA_DNS, \CURL_LOCK_DATA_SSL_SESSION, \CURL_LOCK_DATA_CONNECT, \CURL_LOCK_DATA_PSL);

    registerArgumentsSet('common_dirname_return', __DIR__);
    expectedArguments(\dirname(), 0, argumentsSet('common_dirname_return'), __FILE__);
    expectedReturnValues(\dirname(), argumentsSet('common_dirname_return'));    // this allows completion for "dirname()" inside dirname(<caret>)

    expectedArguments(\EvLoop::__construct(), 0, \Ev::FLAG_AUTO,\Ev::FLAG_NOENV,\Ev::FLAG_FORKCHECK,\Ev::FLAG_NOINOTIFY,\Ev::FLAG_SIGNALFD,\Ev::FLAG_NOSIGMASK); //todo support
    expectedArguments(\DatePeriod::__construct(), 3, \DatePeriod::EXCLUDE_START_DATE | \DatePeriod::INCLUDE_END_DATE); //todo support multiple signatures
    expectedArguments(\Ev::run(), 0, \Ev::FLAG_AUTO,\Ev::FLAG_NOENV,\Ev::FLAG_FORKCHECK,\Ev::FLAG_NOINOTIFY,\Ev::FLAG_SIGNALFD,\Ev::FLAG_NOSIGMASK);
    expectedArguments(\EvLoop::run(), 0, \Ev::RUN_NOWAIT,\Ev::RUN_ONCE);
    expectedArguments(\EvLoop::defaultLoop(), 0, \Ev::FLAG_AUTO,\Ev::FLAG_NOENV,\Ev::FLAG_FORKCHECK,\Ev::FLAG_NOINOTIFY,\Ev::FLAG_SIGNALFD,\Ev::FLAG_NOSIGMASK);
    expectedArguments(\Event::pending(), 0, \Event::READ|\Event::WRITE|\Event::TIMEOUT|\Event::SIGNAL);
    expectedArguments(\EventBase::loop(), 0, \EventBase::LOOP_ONCE, \EventBase::LOOP_NONBLOCK, \EventBase::NOLOCK, \EventBase::STARTUP_IOCP, \EventBase::NO_CACHE_TIME, \EventBase::EPOLL_USE_CHANGELIST);

	expectedArguments(\extension_loaded(), 0, 'amqp', 'apache', 'apc', 'apd', 'bbcode', 'bcmath', 'bcompiler', 'bz2', 'cairo', 'calendar', 'chdb', 'classkit', 'com', 'crack', 'ctype', 'cubrid', 'curl', 'cyrus', 'dba', 'dbase', 'dbplus', 'dbx', 'dio', 'dom', 'dotnet', 'eio', 'enchant', 'ev', 'event', 'exif', 'expect', 'fam', 'fbsql', 'fdf', 'fileinfo', 'filepro', 'filter', 'fribidi', 'ftp', 'gearman', 'gender', 'geoip', 'gettext', 'gmagick', 'gmp', 'gnupg', 'gupnp', 'haru', 'htscanner', 'pecl_http', 'hyperwave', 'hwapi', 'interbase', 'ibm_db2', 'iconv', 'id3', 'informix', 'iisfunc', 'gd', 'imagick', 'imap', 'inclued', 'ingres', 'inotify', 'intl', 'java', 'json', 'judy', 'kadm5', 'ktaglib', 'lapack', 'ldap', 'libevent', 'libxml', 'lua', 'lzf', 'mailparse', 'maxdb', 'mbstring', 'mcrypt', 'mcve', 'memcache', 'memcached', 'memtrack', 'mhash', 'ming', 'mnogosearch', 'mongo', 'mqseries', 'msession', 'msql', 'mssql', 'mysql', 'mysqli', 'mysqlnd', 'mysqlnd_memcache', 'mysqlnd_ms', 'mysqlnd_mux', 'mysqlnd_qc', 'mysqlnd_uh', 'ncurses', 'net_gopher', 'newt', 'notes', 'nsapi', 'oauth', 'oci8', 'oggvorbis', 'openal', 'openssl', 'ovrimos', 'paradox', 'parsekit', 'pcntl', 'pcre', 'pdflib', 'pdo', 'pdo_4d', 'pdo_cubrid', 'pdo_dblib', 'pdo_firebird', 'pdo_ibm', 'pdo_informix', 'pdo_mysql', 'pdo_oci', 'pdo_odbc', 'pdo_pgsql', 'pdo_sqlite', 'pdo_sqlsrv', 'pdo_pgsql', 'phar', 'posix', 'printer', 'proctitle', 'ps', 'pspell', 'pthreads', 'qtdom', 'quickhash', 'radius', 'rar', 'readline', 'recode', 'rpmreader', 'rrd', 'runkit', 'sam', 'sca', 'scream', 'sca_sdo', 'sysvmsg', 'session', 'session_pgsql', 'shmop', 'simplexml', 'snmp', 'soap', 'sockets', 'solr', 'sphinx', 'spl_types', 'spplus', 'sqlite', 'sqlite3', 'sqlsrv', 'ssdeep', 'ssh2', 'stats', 'stomp', 'svm', 'svn', 'swf', 'swish', 'sybase', 'taint', 'tcpwrap', 'tidy', 'tokenizer', 'tokyo_tyrant', 'trader', 'odbc', 'v8js', 'varnish', 'vpopmail', 'w32api', 'wddx', 'weakref', 'win32ps', 'win32service', 'wincache', 'xattr', 'xdiff', 'xhprof', 'xml', 'xmlreader', 'xmlrpc', 'xmlwriter', 'xsl', 'xslt', 'yaf', 'yaml', 'yaz', 'zip', 'zlib');

    registerArgumentsSet('error_levels', E_ALL|E_ERROR|E_WARNING|E_PARSE|E_NOTICE|E_STRICT|E_RECOVERABLE_ERROR|E_DEPRECATED|E_CORE_ERROR|E_CORE_WARNING|E_COMPILE_ERROR|E_COMPILE_WARNING|E_USER_ERROR|E_USER_WARNING|E_USER_NOTICE|E_USER_DEPRECATED);
    expectedArguments(\error_reporting(), 0, argumentsSet('error_levels'));
    expectedReturnValues(\error_reporting(), argumentsSet('error_levels'));

    registerArgumentsSet('user_error_levels', E_USER_NOTICE,E_USER_WARNING,E_USER_ERROR,E_USER_DEPRECATED);
    expectedArguments(\trigger_error(), 1, argumentsSet('user_error_levels'));
    expectedArguments(\user_error(), 1, argumentsSet('user_error_levels'));

    expectedArguments(\ftp_get(), 3, FTP_ASCII,FTP_BINARY);
    expectedArguments(\ftp_fget(), 3, FTP_ASCII,FTP_BINARY);
    expectedArguments(\ftp_put(), 3, FTP_ASCII,FTP_BINARY);
    expectedArguments(\ftp_fput(), 3, FTP_ASCII,FTP_BINARY);

	expectedArguments(\fopen(), 1, 'r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+', 'e');
	expectedArguments(\popen(), 1, 'r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+', 'e');
	expectedArguments(\SplFileInfo::openFile(), 0, 'r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+', 'e');

	expectedArguments(\htmlentities(), 1, ENT_COMPAT | ENT_QUOTES | ENT_NOQUOTES | ENT_IGNORE | ENT_SUBSTITUTE | ENT_DISALLOWED | ENT_HTML401 | ENT_XML1 | ENT_XHTML | ENT_HTML5);
	expectedArguments(\htmlspecialchars(), 1, ENT_COMPAT | ENT_QUOTES | ENT_NOQUOTES | ENT_IGNORE | ENT_SUBSTITUTE | ENT_DISALLOWED | ENT_HTML401 | ENT_XML1 | ENT_XHTML | ENT_HTML5);
	expectedArguments(\html_entity_decode(), 1, ENT_COMPAT | ENT_QUOTES | ENT_NOQUOTES | ENT_HTML401 | ENT_XML1 | ENT_XHTML | ENT_HTML5);
    expectedArguments(\htmlspecialchars_decode(), 1, ENT_COMPAT | ENT_QUOTES | ENT_NOQUOTES | ENT_HTML401 | ENT_XML1 | ENT_XHTML | ENT_HTML5);

    expectedArguments(\parse_url(), 1, PHP_URL_SCHEME, PHP_URL_HOST, PHP_URL_PORT, PHP_URL_USER, PHP_URL_PASS, PHP_URL_PATH, PHP_URL_QUERY, PHP_URL_FRAGMENT);

    expectedArguments(\iconv_mime_decode(), 1, ICONV_MIME_DECODE_STRICT,ICONV_MIME_DECODE_CONTINUE_ON_ERROR);
    expectedArguments(\iconv_mime_decode_headers(), 1, ICONV_MIME_DECODE_STRICT,ICONV_MIME_DECODE_CONTINUE_ON_ERROR);
    expectedArguments(\Imagick::setImageAlphaChannel(), 0, \Imagick::ALPHACHANNEL_ACTIVATE,\Imagick::ALPHACHANNEL_BACKGROUND,\Imagick::ALPHACHANNEL_COPY,\Imagick::ALPHACHANNEL_DEACTIVATE,\Imagick::ALPHACHANNEL_EXTRACT,\Imagick::ALPHACHANNEL_FLATTEN,\Imagick::ALPHACHANNEL_DEACTIVATE,\Imagick::ALPHACHANNEL_OPAQUE,\Imagick::ALPHACHANNEL_REMOVE,\Imagick::ALPHACHANNEL_RESET,\Imagick::ALPHACHANNEL_SET,\Imagick::ALPHACHANNEL_SHAPE,\Imagick::ALPHACHANNEL_TRANSPARENT,\Imagick::ALPHACHANNEL_UNDEFINED); //todo introduce byPrefix
    expectedArguments(\Imagick::montageImage(), 3, \Imagick::MONTAGEMODE_FRAME,\Imagick::MONTAGEMODE_UNFRAME,\Imagick::MONTAGEMODE_CONCATENATE);

    registerArgumentsSet('imagetypes',
        IMAGETYPE_GIF,
        IMAGETYPE_JPEG,
        IMAGETYPE_PNG,
        IMAGETYPE_SWF,
        IMAGETYPE_PSD,
        IMAGETYPE_BMP,
        IMAGETYPE_TIFF_II,
        IMAGETYPE_TIFF_MM,
        IMAGETYPE_JPC,
        IMAGETYPE_JP2,
        IMAGETYPE_JPX,
        IMAGETYPE_JB2,
        IMAGETYPE_SWC,
        IMAGETYPE_IFF,
        IMAGETYPE_WBMP,
        IMAGETYPE_XBM,
        IMAGETYPE_ICO,
        IMAGETYPE_WEBP
    );
    expectedArguments(\image_type_to_extension(), 0, argumentsSet('imagetypes'));
    expectedArguments(\image_type_to_mime_type(), 0, argumentsSet('imagetypes'));
    expectedReturnValues(\exif_imagetype(), argumentsSet('imagetypes'));

    expectedArguments(\exif_read_data(), 1, 'FILE', 'COMPUTED', 'ANY_TAG', 'IFD0', 'THUMBNAIL', 'COMMENT', 'EXIF');

    expectedReturnValues(\expect_expectl(), \EXP_EOF, \EXP_TIMEOUT, \EXP_FULLBUFFER);

    expectedArguments(\imap_close(), 1, CL_EXPUNGE);
    expectedArguments(\event_base_loop(), 1, EVLOOP_ONCE|EVLOOP_NONBLOCK);
    expectedArguments(\mb_convert_case(), 1, MB_CASE_UPPER,MB_CASE_LOWER,MB_CASE_TITLE,MB_CASE_FOLD,MB_CASE_UPPER_SIMPLE,MB_CASE_LOWER_SIMPLE,MB_CASE_TITLE_SIMPLE,MB_CASE_FOLD_SIMPLE);
	expectedArguments(\mb_get_info(), 0, 'all', 'http_output', 'http_input', 'internal_encoding', 'func_overload');
	expectedArguments(\mb_language(), 0, 'Japanese', 'ja', 'English', 'en', 'uni');
    expectedArguments(\MemcachePool::add(), 2, MEMCACHE_COMPRESSED);
    expectedArguments(\MemcachePool::set(), 2, MEMCACHE_COMPRESSED);
    expectedArguments(\MemcachePool::replace(), 2, MEMCACHE_COMPRESSED);

    expectedArguments(\MongoDB\Driver\ReadPreference::__construct(), 0, \MongoDB\Driver\ReadPreference::RP_PRIMARY,\MongoDB\Driver\ReadPreference::RP_PRIMARY_PREFERRED,\MongoDB\Driver\ReadPreference::RP_SECONDARY,\MongoDB\Driver\ReadPreference::RP_SECONDARY_PREFERRED,\MongoDB\Driver\ReadPreference::RP_NEAREST);
    expectedArguments(\mysqli::begin_transaction(), 0, MYSQLI_TRANS_START_READ_ONLY,MYSQLI_TRANS_START_READ_WRITE,MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);
    expectedArguments(\mysqli::commit(), 0, MYSQLI_TRANS_COR_AND_CHAIN|MYSQLI_TRANS_COR_AND_NO_CHAIN|MYSQLI_TRANS_COR_NO_RELEASE|MYSQLI_TRANS_COR_RELEASE);
    expectedArguments(\mysqli::real_connect(), 6, MYSQLI_CLIENT_COMPRESS|MYSQLI_CLIENT_FOUND_ROWS|MYSQLI_CLIENT_IGNORE_SPACE|MYSQLI_CLIENT_INTERACTIVE|MYSQLI_CLIENT_SSL|MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
    expectedArguments(\mysqli::rollback(), 0, MYSQLI_TRANS_COR_AND_CHAIN|MYSQLI_TRANS_COR_AND_NO_CHAIN|MYSQLI_TRANS_COR_NO_RELEASE|MYSQLI_TRANS_COR_RELEASE);
    expectedArguments(\mysqli_begin_transaction(), 1, MYSQLI_TRANS_START_READ_ONLY,MYSQLI_TRANS_START_READ_WRITE,MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);
    expectedArguments(\mysqli_report(), 0, MYSQLI_REPORT_OFF,MYSQLI_REPORT_ERROR,MYSQLI_REPORT_STRICT,MYSQLI_REPORT_INDEX,MYSQLI_REPORT_ALL);
    expectedArguments(\mysqli_real_connect(), 7, MYSQLI_CLIENT_COMPRESS|MYSQLI_CLIENT_FOUND_ROWS|MYSQLI_CLIENT_IGNORE_SPACE|MYSQLI_CLIENT_INTERACTIVE|MYSQLI_CLIENT_SSL|MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
    registerArgumentsSet("mysqliAttributesSet", MYSQLI_STMT_ATTR_UPDATE_MAX_LENGTH, MYSQLI_STMT_ATTR_CURSOR_TYPE, MYSQLI_STMT_ATTR_PREFETCH_ROWS);
    expectedArguments(\mysqli_stmt::attr_set(), 0, argumentsSet("mysqliAttributesSet"));
    expectedArguments(\mysqli_stmt_attr_set(), 1, argumentsSet("mysqliAttributesSet"));

	  expectedArguments(\ob_start(), 2, \PHP_OUTPUT_HANDLER_CLEANABLE | \PHP_OUTPUT_HANDLER_FLUSHABLE | PHP_OUTPUT_HANDLER_REMOVABLE, PHP_OUTPUT_HANDLER_STDFLAGS);
    expectedArguments(\OCI_Lob::flush(), 0, OCI_LOB_BUFFER_FREE);
    expectedArguments(\OCILob::flush(), 0, OCI_LOB_BUFFER_FREE);
    expectedArguments(\oci_execute(), 1, OCI_COMMIT_ON_SUCCESS,OCI_DESCRIBE_ONLY,OCI_NO_AUTO_COMMIT);
    expectedArguments(\odbc_binmode(), 1, ODBC_BINMODE_PASSTHRU,ODBC_BINMODE_RETURN,ODBC_BINMODE_CONVERT);
    expectedArguments(\openlog(), 1, LOG_CONS|LOG_NDELAY|LOG_ODELAY|LOG_PERROR|LOG_PID);
    expectedArguments(\openlog(), 2, LOG_USER,LOG_AUTH,LOG_AUTHPRIV,LOG_CRON,LOG_DAEMON,LOG_KERN,LOG_LOCAL0,LOG_LOCAL1,LOG_LOCAL2,LOG_LOCAL3,LOG_LOCAL4,LOG_LOCAL5,LOG_LOCAL6,LOG_LOCAL7,LOG_LPR,LOG_MAIL,LOG_NEWS,LOG_SYSLOG,LOG_UUCP);
    expectedArguments(\openssl_pkcs7_verify(), 1, PKCS7_TEXT|PKCS7_BINARY|PKCS7_NOINTERN|PKCS7_NOVERIFY|PKCS7_NOCHAIN|PKCS7_NOCERTS|PKCS7_NOATTR|PKCS7_DETACHED|PKCS7_NOSIGS);
    expectedArguments(\openssl_pkcs7_sign(), 5, PKCS7_TEXT|PKCS7_BINARY|PKCS7_NOINTERN|PKCS7_NOVERIFY|PKCS7_NOCHAIN|PKCS7_NOCERTS|PKCS7_NOATTR|PKCS7_DETACHED|PKCS7_NOSIGS);
    expectedArguments(\openssl_pkcs7_encrypt(), 4, PKCS7_TEXT|PKCS7_BINARY|PKCS7_NOINTERN|PKCS7_NOVERIFY|PKCS7_NOCHAIN|PKCS7_NOCERTS|PKCS7_NOATTR|PKCS7_DETACHED|PKCS7_NOSIGS);
    expectedArguments(\preg_match(), 3, PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL);
    expectedArguments(\preg_match_all(), 3, PREG_PATTERN_ORDER|PREG_SET_ORDER|PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL);
    expectedArguments(\preg_split(), 3, PREG_SPLIT_NO_EMPTY);
    expectedArguments(\preg_grep(), 2, PREG_GREP_INVERT);

    expectedArguments(\PDO::query(), 1, \PDO::ATTR_FETCH_CATALOG_NAMES,\PDO::ATTR_FETCH_TABLE_NAMES,\PDO::ATTR_DEFAULT_FETCH_MODE,\PDO::ATTR_STRINGIFY_FETCHES);
    expectedArguments(\PDO::setAttribute(), 0, \PDO::ATTR_CASE,\PDO::ATTR_ERRMODE,\PDO::ATTR_ORACLE_NULLS,\PDO::ATTR_STRINGIFY_FETCHES, \PDO::ATTR_STATEMENT_CLASS, \PDO::ATTR_TIMEOUT, \PDO::ATTR_AUTOCOMMIT, \PDO::ATTR_EMULATE_PREPARES, \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, \PDO::ATTR_DEFAULT_FETCH_MODE);
    expectedArguments(\PDOStatement::bindColumn(), 2, \PDO::PARAM_NULL|\PDO::PARAM_INT|\PDO::PARAM_STR|\PDO::PARAM_LOB|\PDO::PARAM_STMT|\PDO::PARAM_BOOL|\PDO::PARAM_STR_NATL|\PDO::PARAM_STR_CHAR|\PDO::ATTR_DEFAULT_STR_PARAM|\PDO::PARAM_INPUT_OUTPUT|\PDO::PARAM_EVT_ALLOC|\PDO::PARAM_EVT_FREE|\PDO::PARAM_EVT_EXEC_PRE|\PDO::PARAM_EVT_EXEC_POST|\PDO::PARAM_EVT_FETCH_PRE|\PDO::PARAM_EVT_FETCH_POST|\PDO::PARAM_EVT_NORMALIZE);
    expectedArguments(\PDOStatement::fetch(), 0, \PDO::FETCH_ASSOC, \PDO::FETCH_BOTH, \PDO::FETCH_BOUND, \PDO::FETCH_CLASS, \PDO::FETCH_INTO, \PDO::FETCH_LAZY, \PDO::FETCH_NAMED, \PDO::FETCH_NUM, \PDO::FETCH_OBJ, \PDO::FETCH_PROPS_LATE);
    expectedArguments(\PDOStatement::fetch(), 1, \PDO::FETCH_ORI_NEXT, \PDO::FETCH_ORI_PRIOR, \PDO::FETCH_ORI_FIRST, \PDO::FETCH_ORI_LAST, \PDO::FETCH_ORI_ABS, \PDO::FETCH_ORI_REL);
    expectedArguments(\PDOStatement::setFetchMode(), 0, \PDO::ATTR_FETCH_CATALOG_NAMES,\PDO::ATTR_FETCH_TABLE_NAMES,\PDO::ATTR_DEFAULT_FETCH_MODE,\PDO::ATTR_STRINGIFY_FETCHES);
    expectedArguments(\Phar::__construct(), 1, \FilesystemIterator::KEY_AS_PATHNAME|\FilesystemIterator::CURRENT_AS_FILEINFO);
    expectedArguments(\PharData::__construct(), 1, \FilesystemIterator::KEY_AS_PATHNAME|\FilesystemIterator::CURRENT_AS_FILEINFO);
    expectedArguments(\posix_mknod(), 1, POSIX_S_IFREG,POSIX_S_IFCHR,POSIX_S_IFBLK,POSIX_S_IFIFO,POSIX_S_IFSOCK);
    expectedArguments(\posix_access(), 1, POSIX_F_OK|POSIX_R_OK|POSIX_W_OK|POSIX_X_OK);
    expectedArguments(\pspell_new(), 4, PSPELL_FAST,PSPELL_NORMAL,PSPELL_BAD_SPELLERS,PSPELL_RUN_TOGETHER);
    expectedArguments(\pspell_new_personal(), 5, PSPELL_FAST,PSPELL_NORMAL,PSPELL_BAD_SPELLERS,PSPELL_RUN_TOGETHER);
    expectedArguments(\SoapServer::setPersistence(), 0, SOAP_PERSISTENCE_REQUEST,SOAP_PERSISTENCE_SESSION);
    expectedArguments(\socket_recv(), 3, MSG_OOB|MSG_PEEK|MSG_WAITALL|MSG_DONTWAIT);
    expectedArguments(\socket_send(), 3, MSG_OOB|MSG_EOR|MSG_EOF|MSG_DONTROUTE);
    expectedArguments(\socket_recvfrom(), 3, MSG_OOB|MSG_PEEK|MSG_WAITALL|MSG_DONTWAIT);
    expectedArguments(\socket_sendto(), 3, MSG_OOB|MSG_EOR|MSG_EOF|MSG_DONTROUTE);

    expectedArguments(\RecursiveIteratorIterator::__construct(), 1, \RecursiveIteratorIterator::LEAVES_ONLY,\RecursiveIteratorIterator::SELF_FIRST,\RecursiveIteratorIterator::CHILD_FIRST);
    expectedArguments(\RecursiveIteratorIterator::__construct(), 2, \RecursiveIteratorIterator::CATCH_GET_CHILD);
    expectedArguments(\RecursiveCachingIterator::__construct(), 1, \CachingIterator::CALL_TOSTRING|\CachingIterator::TOSTRING_USE_KEY|\CachingIterator::TOSTRING_USE_CURRENT|\CachingIterator::TOSTRING_USE_INNER);
    expectedArguments(\RegexIterator::__construct(), 2, \RegexIterator::MATCH,\RegexIterator::GET_MATCH,\RegexIterator::ALL_MATCHES,\RegexIterator::SPLIT,\RegexIterator::REPLACE);
    expectedArguments(\RegexIterator::__construct(), 3, \RegexIterator::USE_KEY);
    expectedArguments(\RegexIterator::__construct(), 4, \RegexIterator::USE_KEY);
    expectedArguments(\RegexIterator::setMode(), 0, \RegexIterator::MATCH,\RegexIterator::GET_MATCH,\RegexIterator::ALL_MATCHES,\RegexIterator::SPLIT,\RegexIterator::REPLACE);
    expectedArguments(\RegexIterator::setFlags(), 0, \RegexIterator::USE_KEY);
    expectedArguments(\RecursiveRegexIterator::__construct(), 2, \RegexIterator::MATCH,\RegexIterator::GET_MATCH,\RegexIterator::ALL_MATCHES,\RegexIterator::SPLIT,\RegexIterator::REPLACE);
    expectedArguments(\RecursiveRegexIterator::__construct(), 3, \RegexIterator::USE_KEY);
    expectedArguments(\RecursiveRegexIterator::__construct(), 4, \RegexIterator::USE_KEY);
    expectedArguments(\RecursiveTreeIterator::__construct(), 1, \RecursiveTreeIterator::BYPASS_KEY);
    expectedArguments(\RecursiveTreeIterator::__construct(), 2, \CachingIterator::CATCH_GET_CHILD);
    expectedArguments(\RecursiveTreeIterator::__construct(), 3, \RecursiveIteratorIterator::SELF_FIRST);
    expectedArguments(\ArrayObject::__construct(), 1, \ArrayObject::STD_PROP_LIST|\ArrayObject::ARRAY_AS_PROPS);
    expectedArguments(\ArrayIterator::__construct(), 1, \ArrayIterator::STD_PROP_LIST|\ArrayIterator::ARRAY_AS_PROPS);

    expectedArguments(\FilesystemIterator::setFlags(), 0, \FilesystemIterator::CURRENT_MODE_MASK,\FilesystemIterator::CURRENT_AS_PATHNAME,\FilesystemIterator::CURRENT_AS_FILEINFO,\FilesystemIterator::CURRENT_AS_SELF,\FilesystemIterator::KEY_MODE_MASK,\FilesystemIterator::KEY_AS_PATHNAME,\FilesystemIterator::FOLLOW_SYMLINKS,\FilesystemIterator::KEY_AS_FILENAME,\FilesystemIterator::NEW_CURRENT_AND_KEY,\FilesystemIterator::SKIP_DOTS,\FilesystemIterator::UNIX_PATHS,\FilesystemIterator::OTHER_MODE_MASK);

    registerArgumentsSet("splFileObjectFlags", \SplFileObject::DROP_NEW_LINE|\SplFileObject::READ_AHEAD|\SplFileObject::SKIP_EMPTY|\SplFileObject::READ_CSV);
    expectedArguments(\SplFileObject::setFlags(), 0, argumentsSet("splFileObjectFlags"));
    expectedArguments(\SplFileObject::flock(), 0, LOCK_SH, LOCK_EX, LOCK_UN, LOCK_NB);
    expectedArguments(\SplDoublyLinkedList::setIteratorMode(), 0, \SplDoublyLinkedList::IT_MODE_LIFO|\SplDoublyLinkedList::IT_MODE_FIFO|\SplDoublyLinkedList::IT_MODE_DELETE|\SplDoublyLinkedList::IT_MODE_KEEP);
    expectedArguments(\SplQueue::setIteratorMode(), 0, \SplDoublyLinkedList::IT_MODE_LIFO|\SplDoublyLinkedList::IT_MODE_FIFO|\SplDoublyLinkedList::IT_MODE_DELETE|\SplDoublyLinkedList::IT_MODE_KEEP);
    expectedArguments(\SplStack::setIteratorMode(), 0, \SplDoublyLinkedList::IT_MODE_LIFO|\SplDoublyLinkedList::IT_MODE_FIFO|\SplDoublyLinkedList::IT_MODE_DELETE|\SplDoublyLinkedList::IT_MODE_KEEP);
    expectedArguments(\SplPriorityQueue::setExtractFlags(), 0, \SplPriorityQueue::EXTR_DATA,\SplPriorityQueue::EXTR_PRIORITY,\SplPriorityQueue::EXTR_BOTH);
    registerArgumentsSet("multipleIteratorFlags", \MultipleIterator::MIT_NEED_ANY|\MultipleIterator::MIT_NEED_ALL|\MultipleIterator::MIT_KEYS_NUMERIC|\MultipleIterator::MIT_KEYS_ASSOC);
    expectedArguments(\MultipleIterator::setFlags(), 0, argumentsSet("multipleIteratorFlags"));

    expectedArguments(\SQLite3::open(), 1, SQLITE3_OPEN_READWRITE|SQLITE3_OPEN_CREATE|SQLITE3_OPEN_READONLY);
    expectedArguments(\SQLite3::createFunction(), 3, SQLITE3_DETERMINISTIC);
    expectedArguments(\SQLite3::openBlob(), 4, SQLITE3_OPEN_READONLY,SQLITE3_OPEN_READWRITE);
    expectedArguments(\SQLite3::__construct(), 1, SQLITE3_OPEN_READWRITE|SQLITE3_OPEN_CREATE|SQLITE3_OPEN_READONLY);
    expectedArguments(\SQLite3Result::fetchArray(), 0, SQLITE3_ASSOC,SQLITE3_NUM,SQLITE3_BOTH);

    expectedArguments(\ssh2_fingerprint(), 1, SSH2_FINGERPRINT_MD5|SSH2_FINGERPRINT_SHA1|SSH2_FINGERPRINT_HEX|SSH2_FINGERPRINT_RAW);
    expectedArguments(\imagecropauto(), 1, IMG_CROP_BLACK,IMG_CROP_DEFAULT,IMG_CROP_SIDES,IMG_CROP_THRESHOLD,IMG_CROP_TRANSPARENT,IMG_CROP_WHITE);
    expectedArguments(\srand(), 1, MT_RAND_MT19937,MT_RAND_PHP);
    expectedArguments(\mt_srand(), 1, MT_RAND_MT19937,MT_RAND_PHP);
    expectedArguments(\round(), 2, PHP_ROUND_HALF_UP,PHP_ROUND_HALF_DOWN,PHP_ROUND_HALF_EVEN,PHP_ROUND_HALF_ODD);
    expectedArguments(\file_put_contents(), 2, FILE_USE_INCLUDE_PATH|FILE_APPEND|LOCK_EX);
    expectedArguments(\stream_socket_client(), 4, STREAM_CLIENT_CONNECT|STREAM_CLIENT_ASYNC_CONNECT|STREAM_CLIENT_PERSISTENT);
    expectedArguments(\stream_socket_server(), 3, STREAM_SERVER_BIND|STREAM_SERVER_LISTEN);
    expectedArguments(\stream_socket_recvfrom(), 2, STREAM_OOB|STREAM_PEEK);
    expectedArguments(\stream_socket_sendto(), 2, STREAM_OOB);
    expectedArguments(\stream_wrapper_register(), 2, STREAM_IS_URL);
    expectedArguments(\stream_register_wrapper(), 2, STREAM_IS_URL);
    expectedArguments(\fnmatch(), 2, FNM_NOESCAPE|FNM_PATHNAME|FNM_PERIOD|FNM_CASEFOLD);
    expectedArguments(\glob(), 1, GLOB_MARK|GLOB_NOSORT|GLOB_NOCHECK|GLOB_NOESCAPE|GLOB_BRACE|GLOB_ONLYDIR|GLOB_ERR);
    expectedArguments(\count(), 1, COUNT_NORMAL,COUNT_RECURSIVE);
    expectedArguments(\array_filter(), 2, ARRAY_FILTER_USE_KEY,ARRAY_FILTER_USE_BOTH);
    expectedArguments(\svn_checkout(), 4, SVN_NON_RECURSIVE);
    expectedArguments(\svn_log(), 4, SVN_OMIT_MESSAGES|SVN_DISCOVER_CHANGED_PATHS|SVN_STOP_ON_COPY);
    expectedArguments(\svn_status(), 1, \Svn::NON_RECURSIVE|\Svn::ALL|\Svn::SHOW_UPDATES|\Svn::NO_IGNORE|\Svn::IGNORE_EXTERNALS);
    expectedArguments(\msg_receive(), 6, MSG_IPC_NOWAIT|MSG_EXCEPT|MSG_NOERROR);
    expectedArguments(\token_get_all(), 1, TOKEN_PARSE);

    expectedArguments(\V8Js::executeString(), 2, \V8Js::FLAG_NONE,\V8Js::FLAG_FORCE_ARRAY,\V8Js::FLAG_PROPAGATE_PHP_EXCEPTIONS);
    expectedArguments(\V8Js::executeScript(), 1, \V8Js::FLAG_NONE,\V8Js::FLAG_FORCE_ARRAY,\V8Js::FLAG_PROPAGATE_PHP_EXCEPTIONS);

    expectedArguments(\ZipArchive::open(), 1, \ZipArchive::OVERWRITE|\ZipArchive::CREATE|\ZipArchive::EXCL|\ZipArchive::CHECKCONS);
    expectedArguments(\ZipArchive::addGlob(), 1, GLOB_MARK|GLOB_NOSORT|GLOB_NOCHECK|GLOB_NOESCAPE|GLOB_BRACE|GLOB_ONLYDIR|GLOB_ERR);
    expectedArguments(\ZipArchive::getArchiveComment(), 0, \ZipArchive::FL_UNCHANGED);
    expectedArguments(\ZipArchive::getCommentIndex(), 1, \ZipArchive::FL_UNCHANGED);
    expectedArguments(\ZipArchive::getCommentName(), 1, \ZipArchive::FL_UNCHANGED);
    expectedArguments(\ZipArchive::statName(), 1, \ZipArchive::FL_UNCHANGED|\ZipArchive::FL_NOCASE);
    expectedArguments(\ZipArchive::statIndex(), 1, \ZipArchive::FL_UNCHANGED);
    expectedArguments(\ZipArchive::locateName(), 1, \ZipArchive::FL_NOCASE);
    expectedArguments(\ZipArchive::getNameIndex(), 1, \ZipArchive::FL_NOCASE);
    expectedArguments(\ZipArchive::getFromName(), 1, \ZipArchive::FL_NOCASE);
    expectedArguments(\ZipArchive::getFromIndex(), 1, \ZipArchive::FL_NOCASE);

    expectedArguments(\ZMQSocket::recv(), 0, \ZMQ::MODE_DONTWAIT,\ZMQ::MODE_SNDMORE,\ZMQ::MODE_NOBLOCK);

    registerArgumentsSet('ini_values', 'allow_call_time_pass_reference', 'allow_url_fopen', 'allow_url_include', 'always_populate_raw_post_data',
        'apc.cache_by_default', 'apc.enabled', 'apc.enable_cli', 'apc.file_update_protection', 'apc.filters', 'apc.gc_ttl', 'apc.include_once_override', 'apc.localcache', 'apc.localcache.size', 'apc.max_file_size', 'apc.mmap_file_mask', 'apc.num_files_hint', 'apc.optimization', 'apc.report_autofilter', 'apc.rfc1867', 'apc.rfc1867_freq', 'apc.rfc1867_name', 'apc.rfc1867_prefix', 'apc.shm_segments', 'apc.shm_size', 'apc.slam_defense', 'apc.stat', 'apc.stat_ctime', 'apc.ttl', 'apc.user_entries_hint', 'apc.user_ttl', 'apc.write_lock',
        'apd.bitmask', 'apd.dumpdir', 'apd.statement_tracing',
        'arg_separator', 'arg_separator.input', 'arg_separator.output',
        'asp_tags',
        'assert.active', 'assert.bail', 'assert.callback', 'assert.quiet_eval', 'assert.warning',
        'async_send',
        'auto_append_file', 'auto_detect_line_endings', 'auto_globals_jit', 'auto_prepend_file',
        'axis2.client_home', 'axis2.enable_exception', 'axis2.enable_trace', 'axis2.log_path',
        'bcmath.scale',
        'bcompiler.enabled',
        'birdstep.max_links',
        'blenc.key_file',
        'browscap',
        'cgi.check_shebang_line', 'cgi.discard_path', 'cgi.fix_pathinfo', 'cgi.force_redirect', 'cgi.nph', 'cgi.redirect_status_env', 'cgi.rfc2616_headers',
        'child_terminate',
        'cli.pager', 'cli.prompt', 'cli_server.color',
        'coin_acceptor.autoreset', 'coin_acceptor.auto_initialize', 'coin_acceptor.auto_reset', 'coin_acceptor.command_function', 'coin_acceptor.delay', 'coin_acceptor.delay_coins', 'coin_acceptor.delay_prom', 'coin_acceptor.device', 'coin_acceptor.lock_on_close', 'coin_acceptor.start_unlocked',
        'com.allow_dcom', 'com.autoregister_casesensitive', 'com.autoregister_typelib', 'com.autoregister_verbose', 'com.code_page', 'com.typelib_file',
        'crack.default_dictionary',
        'curl.cainfo',
        'daffodildb.default_host', 'daffodildb.default_password', 'daffodildb.default_socket', 'daffodildb.default_user', 'daffodildb.port',
        'date.default_latitude', 'date.default_longitude', 'date.sunrise_zenith', 'date.sunset_zenith', 'date.timezone',
        'dba.default_handler',
        'dbx.colnames_case',
        'default_charset', 'default_mimetype', 'default_socket_timeout',
        'define_syslog_variables',
        'detect_unicode',
        'disable_classes', 'disable_functions',
        'display_errors', 'display_startup_errors',
        'docref_ext', 'docref_root', 'doc_root',
        'enable_dl',
        'engine',
        'error_append_string', 'error_log', 'error_prepend_string', 'error_reporting',
        'etpan.default.charset', 'etpan.default.protocol',
        'exif.decode_jis_intel', 'exif.decode_jis_motorola', 'exif.decode_unicode_intel', 'exif.decode_unicode_motorola', 'exif.encode_jis', 'exif.encode_unicode',
        'exit_on_timeout',
        'expect.logfile', 'expect.loguser', 'expect.timeout',
        'expose_php',
        'extension_dir',
        'fastcgi.impersonate', 'fastcgi.logging',
        'fbsql.allow_persistant', 'fbsql.allow_persistent', 'fbsql.autocommit', 'fbsql.batchSize', 'fbsql.batchsize', 'fbsql.default_database', 'fbsql.default_database_password', 'fbsql.default_host', 'fbsql.default_password', 'fbsql.default_user', 'fbsql.generate_warnings', 'fbsql.max_connections', 'fbsql.max_links', 'fbsql.max_persistent', 'fbsql.max_results', 'fbsql.mbatchSize', 'fbsql.show_timestamp_decimals',
        'file_uploads',
        'filter.default',
        'filter.default_flags',
        'from',
        'gd.jpeg_ignore_warning',
        'geoip.custom_directory', 'geoip.database_standard',
        'gpc_order',
        'hidef.ini_path',
        'highlight.bg', 'highlight.comment', 'highlight.default', 'highlight.html', 'highlight.keyword', 'highlight.string',
        'html_errors',
        'htscanner.config_file', 'htscanner.default_docroot', 'htscanner.default_ttl', 'htscanner.stop_on_error',
        'http.allowed_methods', 'http.allowed_methods_log', 'http.cache_log', 'http.composite_log', 'http.etag.mode', 'http.etag_mode', 'http.force_exit', 'http.log.allowed_methods', 'http.log.cache', 'http.log.composite', 'http.log.not_found', 'http.log.redirect', 'http.ob_deflate_auto', 'http.ob_deflate_flags', 'http.ob_inflate_auto', 'http.ob_inflate_flags', 'http.only_exceptions', 'http.persistent.handles.ident', 'http.persistent.handles.limit', 'http.redirect_log', 'http.request.datashare.connect', 'http.request.datashare.cookie', 'http.request.datashare.dns', 'http.request.datashare.ssl', 'http.request.methods.allowed', 'http.request.methods.custom', 'http.send.deflate.start_auto', 'http.send.deflate.start_flags', 'http.send.inflate.start_auto', 'http.send.inflate.start_flags', 'http.send.not_found_404',
        'hyerwave.allow_persistent', 'hyperwave.allow_persistent', 'hyperwave.default_port',
        'ibase.allow_persistent', 'ibase.dateformat', 'ibase.default_charset', 'ibase.default_db', 'ibase.default_password', 'ibase.default_user', 'ibase.max_links', 'ibase.max_persistent', 'ibase.timeformat', 'ibase.timestampformat',
        'ibm_db2.binmode', 'ibm_db2.i5_allow_commit', 'ibm_db2.i5_dbcs_alloc', 'ibm_db2.instance_name',
        'iconv.input_encoding', 'iconv.internal_encoding', 'iconv.output_encoding',
        'ifx.allow_persistent', 'ifx.blobinfile', 'ifx.byteasvarchar', 'ifx.charasvarchar', 'ifx.default_host', 'ifx.default_password', 'ifx.default_user', 'ifx.max_links', 'ifx.max_persistent', 'ifx.nullformat', 'ifx.textasvarchar',
        'ignore_repeated_errors', 'ignore_repeated_source',
        'ignore_user_abort',
        'imlib2.font_cache_max_size', 'imlib2.font_path',
        'implicit_flush',
        'include_path',
        'ingres.allow_persistent', 'ingres.array_index_start', 'ingres.blob_segment_length', 'ingres.cursor_mode', 'ingres.default_database', 'ingres.default_password', 'ingres.default_user', 'ingres.max_links', 'ingres.max_persistent', 'ingres.report_db_warnings', 'ingres.timeout', 'ingres.trace_connect',
        'ircg.control_user', 'ircg.keep_alive_interval', 'ircg.max_format_message_sets', 'ircg.shared_mem_size', 'ircg.work_dir',
        'last_modified',
        'ldap.base_dn', 'ldap.max_links',
        'log.dbm_dir',
        'log_errors', 'log_errors_max_len',
        'magic_quotes_gpc', 'magic_quotes_runtime', 'magic_quotes_sybase',
        'mail.add_x_header', 'mail.force_extra_parameters', 'mail.log',
        'mailparse.def_charset',
        'maxdb.default_db', 'maxdb.default_host', 'maxdb.default_pw', 'maxdb.default_user', 'maxdb.long_readlen',
        'max_execution_time', 'max_input_nesting_level', 'max_input_vars', 'max_input_time',
        'mbstring.detect_order', 'mbstring.encoding_translation', 'mbstring.func_overload', 'mbstring.http_input', 'mbstring.http_output', 'mbstring.internal_encoding', 'mbstring.language', 'mbstring.script_encoding', 'mbstring.strict_detection', 'mbstring.substitute_character',
        'mcrypt.algorithms_dir', 'mcrypt.modes_dir',
        'memcache.allow_failover', 'memcache.chunk_size', 'memcache.default_port', 'memcache.hash_function', 'memcache.hash_strategy', 'memcache.max_failover_attempts',
        'memory_limit',
        'mime_magic.debug', 'mime_magic.magicfile',
        'mongo.allow_empty_keys', 'mongo.allow_persistent', 'mongo.chunk_size', 'mongo.cmd', 'mongo.default_host', 'mongo.default_port', 'mongo.is_master_interval', 'mongo.long_as_object', 'mongo.native_long', 'mongo.ping_interval', 'mongo.utf8',
        'msql.allow_persistent', 'msql.max_links', 'msql.max_persistent',
        'mssql.allow_persistent', 'mssql.batchsize', 'mssql.charset', 'mssql.compatability_mode', 'mssql.connect_timeout', 'mssql.datetimeconvert', 'mssql.max_links', 'mssql.max_persistent', 'mssql.max_procs', 'mssql.min_error_severity', 'mssql.min_message_severity', 'mssql.secure_connection', 'mssql.textlimit', 'mssql.textsize', 'mssql.timeout',
        'mysql.allow_persistent', 'mysql.connect_timeout', 'mysql.default_host', 'mysql.default_password', 'mysql.default_port', 'mysql.default_socket', 'mysql.default_user', 'mysql.max_links', 'mysql.max_persistent', 'mysql.trace_mode',
        'mysqli.default_host', 'mysqli.default_port', 'mysqli.default_pw', 'mysqli.default_socket', 'mysqli.default_user', 'mysqli.max_links', 'mysqli.reconnect',
        'namazu.debugmode', 'namazu.lang', 'namazu.loggingmode', 'namazu.sortmethod', 'namazu.sortorder',
        'nsapi.read_timeout',
        'oci8.connection_class', 'oci8.default_prefetch', 'oci8.events', 'oci8.max_persistent', 'oci8.old_oci_close_semantics', 'oci8.persistent_timeout', 'oci8.ping_interval', 'oci8.privileged_connect', 'oci8.statement_cache_size',
        'odbc.allow_persistent', 'odbc.check_persistent', 'odbc.defaultbinmode', 'odbc.defaultlrl', 'odbc.default_db', 'odbc.default_pw', 'odbc.default_user', 'odbc.max_links', 'odbc.max_persistent',
        'odbtp.datetime_format', 'odbtp.detach_default_queries', 'odbtp.guid_format', 'odbtp.interface_file', 'odbtp.truncation_errors',
        'opendirectory.default_separator', 'opendirectory.max_refs', 'opendirectory.separator',
        'open_basedir',
        'oracle.allow_persistent', 'oracle.max_links', 'oracle.max_persistent',
        'output_buffering', 'output_handler',
        'pam.servicename',
        'pcre.backtrack_limit', 'pcre.recursion_limit',
        'pdo_odbc.connection_pooling', 'pdo_odbc.db2_instance_name',
        'pfpro.defaulthost', 'pfpro.defaultport', 'pfpro.defaulttimeout', 'pfpro.proxyaddress', 'pfpro.proxylogon', 'pfpro.proxypassword', 'pfpro.proxyport',
        'pgsql.allow_persistent', 'pgsql.auto_reset_persistent', 'pgsql.ignore_notice', 'pgsql.log_notice', 'pgsql.max_links', 'pgsql.max_persistent',
        'phar.extract_list', 'phar.readonly', 'phar.require_hash',
        'enable_post_data_reading',
        'post_max_size',
        'precision',
        'printer.default_printer',
        'python.append_path', 'python.prepend_path',
        'realpath_cache_size', 'realpath_cache_ttl',
        'register_argc_argv', 'register_globals', 'register_long_arrays',
        'report_memleaks', 'report_zend_debug',
        'request_order',
        'runkit.internal_override', 'runkit.superglobal',
        'safe_mode', 'safe_mode_allowed_env_vars', 'safe_mode_exec_dir', 'safe_mode_gid', 'safe_mode_include_dir', 'safe_mode_protected_env_vars',
        'sendmail_from', 'sendmail_path',
        'serialize_precision',
        'session.auto_start', 'session.bug_compat_42', 'session.bug_compat_warn', 'session.cache_expire', 'session.cache_limiter', 'session.cookie_domain', 'session.cookie_httponly', 'session.cookie_lifetime', 'session.cookie_path', 'session.cookie_secure', 'session.entropy_file', 'session.entropy_length', 'session.gc_dividend', 'session.gc_divisor', 'session.gc_maxlifetime', 'session.gc_probability', 'session.hash_bits_per_character', 'session.hash_function', 'session.name', 'session.referer_check', 'session.save_handler', 'session.save_path', 'session.serialize_handler', 'session.use_cookies', 'session.use_only_cookies', 'session.use_trans_sid',
        'session_pgsql.create_table', 'session_pgsql.db', 'session_pgsql.disable', 'session_pgsql.failover_mode', 'session_pgsql.gc_interval', 'session_pgsql.keep_expired', 'session_pgsql.sem_file_name', 'session_pgsql.serializable', 'session_pgsql.short_circuit', 'session_pgsql.use_app_vars', 'session_pgsql.vacuum_interval',
        'short_open_tag',
        'simple_cvs.authMethod', 'simple_cvs.compressionLevel', 'simple_cvs.cvsRoot', 'simple_cvs.host', 'simple_cvs.moduleName', 'simple_cvs.userName', 'simple_cvs.workingDir',
        'SMTP',
        'smtp_port',
        'soap.wsdl_cache', 'soap.wsdl_cache_dir', 'soap.wsdl_cache_enabled', 'soap.wsdl_cache_limit', 'soap.wsdl_cache_ttl',
        'sql.safe_mode',
        'sqlite.assoc_case',
        'sybase.allow_persistent', 'sybase.hostname', 'sybase.interface_file', 'sybase.login_timeout', 'sybase.max_links', 'sybase.max_persistent', 'sybase.min_client_severity', 'sybase.min_error_severity', 'sybase.min_message_severity', 'sybase.min_server_severity', 'sybase.timeout',
        'sybct.allow_persistent', 'sybct.deadlock_retry_count', 'sybct.hostname', 'sybct.login_timeout', 'sybct.max_links', 'sybct.max_persistent', 'sybct.min_client_severity', 'sybct.min_server_severity', 'sybct.packet_size', 'sybct.timeout',
        'sysvshm.init_mem',
        'tidy.clean_output', 'tidy.default_config',
        'track_errors', 'track_vars',
        'unserialize_callback_func',
        'uploadprogress.file.filename_template', 'upload_max_filesize', 'max_file_uploads', 'upload_tmp_dir',
        'url_rewriter.tags',
        'user_agent',
        'user_dir',
        'user_ini.cache_ttl', 'user_ini.filename',
        'valkyrie.auto_validate', 'valkyrie.config_path',
        'variables_order',
        'velocis.max_links',
        'vld.active', 'vld.execute', 'vld.skip_append', 'vld.skip_prepend',
        'windows_show_crt_warning',
        'xbithack',
        'xdebug.auto_profile', 'xdebug.auto_profile_mode', 'xdebug.auto_trace', 'xdebug.collect_includes', 'xdebug.collect_params', 'xdebug.collect_return', 'xdebug.collect_vars', 'xdebug.default_enable', 'xdebug.dump.COOKIE', 'xdebug.dump.ENV', 'xdebug.dump.FILES', 'xdebug.dump.GET', 'xdebug.dump.POST', 'xdebug.dump.REQUEST', 'xdebug.dump.SERVER', 'xdebug.dump.SESSION', 'xdebug.dump_globals', 'xdebug.dump_once', 'xdebug.dump_undefined', 'xdebug.extended_info', 'xdebug.idekey', 'xdebug.manual_url', 'xdebug.max_nesting_level', 'xdebug.output_dir', 'xdebug.profiler_aggregate', 'xdebug.profiler_append', 'xdebug.profiler_enable', 'xdebug.profiler_enable_trigger', 'xdebug.profiler_output_dir', 'xdebug.profiler_output_name', 'xdebug.remote_autostart', 'xdebug.remote_enable', 'xdebug.remote_handler', 'xdebug.remote_host', 'xdebug.remote_log', 'xdebug.remote_mode', 'xdebug.remote_port', 'xdebug.show_exception_trace', 'xdebug.show_local_vars', 'xdebug.show_mem_delta', 'xdebug.trace_format', 'xdebug.trace_options', 'xdebug.trace_output_dir', 'xdebug.trace_output_name', 'xdebug.var_display_max_children', 'xdebug.var_display_max_data', 'xdebug.var_display_max_depth',
        'xmlrpc_errors',
        'xmlrpc_error_number',
        'xmms.path', 'xmms.session',
        'y2k_compliance',
        'yami.response.timeout',
        'yaz.keepalive', 'yaz.log_file', 'yaz.log_mask', 'yaz.max_links',
        'zend.enable_gc', 'zend.multibyte', 'zend.script_encoding', 'zend.signal_check', 'zend.ze1_compatibility_mode',
        'zlib.output_compression', 'zlib.output_compression_level', 'zlib.output_handler');

    expectedArguments(\ini_get(), 0, argumentsSet("ini_values"));
    expectedArguments(\ini_set(), 0, argumentsSet("ini_values"));
    expectedArguments(\ini_alter(), 0, argumentsSet("ini_values"));
    expectedArguments(\ini_restore(), 0, argumentsSet("ini_values"));
    expectedArguments(\get_cfg_var(), 0, argumentsSet("ini_values"));

    registerArgumentsSet('IntlCharUnicodeProperties', \IntlChar::PROPERTY_ALPHABETIC, \IntlChar::PROPERTY_BINARY_START, \IntlChar::PROPERTY_ASCII_HEX_DIGIT, \IntlChar::PROPERTY_BIDI_CONTROL, \IntlChar::PROPERTY_BIDI_MIRRORED, \IntlChar::PROPERTY_DASH, \IntlChar::PROPERTY_DEFAULT_IGNORABLE_CODE_POINT, \IntlChar::PROPERTY_DEPRECATED, \IntlChar::PROPERTY_DIACRITIC, \IntlChar::PROPERTY_EXTENDER, \IntlChar::PROPERTY_FULL_COMPOSITION_EXCLUSION, \IntlChar::PROPERTY_GRAPHEME_BASE, \IntlChar::PROPERTY_GRAPHEME_EXTEND, \IntlChar::PROPERTY_GRAPHEME_LINK, \IntlChar::PROPERTY_HEX_DIGIT, \IntlChar::PROPERTY_HYPHEN, \IntlChar::PROPERTY_ID_CONTINUE, \IntlChar::PROPERTY_ID_START, \IntlChar::PROPERTY_IDEOGRAPHIC, \IntlChar::PROPERTY_IDS_BINARY_OPERATOR, \IntlChar::PROPERTY_IDS_TRINARY_OPERATOR, \IntlChar::PROPERTY_JOIN_CONTROL, \IntlChar::PROPERTY_LOGICAL_ORDER_EXCEPTION, \IntlChar::PROPERTY_LOWERCASE, \IntlChar::PROPERTY_MATH, \IntlChar::PROPERTY_NONCHARACTER_CODE_POINT, \IntlChar::PROPERTY_QUOTATION_MARK, \IntlChar::PROPERTY_RADICAL, \IntlChar::PROPERTY_SOFT_DOTTED, \IntlChar::PROPERTY_TERMINAL_PUNCTUATION, \IntlChar::PROPERTY_UNIFIED_IDEOGRAPH, \IntlChar::PROPERTY_UPPERCASE, \IntlChar::PROPERTY_WHITE_SPACE, \IntlChar::PROPERTY_XID_CONTINUE, \IntlChar::PROPERTY_XID_START, \IntlChar::PROPERTY_CASE_SENSITIVE, \IntlChar::PROPERTY_S_TERM, \IntlChar::PROPERTY_VARIATION_SELECTOR, \IntlChar::PROPERTY_NFD_INERT, \IntlChar::PROPERTY_NFKD_INERT, \IntlChar::PROPERTY_NFC_INERT, \IntlChar::PROPERTY_NFKC_INERT, \IntlChar::PROPERTY_SEGMENT_STARTER, \IntlChar::PROPERTY_PATTERN_SYNTAX, \IntlChar::PROPERTY_PATTERN_WHITE_SPACE, \IntlChar::PROPERTY_POSIX_ALNUM, \IntlChar::PROPERTY_POSIX_BLANK, \IntlChar::PROPERTY_POSIX_GRAPH, \IntlChar::PROPERTY_POSIX_PRINT, \IntlChar::PROPERTY_POSIX_XDIGIT, \IntlChar::PROPERTY_CASED, \IntlChar::PROPERTY_CASE_IGNORABLE, \IntlChar::PROPERTY_CHANGES_WHEN_LOWERCASED, \IntlChar::PROPERTY_CHANGES_WHEN_UPPERCASED, \IntlChar::PROPERTY_CHANGES_WHEN_TITLECASED, \IntlChar::PROPERTY_CHANGES_WHEN_CASEFOLDED, \IntlChar::PROPERTY_CHANGES_WHEN_CASEMAPPED, \IntlChar::PROPERTY_CHANGES_WHEN_NFKC_CASEFOLDED, \IntlChar::PROPERTY_BINARY_LIMIT,
        \IntlChar::PROPERTY_BIDI_CLASS, \IntlChar::PROPERTY_INT_START, \IntlChar::PROPERTY_BLOCK, \IntlChar::PROPERTY_CANONICAL_COMBINING_CLASS, \IntlChar::PROPERTY_DECOMPOSITION_TYPE, \IntlChar::PROPERTY_EAST_ASIAN_WIDTH, \IntlChar::PROPERTY_GENERAL_CATEGORY, \IntlChar::PROPERTY_JOINING_GROUP, \IntlChar::PROPERTY_JOINING_TYPE, \IntlChar::PROPERTY_LINE_BREAK, \IntlChar::PROPERTY_NUMERIC_TYPE, \IntlChar::PROPERTY_SCRIPT, \IntlChar::PROPERTY_HANGUL_SYLLABLE_TYPE, \IntlChar::PROPERTY_NFD_QUICK_CHECK, \IntlChar::PROPERTY_NFKD_QUICK_CHECK, \IntlChar::PROPERTY_NFC_QUICK_CHECK, \IntlChar::PROPERTY_NFKC_QUICK_CHECK, \IntlChar::PROPERTY_LEAD_CANONICAL_COMBINING_CLASS, \IntlChar::PROPERTY_TRAIL_CANONICAL_COMBINING_CLASS, \IntlChar::PROPERTY_GRAPHEME_CLUSTER_BREAK, \IntlChar::PROPERTY_SENTENCE_BREAK, \IntlChar::PROPERTY_WORD_BREAK, \IntlChar::PROPERTY_BIDI_PAIRED_BRACKET_TYPE, \IntlChar::PROPERTY_INT_LIMIT,
        \IntlChar::PROPERTY_GENERAL_CATEGORY_MASK, \IntlChar::PROPERTY_MASK_START, \IntlChar::PROPERTY_MASK_LIMIT,
        \IntlChar::PROPERTY_NUMERIC_VALUE, \IntlChar::PROPERTY_DOUBLE_START, \IntlChar::PROPERTY_DOUBLE_LIMIT,
        \IntlChar::PROPERTY_AGE, \IntlChar::PROPERTY_STRING_START, \IntlChar::PROPERTY_BIDI_MIRRORING_GLYPH, \IntlChar::PROPERTY_CASE_FOLDING, \IntlChar::PROPERTY_ISO_COMMENT, \IntlChar::PROPERTY_LOWERCASE_MAPPING, \IntlChar::PROPERTY_NAME, \IntlChar::PROPERTY_SIMPLE_CASE_FOLDING, \IntlChar::PROPERTY_SIMPLE_LOWERCASE_MAPPING, \IntlChar::PROPERTY_SIMPLE_TITLECASE_MAPPING, \IntlChar::PROPERTY_SIMPLE_UPPERCASE_MAPPING, \IntlChar::PROPERTY_TITLECASE_MAPPING, \IntlChar::PROPERTY_UNICODE_1_NAME, \IntlChar::PROPERTY_UPPERCASE_MAPPING, \IntlChar::PROPERTY_BIDI_PAIRED_BRACKET, \IntlChar::PROPERTY_STRING_LIMIT, \IntlChar::PROPERTY_SCRIPT_EXTENSIONS, \IntlChar::PROPERTY_OTHER_PROPERTY_START, \IntlChar::PROPERTY_OTHER_PROPERTY_LIMIT, \IntlChar::PROPERTY_INVALID_CODE);
    expectedArguments(\IntlChar::hasBinaryProperty(), 1, argumentsSet('IntlCharUnicodeProperties'));
    expectedArguments(\IntlChar::getIntPropertyMaxValue(), 0, argumentsSet('IntlCharUnicodeProperties'));
    expectedArguments(\IntlChar::getIntPropertyMinValue(), 0, argumentsSet('IntlCharUnicodeProperties'));
    expectedArguments(\IntlChar::getIntPropertyValue(), 1, argumentsSet('IntlCharUnicodeProperties'));
    registerArgumentsSet('IntlCharBlockCodePropertyValues', \IntlChar::BLOCK_CODE_NO_BLOCK, \IntlChar::BLOCK_CODE_BASIC_LATIN, \IntlChar::BLOCK_CODE_LATIN_1_SUPPLEMENT, \IntlChar::BLOCK_CODE_LATIN_EXTENDED_A, \IntlChar::BLOCK_CODE_LATIN_EXTENDED_B, \IntlChar::BLOCK_CODE_IPA_EXTENSIONS, \IntlChar::BLOCK_CODE_SPACING_MODIFIER_LETTERS, \IntlChar::BLOCK_CODE_COMBINING_DIACRITICAL_MARKS, \IntlChar::BLOCK_CODE_GREEK, \IntlChar::BLOCK_CODE_CYRILLIC, \IntlChar::BLOCK_CODE_ARMENIAN, \IntlChar::BLOCK_CODE_HEBREW, \IntlChar::BLOCK_CODE_ARABIC, \IntlChar::BLOCK_CODE_SYRIAC, \IntlChar::BLOCK_CODE_THAANA, \IntlChar::BLOCK_CODE_DEVANAGARI, \IntlChar::BLOCK_CODE_BENGALI, \IntlChar::BLOCK_CODE_GURMUKHI, \IntlChar::BLOCK_CODE_GUJARATI, \IntlChar::BLOCK_CODE_ORIYA, \IntlChar::BLOCK_CODE_TAMIL, \IntlChar::BLOCK_CODE_TELUGU, \IntlChar::BLOCK_CODE_KANNADA, \IntlChar::BLOCK_CODE_MALAYALAM, \IntlChar::BLOCK_CODE_SINHALA, \IntlChar::BLOCK_CODE_THAI, \IntlChar::BLOCK_CODE_LAO, \IntlChar::BLOCK_CODE_TIBETAN, \IntlChar::BLOCK_CODE_MYANMAR, \IntlChar::BLOCK_CODE_GEORGIAN, \IntlChar::BLOCK_CODE_HANGUL_JAMO, \IntlChar::BLOCK_CODE_ETHIOPIC, \IntlChar::BLOCK_CODE_CHEROKEE, \IntlChar::BLOCK_CODE_UNIFIED_CANADIAN_ABORIGINAL_SYLLABICS, \IntlChar::BLOCK_CODE_OGHAM, \IntlChar::BLOCK_CODE_RUNIC, \IntlChar::BLOCK_CODE_KHMER, \IntlChar::BLOCK_CODE_MONGOLIAN, \IntlChar::BLOCK_CODE_LATIN_EXTENDED_ADDITIONAL, \IntlChar::BLOCK_CODE_GREEK_EXTENDED, \IntlChar::BLOCK_CODE_GENERAL_PUNCTUATION, \IntlChar::BLOCK_CODE_SUPERSCRIPTS_AND_SUBSCRIPTS, \IntlChar::BLOCK_CODE_CURRENCY_SYMBOLS, \IntlChar::BLOCK_CODE_COMBINING_MARKS_FOR_SYMBOLS, \IntlChar::BLOCK_CODE_LETTERLIKE_SYMBOLS, \IntlChar::BLOCK_CODE_NUMBER_FORMS, \IntlChar::BLOCK_CODE_ARROWS, \IntlChar::BLOCK_CODE_MATHEMATICAL_OPERATORS, \IntlChar::BLOCK_CODE_MISCELLANEOUS_TECHNICAL, \IntlChar::BLOCK_CODE_CONTROL_PICTURES, \IntlChar::BLOCK_CODE_OPTICAL_CHARACTER_RECOGNITION, \IntlChar::BLOCK_CODE_ENCLOSED_ALPHANUMERICS, \IntlChar::BLOCK_CODE_BOX_DRAWING, \IntlChar::BLOCK_CODE_BLOCK_ELEMENTS, \IntlChar::BLOCK_CODE_GEOMETRIC_SHAPES, \IntlChar::BLOCK_CODE_MISCELLANEOUS_SYMBOLS, \IntlChar::BLOCK_CODE_DINGBATS, \IntlChar::BLOCK_CODE_BRAILLE_PATTERNS, \IntlChar::BLOCK_CODE_CJK_RADICALS_SUPPLEMENT, \IntlChar::BLOCK_CODE_KANGXI_RADICALS, \IntlChar::BLOCK_CODE_IDEOGRAPHIC_DESCRIPTION_CHARACTERS, \IntlChar::BLOCK_CODE_CJK_SYMBOLS_AND_PUNCTUATION, \IntlChar::BLOCK_CODE_HIRAGANA, \IntlChar::BLOCK_CODE_KATAKANA, \IntlChar::BLOCK_CODE_BOPOMOFO, \IntlChar::BLOCK_CODE_HANGUL_COMPATIBILITY_JAMO, \IntlChar::BLOCK_CODE_KANBUN, \IntlChar::BLOCK_CODE_BOPOMOFO_EXTENDED, \IntlChar::BLOCK_CODE_ENCLOSED_CJK_LETTERS_AND_MONTHS, \IntlChar::BLOCK_CODE_CJK_COMPATIBILITY, \IntlChar::BLOCK_CODE_CJK_UNIFIED_IDEOGRAPHS_EXTENSION_A, \IntlChar::BLOCK_CODE_CJK_UNIFIED_IDEOGRAPHS, \IntlChar::BLOCK_CODE_YI_SYLLABLES, \IntlChar::BLOCK_CODE_YI_RADICALS, \IntlChar::BLOCK_CODE_HANGUL_SYLLABLES, \IntlChar::BLOCK_CODE_HIGH_SURROGATES, \IntlChar::BLOCK_CODE_HIGH_PRIVATE_USE_SURROGATES, \IntlChar::BLOCK_CODE_LOW_SURROGATES, \IntlChar::BLOCK_CODE_PRIVATE_USE_AREA, \IntlChar::BLOCK_CODE_PRIVATE_USE, \IntlChar::BLOCK_CODE_CJK_COMPATIBILITY_IDEOGRAPHS, \IntlChar::BLOCK_CODE_ALPHABETIC_PRESENTATION_FORMS, \IntlChar::BLOCK_CODE_ARABIC_PRESENTATION_FORMS_A, \IntlChar::BLOCK_CODE_COMBINING_HALF_MARKS, \IntlChar::BLOCK_CODE_CJK_COMPATIBILITY_FORMS, \IntlChar::BLOCK_CODE_SMALL_FORM_VARIANTS, \IntlChar::BLOCK_CODE_ARABIC_PRESENTATION_FORMS_B, \IntlChar::BLOCK_CODE_SPECIALS, \IntlChar::BLOCK_CODE_HALFWIDTH_AND_FULLWIDTH_FORMS, \IntlChar::BLOCK_CODE_OLD_ITALIC, \IntlChar::BLOCK_CODE_GOTHIC, \IntlChar::BLOCK_CODE_DESERET, \IntlChar::BLOCK_CODE_BYZANTINE_MUSICAL_SYMBOLS, \IntlChar::BLOCK_CODE_MUSICAL_SYMBOLS, \IntlChar::BLOCK_CODE_MATHEMATICAL_ALPHANUMERIC_SYMBOLS, \IntlChar::BLOCK_CODE_CJK_UNIFIED_IDEOGRAPHS_EXTENSION_B, \IntlChar::BLOCK_CODE_CJK_COMPATIBILITY_IDEOGRAPHS_SUPPLEMENT, \IntlChar::BLOCK_CODE_TAGS, \IntlChar::BLOCK_CODE_CYRILLIC_SUPPLEMENT, \IntlChar::BLOCK_CODE_CYRILLIC_SUPPLEMENTARY, \IntlChar::BLOCK_CODE_TAGALOG, \IntlChar::BLOCK_CODE_HANUNOO, \IntlChar::BLOCK_CODE_BUHID, \IntlChar::BLOCK_CODE_TAGBANWA, \IntlChar::BLOCK_CODE_MISCELLANEOUS_MATHEMATICAL_SYMBOLS_A, \IntlChar::BLOCK_CODE_SUPPLEMENTAL_ARROWS_A, \IntlChar::BLOCK_CODE_SUPPLEMENTAL_ARROWS_B, \IntlChar::BLOCK_CODE_MISCELLANEOUS_MATHEMATICAL_SYMBOLS_B, \IntlChar::BLOCK_CODE_SUPPLEMENTAL_MATHEMATICAL_OPERATORS, \IntlChar::BLOCK_CODE_KATAKANA_PHONETIC_EXTENSIONS, \IntlChar::BLOCK_CODE_VARIATION_SELECTORS, \IntlChar::BLOCK_CODE_SUPPLEMENTARY_PRIVATE_USE_AREA_A, \IntlChar::BLOCK_CODE_SUPPLEMENTARY_PRIVATE_USE_AREA_B, \IntlChar::BLOCK_CODE_LIMBU, \IntlChar::BLOCK_CODE_TAI_LE, \IntlChar::BLOCK_CODE_KHMER_SYMBOLS, \IntlChar::BLOCK_CODE_PHONETIC_EXTENSIONS, \IntlChar::BLOCK_CODE_MISCELLANEOUS_SYMBOLS_AND_ARROWS, \IntlChar::BLOCK_CODE_YIJING_HEXAGRAM_SYMBOLS, \IntlChar::BLOCK_CODE_LINEAR_B_SYLLABARY, \IntlChar::BLOCK_CODE_LINEAR_B_IDEOGRAMS, \IntlChar::BLOCK_CODE_AEGEAN_NUMBERS, \IntlChar::BLOCK_CODE_UGARITIC, \IntlChar::BLOCK_CODE_SHAVIAN, \IntlChar::BLOCK_CODE_OSMANYA, \IntlChar::BLOCK_CODE_CYPRIOT_SYLLABARY, \IntlChar::BLOCK_CODE_TAI_XUAN_JING_SYMBOLS, \IntlChar::BLOCK_CODE_VARIATION_SELECTORS_SUPPLEMENT, \IntlChar::BLOCK_CODE_ANCIENT_GREEK_MUSICAL_NOTATION, \IntlChar::BLOCK_CODE_ANCIENT_GREEK_NUMBERS, \IntlChar::BLOCK_CODE_ARABIC_SUPPLEMENT, \IntlChar::BLOCK_CODE_BUGINESE, \IntlChar::BLOCK_CODE_CJK_STROKES, \IntlChar::BLOCK_CODE_COMBINING_DIACRITICAL_MARKS_SUPPLEMENT, \IntlChar::BLOCK_CODE_COPTIC, \IntlChar::BLOCK_CODE_ETHIOPIC_EXTENDED, \IntlChar::BLOCK_CODE_ETHIOPIC_SUPPLEMENT, \IntlChar::BLOCK_CODE_GEORGIAN_SUPPLEMENT, \IntlChar::BLOCK_CODE_GLAGOLITIC, \IntlChar::BLOCK_CODE_KHAROSHTHI, \IntlChar::BLOCK_CODE_MODIFIER_TONE_LETTERS, \IntlChar::BLOCK_CODE_NEW_TAI_LUE, \IntlChar::BLOCK_CODE_OLD_PERSIAN, \IntlChar::BLOCK_CODE_PHONETIC_EXTENSIONS_SUPPLEMENT, \IntlChar::BLOCK_CODE_SUPPLEMENTAL_PUNCTUATION, \IntlChar::BLOCK_CODE_SYLOTI_NAGRI, \IntlChar::BLOCK_CODE_TIFINAGH, \IntlChar::BLOCK_CODE_VERTICAL_FORMS, \IntlChar::BLOCK_CODE_NKO, \IntlChar::BLOCK_CODE_BALINESE, \IntlChar::BLOCK_CODE_LATIN_EXTENDED_C, \IntlChar::BLOCK_CODE_LATIN_EXTENDED_D, \IntlChar::BLOCK_CODE_PHAGS_PA, \IntlChar::BLOCK_CODE_PHOENICIAN, \IntlChar::BLOCK_CODE_CUNEIFORM, \IntlChar::BLOCK_CODE_CUNEIFORM_NUMBERS_AND_PUNCTUATION, \IntlChar::BLOCK_CODE_COUNTING_ROD_NUMERALS, \IntlChar::BLOCK_CODE_SUNDANESE, \IntlChar::BLOCK_CODE_LEPCHA, \IntlChar::BLOCK_CODE_OL_CHIKI, \IntlChar::BLOCK_CODE_CYRILLIC_EXTENDED_A, \IntlChar::BLOCK_CODE_VAI, \IntlChar::BLOCK_CODE_CYRILLIC_EXTENDED_B, \IntlChar::BLOCK_CODE_SAURASHTRA, \IntlChar::BLOCK_CODE_KAYAH_LI, \IntlChar::BLOCK_CODE_REJANG, \IntlChar::BLOCK_CODE_CHAM, \IntlChar::BLOCK_CODE_ANCIENT_SYMBOLS, \IntlChar::BLOCK_CODE_PHAISTOS_DISC, \IntlChar::BLOCK_CODE_LYCIAN, \IntlChar::BLOCK_CODE_CARIAN, \IntlChar::BLOCK_CODE_LYDIAN, \IntlChar::BLOCK_CODE_MAHJONG_TILES, \IntlChar::BLOCK_CODE_DOMINO_TILES, \IntlChar::BLOCK_CODE_SAMARITAN, \IntlChar::BLOCK_CODE_UNIFIED_CANADIAN_ABORIGINAL_SYLLABICS_EXTENDED, \IntlChar::BLOCK_CODE_TAI_THAM, \IntlChar::BLOCK_CODE_VEDIC_EXTENSIONS, \IntlChar::BLOCK_CODE_LISU, \IntlChar::BLOCK_CODE_BAMUM, \IntlChar::BLOCK_CODE_COMMON_INDIC_NUMBER_FORMS, \IntlChar::BLOCK_CODE_DEVANAGARI_EXTENDED, \IntlChar::BLOCK_CODE_HANGUL_JAMO_EXTENDED_A, \IntlChar::BLOCK_CODE_JAVANESE, \IntlChar::BLOCK_CODE_MYANMAR_EXTENDED_A, \IntlChar::BLOCK_CODE_TAI_VIET, \IntlChar::BLOCK_CODE_MEETEI_MAYEK, \IntlChar::BLOCK_CODE_HANGUL_JAMO_EXTENDED_B, \IntlChar::BLOCK_CODE_IMPERIAL_ARAMAIC, \IntlChar::BLOCK_CODE_OLD_SOUTH_ARABIAN, \IntlChar::BLOCK_CODE_AVESTAN, \IntlChar::BLOCK_CODE_INSCRIPTIONAL_PARTHIAN, \IntlChar::BLOCK_CODE_INSCRIPTIONAL_PAHLAVI, \IntlChar::BLOCK_CODE_OLD_TURKIC, \IntlChar::BLOCK_CODE_RUMI_NUMERAL_SYMBOLS, \IntlChar::BLOCK_CODE_KAITHI, \IntlChar::BLOCK_CODE_EGYPTIAN_HIEROGLYPHS, \IntlChar::BLOCK_CODE_ENCLOSED_ALPHANUMERIC_SUPPLEMENT, \IntlChar::BLOCK_CODE_ENCLOSED_IDEOGRAPHIC_SUPPLEMENT, \IntlChar::BLOCK_CODE_CJK_UNIFIED_IDEOGRAPHS_EXTENSION_C, \IntlChar::BLOCK_CODE_MANDAIC, \IntlChar::BLOCK_CODE_BATAK, \IntlChar::BLOCK_CODE_ETHIOPIC_EXTENDED_A, \IntlChar::BLOCK_CODE_BRAHMI, \IntlChar::BLOCK_CODE_BAMUM_SUPPLEMENT, \IntlChar::BLOCK_CODE_KANA_SUPPLEMENT, \IntlChar::BLOCK_CODE_PLAYING_CARDS, \IntlChar::BLOCK_CODE_MISCELLANEOUS_SYMBOLS_AND_PICTOGRAPHS, \IntlChar::BLOCK_CODE_EMOTICONS, \IntlChar::BLOCK_CODE_TRANSPORT_AND_MAP_SYMBOLS, \IntlChar::BLOCK_CODE_ALCHEMICAL_SYMBOLS, \IntlChar::BLOCK_CODE_CJK_UNIFIED_IDEOGRAPHS_EXTENSION_D, \IntlChar::BLOCK_CODE_ARABIC_EXTENDED_A, \IntlChar::BLOCK_CODE_ARABIC_MATHEMATICAL_ALPHABETIC_SYMBOLS, \IntlChar::BLOCK_CODE_CHAKMA, \IntlChar::BLOCK_CODE_MEETEI_MAYEK_EXTENSIONS, \IntlChar::BLOCK_CODE_MEROITIC_CURSIVE, \IntlChar::BLOCK_CODE_MEROITIC_HIEROGLYPHS, \IntlChar::BLOCK_CODE_MIAO, \IntlChar::BLOCK_CODE_SHARADA, \IntlChar::BLOCK_CODE_SORA_SOMPENG, \IntlChar::BLOCK_CODE_SUNDANESE_SUPPLEMENT, \IntlChar::BLOCK_CODE_TAKRI, \IntlChar::BLOCK_CODE_BASSA_VAH, \IntlChar::BLOCK_CODE_CAUCASIAN_ALBANIAN, \IntlChar::BLOCK_CODE_COPTIC_EPACT_NUMBERS, \IntlChar::BLOCK_CODE_COMBINING_DIACRITICAL_MARKS_EXTENDED, \IntlChar::BLOCK_CODE_DUPLOYAN, \IntlChar::BLOCK_CODE_ELBASAN, \IntlChar::BLOCK_CODE_GEOMETRIC_SHAPES_EXTENDED, \IntlChar::BLOCK_CODE_GRANTHA, \IntlChar::BLOCK_CODE_KHOJKI, \IntlChar::BLOCK_CODE_KHUDAWADI, \IntlChar::BLOCK_CODE_LATIN_EXTENDED_E, \IntlChar::BLOCK_CODE_LINEAR_A, \IntlChar::BLOCK_CODE_MAHAJANI, \IntlChar::BLOCK_CODE_MANICHAEAN, \IntlChar::BLOCK_CODE_MENDE_KIKAKUI, \IntlChar::BLOCK_CODE_MODI, \IntlChar::BLOCK_CODE_MRO, \IntlChar::BLOCK_CODE_MYANMAR_EXTENDED_B, \IntlChar::BLOCK_CODE_NABATAEAN, \IntlChar::BLOCK_CODE_OLD_NORTH_ARABIAN, \IntlChar::BLOCK_CODE_OLD_PERMIC, \IntlChar::BLOCK_CODE_ORNAMENTAL_DINGBATS, \IntlChar::BLOCK_CODE_PAHAWH_HMONG, \IntlChar::BLOCK_CODE_PALMYRENE, \IntlChar::BLOCK_CODE_PAU_CIN_HAU, \IntlChar::BLOCK_CODE_PSALTER_PAHLAVI, \IntlChar::BLOCK_CODE_SHORTHAND_FORMAT_CONTROLS, \IntlChar::BLOCK_CODE_SIDDHAM, \IntlChar::BLOCK_CODE_SINHALA_ARCHAIC_NUMBERS, \IntlChar::BLOCK_CODE_SUPPLEMENTAL_ARROWS_C, \IntlChar::BLOCK_CODE_TIRHUTA, \IntlChar::BLOCK_CODE_WARANG_CITI, \IntlChar::BLOCK_CODE_COUNT);
    registerArgumentsSet('IntlCharOtherEnumPropertyValues', \IntlChar::BPT_NONE, \IntlChar::BPT_OPEN, \IntlChar::BPT_CLOSE, \IntlChar::BPT_COUNT,
        \IntlChar::EA_NEUTRAL, \IntlChar::EA_AMBIGUOUS, \IntlChar::EA_HALFWIDTH, \IntlChar::EA_FULLWIDTH, \IntlChar::EA_NARROW, \IntlChar::EA_WIDE, \IntlChar::EA_COUNT,
        \IntlChar::DT_NONE, \IntlChar::DT_CANONICAL, \IntlChar::DT_COMPAT, \IntlChar::DT_CIRCLE, \IntlChar::DT_FINAL, \IntlChar::DT_FONT, \IntlChar::DT_FRACTION, \IntlChar::DT_INITIAL, \IntlChar::DT_ISOLATED, \IntlChar::DT_MEDIAL, \IntlChar::DT_NARROW, \IntlChar::DT_NOBREAK, \IntlChar::DT_SMALL, \IntlChar::DT_SQUARE, \IntlChar::DT_SUB, \IntlChar::DT_SUPER, \IntlChar::DT_VERTICAL, \IntlChar::DT_WIDE, \IntlChar::DT_COUNT,
        \IntlChar::JT_NON_JOINING, \IntlChar::JT_JOIN_CAUSING, \IntlChar::JT_DUAL_JOINING, \IntlChar::JT_LEFT_JOINING, \IntlChar::JT_RIGHT_JOINING, \IntlChar::JT_TRANSPARENT, \IntlChar::JT_COUNT,
        \IntlChar::JG_NO_JOINING_GROUP, \IntlChar::JG_AIN, \IntlChar::JG_ALAPH, \IntlChar::JG_ALEF, \IntlChar::JG_BEH, \IntlChar::JG_BETH, \IntlChar::JG_DAL, \IntlChar::JG_DALATH_RISH, \IntlChar::JG_E, \IntlChar::JG_FEH, \IntlChar::JG_FINAL_SEMKATH, \IntlChar::JG_GAF, \IntlChar::JG_GAMAL, \IntlChar::JG_HAH, \IntlChar::JG_TEH_MARBUTA_GOAL, \IntlChar::JG_HAMZA_ON_HEH_GOAL, \IntlChar::JG_HE, \IntlChar::JG_HEH, \IntlChar::JG_HEH_GOAL, \IntlChar::JG_HETH, \IntlChar::JG_KAF, \IntlChar::JG_KAPH, \IntlChar::JG_KNOTTED_HEH, \IntlChar::JG_LAM, \IntlChar::JG_LAMADH, \IntlChar::JG_MEEM, \IntlChar::JG_MIM, \IntlChar::JG_NOON, \IntlChar::JG_NUN, \IntlChar::JG_PE, \IntlChar::JG_QAF, \IntlChar::JG_QAPH, \IntlChar::JG_REH, \IntlChar::JG_REVERSED_PE, \IntlChar::JG_SAD, \IntlChar::JG_SADHE, \IntlChar::JG_SEEN, \IntlChar::JG_SEMKATH, \IntlChar::JG_SHIN, \IntlChar::JG_SWASH_KAF, \IntlChar::JG_SYRIAC_WAW, \IntlChar::JG_TAH, \IntlChar::JG_TAW, \IntlChar::JG_TEH_MARBUTA, \IntlChar::JG_TETH, \IntlChar::JG_WAW, \IntlChar::JG_YEH, \IntlChar::JG_YEH_BARREE, \IntlChar::JG_YEH_WITH_TAIL, \IntlChar::JG_YUDH, \IntlChar::JG_YUDH_HE, \IntlChar::JG_ZAIN, \IntlChar::JG_FE, \IntlChar::JG_KHAPH, \IntlChar::JG_ZHAIN, \IntlChar::JG_BURUSHASKI_YEH_BARREE, \IntlChar::JG_FARSI_YEH, \IntlChar::JG_NYA, \IntlChar::JG_ROHINGYA_YEH, \IntlChar::JG_MANICHAEAN_ALEPH, \IntlChar::JG_MANICHAEAN_AYIN, \IntlChar::JG_MANICHAEAN_BETH, \IntlChar::JG_MANICHAEAN_DALETH, \IntlChar::JG_MANICHAEAN_DHAMEDH, \IntlChar::JG_MANICHAEAN_FIVE, \IntlChar::JG_MANICHAEAN_GIMEL, \IntlChar::JG_MANICHAEAN_HETH, \IntlChar::JG_MANICHAEAN_HUNDRED, \IntlChar::JG_MANICHAEAN_KAPH, \IntlChar::JG_MANICHAEAN_LAMEDH, \IntlChar::JG_MANICHAEAN_MEM, \IntlChar::JG_MANICHAEAN_NUN, \IntlChar::JG_MANICHAEAN_ONE, \IntlChar::JG_MANICHAEAN_PE, \IntlChar::JG_MANICHAEAN_QOPH, \IntlChar::JG_MANICHAEAN_RESH, \IntlChar::JG_MANICHAEAN_SADHE, \IntlChar::JG_MANICHAEAN_SAMEKH, \IntlChar::JG_MANICHAEAN_TAW, \IntlChar::JG_MANICHAEAN_TEN, \IntlChar::JG_MANICHAEAN_TETH, \IntlChar::JG_MANICHAEAN_THAMEDH, \IntlChar::JG_MANICHAEAN_TWENTY, \IntlChar::JG_MANICHAEAN_WAW, \IntlChar::JG_MANICHAEAN_YODH, \IntlChar::JG_MANICHAEAN_ZAYIN, \IntlChar::JG_STRAIGHT_WAW, \IntlChar::JG_COUNT,
        \IntlChar::GCB_OTHER, \IntlChar::GCB_CONTROL, \IntlChar::GCB_CR, \IntlChar::GCB_EXTEND, \IntlChar::GCB_L, \IntlChar::GCB_LF, \IntlChar::GCB_LV, \IntlChar::GCB_LVT, \IntlChar::GCB_T, \IntlChar::GCB_V, \IntlChar::GCB_SPACING_MARK, \IntlChar::GCB_PREPEND, \IntlChar::GCB_REGIONAL_INDICATOR, \IntlChar::GCB_COUNT,
        \IntlChar::WB_OTHER, \IntlChar::WB_ALETTER, \IntlChar::WB_FORMAT, \IntlChar::WB_KATAKANA, \IntlChar::WB_MIDLETTER, \IntlChar::WB_MIDNUM, \IntlChar::WB_NUMERIC, \IntlChar::WB_EXTENDNUMLET, \IntlChar::WB_CR, \IntlChar::WB_EXTEND, \IntlChar::WB_LF, \IntlChar::WB_MIDNUMLET, \IntlChar::WB_NEWLINE, \IntlChar::WB_REGIONAL_INDICATOR, \IntlChar::WB_HEBREW_LETTER, \IntlChar::WB_SINGLE_QUOTE, \IntlChar::WB_DOUBLE_QUOTE, \IntlChar::WB_COUNT,
        \IntlChar::SB_OTHER, \IntlChar::SB_ATERM, \IntlChar::SB_CLOSE, \IntlChar::SB_FORMAT, \IntlChar::SB_LOWER, \IntlChar::SB_NUMERIC, \IntlChar::SB_OLETTER, \IntlChar::SB_SEP, \IntlChar::SB_SP, \IntlChar::SB_STERM, \IntlChar::SB_UPPER, \IntlChar::SB_CR, \IntlChar::SB_EXTEND, \IntlChar::SB_LF, \IntlChar::SB_SCONTINUE, \IntlChar::SB_COUNT,
        \IntlChar::LB_UNKNOWN, \IntlChar::LB_AMBIGUOUS, \IntlChar::LB_ALPHABETIC, \IntlChar::LB_BREAK_BOTH, \IntlChar::LB_BREAK_AFTER, \IntlChar::LB_BREAK_BEFORE, \IntlChar::LB_MANDATORY_BREAK, \IntlChar::LB_CONTINGENT_BREAK, \IntlChar::LB_CLOSE_PUNCTUATION, \IntlChar::LB_COMBINING_MARK, \IntlChar::LB_CARRIAGE_RETURN, \IntlChar::LB_EXCLAMATION, \IntlChar::LB_GLUE, \IntlChar::LB_HYPHEN, \IntlChar::LB_IDEOGRAPHIC, \IntlChar::LB_INSEPARABLE, \IntlChar::LB_INSEPERABLE, \IntlChar::LB_INFIX_NUMERIC, \IntlChar::LB_LINE_FEED, \IntlChar::LB_NONSTARTER, \IntlChar::LB_NUMERIC, \IntlChar::LB_OPEN_PUNCTUATION, \IntlChar::LB_POSTFIX_NUMERIC, \IntlChar::LB_PREFIX_NUMERIC, \IntlChar::LB_QUOTATION, \IntlChar::LB_COMPLEX_CONTEXT, \IntlChar::LB_SURROGATE, \IntlChar::LB_SPACE, \IntlChar::LB_BREAK_SYMBOLS, \IntlChar::LB_ZWSPACE, \IntlChar::LB_NEXT_LINE, \IntlChar::LB_WORD_JOINER, \IntlChar::LB_H2, \IntlChar::LB_H3, \IntlChar::LB_JL, \IntlChar::LB_JT, \IntlChar::LB_JV, \IntlChar::LB_CLOSE_PARENTHESIS, \IntlChar::LB_CONDITIONAL_JAPANESE_STARTER, \IntlChar::LB_HEBREW_LETTER, \IntlChar::LB_REGIONAL_INDICATOR, \IntlChar::LB_COUNT,
        \IntlChar::NT_NONE, \IntlChar::NT_DECIMAL, \IntlChar::NT_DIGIT, \IntlChar::NT_NUMERIC, \IntlChar::NT_COUNT,
        \IntlChar::HST_NOT_APPLICABLE, \IntlChar::HST_LEADING_JAMO, \IntlChar::HST_VOWEL_JAMO, \IntlChar::HST_TRAILING_JAMO, \IntlChar::HST_LV_SYLLABLE, \IntlChar::HST_LVT_SYLLABLE, \IntlChar::HST_COUNT);
    expectedReturnValues(\IntlChar::getIntPropertyValue(), argumentsSet('IntlCharBlockCodePropertyValues'), argumentsSet('IntlCharOtherEnumPropertyValues'));
    expectedReturnValues(\IntlChar::getPropertyEnum(), argumentsSet('IntlCharUnicodeProperties'));
    expectedArguments(\IntlChar::getPropertyName(), 0, argumentsSet('IntlCharUnicodeProperties'));
    expectedArguments(\IntlChar::getPropertyValueEnum(), 0, argumentsSet('IntlCharUnicodeProperties'));
    expectedReturnValues(\IntlChar::getPropertyValueEnum(), \IntlChar::PROPERTY_INVALID_CODE, argumentsSet('IntlCharBlockCodePropertyValues'), argumentsSet('IntlCharOtherEnumPropertyValues'));
    registerArgumentsSet('IntlCharPropertyNameChoices', \IntlChar::SHORT_PROPERTY_NAME, \IntlChar::LONG_PROPERTY_NAME, \IntlChar::PROPERTY_NAME_CHOICE_COUNT);
    expectedArguments(\IntlChar::getPropertyName(), 1, argumentsSet('IntlCharPropertyNameChoices'));
    expectedArguments(\IntlChar::getPropertyValueName(), 0, argumentsSet('IntlCharUnicodeProperties'));
    expectedArguments(\IntlChar::getPropertyValueName(), 1, argumentsSet('IntlCharBlockCodePropertyValues'), argumentsSet('IntlCharOtherEnumPropertyValues'));
    expectedArguments(\IntlChar::getPropertyValueName(), 2, argumentsSet('IntlCharPropertyNameChoices'));
    expectedArguments(\IntlChar::charFromName(), 1, \IntlChar::UNICODE_CHAR_NAME, \IntlChar::UNICODE_10_CHAR_NAME, \IntlChar::EXTENDED_CHAR_NAME, \IntlChar::CHAR_NAME_ALIAS, \IntlChar::CHAR_NAME_CHOICE_COUNT);
    expectedArguments(\IntlChar::foldCase(), 1, \IntlChar::FOLD_CASE_DEFAULT, \IntlChar::FOLD_CASE_EXCLUDE_SPECIAL_I);
    expectedReturnValues(\IntlChar::getBlockCode(), argumentsSet('IntlCharBlockCodePropertyValues'), \IntlChar::BLOCK_CODE_INVALID_CODE);
    expectedReturnValues(\IntlChar::charType(), \IntlChar::CHAR_CATEGORY_UNASSIGNED, \IntlChar::CHAR_CATEGORY_GENERAL_OTHER_TYPES, \IntlChar::CHAR_CATEGORY_UPPERCASE_LETTER, \IntlChar::CHAR_CATEGORY_LOWERCASE_LETTER, \IntlChar::CHAR_CATEGORY_TITLECASE_LETTER, \IntlChar::CHAR_CATEGORY_MODIFIER_LETTER, \IntlChar::CHAR_CATEGORY_OTHER_LETTER, \IntlChar::CHAR_CATEGORY_NON_SPACING_MARK, \IntlChar::CHAR_CATEGORY_ENCLOSING_MARK, \IntlChar::CHAR_CATEGORY_COMBINING_SPACING_MARK, \IntlChar::CHAR_CATEGORY_DECIMAL_DIGIT_NUMBER, \IntlChar::CHAR_CATEGORY_LETTER_NUMBER, \IntlChar::CHAR_CATEGORY_OTHER_NUMBER, \IntlChar::CHAR_CATEGORY_SPACE_SEPARATOR, \IntlChar::CHAR_CATEGORY_LINE_SEPARATOR, \IntlChar::CHAR_CATEGORY_PARAGRAPH_SEPARATOR, \IntlChar::CHAR_CATEGORY_CONTROL_CHAR, \IntlChar::CHAR_CATEGORY_FORMAT_CHAR, \IntlChar::CHAR_CATEGORY_PRIVATE_USE_CHAR, \IntlChar::CHAR_CATEGORY_SURROGATE, \IntlChar::CHAR_CATEGORY_DASH_PUNCTUATION, \IntlChar::CHAR_CATEGORY_START_PUNCTUATION, \IntlChar::CHAR_CATEGORY_END_PUNCTUATION, \IntlChar::CHAR_CATEGORY_CONNECTOR_PUNCTUATION, \IntlChar::CHAR_CATEGORY_OTHER_PUNCTUATION, \IntlChar::CHAR_CATEGORY_MATH_SYMBOL, \IntlChar::CHAR_CATEGORY_CURRENCY_SYMBOL, \IntlChar::CHAR_CATEGORY_MODIFIER_SYMBOL, \IntlChar::CHAR_CATEGORY_OTHER_SYMBOL, \IntlChar::CHAR_CATEGORY_INITIAL_PUNCTUATION, \IntlChar::CHAR_CATEGORY_FINAL_PUNCTUATION, \IntlChar::CHAR_CATEGORY_CHAR_CATEGORY_COUNT);
    expectedReturnValues(\IntlChar::charDirection(), \IntlChar::CHAR_DIRECTION_LEFT_TO_RIGHT, \IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT, \IntlChar::CHAR_DIRECTION_EUROPEAN_NUMBER, \IntlChar::CHAR_DIRECTION_EUROPEAN_NUMBER_SEPARATOR, \IntlChar::CHAR_DIRECTION_EUROPEAN_NUMBER_TERMINATOR, \IntlChar::CHAR_DIRECTION_ARABIC_NUMBER, \IntlChar::CHAR_DIRECTION_COMMON_NUMBER_SEPARATOR, \IntlChar::CHAR_DIRECTION_BLOCK_SEPARATOR, \IntlChar::CHAR_DIRECTION_SEGMENT_SEPARATOR, \IntlChar::CHAR_DIRECTION_WHITE_SPACE_NEUTRAL, \IntlChar::CHAR_DIRECTION_OTHER_NEUTRAL, \IntlChar::CHAR_DIRECTION_LEFT_TO_RIGHT_EMBEDDING, \IntlChar::CHAR_DIRECTION_LEFT_TO_RIGHT_OVERRIDE, \IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_ARABIC, \IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_EMBEDDING, \IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_OVERRIDE, \IntlChar::CHAR_DIRECTION_POP_DIRECTIONAL_FORMAT, \IntlChar::CHAR_DIRECTION_DIR_NON_SPACING_MARK, \IntlChar::CHAR_DIRECTION_BOUNDARY_NEUTRAL, \IntlChar::CHAR_DIRECTION_FIRST_STRONG_ISOLATE, \IntlChar::CHAR_DIRECTION_LEFT_TO_RIGHT_ISOLATE, \IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_ISOLATE, \IntlChar::CHAR_DIRECTION_POP_DIRECTIONAL_ISOLATE, \IntlChar::CHAR_DIRECTION_CHAR_DIRECTION_COUNT);

    expectedArguments(\json_encode(), 1, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_LINE_TERMINATORS | JSON_THROW_ON_ERROR);
    expectedArguments(\json_decode(), 3, JSON_BIGINT_AS_STRING | JSON_OBJECT_AS_ARRAY | JSON_THROW_ON_ERROR);

    expectedArguments(\idn_to_ascii(), 1, IDNA_ALLOW_UNASSIGNED | IDNA_CHECK_BIDI | IDNA_CHECK_CONTEXTJ | IDNA_DEFAULT | IDNA_NONTRANSITIONAL_TO_ASCII | IDNA_NONTRANSITIONAL_TO_UNICODE | IDNA_USE_STD3_RULES);
    expectedArguments(\idn_to_ascii(), 2, INTL_IDNA_VARIANT_UTS46, INTL_IDNA_VARIANT_2003);
    expectedArguments(\idn_to_utf8(), 1, IDNA_ALLOW_UNASSIGNED | IDNA_CHECK_BIDI | IDNA_CHECK_CONTEXTJ | IDNA_DEFAULT | IDNA_NONTRANSITIONAL_TO_ASCII | IDNA_NONTRANSITIONAL_TO_UNICODE | IDNA_USE_STD3_RULES);
    expectedArguments(\idn_to_utf8(), 2, INTL_IDNA_VARIANT_UTS46, INTL_IDNA_VARIANT_2003);

    expectedArguments(\debug_print_backtrace(), 0, DEBUG_BACKTRACE_IGNORE_ARGS);
    expectedArguments(\debug_backtrace(), 0, DEBUG_BACKTRACE_PROVIDE_OBJECT|DEBUG_BACKTRACE_IGNORE_ARGS);

    expectedArguments(\geoip_database_info(), 0, GEOIP_COUNTRY_EDITION,GEOIP_REGION_EDITION_REV0,GEOIP_CITY_EDITION_REV0,GEOIP_ORG_EDITION,GEOIP_ISP_EDITION,GEOIP_CITY_EDITION_REV1,GEOIP_REGION_EDITION_REV1,GEOIP_PROXY_EDITION,GEOIP_ASNUM_EDITION,GEOIP_NETSPEED_EDITION,GEOIP_DOMAIN_EDITION);
    expectedArguments(\count_chars(), 1, 0, 1, 2, 3, 4);
    expectedArguments(\cubrid_fetch_array(), 1, CUBRID_NUM, CUBRID_ASSOC, CUBRID_BOTH);
    expectedArguments(\imagelayereffect(), 1, IMG_EFFECT_REPLACE, IMG_EFFECT_ALPHABLEND, IMG_EFFECT_NORMAL, IMG_EFFECT_OVERLAY, IMG_EFFECT_MULTIPLY);
    expectedArguments(\db2_autocommit(), 1, DB2_AUTOCOMMIT_OFF, DB2_AUTOCOMMIT_ON);

    registerArgumentsSet("mysqliOptions", MYSQLI_OPT_CONNECT_TIMEOUT, MYSQLI_OPT_LOCAL_INFILE, MYSQLI_INIT_COMMAND, MYSQLI_READ_DEFAULT_FILE, MYSQLI_READ_DEFAULT_GROUP, MYSQLI_SERVER_PUBLIC_KEY, MYSQLI_OPT_NET_CMD_BUFFER_SIZE, MYSQLI_OPT_NET_READ_BUFFER_SIZE, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT);
    expectedArguments(\mysqli::options(), 0, argumentsSet("mysqliOptions"));
    expectedArguments(\mysqli_options(), 1, argumentsSet("mysqliOptions"));

    registerArgumentsSet("interbase_transaction_behavior_set", \IBASE_DEFAULT | \IBASE_READ | \IBASE_WRITE | \IBASE_CONSISTENCY | \IBASE_CONCURRENCY | \IBASE_COMMITTED | \IBASE_REC_VERSION | \IBASE_REC_NO_VERSION | \IBASE_WAIT | \IBASE_NOWAIT);
    expectedArguments(\ibase_trans(), 0, argumentsSet("interbase_transaction_behavior_set"));
    expectedArguments(\fbird_trans(), 0, argumentsSet("interbase_transaction_behavior_set"));
    registerArgumentsSet("interbase_fetch_flags", \IBASE_TEXT | \IBASE_FETCH_BLOBS | \IBASE_FETCH_ARRAYS | \IBASE_UNIXTIME);
    expectedArguments(\ibase_fetch_row(), 1, argumentsSet("interbase_fetch_flags"));
    expectedArguments(\ibase_fetch_assoc(), 1, argumentsSet("interbase_fetch_flags"));
    expectedArguments(\ibase_fetch_object(), 1, argumentsSet("interbase_fetch_flags"));
    expectedArguments(\fbird_fetch_row(), 1, argumentsSet("interbase_fetch_flags"));
    expectedArguments(\fbird_fetch_assoc(), 1, argumentsSet("interbase_fetch_flags"));
    expectedArguments(\fbird_fetch_object(), 1, argumentsSet("interbase_fetch_flags"));
    registerArgumentsSet("interbase_db_info_actions", \IBASE_STS_DATA_PAGES, \IBASE_STS_DB_LOG, \IBASE_STS_HDR_PAGES, \IBASE_STS_IDX_PAGES, \IBASE_STS_SYS_RELATIONS);
    expectedArguments(\ibase_db_info(), 2, argumentsSet("interbase_db_info_actions"));
    expectedArguments(\fbird_db_info(), 2, argumentsSet("interbase_db_info_actions"));
    registerArgumentsSet("interbase_server_info_actions", \IBASE_SVC_SERVER_VERSION, \IBASE_SVC_IMPLEMENTATION, \IBASE_SVC_GET_ENV, \IBASE_SVC_GET_ENV_LOCK, \IBASE_SVC_GET_ENV_MSG, \IBASE_SVC_USER_DBPATH, \IBASE_SVC_SVR_DB_INFO, \IBASE_SVC_GET_USERS);
    expectedArguments(\ibase_server_info(), 1, argumentsSet("interbase_server_info_actions"));
    expectedArguments(\fbird_server_info(), 1, argumentsSet("interbase_server_info_actions"));
    registerArgumentsSet("interbase_maintain_db_actions", \IBASE_PRP_PAGE_BUFFERS, \IBASE_PRP_SWEEP_INTERVAL, \IBASE_PRP_SHUTDOWN_DB, \IBASE_PRP_DENY_NEW_TRANSACTIONS, \IBASE_PRP_DENY_NEW_ATTACHMENTS, \IBASE_PRP_RESERVE_SPACE, \IBASE_PRP_RES_USE_FULL, \IBASE_PRP_RES, \IBASE_PRP_WRITE_MODE, \IBASE_PRP_WM_ASYNC, \IBASE_PRP_WM_SYNC, \IBASE_PRP_ACCESS_MODE, \IBASE_PRP_AM_READONLY, \IBASE_PRP_AM_READWRITE, \IBASE_PRP_SET_SQL_DIALECT, \IBASE_PRP_ACTIVATE, \IBASE_PRP_DB_ONLINE, \IBASE_RPR_CHECK_DB, \IBASE_RPR_IGNORE_CHECKSUM, \IBASE_RPR_KILL_SHADOWS, \IBASE_RPR_MEND_DB, \IBASE_RPR_VALIDATE_DB, \IBASE_RPR_FULL, \IBASE_RPR_SWEEP_DB);
    expectedArguments(\ibase_maintain_db(), 2, argumentsSet("interbase_maintain_db_actions"));
    expectedArguments(\fbird_maintain_db(), 2, argumentsSet("interbase_maintain_db_actions"));
    registerArgumentsSet("interbase_backup_options", \IBASE_BKP_IGNORE_CHECKSUMS | \IBASE_BKP_IGNORE_LIMBO | \IBASE_BKP_METADATA_ONLY | \IBASE_BKP_NO_GARBAGE_COLLECT | \IBASE_BKP_OLD_DESCRIPTIONS | \IBASE_BKP_NON_TRANSPORTABLE | \IBASE_BKP_CONVERT);
    expectedArguments(\ibase_backup(), 3, argumentsSet("interbase_backup_options"));
    expectedArguments(\fbird_backup(), 3, argumentsSet("interbase_backup_options"));
    registerArgumentsSet("interbase_restore_options", \IBASE_RES_DEACTIVATE_IDX | \IBASE_RES_NO_SHADOW | \IBASE_RES_NO_VALIDITY | \IBASE_RES_ONE_AT_A_TIME | \IBASE_RES_REPLACE | \IBASE_RES_CREATE | \IBASE_RES_USE_ALL_SPACE | \IBASE_PRP_PAGE_BUFFERS | \IBASE_PRP_SWEEP_INTERVAL | \IBASE_RES_CREATE);
    expectedArguments(\ibase_restore(), 3, argumentsSet("interbase_restore_options"));
    expectedArguments(\fbird_restore(), 3, argumentsSet("interbase_restore_options"));

    expectedArguments(\SolrClient::setServlet(), 0, \SolrClient::SEARCH_SERVLET_TYPE, \SolrClient::UPDATE_SERVLET_TYPE, \SolrClient::THREADS_SERVLET_TYPE, \SolrClient::PING_SERVLET_TYPE, \SolrClient::TERMS_SERVLET_TYPE);
    expectedArguments(\stream_socket_shutdown(), 1, STREAM_SHUT_RD, STREAM_SHUT_WR, STREAM_SHUT_RDWR);

    function expectedReturnValues($functionReference, $values) {
        return "expectedReturnValues " . $functionReference . ": " . $values;
    }
    expectedReturnValues(\fseek(), 0, -1);
    expectedReturnValues(\SplFileObject::getFlags(), argumentsSet("splFileObjectFlags"));
    expectedReturnValues(\MultipleIterator::getFlags(), argumentsSet("multipleIteratorFlags"));
    expectedReturnValues(\json_last_error(), JSON_ERROR_NONE, JSON_ERROR_DEPTH, JSON_ERROR_STATE_MISMATCH, JSON_ERROR_CTRL_CHAR, JSON_ERROR_SYNTAX, JSON_ERROR_UTF8, JSON_ERROR_RECURSION, JSON_ERROR_INF_OR_NAN, JSON_ERROR_UNSUPPORTED_TYPE, JSON_ERROR_INVALID_PROPERTY_NAME, JSON_ERROR_UTF16);
    expectedReturnValues(\preg_last_error(), PREG_NO_ERROR,PREG_INTERNAL_ERROR,PREG_BACKTRACK_LIMIT_ERROR,PREG_RECURSION_LIMIT_ERROR,PREG_BAD_UTF8_ERROR,PREG_BAD_UTF8_OFFSET_ERROR);

    expectedArguments(\password_hash(), 1, PASSWORD_DEFAULT, PASSWORD_BCRYPT, PASSWORD_ARGON2I, PASSWORD_ARGON2ID);
    expectedArguments(\password_needs_rehash(), 1, PASSWORD_DEFAULT, PASSWORD_BCRYPT, PASSWORD_ARGON2I, PASSWORD_ARGON2ID);

    registerArgumentsSet('pgResultTypes', PGSQL_ASSOC, PGSQL_NUM, PGSQL_BOTH);
    expectedArguments(\pg_fetch_all(), 1, argumentsSet('pgResultTypes'));
    expectedArguments(\pg_fetch_array(), 2, argumentsSet('pgResultTypes'));
    expectedArguments(\pg_get_notify(), 1, argumentsSet('pgResultTypes'));
    expectedArguments(\pg_select(), 3, PGSQL_CONV_FORCE_NULL | PGSQL_DML_NO_CONV | PGSQL_DML_EXEC | PGSQL_DML_ASYNC | PGSQL_DML_STRING);
    expectedArguments(\pg_select(), 4, argumentsSet('pgResultTypes'));

    expectedReturnValues(\php_sapi_name(), 'aolserver', 'apache', 'apache2filter', 'apache2handler', 'caudium', 'cgi-fcgi', 'cli', 'cli-server', 'continuity', 'embed', 'fpm-fcgi', 'isapi', 'litespeed', 'milter', 'nsapi', 'phpdbg', 'phttpd', 'pi3web', 'roxen', 'thttpd', 'tux', 'webjames');
    expectedArguments(\phpversion(), 0, 'amqp', 'apache', 'apc', 'apd', 'bbcode', 'bcmath', 'bcompiler', 'bz2', 'cairo', 'calendar', 'chdb', 'classkit', 'com', 'crack', 'ctype', 'cubrid', 'curl', 'cyrus', 'dba', 'dbase', 'dbplus', 'dbx', 'dio', 'dom', 'dotnet', 'eio', 'enchant', 'ev', 'event', 'exif', 'expect', 'fam', 'fbsql', 'fdf', 'fileinfo', 'filepro', 'filter', 'fribidi', 'ftp', 'gearman', 'gender', 'geoip', 'gettext', 'gmagick', 'gmp', 'gnupg', 'gupnp', 'haru', 'htscanner', 'pecl_http', 'hyperwave', 'hwapi', 'interbase', 'ibm_db2', 'iconv', 'id3', 'informix', 'iisfunc', 'gd', 'imagick', 'imap', 'inclued', 'ingres', 'inotify', 'intl', 'java', 'json', 'judy', 'kadm5', 'ktaglib', 'lapack', 'ldap', 'libevent', 'libxml', 'lua', 'lzf', 'mailparse', 'maxdb', 'mbstring', 'mcrypt', 'mcve', 'memcache', 'memcached', 'memtrack', 'mhash', 'ming', 'mnogosearch', 'mongo', 'mqseries', 'msession', 'msql', 'mssql', 'mysql', 'mysqli', 'mysqlnd', 'mysqlnd_memcache', 'mysqlnd_ms', 'mysqlnd_mux', 'mysqlnd_qc', 'mysqlnd_uh', 'ncurses', 'net_gopher', 'newt', 'notes', 'nsapi', 'oauth', 'oci8', 'oggvorbis', 'openal', 'openssl', 'ovrimos', 'paradox', 'parsekit', 'pcntl', 'pcre', 'pdflib', 'pdo', 'pdo_4d', 'pdo_cubrid', 'pdo_dblib', 'pdo_firebird', 'pdo_ibm', 'pdo_informix', 'pdo_mysql', 'pdo_oci', 'pdo_odbc', 'pdo_pgsql', 'pdo_sqlite', 'pdo_sqlsrv', 'pdo_pgsql', 'phar', 'posix', 'printer', 'proctitle', 'ps', 'pspell', 'pthreads', 'qtdom', 'quickhash', 'radius', 'rar', 'readline', 'recode', 'rpmreader', 'rrd', 'runkit', 'sam', 'sca', 'scream', 'sca_sdo', 'sysvmsg', 'session', 'session_pgsql', 'shmop', 'simplexml', 'snmp', 'soap', 'sockets', 'solr', 'sphinx', 'spl_types', 'spplus', 'sqlite', 'sqlite3', 'sqlsrv', 'ssdeep', 'ssh2', 'stats', 'stomp', 'svm', 'svn', 'swf', 'swish', 'sybase', 'taint', 'tcpwrap', 'tidy', 'tokenizer', 'tokyo_tyrant', 'trader', 'odbc', 'v8js', 'varnish', 'vpopmail', 'w32api', 'wddx', 'weakref', 'win32ps', 'win32service', 'wincache', 'xattr', 'xdiff', 'xhprof', 'xml', 'xmlreader', 'xmlrpc', 'xmlwriter', 'xsl', 'xslt', 'yaf', 'yaml', 'yaz', 'zip', 'zlib');

    registerArgumentsSet('session_cache_limiters', 'public', 'private_no_expire', 'private', 'nocache');
    expectedArguments(\session_cache_limiter(), 0, argumentsSet('session_cache_limiters'));
    expectedReturnValues(\session_cache_limiter(), argumentsSet('session_cache_limiters'));
    registerArgumentsSet('session_module_names', 'files', 'mm', 'user');
    expectedArguments(\session_module_name(), 0, argumentsSet('session_module_names'));
    expectedReturnValues(\session_module_name(), argumentsSet('session_module_names'));
    expectedReturnValues(\session_status(), \PHP_SESSION_DISABLED, \PHP_SESSION_NONE, \PHP_SESSION_ACTIVE);

    expectedArguments(\checkdnsrr(), 1, 'A', 'MX', 'NS', 'SOA', 'PTR', 'CNAME', 'AAAA', 'A6', 'SRV', 'NAPTR', 'TXT', 'ANY');

    expectedArguments(\yaml_emit(), 1, YAML_ANY_ENCODING, YAML_UTF8_ENCODING, YAML_UTF16LE_ENCODING, YAML_UTF16BE_ENCODING);
    expectedArguments(\yaml_emit(), 2, YAML_ANY_BREAK, YAML_CR_BREAK, YAML_LN_BREAK, YAML_CRLN_BREAK);

    expectedArguments(\AMQPExchange::delete(), 1, AMQP_NOPARAM, AMQP_IFUNUSED);
    expectedArguments(\AMQPExchange::publish(), 2, AMQP_NOPARAM | AMQP_MANDATORY | AMQP_IMMEDIATE);
    expectedArguments(\AMQPExchange::setFlags(), 0, AMQP_PASSIVE | AMQP_DURABLE | AMQP_AUTODELETE | AMQP_INTERNAL);
    expectedReturnValues(\AMQPExchange::getFlags(), AMQP_PASSIVE | AMQP_DURABLE | AMQP_AUTODELETE | AMQP_INTERNAL);

    expectedArguments(\AMQPQueue::ack(), 1, AMQP_NOPARAM, AMQP_MULTIPLE);
    expectedArguments(\AMQPQueue::consume(), 1, AMQP_NOPARAM | AMQP_AUTOACK | AMQP_JUST_CONSUME | AMQP_NOLOCAL);
    expectedArguments(\AMQPQueue::delete(), 0, AMQP_NOPARAM | AMQP_IFUNUSED | AMQP_IFEMPTY);
    expectedArguments(\AMQPQueue::get(), 0, AMQP_NOPARAM, AMQP_AUTOACK);
    expectedArguments(\AMQPQueue::nack(), 1, AMQP_NOPARAM | AMQP_REQUEUE | AMQP_MULTIPLE);
    expectedArguments(\AMQPQueue::reject(), 1, AMQP_NOPARAM, AMQP_REQUEUE);
    expectedArguments(\AMQPQueue::setFlags(), 0, AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_EXCLUSIVE | AMQP_AUTODELETE);
    expectedReturnValues(\AMQPQueue::getFlags(), AMQP_NOPARAM | AMQP_DURABLE | AMQP_PASSIVE | AMQP_EXCLUSIVE | AMQP_AUTODELETE);

    expectedArguments(\Attribute::__construct(), 0,
        \Attribute::IS_REPEATABLE |
        \Attribute::TARGET_CLASS |
        \Attribute::TARGET_ALL |
        \Attribute::TARGET_METHOD |
        \Attribute::TARGET_PARAMETER |
        \Attribute::TARGET_PROPERTY |
        \Attribute::TARGET_FUNCTION |
        \Attribute::TARGET_CLASS_CONSTANT
    );

    registerArgumentsSet('SortFlags', SORT_REGULAR | SORT_NUMERIC | SORT_STRING | SORT_LOCALE_STRING | SORT_NATURAL | SORT_FLAG_CASE);
    expectedArguments(\sort(), 1, argumentsSet('SortFlags'));
    expectedArguments(\rsort(), 1, argumentsSet('SortFlags'));
    expectedArguments(\asort(), 1, argumentsSet('SortFlags'));
    expectedArguments(\arsort(), 1, argumentsSet('SortFlags'));
    expectedArguments(\ksort(), 1, argumentsSet('SortFlags'));
    expectedArguments(\krsort(), 1, argumentsSet('SortFlags'));

    expectedArguments(\array_unique(), 1, SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING);

    /**
     * Use this constant to mark the function with an argument on the specified position as an exit point
     *
     * {@see exitPoint()}
     */
    const ANY_ARGUMENT = 1;

    /**
     * You can use this facility to mark the function as halting the execution flow.
     * Such marked functions will be treated like die() or exit() calls by control flow inspections.
     * In most cases, just calling this function with a method or function reference with 0 arguments will work.
     * To mark the function as the exit point only when it's called with some constant arguments, specify them in $funcionReference param
     *
     * {@see ANY_ARGUMENT}
     */
    function exitPoint($functionReference) {
        return "exitPoint " . $functionReference;
    }

    exitPoint(\trigger_error(ANY_ARGUMENT, \E_USER_ERROR));
    exitPoint(\jexit());
    exitPoint(\wp_die());
    exitPoint(\dd());

//  override( \ServiceLocatorInterface::get(0),
//    map( [
//      "A" => \Exception::class,
//      \ExampleFactory::EXAMPLE_B => ExampleB::class,
//      \EXAMPLE_B => \ExampleB::class,
//      '' =>  '@|\Iterator',
//    ]));

}
