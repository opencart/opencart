<?php
/**
 * Radius constants
 * @link https://secure.php.net/manual/en/radius.constants.php
 */
/** The maximum length of MPPE keys. */
define('RADIUS_MPPE_KEY_LEN', 16);

/*
 * RADIUS Options
 * @link https://secure.php.net/manual/en/radius.constants.options.php
 */

/** When set, this option will result in the attribute value being salt-encrypted. */
define('RAD_OPTION_TAG', 1);
/** When set, this option will result in the attribute value being tagged with the value of the tag parameter. */
define('RADIUS_OPTION_SALT', 2);

/*
 * RADIUS Packet Types
 * @link https://secure.php.net/manual/en/radius.constants.packets.php
 */

/** An Access-Request, used to authenticate a user against a RADIUS server. Access request packets must include a <b>RADIUS_NAS_IP_ADDRESS</b> or a <b>RADIUS_NAS_IDENTIFIER</b> attribute, must also include a <b>RADIUS_USER_PASSWORD</b>, <b>RADIUS_CHAP_PASSWORD</b> or a <b>RADIUS_STATE</b> attribute, and should include a <b>RADIUS_USER_NAME</b> attribute. */
define('RADIUS_ACCESS_REQUEST', 1);

/** An Access-Accept response to an Access-Request indicating that the RADIUS server authenticated the user successfully. */
define('RADIUS_ACCESS_ACCEPT', 2);

/** An Access-Reject response to an Access-Request indicating that the RADIUS server could not authenticate the user. */
define('RADIUS_ACCESS_REJECT', 3);

/** An Accounting-Request, used to convey accounting information for a service to the RADIUS server. */
define('RADIUS_ACCOUNTING_REQUEST', 4);

/** An Accounting-Response response to an Accounting-Request. */
define('RADIUS_ACCOUNTING_RESPONSE', 5);

/** An Access-Challenge response to an Access-Request indicating that the RADIUS server requires further information in another Access-Request before authenticating the user. */
define('RADIUS_ACCESS_CHALLENGE', 11);

/**
 * A Disconnect-Request, sent from the RADIUS server to indicate that the user session must be terminated.
 * @since 1.3.0
 */
define('RADIUS_DISCONNECT_REQUEST', 40);

/**
 * A Disconnect-ACK, sent to the RADIUS server to indicate that the user session has been terminated.
 * @since 1.3.0
 */
define('RADIUS_DISCONNECT_ACK', 41);

/**
 * A Disconnect-NAK, sent to the RADIUS server to indicate that the user session could not be terminated.
 * @since 1.3.0
 */
define('RADIUS_DISCONNECT_NAK', 42);

/**
 * A CoA-Request, sent from the RADIUS server to indicate that the authorisations within the user session have changed. A response must be sent in the form of a CoA-ACK or a CoA-NAK.
 * @since 1.3.0
 */
define('RADIUS_COA_REQUEST', 43);

/**
 * A CoA-ACK, sent to the RADIUS server to indicate that the user authorisations have been updated.
 * @since 1.3.0
 */
define('RADIUS_COA_ACK', 44);

/**
 * A CoA-NAK, sent to the RADIUS server to indicate that the user authorisations could not be updated.
 * @since 1.3.0
 */
define('RADIUS_COA_NAK', 45);

/*
 * RADIUS Attribute Types
 * @link https://secure.php.net/manual/en/radius.constants.attributes.php
 */

/** The User-Name attribute. The attribute value is expected to be a string containing the name of the user being authenticated, and can be set using {@see radius_put_attr()}. */
define('RADIUS_USER_NAME', 1);

/**
 * The User-Password attribute. The attribute value is expected to be a string containing the user's password, and can be set using {@see radius_put_attr()}. This value will be obfuscated on transmission as described in section 5.2 of RFC 2865.
 * @link https://secure.php.net/manual/en/radius.constants.attributes.php */
