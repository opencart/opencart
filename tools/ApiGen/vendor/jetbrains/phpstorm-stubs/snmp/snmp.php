<?php

// Start of snmp v.0.1

/**
 * Represents SNMP session.
 * @link https://php.net/manual/en/class.snmp.php
 */
class SNMP
{
    /**
     * @var int Maximum OID per GET/SET/GETBULK request
     * @link https://secure.php.net/manual/en/class.snmp.php#snmp.props.max-oids
     */
    public $max_oids;

    /**
     * @var int Controls the method how the SNMP values will be returned
     * <dl>
     * <dt>SNMP_VALUE_LIBRARY</dt><dd>The return values will be as returned by the Net-SNMP library.</dd>
     * <dt>SNMP_VALUE_PLAIN</dt><dd>The return values will be the plain value without the SNMP type hint.</dd>
     * <dt>SNMP_VALUE_OBJECT</dt><dd>The return values will be objects with the properties "value" and "type", where the latter is one of the SNMP_OCTET_STR, SNMP_COUNTER etc. constants. The way "value" is returned is based on which one of SNMP_VALUE_LIBRARY, SNMP_VALUE_PLAIN is set</dd>
     * <dl>
     * @link https://secure.php.net/manual/en/class.snmp.php#snmp.props.max-oids
     */
    public $valueretrieval;

    /**
     * @var bool Value of quick_print within the NET-SNMP library
     * <p>Sets the value of quick_print within the NET-SNMP library. When this is set (1), the SNMP library will return 'quick printed' values. This means that just the value will be printed. When quick_print is not enabled (default) the UCD SNMP library prints extra information including the type of the value (i.e. IpAddress or OID). Additionally, if quick_print is not enabled, the library prints additional hex values for all strings of three characters or less.
     * @link https://secure.php.net/manual/en/class.snmp.php#snmp.props.quick-print
     */
    public $quick_print;

    /**
     * @var bool Controls the way enum values are printed
     * <p>Parameter toggles if walk/get etc. should automatically lookup enum values in the MIB and return them together with their human readable string.
     * @link https://secure.php.net/manual/en/class.snmp.php#snmp.props.enum-print
     */
    public $enum_print;

    /**
     * @var int Controls OID output format
     * <p>OID .1.3.6.1.2.1.1.3.0 representation for various oid_output_format values
     * <dl>
     * <dt>SNMP_OID_OUTPUT_FULL</dt><dd>.iso.org.dod.internet.mgmt.mib-2.system.sysUpTime.sysUpTimeInstance</dd>
     * <dt>SNMP_OID_OUTPUT_NUMERIC</dt><dd>.1.3.6.1.2.1.1.3.0</dd>
     * <dt>SNMP_OID_OUTPUT_MODULE</dt><dd>DISMAN-EVENT-MIB::sysUpTimeInstance</dd>
     * <dt>SNMP_OID_OUTPUT_SUFFIX</dt><dd>sysUpTimeInstance</dd>
     * <dt>SNMP_OID_OUTPUT_UCD</dt><dd>system.sysUpTime.sysUpTimeInstance</dd>
     * <dt>SNMP_OID_OUTPUT_NONE</dt><dd>Undefined</dd>
     * </dl>
     * @link https://secure.php.net/manual/en/class.snmp.php#snmp.props.oid-output-format
     */
    public $oid_output_format;

    /**
     * @var bool Controls disabling check for increasing OID while walking OID tree
     * <p> Some SNMP agents are known for returning OIDs out of order but can complete the walk anyway. Other agents return OIDs that are out of order and can cause SNMP::walk() to loop indefinitely until memory limit will be reached. PHP SNMP library by default performs OID increasing check and stops walking on OID tree when it detects possible loop with issuing warning about non-increasing OID faced. Set oid_increasing_check to <b>FALSE</b> to disable this check.
     * @link https://secure.php.net/manual/en/class.snmp.php#snmp.props.oid-increasing-check
     */
    public $oid_increasing_check;

    /**
     * @var int Controls which failures will raise SNMPException instead of warning. Use bitwise OR'ed SNMP::ERRNO_* constants. By default all SNMP exceptions are disabled.
     * @link https://secure.php.net/manual/en/class.snmp.php#snmp.props.exceptions-enabled
     */
    public $exceptions_enabled;

    /**
     * @var array Read-only property with remote agent configuration: hostname, port, default timeout, default retries count
     * @link https://secure.php.net/manual/en/class.snmp.php#snmp.props.info
     */
    public $info;
    public const VERSION_1 = 0;
    public const VERSION_2c = 1;
    public const VERSION_2C = 1;
    public const VERSION_3 = 3;
    public const ERRNO_NOERROR = 0;
    public const ERRNO_ANY = 126;
    public const ERRNO_GENERIC = 2;
    public const ERRNO_TIMEOUT = 4;
    public const ERRNO_ERROR_IN_REPLY = 8;
    public const ERRNO_OID_NOT_INCREASING = 16;
    public const ERRNO_OID_PARSING_ERROR = 32;
    public const ERRNO_MULTIPLE_SET_QUERIES = 64;

    /**
     * Creates SNMP instance representing session to remote SNMP agent
     * @link https://php.net/manual/en/snmp.construct.php
     * @param int $version <p>SNMP protocol version:
     * <b>SNMP::VERSION_1</b>,
     * <b>SNMP::VERSION_2C</b>,
     * <b>SNMP::VERSION_3</b>.</p>
     * @param string $hostname The SNMP agent. <i>hostname</i> may be suffixed with
     * optional <acronym title="Simple Network Management Protocol">SNMP</acronym> agent port after colon. IPv6 addresses must be enclosed in square
     * brackets if used with port. If FQDN is used for <i>hostname</i>
     * it will be resolved by php-snmp library, not by Net-SNMP engine. Usage
     * of IPv6 addresses when specifying FQDN may be forced by enclosing FQDN
     * into square brackets. Here it is some examples:
     * <table>
     * <tbody>
     * <tr><td>IPv4 with default port</td><td>127.0.0.1</td></tr>
     * <tr><td>IPv6 with default port</td><td>::1 or [::1]</td></tr>
     * <tr><td>IPv4 with specific port</td><td>127.0.0.1:1161</td></tr>
     * <tr><td>IPv6 with specific port</td><td>[::1]:1161</td></tr>
     * <tr><td>FQDN with default port</td><td>host.domain</td></tr>
     * <tr><td>FQDN with specific port</td><td>host.domain:1161</td></tr>
     * <tr><td>FQDN with default port, force usage of IPv6 address</td><td>[host.domain]</td></tr>
     * <tr><td>FQDN with specific port, force usage of IPv6 address</td><td>[host.domain]:1161</td></tr>
     * </tbody>
     * </table>
     * @param string $community <p>The purpuse of <i>community</i> is
     * <acronym title="Simple Network Management Protocol">SNMP</acronym> version specific:</p>
     * <table>
     * <tbody>
     * <tr><td>SNMP::VERSION_1</td><td><acronym title="Simple Network Management Protocol">SNMP</acronym> community</td></tr>
     * <tr><td>SNMP::VERSION_2C</td><td><acronym title="Simple Network Management Protocol">SNMP</acronym> community</td></tr>
     * <tr><td>SNMP::VERSION_3</td><td><acronym title="Simple Network Management Protocol">SNMP</acronym>v3 securityName</td></tr>
     * </tbody>
     * </table>
     * @param int $timeout [optional] The number of microseconds until the first timeout.
     * @param int $retries [optional] The number of retries in case timeout occurs.
     * @since 5.4
     */
    public function __construct($version, $hostname, $community, $timeout = 1000000, $retries = 5) {}

    /**
     * Close SNMP session
     * @link https://php.net/manual/en/snmp.close.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.4
     */
    public function close() {}

    /**
     * Configures security-related SNMPv3 session parameters
     * @link https://php.net/manual/en/snmp.setsecurity.php
     * @param string $sec_level the security level (noAuthNoPriv|authNoPriv|authPriv)
     * @param string $auth_protocol [optional] the authentication protocol (MD5 or SHA)
     * @param string $auth_passphrase [optional] the authentication pass phrase
     * @param string $priv_protocol [optional] the privacy protocol (DES or AES)
     * @param string $priv_passphrase [optional] the privacy pass phrase
     * @param string $contextName [optional] the context name
     * @param string $contextEngineID [optional] the context EngineID
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.4
     */
    public function setSecurity($sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase, $contextName, $contextEngineID) {}

    /**
     * Fetch an SNMP object
     * @link https://php.net/manual/en/snmp.get.php
     * @param mixed $object_id The SNMP object (OID) or objects
     * @param bool $preserve_keys [optional] When object_id is a array and preserve_keys set to <b>TRUE</b> keys in results will be taken exactly as in object_id, otherwise SNMP::oid_output_format property is used to determinate the form of keys.
     * @return mixed SNMP objects requested as string or array
     * depending on <i>object_id</i> type or <b>FALSE</b> on error.
     * @since 5.4
     */
    public function get($object_id, $preserve_keys = false) {}

    /**
     * Fetch an SNMP object which
     * follows the given object id
     * @link https://php.net/manual/en/snmp.getnext.php
     * @param mixed $object_id <p>
     * The <acronym title="Simple Network Management Protocol">SNMP</acronym> object (OID) or objects
     * </p>
     * @return mixed SNMP objects requested as string or array
     * depending on <i>object_id</i> type or <b>FALSE</b> on error.
     * @since 5.4
     */
    public function getnext($object_id) {}

    /**
     * Fetch SNMP object subtree
     * @link https://php.net/manual/en/snmp.walk.php
     * @param string $object_id <p>Root of subtree to be fetched</p>
     * @param bool $suffix_as_keys [optional] <p>By default full OID notation is used for keys in output array. If set to <b>TRUE</b> subtree prefix will be removed from keys leaving only suffix of object_id.</p>
     * @param int $max_repetitions [optional] <p>This specifies the maximum number of iterations over the repeating variables. The default is to use this value from SNMP object.</p>
     * @param int $non_repeaters [optional] <p>This specifies the number of supplied variables that should not be iterated over. The default is to use this value from SNMP object.</p>
     * @return array|false associative array of the SNMP object ids and their values on success or <b>FALSE</b> on error.
     * When a SNMP error occures <b>SNMP::getErrno</b> and
     * <b>SNMP::getError</b> can be used for retrieving error
     * number (specific to SNMP extension, see class constants) and error message
     * respectively.
     * @since 5.4
     */
    public function walk($object_id, $suffix_as_keys = false, $max_repetitions, $non_repeaters) {}