define('RADIUS_USER_PASSWORD', 2);

/** The Chap-Password attribute. The attribute value is expected to be a string with the first byte containing the CHAP identifier, and the subsequent 16 bytes containing the MD5 hash of the CHAP identifier, the plaintext password and the CHAP challenge value concatenated together. Note that the CHAP challenge value should also be sent separately in a <b>{@see RADIUS_CHAP_CHALLENGE}</b> attribute. */
define('RADIUS_CHAP_PASSWORD', 3);

/** The NAS-IP-Address attribute. The attribute value is expected to the IP address of the RADIUS client encoded as an integer, which can be set using {@see radius_put_addr()}. */
define('RADIUS_NAS_IP_ADDRESS', 4);

/** The NAS-Port attribute. The attribute value is expected to be the physical port of the user on the RADIUS client encoded as an integer, which can be set using {@see radius_put_int()}. */
define('RADIUS_NAS_PORT', 5);

/**
 * The Service-Type attribute. The attribute value indicates the service type the user is requesting, and is expected to be an integer, which can be set using {@see radius_put_int()}.
 * A number of constants are provided to represent the possible values of this attribute. They include:
 * - RADIUS_LOGIN
 * - RADIUS_FRAMED
 * - RADIUS_CALLBACK_LOGIN
 * - RADIUS_CALLBACK_FRAMED
 * - RADIUS_OUTBOUND
 * - RADIUS_ADMINISTRATIVE
 * - RADIUS_NAS_PROMPT
 * - RADIUS_AUTHENTICATE_ONLY
 * - RADIUS_CALLBACK_NAS_PROMPT
 */
define('RADIUS_SERVICE_TYPE', 6);
define('RADIUS_LOGIN', 1);
define('RADIUS_FRAMED', 2);
define('RADIUS_CALLBACK_LOGIN', 3);
define('RADIUS_CALLBACK_FRAMED', 4);
define('RADIUS_OUTBOUND', 5);
define('RADIUS_ADMINISTRATIVE', 6);
define('RADIUS_NAS_PROMPT', 7);
define('RADIUS_AUTHENTICATE_ONLY', 8);
define('RADIUS_CALLBACK_NAS_PROMPT', 9);

/**
 * The Framed-Protocol attribute. The attribute value is expected to be an integer indicating the framing to be used for framed access, and can be set using {@see radius_put_int()}. The possible attribute values include these constants:
 * - RADIUS_PPP
 * - RADIUS_SLIP
 * - RADIUS_ARAP
 * - RADIUS_GANDALF
 * - RADIUS_XYLOGICS
 */
define('RADIUS_FRAMED_PROTOCOL', 7);
define('RADIUS_PPP', 1);
define('RADIUS_SLIP', 2);
define('RADIUS_ARAP', 3);
define('RADIUS_GANDALF', 4);
define('RADIUS_XYLOGICS', 5);

/** The Framed-IP-Address attribute. The attribute value is expected to be the address of the user's network encoded as an integer, which can be set using {@see radius_put_addr()} and retrieved using {@see radius_cvt_addr()}. */
define('RADIUS_FRAMED_IP_ADDRESS', 8);

/** The Framed-IP-Netmask attribute. The attribute value is expected to be the netmask of the user's network encoded as an integer, which can be set using {@see radius_put_addr()} and retrieved using {@see radius_cvt_addr()}. */
define('RADIUS_FRAMED_IP_NETMASK', 9);

/**
 * The Framed-Routing attribute. The attribute value is expected to be an integer indicating the routing method for the user, which can be set using {@see radius_put_int()}.<br>
 * <br>
 * Possible values include:
 * - 0: No routing
 * - 1: Send routing packets
 * - 2: Listen for routing packets
 * - 3: Send and listen
 */
define('RADIUS_FRAMED_ROUTING', 10);

/** The Filter-ID attribute. The attribute value is expected to be an implementation-specific, human-readable string of filters, which can be set using {@see radius_put_attr()}.*/
define('RADIUS_FILTER_ID', 11);

/** The Framed-MTU attribute. The attribute value is expected to be an integer indicating the MTU to be configured for the user, and can be set using {@see radius_put_int()}.*/
define('RADIUS_FRAMED_MTU', 12);

/** The Framed-Compression attribute. The attribute value is expected to be an integer indicating the compression protocol to be used, and can be set using radius_put_int(). Possible values include these constants:
 * - <b>RADIUS_COMP_NONE</b>: No compression
 * - <b>RADIUS_COMP_VJ</b>: VJ TCP/IP header compression
 * - <b>RADIUS_COMP_IPXHDR</b>: IPX header compression
 * - <b>RADIUS_COMP_STAC_LZS</b>: Stac-LZS compression (added in PECL radius 1.3.0b2)
 */
define('RADIUS_FRAMED_COMPRESSION', 13);
define('RADIUS_COMP_NONE', 0);
define('RADIUS_COMP_VJ', 1);
define('RADIUS_COMP_IPXHDR', 2);

/** The Login-IP-Host attribute. The attribute value is expected to the IP address to connect the user to, encoded as an integer, which can be set using {@see radius_put_addr()}. */
define('RADIUS_LOGIN_IP_HOST', 14);