    /**
     * Set the value of an SNMP object
     * @link https://php.net/manual/en/snmp.set.php
     * @param string $object_id <p>The SNMP object id</p>
     * @since 5.4
     *
     * <p>When count of OIDs in object_id array is greater than
     * max_oids object property set method will have to use multiple queries
     * to perform requested value updates. In this case type and value checks
     * are made per-chunk so second or subsequent requests may fail due to
     * wrong type or value for OID requested. To mark this a warning is
     * raised when count of OIDs in object_id array is greater than max_oids.
     * When count of OIDs in object_id array is greater than max_oids object property set method will have to use multiple queries to perform requested value updates. In this case type and value checks are made per-chunk so second or subsequent requests may fail due to wrong type or value for OID requested. To mark this a warning is raised when count of OIDs in object_id array is greater than max_oids.</p>
     * @param mixed $type <p>The MIB defines the type of each object id. It has to be specified as a single character from the below list.</p>
     * <b>types:</b>
     * <table>
     * <tbody>
     * <tr><td>=</td><td>The type is taken from the MIB</td></tr>
     * <tr><td>i</td><td>INTEGER</td> </tr>
     * <tr><td>u</td><td>INTEGER</td></tr>
     * <tr><td>s</td><td>STRING</td></tr>
     * <tr><td>x</td><td>HEX STRING</td></tr>
     * <tr><td>d</td><td>DECIMAL STRING</td></tr>
     * <tr><td>n</td><td>NULLOBJ</td></tr>
     * <tr><td>o</td><td>OBJID</td></tr>
     * <tr><td>t</td><td>TIMETICKS</td></tr>
     * <tr><td>a</td><td>IPADDRESS</td></tr>
     * <tr><td>b</td><td>BITS</td></tr>
     * </tbody>
     * </table>
     * <p>
     * If <b>OPAQUE_SPECIAL_TYPES</b> was defined while compiling the SNMP library, the following are also valid:
     * </p>
     * <b>types:</b>
     * <table>
     * <tbody>
     * <tr><td>U</td><td>unsigned int64</td></tr>
     * <tr><td>I</td><td>signed int64</td></tr>
     * <tr><td>F</td><td>float</td></tr>
     * <tr><td>D</td><td>double</td></tr>
     * </tbody>
     * </table>
     * <p>
     * Most of these will use the obvious corresponding ASN.1 type.  's', 'x', 'd' and 'b' are all different ways of specifying an OCTET STRING value, and
     * the 'u' unsigned type is also used for handling Gauge32 values.
     * </p>
     *
     * <p>
     * If the MIB-Files are loaded by into the MIB Tree with "snmp_read_mib" or by specifying it in the libsnmp config, '=' may be used as
     * the <i>type</i> parameter for all object ids as the type can then be automatically read from the MIB.
     * </p>
     *
     * <p>
     * Note that there are two ways to set a variable of the type BITS like e.g.
     * "SYNTAX    BITS {telnet(0), ftp(1), http(2), icmp(3), snmp(4), ssh(5), https(6)}":
     * </p>
     * <ul>
     * <li>
     * Using type "b" and a list of bit numbers. This method is not recommended since GET query for the same OID would return e.g. 0xF8.
     * </li>
     * <li>
     * Using type "x" and a hex number but without(!) the usual "0x" prefix.
     * </li>
     * </ul>
     * <p>
     * See examples section for more details.
     * </p>
     * @param mixed $value <p>
     * The new value.</p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function set($object_id, $type, $value) {}

    /**
     * Get last error code
     * @link https://php.net/manual/en/snmp.geterrno.php
     * @return int one of SNMP error code values described in constants chapter.
     * @since 5.4
     */
    public function getErrno() {}

    /**
     * Get last error message
     * @link https://php.net/manual/en/snmp.geterror.php
     * @return string String describing error from last SNMP request.
     * @since 5.4
     */
    public function getError() {}
}

/**
 * Represents an error raised by SNMP. You should not throw a
 * <b>SNMPException</b> from your own code.
 * See Exceptions for more
 * information about Exceptions in PHP.
 * @link https://php.net/manual/en/class.snmpexception.php
 */
class SNMPException extends RuntimeException
{
    /**
     * @var string Textual error message. Exception::getMessage() to access it.
     */
    protected $message;

    /**
     * @var string SNMP library error code. Use Exception::getCode() to access it.
     */
    protected $code;
}

/**
 * Fetch an SNMP object
 * @link https://php.net/manual/en/function.snmpget.php
 * @param string $hostname <p>
 * The SNMP agent.
 * </p>
 * @param string $community <p>
 * The read community.
 * </p>
 * @param string $object_id <p>
 * The SNMP object.
 * </p>
 * @param int $timeout [optional] <p>
 * The number of microseconds until the first timeout.
 * </p>
 * @param int $retries [optional] <p>
 * The number of times to retry if timeouts occur.
 * </p>
 * @return string|false SNMP object value on success or <b>FALSE</b> on error.
 */
function snmpget($hostname, $community, $object_id, $timeout = 1000000, $retries = 5) {}

/**
 * Fetch the SNMP object which follows the given object id
 * @link https://php.net/manual/en/function.snmpgetnext.php
 * @param string $host <p>The hostname of the SNMP agent (server).</p>
 * @param string $community <p>The read community.</p>
 * @param string $object_id <p>The SNMP object id which precedes the wanted one.</p>
 * @param int $timeout [optional] <p>The number of microseconds until the first timeout.</p>
 * @param int $retries [optional] <p>The number of times to retry if timeouts occur.</p>
 * @return string|false SNMP object value on success or <b>FALSE</b> on error.
 * In case of an error, an E_WARNING message is shown.
 */
function snmpgetnext($host, $community, $object_id, $timeout = 1000000, $retries = 5) {}

/**
 * Fetch all the SNMP objects from an agent
 * @link https://php.net/manual/en/function.snmpwalk.php
 * @param string $hostname <p>
 * The SNMP agent (server).
 * </p>
 * @param string $community <p>
 * The read community.
 * </p>
 * @param string $object_id <p>
 * If <b>NULL</b>, <i>object_id</i> is taken as the root of
 * the SNMP objects tree and all objects under that tree are returned as
 * an array.
 * </p>
 * <p>
 * If <i>object_id</i> is specified, all the SNMP objects
 * below that <i>object_id</i> are returned.
 * </p>
 * @param int $timeout [optional] <p>
 * The number of microseconds until the first timeout.
 * </p>
 * @param int $retries [optional] <p>The number of times to retry if timeouts occur.</p>
 * @return array an array of SNMP object values starting from the
 * <i>object_id</i> as root or <b>FALSE</b> on error.
 */
function snmpwalk($hostname, $community, $object_id, $timeout = 1000000, $retries = 5) {}

/**
 * Return all objects including their respective object ID within the specified one
 * @link https://php.net/manual/en/function.snmprealwalk.php
 * @param string $host <p>The hostname of the SNMP agent (server).</p>
 * @param string $community <p>The read community.</p>
 * @param string $object_id <p>The SNMP object id which precedes the wanted one.</p>
 * @param int $timeout [optional] <p>The number of microseconds until the first timeout.</p>
 * @param int $retries [optional] <p>The number of times to retry if timeouts occur.</p>
 * @return array|false an associative array of the SNMP object ids and their values on success or <b>FALSE</b> on error.
 * In case of an error, an E_WARNING message is shown.
 */
function snmprealwalk($host, $community, $object_id, $timeout = 1000000, $retries = 5) {}

/**
 * Query for a tree of information about a network entity
 * @link https://php.net/manual/en/function.snmpwalkoid.php
 * @param string $hostname <p>
 * The SNMP agent.
 * </p>
 * @param string $community <p>
 * The read community.
 * </p>
 * @param string $object_id <p>
 * If <b>NULL</b>, <i>object_id</i> is taken as the root of
 * the SNMP objects tree and all objects under that tree are returned as
 * an array.
 * </p>
 * <p>
 * If <i>object_id</i> is specified, all the SNMP objects
 * below that <i>object_id</i> are returned.
 * </p>
 * @param int $timeout [optional] <p>
 * The number of microseconds until the first timeout.
 * </p>
 * @param int $retries [optional] <p>
 * The number of times to retry if timeouts occur.
 * </p>
 * @return array an associative array with object ids and their respective
 * object value starting from the <i>object_id</i>
 * as root or <b>FALSE</b> on error.
 */
function snmpwalkoid($hostname, $community, $object_id, $timeout = 1000000, $retries = 5) {}

/**
 * Set the value of an SNMP object
 * @link https://php.net/manual/en/function.snmpset.php
 * @param string $host <p>
 * The hostname of the SNMP agent (server).
 * </p>
 * @param string $community <p>
 * The write community.
 * </p>
 * @param string $object_id <p>
 * The SNMP object id.
 * </p>
 * @param string $type The MIB defines the type of each object id. It has to be specified as a single character from the below list.
 * </p>
 * types
 * <tr valign="top"><td>=</td><td>The type is taken from the MIB</td></tr>
 * <tr valign="top"><td>i</td><td>INTEGER</td> </tr>
 * <tr valign="top"><td>u</td><td>INTEGER</td></tr>
 * <tr valign="top"><td>s</td><td>STRING</td></tr>
 * <tr valign="top"><td>x</td><td>HEX STRING</td></tr>
 * <tr valign="top"><td>d</td><td>DECIMAL STRING</td></tr>
 * <tr valign="top"><td>n</td><td>NULLOBJ</td></tr>
 * <tr valign="top"><td>o</td><td>OBJID</td></tr>
 * <tr valign="top"><td>t</td><td>TIMETICKS</td></tr>
 * <tr valign="top"><td>a</td><td>IPADDRESS</td></tr>
 * <tr valign="top"><td>b</td><td>BITS</td></tr>
 * </table>
 * If <b>OPAQUE_SPECIAL_TYPES</b> was defined while compiling the SNMP library, the following are also valid:
 * </p>
 * types
 * <tr valign="top"><td>U</td><td>unsigned int64</td></tr>
 * <tr valign="top"><td>I</td><td>signed int64</td></tr>
 * <tr valign="top"><td>F</td><td>float</td></tr>
 * <tr valign="top"><td>D</td><td>double</td></tr>
 * </table>
 * Most of these will use the obvious corresponding ASN.1 type. &#x00027;s&#x00027;, &#x00027;x&#x00027;, &#x00027;d&#x00027; and &#x00027;b&#x00027; are all different ways of specifying an OCTET STRING value, and
 * the &#x00027;u&#x00027; unsigned type is also used for handling Gauge32 values.
 * </p>
 * If the MIB-Files are loaded by into the MIB Tree with "snmp_read_mib" or by specifying it in the libsnmp config, &#x00027;=&#x00027; may be used as
 * the <i>type</i> parameter for all object ids as the type can then be automatically read from the MIB.
 * </p>
 * Note that there are two ways to set a variable of the type BITS like e.g.
 * "SYNTAX BITS {telnet(0), ftp(1), http(2), icmp(3), snmp(4), ssh(5), https(6)}":
 * </p>
 * Using type "b" and a list of bit numbers. This method is not recommended since GET query for the same OID would return e.g. 0xF8.
 * Using type "x" and a hex number but without(!) the usual "0x" prefix.
 * See examples section for more details.
 * </p>
 * @param mixed $value <p>
 * The new value.
 * </p>
 * @param int $timeout [optional] <p>
 * The number of microseconds until the first timeout.
 * </p>
 * @param int $retries [optional] <p>
 * The number of times to retry if timeouts occur.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * <p>
 * If the SNMP host rejects the data type, an E_WARNING message like "Warning: Error in packet. Reason: (badValue) The value given has the wrong type or length." is shown.
 * If an unknown or invalid OID is specified the warning probably reads "Could not add variable".
 * </p>
 */