/** The Login-Service attribute. The attribute value is an integer indicating the service to connect the user to on the login host. The value can be converted to a PHP integer via {@see radius_cvt_int()}.*/
define('RADIUS_LOGIN_SERVICE', 15);
define('RADIUS_LOGIN_TCP_PORT', 16);
define('RADIUS_REPLY_MESSAGE', 18);
define('RADIUS_CALLBACK_NUMBER', 19);
define('RADIUS_CALLBACK_ID', 20);
define('RADIUS_FRAMED_ROUTE', 22);
define('RADIUS_FRAMED_IPX_NETWORK', 23);
define('RADIUS_STATE', 24);
define('RADIUS_CLASS', 25);
define('RADIUS_VENDOR_SPECIFIC', 26);
define('RADIUS_SESSION_TIMEOUT', 27);
define('RADIUS_IDLE_TIMEOUT', 28);
define('RADIUS_TERMINATION_ACTION', 29);
define('RADIUS_CALLED_STATION_ID', 30);
define('RADIUS_CALLING_STATION_ID', 31);
define('RADIUS_NAS_IDENTIFIER', 32);
define('RADIUS_PROXY_STATE', 33);
define('RADIUS_LOGIN_LAT_SERVICE', 34);
define('RADIUS_LOGIN_LAT_NODE', 35);
define('RADIUS_LOGIN_LAT_GROUP', 36);
define('RADIUS_FRAMED_APPLETALK_LINK', 37);
define('RADIUS_FRAMED_APPLETALK_NETWORK', 38);
define('RADIUS_FRAMED_APPLETALK_ZONE', 39);
define('RADIUS_CHAP_CHALLENGE', 60);
define('RADIUS_NAS_PORT_TYPE', 61);
define('RADIUS_ASYNC', 0);
define('RADIUS_SYNC', 1);
define('RADIUS_ISDN_SYNC', 2);
define('RADIUS_ISDN_ASYNC_V120', 3);
define('RADIUS_ISDN_ASYNC_V110', 4);
define('RADIUS_VIRTUAL', 5);
define('RADIUS_PIAFS', 6);
define('RADIUS_HDLC_CLEAR_CHANNEL', 7);
define('RADIUS_X_25', 8);
define('RADIUS_X_75', 9);
define('RADIUS_G_3_FAX', 10);
define('RADIUS_SDSL', 11);
define('RADIUS_ADSL_CAP', 12);
define('RADIUS_ADSL_DMT', 13);
define('RADIUS_IDSL', 14);
define('RADIUS_ETHERNET', 15);
define('RADIUS_XDSL', 16);
define('RADIUS_CABLE', 17);
define('RADIUS_WIRELESS_OTHER', 18);
define('RADIUS_WIRELESS_IEEE_802_11', 19);
define('RADIUS_PORT_LIMIT', 62);
define('RADIUS_LOGIN_LAT_PORT', 63);
define('RADIUS_CONNECT_INFO', 77);
define('RADIUS_NAS_IPV6_ADDRESS', 95);
define('RADIUS_FRAMED_INTERFACE_ID', 96);
define('RADIUS_FRAMED_IPV6_PREFIX', 97);
define('RADIUS_LOGIN_IPV6_HOST', 98);
define('RADIUS_FRAMED_IPV6_ROUTE', 99);
define('RADIUS_FRAMED_IPV6_POOL', 100);
define('RADIUS_ERROR_CAUSE', 101);
define('RADIUS_ERROR_CAUSE_RESIDUAL_SESSION_CONTEXT_REMOVED', 201);
define('RADIUS_ERROR_CAUSE_INVALID_EAP_PACKET', 202);
define('RADIUS_ERROR_CAUSE_UNSUPPORTED_ATTRIBUTE', 401);
define('RADIUS_ERROR_CAUSE_MISSING_ATTRIBUTE', 402);
define('RADIUS_ERROR_CAUSE_NAS_IDENTIFICATION_MISMATCH', 403);
define('RADIUS_ERROR_CAUSE_INVALID_REQUEST', 404);
define('RADIUS_ERROR_CAUSE_UNSUPPORTED_SERVICE', 405);
define('RADIUS_ERROR_CAUSE_UNSUPPORTED_EXCEPTION', 406);
define('RADIUS_ERROR_CAUSE_ADMINISTRATIVELY_PROHIBITED', 501);
define('RADIUS_ERROR_CAUSE_REQUEST_NOT_ROUTABLE', 502);
define('RADIUS_ERROR_CAUSE_SESSION_CONTEXT_NOT_FOUND', 503);
define('RADIUS_ERROR_CAUSE_SESSION_CONTEXT_NOT_REMOVABLE', 504);
define('RADIUS_ERROR_CAUSE_OTHER_PROXY_PROCESSING_ERROR', 505);
define('RADIUS_ERROR_CAUSE_RESOURCES_UNAVAILABLE', 506);
define('RADIUS_ERROR_CAUSE_REQUEST_INITIATED', 507);
define('RADIUS_ACCT_STATUS_TYPE', 40);
define('RADIUS_START', 1);
define('RADIUS_STOP', 2);
define('RADIUS_ACCOUNTING_ON', 7);
define('RADIUS_ACCOUNTING_OFF', 8);
define('RADIUS_ACCT_DELAY_TIME', 41);
define('RADIUS_ACCT_INPUT_OCTETS', 42);
define('RADIUS_ACCT_OUTPUT_OCTETS', 43);
define('RADIUS_ACCT_SESSION_ID', 44);
define('RADIUS_ACCT_AUTHENTIC', 45);
define('RADIUS_AUTH_RADIUS', 1);
define('RADIUS_AUTH_LOCAL', 2);
define('RADIUS_AUTH_REMOTE', 3);
define('RADIUS_ACCT_SESSION_TIME', 46);
define('RADIUS_ACCT_INPUT_PACKETS', 47);
define('RADIUS_ACCT_OUTPUT_PACKETS', 48);
define('RADIUS_ACCT_TERMINATE_CAUSE', 49);
define('RADIUS_TERM_USER_REQUEST', 1);
define('RADIUS_TERM_LOST_CARRIER', 2);
define('RADIUS_TERM_LOST_SERVICE', 3);
define('RADIUS_TERM_IDLE_TIMEOUT', 4);
define('RADIUS_TERM_SESSION_TIMEOUT', 5);
define('RADIUS_TERM_ADMIN_RESET', 6);
define('RADIUS_TERM_ADMIN_REBOOT', 7);
define('RADIUS_TERM_PORT_ERROR', 8);
define('RADIUS_TERM_NAS_ERROR', 9);
define('RADIUS_TERM_NAS_REQUEST', 10);
define('RADIUS_TERM_NAS_REBOOT', 11);
define('RADIUS_TERM_PORT_UNNEEDED', 12);
define('RADIUS_TERM_PORT_PREEMPTED', 13);
define('RADIUS_TERM_PORT_SUSPENDED', 14);
define('RADIUS_TERM_SERVICE_UNAVAILABLE', 15);
define('RADIUS_TERM_CALLBACK', 16);
define('RADIUS_TERM_USER_ERROR', 17);
define('RADIUS_TERM_HOST_REQUEST', 18);
define('RADIUS_ACCT_MULTI_SESSION_ID', 50);
define('RADIUS_ACCT_LINK_COUNT', 51);
define('RADIUS_VENDOR_MICROSOFT', 311);
define('RADIUS_MICROSOFT_MS_CHAP_RESPONSE', 1);
define('RADIUS_MICROSOFT_MS_CHAP_ERROR', 2);
define('RADIUS_MICROSOFT_MS_CHAP_PW_1', 3);
define('RADIUS_MICROSOFT_MS_CHAP_PW_2', 4);
define('RADIUS_MICROSOFT_MS_CHAP_LM_ENC_PW', 5);
define('RADIUS_MICROSOFT_MS_CHAP_NT_ENC_PW', 6);
define('RADIUS_MICROSOFT_MS_MPPE_ENCRYPTION_POLICY', 7);
define('RADIUS_MICROSOFT_MS_MPPE_ENCRYPTION_TYPES', 8);
define('RADIUS_MICROSOFT_MS_RAS_VENDOR', 9);
define('RADIUS_MICROSOFT_MS_CHAP_DOMAIN', 10);
define('RADIUS_MICROSOFT_MS_CHAP_CHALLENGE', 11);
define('RADIUS_MICROSOFT_MS_CHAP_MPPE_KEYS', 12);
define('RADIUS_MICROSOFT_MS_BAP_USAGE', 13);
define('RADIUS_MICROSOFT_MS_LINK_UTILIZATION_THRESHOLD', 14);
define('RADIUS_MICROSOFT_MS_LINK_DROP_TIME_LIMIT', 15);
define('RADIUS_MICROSOFT_MS_MPPE_SEND_KEY', 16);
define('RADIUS_MICROSOFT_MS_MPPE_RECV_KEY', 17);
define('RADIUS_MICROSOFT_MS_RAS_VERSION', 18);
define('RADIUS_MICROSOFT_MS_OLD_ARAP_PASSWORD', 19);
define('RADIUS_MICROSOFT_MS_NEW_ARAP_PASSWORD', 20);
define('RADIUS_MICROSOFT_MS_ARAP_PASSWORD_CHANGE_REASON', 21);
define('RADIUS_MICROSOFT_MS_FILTER', 22);
define('RADIUS_MICROSOFT_MS_ACCT_AUTH_TYPE', 23);
define('RADIUS_MICROSOFT_MS_ACCT_EAP_TYPE', 24);
define('RADIUS_MICROSOFT_MS_CHAP2_RESPONSE', 25);
define('RADIUS_MICROSOFT_MS_CHAP2_SUCCESS', 26);
define('RADIUS_MICROSOFT_MS_CHAP2_PW', 27);
define('RADIUS_MICROSOFT_MS_PRIMARY_DNS_SERVER', 28);
define('RADIUS_MICROSOFT_MS_SECONDARY_DNS_SERVER', 29);
define('RADIUS_MICROSOFT_MS_PRIMARY_NBNS_SERVER', 30);
define('RADIUS_MICROSOFT_MS_SECONDARY_NBNS_SERVER', 31);
define('RADIUS_MICROSOFT_MS_ARAP_CHALLENGE', 33);
define('RADIUS_OPTION_NONE', RADIUS_OPTION_NONE);
define('RADIUS_OPTION_TAGGED', RADIUS_OPTION_TAGGED);