function snmpset($host, $community, $object_id, $type, $value, $timeout = 1000000, $retries = 5) {}

/**
 * Fetches the current value of the UCD library's quick_print setting
 * @link https://php.net/manual/en/function.snmp-get-quick-print.php
 * @return bool <b>TRUE</b> if quick_print is on, <b>FALSE</b> otherwise.
 */
function snmp_get_quick_print() {}

/**
 * Set the value of <i>quick_print</i> within the UCD SNMP library
 * @link https://php.net/manual/en/function.snmp-set-quick-print.php
 * @param bool $quick_print
 * @return bool No value is returned.
 */
function snmp_set_quick_print($quick_print) {}

/**
 * Return all values that are enums with their enum value instead of the raw integer
 * @link https://php.net/manual/en/function.snmp-set-enum-print.php
 * @param int $enum_print <p>
 * As the value is interpreted as boolean by the Net-SNMP library, it can only be "0" or "1".
 * </p>
 * @return bool
 */
function snmp_set_enum_print($enum_print) {}

/**
 * Set the OID output format
 * @link https://php.net/manual/en/function.snmp-set-oid-output-format.php
 * @param int $oid_format [optional] <table>
 * OID .1.3.6.1.2.1.1.3.0 representation for various <i>oid_format</i> values
 * <tr valign="top"><td><b>SNMP_OID_OUTPUT_FULL</b></td><td>.iso.org.dod.internet.mgmt.mib-2.system.sysUpTime.sysUpTimeInstance</td></tr>
 * <tr valign="top"><td><b>SNMP_OID_OUTPUT_NUMERIC</b></td><td>.1.3.6.1.2.1.1.3.0</td> </tr>
 * </table>
 * <p>Begining from PHP 5.4.0 four additional constants available:
 * <table>
 * <tr valign="top"><td><b>SNMP_OID_OUTPUT_MODULE</b></td><td>DISMAN-EVENT-MIB::sysUpTimeInstance</td></tr>
 * <tr valign="top"><td><b>SNMP_OID_OUTPUT_SUFFIX</b></td><td>sysUpTimeInstance</td></tr>
 * <tr valign="top"><td><b>SNMP_OID_OUTPUT_UCD</b></td><td>system.sysUpTime.sysUpTimeInstance</td></tr>
 * <tr valign="top"><td><b>SNMP_OID_OUTPUT_NONE</b></td><td>Undefined</td></tr>
 * </table>
 * </p>
 * @return bool No value is returned.
 */
function snmp_set_oid_output_format($oid_format = SNMP_OID_OUTPUT_MODULE) {}

/**
 * Set the oid output format
 * @link https://php.net/manual/en/function.snmp-set-oid-numeric-print.php
 * @param int $oid_format
 * @return void
 */
function snmp_set_oid_numeric_print($oid_format) {}

/**
 * Fetch an SNMP object
 * @link https://php.net/manual/en/function.snmp2-get.php
 * @param string $host <p>
 * The SNMP agent.
 * </p>
 * @param string $community <p>
 * The read community.
 * </p>
 * @param string $object_id <p>
 * The SNMP object.
 * </p>
 * @param int $timeout [optional] <p>
 * The number of microseconds until the first timeout.
 * </p>
 * @param int $retries [optional] <p>
 * The number of times to retry if timeouts occur.
 * </p>
 * @return string|false SNMP object value on success or <b>FALSE</b> on error.
 */
function snmp2_get($host, $community, $object_id, $timeout = 1000000, $retries = 5) {}

/**
 * Fetch the SNMP object which follows the given object id
 * @link https://php.net/manual/en/function.snmp2-getnext.php
 * @param string $host <p>
 * The hostname of the SNMP agent (server).
 * </p>
 * @param string $community <p>
 * The read community.
 * </p>
 * @param string $object_id <p>
 * The SNMP object id which precedes the wanted one.
 * </p>
 * @param int $timeout [optional] <p>
 * The number of microseconds until the first timeout.
 * </p>
 * @param int $retries [optional] <p>
 * The number of times to retry if timeouts occur.
 * </p>
 * @return string|false SNMP object value on success or <b>FALSE</b> on error.
 * In case of an error, an E_WARNING message is shown.
 */
function snmp2_getnext($host, $community, $object_id, $timeout = 1000000, $retries = 5) {}

/**
 * Fetch all the SNMP objects from an agent
 * @link https://php.net/manual/en/function.snmp2-walk.php
 * @param string $host <p>
 * The SNMP agent (server).
 * </p>
 * @param string $community <p>
 * The read community.
 * </p>
 * @param string $object_id <p>
 * If <b>NULL</b>, <i>object_id</i> is taken as the root of
 * the SNMP objects tree and all objects under that tree are returned as
 * an array.
 * </p>
 * <p>
 * If <i>object_id</i> is specified, all the SNMP objects
 * below that <i>object_id</i> are returned.
 * </p>
 * @param int $timeout [optional] <p>
 * The number of microseconds until the first timeout.
 * </p>
 * @param int $retries [optional] <p>
 * The number of times to retry if timeouts occur.
 * </p>
 * @return array an array of SNMP object values starting from the
 * <i>object_id</i> as root or <b>FALSE</b> on error.
 */
function snmp2_walk($host, $community, $object_id, $timeout = 1000000, $retries = 5) {}

/**
 * Return all objects including their respective object ID within the specified one
 * @link https://php.net/manual/en/function.snmp2-real-walk.php
 * @param string $host <p>
 * The hostname of the SNMP agent (server).
 * </p>
 * @param string $community <p>
 * The read community.
 * </p>
 * @param string $object_id <p>
 * The SNMP object id which precedes the wanted one.
 * </p>
 * @param int $timeout [optional] <p>
 * The number of microseconds until the first timeout.
 * </p>
 * @param int $retries [optional] <p>
 * The number of times to retry if timeouts occur.
 * </p>
 * @return array|false an associative array of the SNMP object ids and their values on success or <b>FALSE</b> on error.
 * In case of an error, an E_WARNING message is shown.
 */