/**
 * Creates a Radius handle for accounting
 * @link https://secure.php.net/manual/en/function.radius-acct-open.php
 * @return resource|false Returns a handle on success, <b>FALSE</b> on error. This function only fails if insufficient memory is available.
 * @since 1.1.0
 */
function radius_acct_open() {}

/**
 * <b>radius_add_server()</b> may be called multiple times, and it may be used together with {@see radius_config()}. At most 10 servers may be specified. When multiple servers are given, they are tried in round-robin fashion until a valid response is received, or until each server's max_tries limit has been reached.
 * @link https://secure.php.net/manual/en/function.radius-add-server.php
 * @param resource $radius_handle
 * @param string $hostname The <b>hostname</b> parameter specifies the server host, either as a fully qualified domain name or as a dotted-quad IP address in text form.
 * @param int $port The <b>port</b> specifies the UDP port to contact on the server.<br>
 *                  If port is given as 0, the library looks up the radius/udp or radacct/udp service in the network services database, and uses the port found there.<br>
 *                  If no entry is found, the library uses the standard Radius ports, 1812 for authentication and 1813 for accounting.
 * @param string $secret The shared secret for the server host is passed to the <b>secret</b> parameter. The Radius protocol ignores all but the leading 128 bytes of the shared secret.
 * @param int $timeout The timeout for receiving replies from the server is passed to the <b>timeout</b> parameter, in units of seconds.
 * @param int $max_tries The maximum number of repeated requests to make before giving up is passed into the <b>max_tries</b>.
 * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * @see radius_config()
 * @since 1.1.0
 */