function snmp2_real_walk($host, $community, $object_id, $timeout = 1000000, $retries = 5) {}

/**
 * Set the value of an SNMP object
 * @link https://php.net/manual/en/function.snmp2-set.php
 * @param string $host <p>
 * The hostname of the SNMP agent (server).
 * </p>
 * @param string $community <p>
 * The write community.
 * </p>
 * @param string $object_id <p>
 * The SNMP object id.
 * </p>
 * @param string $type <p>The MIB defines the type of each object id. It has to be specified as a single character from the below list.
 * </p>
 * <p>types:</p>
 * <table>
 * <tr valign="top"><td>=</td><td>The type is taken from the MIB</td></tr>
 * <tr valign="top"><td>i</td><td>INTEGER</td> </tr>
 * <tr valign="top"><td>u</td><td>INTEGER</td></tr>
 * <tr valign="top"><td>s</td><td>STRING</td></tr>
 * <tr valign="top"><td>x</td><td>HEX STRING</td></tr>
 * <tr valign="top"><td>d</td><td>DECIMAL STRING</td></tr>
 * <tr valign="top"><td>n</td><td>NULLOBJ</td></tr>
 * <tr valign="top"><td>o</td><td>OBJID</td></tr>
 * <tr valign="top"><td>t</td><td>TIMETICKS</td></tr>
 * <tr valign="top"><td>a</td><td>IPADDRESS</td></tr>
 * <tr valign="top"><td>b</td><td>BITS</td></tr>
 * </table>
 * <p>If <b>OPAQUE_SPECIAL_TYPES</b> was defined while compiling the SNMP library, the following are also valid:
 * </p>
 * <p>types:</p>
 * <table>
 * <tr valign="top"><td>U</td><td>unsigned int64</td></tr>
 * <tr valign="top"><td>I</td><td>signed int64</td></tr>
 * <tr valign="top"><td>F</td><td>float</td></tr>
 * <tr valign="top"><td>D</td><td>double</td></tr>
 * </table>
 * <p>Most of these will use the obvious corresponding ASN.1 type. &#x00027;s&#x00027;, &#x00027;x&#x00027;, &#x00027;d&#x00027; and &#x00027;b&#x00027; are all different ways of specifying an OCTET STRING value, and
 * the &#x00027;u&#x00027; unsigned type is also used for handling Gauge32 values.
 * </p><p>
 * If the MIB-Files are loaded by into the MIB Tree with "snmp_read_mib" or by specifying it in the libsnmp config, &#x00027;=&#x00027; may be used as
 * the <i>type</i> parameter for all object ids as the type can then be automatically read from the MIB.
 * </p>
 * Note that there are two ways to set a variable of the type BITS like e.g.
 * "SYNTAX BITS {telnet(0), ftp(1), http(2), icmp(3), snmp(4), ssh(5), https(6)}":
 * </p>
 * Using type "b" and a list of bit numbers. This method is not recommended since GET query for the same OID would return e.g. 0xF8.
 * Using type "x" and a hex number but without(!) the usual "0x" prefix.
 * See examples section for more details.
 * </p>
 * @param string $value <p>
 * The new value.
 * </p>
 * @param int $timeout [optional] <p>
 * The number of microseconds until the first timeout.
 * </p>
 * @param int $retries [optional] <p>
 * The number of times to retry if timeouts occur.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * <p>
 * If the SNMP host rejects the data type, an E_WARNING message like "Warning: Error in packet. Reason: (badValue) The value given has the wrong type or length." is shown.
 * If an unknown or invalid OID is specified the warning probably reads "Could not add variable".
 * </p>
 */
function snmp2_set($host, $community, $object_id, $type, $value, $timeout = 1000000, $retries = 5) {}

/**
 * Fetch an SNMP object
 * @link https://php.net/manual/en/function.snmp3-get.php
 * @param string $host <p>
 * The hostname of the SNMP agent (server).
 * </p>
 * @param string $sec_name <p>
 * the security name, usually some kind of username
 * </p>
 * @param string $sec_level <p>
 * the security level (noAuthNoPriv|authNoPriv|authPriv)
 * </p>
 * @param string $auth_protocol <p>
 * the authentication protocol (MD5 or SHA)
 * </p>
 * @param string $auth_passphrase <p>
 * the authentication pass phrase
 * </p>
 * @param string $priv_protocol <p>
 * the privacy protocol (DES or AES)
 * </p>
 * @param string $priv_passphrase <p>
 * the privacy pass phrase
 * </p>
 * @param string $object_id <p>
 * The SNMP object id.
 * </p>
 * @param int $timeout [optional] <p>
 * The number of microseconds until the first timeout.
 * </p>
 * @param int $retries [optional] <p>
 * The number of times to retry if timeouts occur.
 * </p>
 * @return string|false SNMP object value on success or <b>FALSE</b> on error.
 */
function snmp3_get($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase, $object_id, $timeout = 1000000, $retries = 5) {}

/**
 * Fetch the SNMP object which follows the given object id
 * @link https://php.net/manual/en/function.snmp3-getnext.php
 * @param string $host <p>
 * The hostname of the
 * SNMP agent (server).
 * </p>
 * @param string $sec_name <p>
 * the security name, usually some kind of username
 * </p>
 * @param string $sec_level <p>
 * the security level (noAuthNoPriv|authNoPriv|authPriv)
 * </p>
 * @param string $auth_protocol <p>
 * the authentication protocol (MD5 or SHA)
 * </p>
 * @param string $auth_passphrase <p>
 * the authentication pass phrase
 * </p>
 * @param string $priv_protocol <p>
 * the privacy protocol (DES or AES)
 * </p>
 * @param string $priv_passphrase <p>
 * the privacy pass phrase
 * </p>
 * @param string $object_id <p>
 * The SNMP object id.
 * </p>
 * @param int $timeout [optional] <p>
 * The number of microseconds until the first timeout.
 * </p>
 * @param int $retries [optional] <p>
 * The number of times to retry if timeouts occur.
 * </p>
 * @return string|false SNMP object value on success or <b>FALSE</b> on error.
 * In case of an error, an E_WARNING message is shown.
 */
function snmp3_getnext($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase, $object_id, $timeout = 1000000, $retries = 5) {}

/**
 * Fetch all the SNMP objects from an agent
 * @link https://php.net/manual/en/function.snmp3-walk.php
 * @param string $host <p>
 * The hostname of the SNMP agent (server).
 * </p>
 * @param string $sec_name <p>
 * the security name, usually some kind of username
 * </p>
 * @param string $sec_level <p>
 * the security level (noAuthNoPriv|authNoPriv|authPriv)
 * </p>
 * @param string $auth_protocol <p>
 * the authentication protocol (MD5 or SHA)
 * </p>
 * @param string $auth_passphrase <p>
 * the authentication pass phrase
 * </p>
 * @param string $priv_protocol <p>
 * the privacy protocol (DES or AES)
 * </p>
 * @param string $priv_passphrase <p>
 * the privacy pass phrase
 * </p>
 * @param string $object_id <p>
 * If <b>NULL</b>, <i>object_id</i> is taken as the root of
 * the SNMP objects tree and all objects under that tree are returned as
 * an array.
 * </p>
 * <p>
 * If <i>object_id</i> is specified, all the SNMP objects
 * below that <i>object_id</i> are returned.
 * </p>
 * @param int $timeout [optional] <p>
 * The number of microseconds until the first timeout.
 * </p>
 * @param int $retries [optional] <p>
 * The number of times to retry if timeouts occur.
 * </p>
 * @return array an array of SNMP object values starting from the
 * <i>object_id</i> as root or <b>FALSE</b> on error.
 */
function snmp3_walk($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase, $object_id, $timeout = 1000000, $retries = 5) {}

/**
 * Return all objects including their respective object ID within the specified one
 * @link https://php.net/manual/en/function.snmp3-real-walk.php
 * @param string $host <p>
 * The hostname of the
 * SNMP agent (server).
 * </p>
 * @param string $sec_name <p>
 * the security name, usually some kind of username
 * </p>
 * @param string $sec_level <p>
 * the security level (noAuthNoPriv|authNoPriv|authPriv)
 * </p>
 * @param string $auth_protocol <p>
 * the authentication protocol (MD5 or SHA)
 * </p>
 * @param string $auth_passphrase <p>
 * the authentication pass phrase
 * </p>
 * @param string $priv_protocol <p>
 * the privacy protocol (DES or AES)
 * </p>
 * @param string $priv_passphrase <p>
 * the privacy pass phrase
 * </p>
 * @param string $object_id <p>
 * The SNMP object id.
 * </p>
 * @param int $timeout [optional] <p>
 * The number of microseconds until the first timeout.
 * </p>
 * @param int $retries [optional] <p>
 * The number of times to retry if timeouts occur.
 * </p>
 * @return array an associative array of the
 * SNMP object ids and their values on success or <b>FALSE</b> on error.
 * In case of an error, an E_WARNING message is shown.
 */
function snmp3_real_walk($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase, $object_id, $timeout = null, $retries = null) {}