function radius_add_server($radius_handle, $hostname, $port, $secret, $timeout, $max_tries) {}

/**
 * Creates a Radius handle for authentication
 * @link https://secure.php.net/manual/en/function.radius-auth-open.php
 * @return resource|false Returns a handle on success, <b>FALSE</b> on error. This function only fails if insufficient memory is available.
 * @since 1.1.0
 */
function radius_auth_open() {}

/**
 * Free all resources. It is not needed to call this function because php frees all resources at the end of each request.
 * @link https://secure.php.net/manual/en/function.radius-close.php
 * @param resource $radius_handle
 * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * @since 1.1.0
 */
function radius_close($radius_handle) {}

/**
 * Before issuing any Radius requests, the library must be made aware of the servers it can contact. The easiest way to configure the library is to call <b>radius_config()</b>. <b>radius_config()</b> causes the library to read a configuration file whose format is described in radius.conf.
 * @link https://secure.php.net/manual/en/function.radius-config.php
 * @link https://www.freebsd.org/cgi/man.cgi?query=radius.conf
 * @param resource $radius_handle
 * @param string $file The pathname of the configuration file is passed as the file argument to {@see radius_config()}. The library can also be configured programmatically by calls to <b>{@see radius_add_server()}</b>.
 * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * @see radius_add_server()
 * @since 1.1.0
 */
function radius_config($radius_handle, $file) {}

/**
 * A Radius request consists of a code specifying the kind of request, and zero or more attributes which provide additional information. To begin constructing a new request, call <b>radius_create_request()</b>.<br />
 * <b>Note:</b> Attention: You must call this function, before you can put any attribute!
 * @link https://secure.php.net/manual/en/function.radius-create-request.php
 * @param resource $radius_handle
 * @param int $type Type is <b>RADIUS_ACCESS_REQUEST</b> or <b>RADIUS_ACCOUNTING_REQUEST</b>.
 * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * @see radius_send_request()
 * @since 1.1.0
 */
function radius_create_request($radius_handle, $type) {}