/**
 * Set the value of an SNMP object
 * @link https://php.net/manual/en/function.snmp3-set.php
 * @param string $host <p>
 * The hostname of the SNMP agent (server).
 * </p>
 * @param string $sec_name <p>
 * the security name, usually some kind of username
 * </p>
 * @param string $sec_level <p>
 * the security level (noAuthNoPriv|authNoPriv|authPriv)
 * </p>
 * @param string $auth_protocol <p>
 * the authentication protocol (MD5 or SHA)
 * </p>
 * @param string $auth_passphrase <p>
 * the authentication pass phrase
 * </p>
 * @param string $priv_protocol <p>
 * the privacy protocol (DES or AES)
 * </p>
 * @param string $priv_passphrase <p>
 * the privacy pass phrase
 * </p>
 * @param string $object_id <p>
 * The SNMP object id.
 * </p>
 * @param string $type <p>The MIB defines the type of each object id. It has to be specified as a single character from the below list.</p>
 * <p>types:</p>
 * <table>
 * <tr valign="top"><td>=</td><td>The type is taken from the MIB</td></tr>
 * <tr valign="top"><td>i</td><td>INTEGER</td></tr>
 * <tr valign="top"><td>u</td><td>INTEGER</td></tr>
 * <tr valign="top"><td>s</td><td>STRING</td></tr>
 * <tr valign="top"><td>x</td><td>HEX STRING</td></tr>
 * <tr valign="top"><td>d</td><td>DECIMAL STRING</td></tr>
 * <tr valign="top"><td>n</td><td>NULLOBJ</td></tr>
 * <tr valign="top"><td>o</td><td>OBJID</td></tr>
 * <tr valign="top"><td>t</td><td>TIMETICKS</td></tr>
 * <tr valign="top"><td>a</td><td>IPADDRESS</td></tr>
 * <tr valign="top"><td>b</td><td>BITS</td></tr>
 * </table>
 * <p>If <b>OPAQUE_SPECIAL_TYPES</b> was defined while compiling the SNMP library, the following are also valid:
 * </p>
 * <p>types:</p>
 * <table>
 * <tr valign="top"><td>U</td><td>unsigned int64</td></tr>
 * <tr valign="top"><td>I</td><td>signed int64</td></tr>
 * <tr valign="top"><td>F</td><td>float</td></tr>
 * <tr valign="top"><td>D</td><td>double</td></tr>
 * </table>
 * <p>Most of these will use the obvious corresponding ASN.1 type. &#x00027;s&#x00027;, &#x00027;x&#x00027;, &#x00027;d&#x00027; and &#x00027;b&#x00027; are all different ways of specifying an OCTET STRING value, and
 * the &#x00027;u&#x00027; unsigned type is also used for handling Gauge32 values.
 * </p>
 * <p>
 * If the MIB-Files are loaded by into the MIB Tree with "snmp_read_mib" or by specifying it in the libsnmp config, &#x00027;=&#x00027; may be used as
 * the <i>type</i> parameter for all object ids as the type can then be automatically read from the MIB.
 * </p>
 * <p>
 * Note that there are two ways to set a variable of the type BITS like e.g.
 * "SYNTAX BITS {telnet(0), ftp(1), http(2), icmp(3), snmp(4), ssh(5), https(6)}":
 * </p>
 * <p>
 * Using type "b" and a list of bit numbers. This method is not recommended since GET query for the same OID would return e.g. 0xF8.
 * Using type "x" and a hex number but without(!) the usual "0x" prefix.
 * See examples section for more details.
 * </p>
 * @param string $value <p>
 * The new value
 * </p>
 * @param int $timeout [optional] <p>
 * The number of microseconds until the first timeout.
 * </p>
 * @param int $retries [optional] <p>
 * The number of times to retry if timeouts occur.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * <p>
 * If the SNMP host rejects the data type, an E_WARNING message like "Warning: Error in packet. Reason: (badValue) The value given has the wrong type or length." is shown.
 * If an unknown or invalid OID is specified the warning probably reads "Could not add variable".
 * </p>
 */
function snmp3_set($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase, $object_id, $type, $value, $timeout = 1000000, $retries = 5) {}

/**
 * Specify the method how the SNMP values will be returned
 * @link https://php.net/manual/en/function.snmp-set-valueretrieval.php
 * @param int $method <table>
 * types
 * <tr valign="top">
 * <td>SNMP_VALUE_LIBRARY</td>
 * <td>The return values will be as returned by the Net-SNMP library.</td>
 * </tr>
 * <tr valign="top">
 * <td>SNMP_VALUE_PLAIN</td>
 * <td>The return values will be the plain value without the SNMP type hint.</td>
 * </tr>
 * <tr valign="top">
 * <td>SNMP_VALUE_OBJECT</td>
 * <td>
 * The return values will be objects with the properties "value" and "type", where the latter
 * is one of the SNMP_OCTET_STR, SNMP_COUNTER etc. constants. The
 * way "value" is returned is based on which one of constants
 * <b>SNMP_VALUE_LIBRARY</b>, <b>SNMP_VALUE_PLAIN</b> is set.
 * </td>
 * </tr>
 * </table>
 * @return bool
 */
function snmp_set_valueretrieval($method) {}

/**
 * Return the method how the SNMP values will be returned
 * @link https://php.net/manual/en/function.snmp-get-valueretrieval.php
 * @return int OR-ed combitantion of constants ( <b>SNMP_VALUE_LIBRARY</b> or
 * <b>SNMP_VALUE_PLAIN</b> ) with
 * possible SNMP_VALUE_OBJECT set.
 */
function snmp_get_valueretrieval() {}

/**
 * Reads and parses a MIB file into the active MIB tree
 * @link https://php.net/manual/en/function.snmp-read-mib.php
 * @param string $filename <p>The filename of the MIB.</p>
 * @return bool
 */
function snmp_read_mib($filename) {}

/**
 * As of 5.4
 * @link https://php.net/manual/en/snmp.constants.php
 */
define('SNMP_OID_OUTPUT_SUFFIX', 1);

/**
 * As of 5.4
 * @link https://php.net/manual/en/snmp.constants.php
 */
define('SNMP_OID_OUTPUT_MODULE', 2);

/**
 * As of 5.2
 * @link https://php.net/manual/en/snmp.constants.php
 */
define('SNMP_OID_OUTPUT_FULL', 3);

/**
 * As of 5.2
 * @link https://php.net/manual/en/snmp.constants.php
 */
define('SNMP_OID_OUTPUT_NUMERIC', 4);

/**
 * As of 5.4
 * @link https://php.net/manual/en/snmp.constants.php
 */
define('SNMP_OID_OUTPUT_UCD', 5);

/**
 * As of 5.4
 * @link https://php.net/manual/en/snmp.constants.php
 */
define('SNMP_OID_OUTPUT_NONE', 6);
define('SNMP_VALUE_LIBRARY', 0);
define('SNMP_VALUE_PLAIN', 1);
define('SNMP_VALUE_OBJECT', 2);
define('SNMP_BIT_STR', 3);
define('SNMP_OCTET_STR', 4);
define('SNMP_OPAQUE', 68);
define('SNMP_NULL', 5);
define('SNMP_OBJECT_ID', 6);
define('SNMP_IPADDRESS', 64);
define('SNMP_COUNTER', 66);
define('SNMP_UNSIGNED', 66);
define('SNMP_TIMETICKS', 67);
define('SNMP_UINTEGER', 71);
define('SNMP_INTEGER', 2);
define('SNMP_COUNTER64', 70);

// End of snmp v.0.1
