<?php

// Start of soap v.
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;
use JetBrains\PhpStorm\Pure;

/**
 * The SoapClient class provides a client for SOAP 1.1, SOAP 1.2 servers. It can be used in WSDL
 * or non-WSDL mode.
 * @link https://php.net/manual/en/class.soapclient.php
 */
class SoapClient
{
    /**
     * SoapClient constructor
     * @link https://php.net/manual/en/soapclient.construct.php
     * @param string|null $wsdl <p>
     * URI of the WSDL file or <b>NULL</b> if working in
     * non-WSDL mode.
     * </p>
     * <p>
     * During development, WSDL caching may be disabled by the
     * use of the soap.wsdl_cache_ttl <i>php.ini</i> setting
     * otherwise changes made to the WSDL file will have no effect until
     * soap.wsdl_cache_ttl is expired.
     * </p>
     * @param array $options [optional] <p>
     * An array of options. If working in WSDL mode, this parameter is optional.
     * If working in non-WSDL mode, the location and
     * uri options must be set, where location
     * is the URL of the SOAP server to send the request to, and uri
     * is the target namespace of the SOAP service.
     * </p>
     * <p>
     * The style and use options only work in
     * non-WSDL mode. In WSDL mode, they come from the WSDL file.
     * </p>
     * <p>
     * The soap_version option should be one of either
     * <b>SOAP_1_1</b> or <b>SOAP_1_2</b> to
     * select SOAP 1.1 or 1.2, respectively. If omitted, 1.1 is used.
     * </p>
     * <p>
     * For HTTP authentication, the login and
     * password options can be used to supply credentials.
     * For making an HTTP connection through
     * a proxy server, the options proxy_host,
     * proxy_port, proxy_login
     * and proxy_password are also available.
     * For HTTPS client certificate authentication use
     * local_cert and passphrase options. An
     * authentication may be supplied in the authentication
     * option. The authentication method may be either
     * <b>SOAP_AUTHENTICATION_BASIC</b> (default) or
     * <b>SOAP_AUTHENTICATION_DIGEST</b>.
     * </p>
     * <p>
     * The compression option allows to use compression
     * of HTTP SOAP requests and responses.
     * </p>
     * <p>
     * The encoding option defines internal character
     * encoding. This option does not change the encoding of SOAP requests (it is
     * always utf-8), but converts strings into it.
     * </p>
     * <p>
     * The trace option enables tracing of request so faults
     * can be backtraced. This defaults to <b>FALSE</b>
     * </p>
     * <p>
     * The classmap option can be used to map some WSDL
     * types to PHP classes. This option must be an array with WSDL types
     * as keys and names of PHP classes as values.
     * </p>
     * <p>
     * Setting the boolean trace option enables use of the
     * methods
     * SoapClient->__getLastRequest,
     * SoapClient->__getLastRequestHeaders,
     * SoapClient->__getLastResponse and
     * SoapClient->__getLastResponseHeaders.
     * </p>
     * <p>
     * The exceptions option is a boolean value defining whether
     * soap errors throw exceptions of type
     * SoapFault.
     * </p>
     * <p>
     * The connection_timeout option defines a timeout in seconds
     * for the connection to the SOAP service. This option does not define a timeout
     * for services with slow responses. To limit the time to wait for calls to finish the
     * default_socket_timeout setting
     * is available.
     * </p>
     * <p>
     * The typemap option is an array of type mappings.
     * Type mapping is an array with keys type_name,
     * type_ns (namespace URI), from_xml
     * (callback accepting one string parameter) and to_xml
     * (callback accepting one object parameter).
     * </p>
     * <p>
     * The cache_wsdl option is one of
     * <b>WSDL_CACHE_NONE</b>,
     * <b>WSDL_CACHE_DISK</b>,
     * <b>WSDL_CACHE_MEMORY</b> or
     * <b>WSDL_CACHE_BOTH</b>.
     * </p>
     * <p>
     * The user_agent option specifies string to use in
     * User-Agent header.
     * </p>
     * <p>
     * The stream_context option is a resource
     * for context.
     * </p>
     * <p>
     * The features option is a bitmask of
     * <b>SOAP_SINGLE_ELEMENT_ARRAYS</b>,
     * <b>SOAP_USE_XSI_ARRAY_TYPE</b>,
     * <b>SOAP_WAIT_ONE_WAY_CALLS</b>.
     * </p>
     * <p>
     * The keep_alive option is a boolean value defining whether
     * to send the Connection: Keep-Alive header or
     * Connection: close.
     * </p>
     * <p>
     * The ssl_method option is one of
     * <b>SOAP_SSL_METHOD_TLS</b>,
     * <b>SOAP_SSL_METHOD_SSLv2</b>,
     * <b>SOAP_SSL_METHOD_SSLv3</b> or
     * <b>SOAP_SSL_METHOD_SSLv23</b>.
     * </p>
     * @since 5.0.1
     *
     * @removed 8.0
     */
    public function SoapClient($wsdl, array $options = null) {}

    /**
     * SoapClient constructor
     * @link https://php.net/manual/en/soapclient.construct.php
     * @param string|null $wsdl <p>
     * URI of the WSDL file or <b>NULL</b> if working in
     * non-WSDL mode.
     * </p>
     * <p>
     * During development, WSDL caching may be disabled by the
     * use of the soap.wsdl_cache_ttl <i>php.ini</i> setting
     * otherwise changes made to the WSDL file will have no effect until
     * soap.wsdl_cache_ttl is expired.
     * </p>
     * @param array $options [optional] <p>
     * An array of options. If working in WSDL mode, this parameter is optional.
     * If working in non-WSDL mode, the location and
     * uri options must be set, where location
     * is the URL of the SOAP server to send the request to, and uri
     * is the target namespace of the SOAP service.
     * </p>
     * <p>
     * The style and use options only work in
     * non-WSDL mode. In WSDL mode, they come from the WSDL file.
     * </p>
     * <p>
     * The soap_version option should be one of either
     * <b>SOAP_1_1</b> or <b>SOAP_1_2</b> to
     * select SOAP 1.1 or 1.2, respectively. If omitted, 1.1 is used.
     * </p>
     * <p>
     * For HTTP authentication, the login and
     * password options can be used to supply credentials.
     * For making an HTTP connection through
     * a proxy server, the options proxy_host,
     * proxy_port, proxy_login
     * and proxy_password are also available.
     * For HTTPS client certificate authentication use
     * local_cert and passphrase options. An
     * authentication may be supplied in the authentication
     * option. The authentication method may be either
     * <b>SOAP_AUTHENTICATION_BASIC</b> (default) or
     * <b>SOAP_AUTHENTICATION_DIGEST</b>.
     * </p>
     * <p>
     * The compression option allows to use compression
     * of HTTP SOAP requests and responses.
     * </p>
     * <p>
     * The encoding option defines internal character
     * encoding. This option does not change the encoding of SOAP requests (it is
     * always utf-8), but converts strings into it.
     * </p>
     * <p>
     * The trace option enables tracing of request so faults
     * can be backtraced. This defaults to <b>FALSE</b>
     * </p>
     * <p>
     * The classmap option can be used to map some WSDL
     * types to PHP classes. This option must be an array with WSDL types
     * as keys and names of PHP classes as values.
     * </p>
     * <p>
     * Setting the boolean trace option enables use of the
     * methods
     * SoapClient->__getLastRequest,
     * SoapClient->__getLastRequestHeaders,
     * SoapClient->__getLastResponse and
     * SoapClient->__getLastResponseHeaders.
     * </p>
     * <p>
     * The exceptions option is a boolean value defining whether
     * soap errors throw exceptions of type
     * SoapFault.
     * </p>
     * <p>
     * The connection_timeout option defines a timeout in seconds
     * for the connection to the SOAP service. This option does not define a timeout
     * for services with slow responses. To limit the time to wait for calls to finish the
     * default_socket_timeout setting
     * is available.
     * </p>
     * <p>
     * The typemap option is an array of type mappings.
     * Type mapping is an array with keys type_name,
     * type_ns (namespace URI), from_xml
     * (callback accepting one string parameter) and to_xml
     * (callback accepting one object parameter).
     * </p>
     * <p>
     * The cache_wsdl option is one of
     * <b>WSDL_CACHE_NONE</b>,
     * <b>WSDL_CACHE_DISK</b>,
     * <b>WSDL_CACHE_MEMORY</b> or
     * <b>WSDL_CACHE_BOTH</b>.
     * </p>
     * <p>
     * The user_agent option specifies string to use in
     * User-Agent header.
     * </p>
     * <p>
     * The stream_context option is a resource
     * for context.
     * </p>
     * <p>
     * The features option is a bitmask of
     * <b>SOAP_SINGLE_ELEMENT_ARRAYS</b>,
     * <b>SOAP_USE_XSI_ARRAY_TYPE</b>,
     * <b>SOAP_WAIT_ONE_WAY_CALLS</b>.
     * </p>
     * <p>
     * The keep_alive option is a boolean value defining whether
     * to send the Connection: Keep-Alive header or
     * Connection: close.
     * </p>
     * <p>
     * The ssl_method option is one of
     * <b>SOAP_SSL_METHOD_TLS</b>,
     * <b>SOAP_SSL_METHOD_SSLv2</b>,
     * <b>SOAP_SSL_METHOD_SSLv3</b> or
     * <b>SOAP_SSL_METHOD_SSLv23</b>.
     * </p>
     * @throws SoapFault A SoapFault exception will be thrown if the wsdl URI cannot be loaded.
     * @since 5.0.1
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $wsdl,
        array $options = null
    ) {}

    /**
     * @link https://php.net/manual/en/soapclient.call.php
     * @param string $name
     * @param array $args
     * @return mixed
     * @since 5.0.1
     */
    #[Deprecated]
    #[TentativeType]
    public function __call(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name,
        array $args
    ): mixed {}

    /**
     * Calls a SOAP function
     * @link https://php.net/manual/en/soapclient.soapcall.php
     * @param string $name <p>
     * The name of the SOAP function to call.
     * </p>
     * @param array $args <p>
     * An array of the arguments to pass to the function. This can be either
     * an ordered or an associative array. Note that most SOAP servers require
     * parameter names to be provided, in which case this must be an
     * associative array.
     * </p>
     * @param array $options [optional] <p>
     * An associative array of options to pass to the client.
     * </p>
     * <p>
     * The location option is the URL of the remote Web service.
     * </p>
     * <p>
     * The uri option is the target namespace of the SOAP service.
     * </p>
     * <p>
     * The soapaction option is the action to call.
     * </p>
     * @param mixed $inputHeaders [optional] <p>
     * An array of headers to be sent along with the SOAP request.
     * </p>
     * @param array &$outputHeaders [optional] <p>
     * If supplied, this array will be filled with the headers from the SOAP response.
     * </p>
     * @return mixed SOAP functions may return one, or multiple values. If only one value is returned
     * by the SOAP function, the return value of __soapCall will be
     * a simple value (e.g. an integer, a string, etc). If multiple values are
     * returned, __soapCall will return
     * an associative array of named output parameters.
     * </p>
     * <p>
     * On error, if the SoapClient object was constructed with the exceptions
     * option set to <b>FALSE</b>, a SoapFault object will be returned.
     * @since 5.0.1
     */
    #[TentativeType]
    public function __soapCall(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name,
        array $args,
        #[LanguageLevelTypeAware(['8.0' => 'array|null'], default: '')] $options = null,
        $inputHeaders = null,
        &$outputHeaders = null
    ): mixed {}

    /**
     * Returns last SOAP request
     * @link https://php.net/manual/en/soapclient.getlastrequest.php
     * @return string|null The last SOAP request, as an XML string.
     * @since 5.0.1
     */
    #[TentativeType]
    public function __getLastRequest(): ?string {}

    /**
     * Returns last SOAP response
     * @link https://php.net/manual/en/soapclient.getlastresponse.php
     * @return string|null The last SOAP response, as an XML string.
     * @since 5.0.1
     */
    #[TentativeType]
    public function __getLastResponse(): ?string {}

    /**
     * Returns the SOAP headers from the last request
     * @link https://php.net/manual/en/soapclient.getlastrequestheaders.php
     * @return string|null The last SOAP request headers.
     * @since 5.0.1
     */
    #[TentativeType]
    public function __getLastRequestHeaders(): ?string {}

    /**
     * Returns the SOAP headers from the last response
     * @link https://php.net/manual/en/soapclient.getlastresponseheaders.php
     * @return string|null The last SOAP response headers.
     * @since 5.0.1
     */
    #[TentativeType]
    public function __getLastResponseHeaders(): ?string {}

    /**
     * Returns list of available SOAP functions
     * @link https://php.net/manual/en/soapclient.getfunctions.php
     * @return array|null The array of SOAP function prototypes, detailing the return type,
     * the function name and type-hinted parameters.
     * @since 5.0.1
     */
    #[TentativeType]
    public function __getFunctions(): ?array {}

    /**
     * Returns a list of SOAP types
     * @link https://php.net/manual/en/soapclient.gettypes.php
     * @return array|null The array of SOAP types, detailing all structures and types.
     * @since 5.0.1
     */
    #[TentativeType]
    public function __getTypes(): ?array {}

    /**
     * Returns a list of all cookies
     * @link https://php.net/manual/en/soapclient.getcookies.php
     * @return array The array of all cookies
     * @since 5.4.3
     */
    #[TentativeType]
    public function __getCookies(): array {}

    /**
     * Performs a SOAP request
     * @link https://php.net/manual/en/soapclient.dorequest.php
     * @param string $request <p>
     * The XML SOAP request.
     * </p>
     * @param string $location <p>
     * The URL to request.
     * </p>
     * @param string $action <p>
     * The SOAP action.
     * </p>
     * @param int $version <p>
     * The SOAP version.
     * </p>
     * @param bool|int $oneWay [optional] <p>
     * If $oneWay is set to 1, this method returns nothing.
     * Use this where a response is not expected.
     * </p>
     * @return string|null The XML SOAP response.
     * @since 5.0.1
     */
    #[TentativeType]
    public function __doRequest(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $request,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $location,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $action,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $version,
        #[LanguageLevelTypeAware(["8.0" => 'bool'], default: 'int')] $oneWay = false
    ): ?string {}

    /**
     * The __setCookie purpose
     * @link https://php.net/manual/en/soapclient.setcookie.php
     * @param string $name <p>
     * The name of the cookie.
     * </p>
     * @param string $value [optional] <p>
     * The value of the cookie. If not specified, the cookie will be deleted.
     * </p>
     * @return void No value is returned.
     * @since 5.0.4
     */
    #[TentativeType]
    public function __setCookie(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name,
        #[LanguageLevelTypeAware(["8.0" => "string|null"], default: "string")] $value
    ): void {}

    /**
     * Sets the location of the Web service to use
     * @link https://php.net/manual/en/soapclient.setlocation.php
     * @param string $location [optional] <p>
     * The new endpoint URL.
     * </p>
     * @return string|null The old endpoint URL.
     * @since 5.0.1
     */
    #[TentativeType]
    public function __setLocation(#[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $location = ''): ?string {}

    /**
     * Sets SOAP headers for subsequent calls
     * @link https://php.net/manual/en/soapclient.setsoapheaders.php
     * @param mixed $headers <p>
     * The headers to be set. It could be <b>SoapHeader</b>
     * object or array of <b>SoapHeader</b> objects.
     * If not specified or set to <b>NULL</b>, the headers will be deleted.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.0.5
     */
    #[TentativeType]
    public function __setSoapHeaders(
        #[PhpStormStubsElementAvailable(from: '5.3', to: '5.6')] $headers,
        #[PhpStormStubsElementAvailable(from: '7.0')] $headers = null
    ): bool {}
}

/**
 * A class representing a variable or object for use with SOAP services.
 * @link https://php.net/manual/en/class.soapvar.php
 */
class SoapVar
{
    /**
     * @var int
     * @since 8.1
     */
    public int $enc_type;

    /**
     * @var mixed
     * @since 8.1
     */
    public mixed $enc_value;

    /**
     * @var string|null
     * @since 8.1
     */
    public string|null $enc_stype;

    /**
     * @var string|null
     * @since 8.1
     */
    public string|null $enc_ns;

    /**
     * @var string|null
     * @since 8.1
     */
    public string|null $enc_name;

    /**
     * @var string|null
     * @since 8.1
     */
    public string|null $enc_namens;

    /**
     * SoapVar constructor
     * @link https://php.net/manual/en/soapvar.construct.php
     * @param mixed $data <p>
     * The data to pass or return.
     * </p>
     * @param int|null $encoding <p>
     * The encoding ID, one of the XSD_... constants.
     * </p>
     * @param string $typeName [optional] <p>
     * The type name.
     * </p>
     * @param string $typeNamespace [optional] <p>
     * The type namespace.
     * </p>
     * @param string $nodeName [optional] <p>
     * The XML node name.
     * </p>
     * @param string $nodeNamespace [optional] <p>
     * The XML node namespace.
     * </p>
     * @since 5.0.1
     */
    public function __construct(
        #[LanguageLevelTypeAware(["8.0" => 'mixed'], default: '')] $data,
        #[LanguageLevelTypeAware(["7.1" => "int|null"], default: "int")] $encoding,
        #[LanguageLevelTypeAware(["8.0" => "string|null"], default: "string")] $typeName,
        #[LanguageLevelTypeAware(["8.0" => 'string|null'], default: '')] $typeNamespace = '',
        #[LanguageLevelTypeAware(["8.0" => 'string|null'], default: '')] $nodeName = '',
        #[LanguageLevelTypeAware(["8.0" => 'string|null'], default: '')] $nodeNamespace = ''
    ) {}

    /**
     * SoapVar constructor
     * @link https://php.net/manual/en/soapvar.construct.php
     * @param mixed $data <p>
     * The data to pass or return.
     * </p>
     * @param int|null $encoding <p>
     * The encoding ID, one of the XSD_... constants.
     * </p>
     * @param string $type_name [optional] <p>
     * The type name.
     * </p>
     * @param string $type_namespace [optional] <p>
     * The type namespace.
     * </p>
     * @param string $node_name [optional] <p>
     * The XML node name.
     * </p>
     * @param string $node_namespace [optional] <p>
     * The XML node namespace.
     * </p>
     * @since 5.0.1
     * @removed 8.0
     */
    public function SoapVar($data, $encoding, $type_name = '', $type_namespace = '', $node_name = '', $node_namespace = '') {}
}

/**
 * The SoapServer class provides a server for the SOAP 1.1 and SOAP 1.2 protocols. It can be used with or without a WSDL service description.
 * @link https://php.net/manual/en/class.soapserver.php
 */
class SoapServer
{
    /**
     * SoapServer constructor
     * @link https://php.net/manual/en/soapserver.soapserver.php
     * @param mixed $wsdl <p>
     * To use the SoapServer in WSDL mode, pass the URI of a WSDL file.
     * Otherwise, pass <b>NULL</b> and set the uri option to the
     * target namespace for the server.
     * </p>
     * @param array $options [optional] <p>
     * Allow setting a default SOAP version (soap_version),
     * internal character encoding (encoding),
     * and actor URI (actor).
     * </p>
     * <p>
     * The classmap option can be used to map some WSDL
     * types to PHP classes. This option must be an array with WSDL types
     * as keys and names of PHP classes as values.
     * </p>
     * <p>
     * The typemap option is an array of type mappings.
     * Type mapping is an array with keys type_name,
     * type_ns (namespace URI), from_xml
     * (callback accepting one string parameter) and to_xml
     * (callback accepting one object parameter).
     * </p>
     * <p>
     * The cache_wsdl option is one of
     * <b>WSDL_CACHE_NONE</b>,
     * <b>WSDL_CACHE_DISK</b>,
     * <b>WSDL_CACHE_MEMORY</b> or
     * <b>WSDL_CACHE_BOTH</b>.
     * </p>
     * <p>
     * There is also a features option which can be set to
     * <b>SOAP_WAIT_ONE_WAY_CALLS</b>,
     * <b>SOAP_SINGLE_ELEMENT_ARRAYS</b>,
     * <b>SOAP_USE_XSI_ARRAY_TYPE</b>.
     * </p>
     * @since 5.0.1
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $wsdl,
        array $options = null
    ) {}

    /**
     * SoapServer constructor
     * @link https://php.net/manual/en/soapserver.soapserver.php
     * @param mixed $wsdl <p>
     * To use the SoapServer in WSDL mode, pass the URI of a WSDL file.
     * Otherwise, pass <b>NULL</b> and set the uri option to the
     * target namespace for the server.
     * </p>
     * @param array $options [optional] <p>
     * Allow setting a default SOAP version (soap_version),
     * internal character encoding (encoding),
     * and actor URI (actor).
     * </p>
     * <p>
     * The classmap option can be used to map some WSDL
     * types to PHP classes. This option must be an array with WSDL types
     * as keys and names of PHP classes as values.
     * </p>
     * <p>
     * The typemap option is an array of type mappings.
     * Type mapping is an array with keys type_name,
     * type_ns (namespace URI), from_xml
     * (callback accepting one string parameter) and to_xml
     * (callback accepting one object parameter).
     * </p>
     * <p>
     * The cache_wsdl option is one of
     * <b>WSDL_CACHE_NONE</b>,
     * <b>WSDL_CACHE_DISK</b>,
     * <b>WSDL_CACHE_MEMORY</b> or
     * <b>WSDL_CACHE_BOTH</b>.
     * </p>
     * <p>
     * There is also a features option which can be set to
     * <b>SOAP_WAIT_ONE_WAY_CALLS</b>,
     * <b>SOAP_SINGLE_ELEMENT_ARRAYS</b>,
     * <b>SOAP_USE_XSI_ARRAY_TYPE</b>.
     * </p>
     * @since 5.0.1
     * @removed 8.0
     */
    public function SoapServer($wsdl, array $options = null) {}

    /**
     * Sets SoapServer persistence mode
     * @link https://php.net/manual/en/soapserver.setpersistence.php
     * @param int $mode <p>
     * One of the SOAP_PERSISTENCE_XXX constants.
     * </p>
     * <p>
     * <b>SOAP_PERSISTENCE_REQUEST</b> - SoapServer data does not persist between
     * requests. This is the default behavior of any SoapServer
     * object after setClass is called.
     * </p>
     * <p>
     * <b>SOAP_PERSISTENCE_SESSION</b> - SoapServer data persists between requests.
     * This is accomplished by serializing the SoapServer class data into
     * $_SESSION['_bogus_session_name'], because of this
     * <b>session_start</b> must be called before this persistence mode is set.
     * </p>
     * @return void No value is returned.
     * @since 5.1.2
     */
    #[TentativeType]
    public function setPersistence(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $mode): void {}

    /**
     * Sets the class which handles SOAP requests
     * @link https://php.net/manual/en/soapserver.setclass.php
     * @param string $class <p>
     * The name of the exported class.
     * </p>
     * @param mixed ...$args [optional] These optional parameters will be passed to the default class constructor during object creation.
     * @return void No value is returned.
     * @since 5.0.1
     */
    #[TentativeType]
    public function setClass(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $class,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] ...$args
    ): void {}

    /**
     * Sets the object which will be used to handle SOAP requests
     * @link https://php.net/manual/en/soapserver.setobject.php
     * @param object $object <p>
     * The object to handle the requests.
     * </p>
     * @return void No value is returned.
     */
    #[TentativeType]
    public function setObject(object $object): void {}

    /**
     * Adds one or more functions to handle SOAP requests
     * @link https://php.net/manual/en/soapserver.addfunction.php
     * @param mixed $functions <p>
     * To export one function, pass the function name into this parameter as
     * a string.
     * </p>
     * <p>
     * To export several functions, pass an array of function names.
     * </p>
     * <p>
     * To export all the functions, pass a special constant <b>SOAP_FUNCTIONS_ALL</b>.
     * </p>
     * <p>
     * <i>functions</i> must receive all input arguments in the same
     * order as defined in the WSDL file (They should not receive any output parameters
     * as arguments) and return one or more values. To return several values they must
     * return an array with named output parameters.
     * </p>
     * @return void No value is returned.
     * @since 5.0.1
     */
    #[TentativeType]
    public function addFunction($functions): void {}

    /**
     * Returns list of defined functions
     * @link https://php.net/manual/en/soapserver.getfunctions.php
     * @return array An array of the defined functions.
     * @since 5.0.1
     */
    #[TentativeType]
    public function getFunctions(): array {}

    /**
     * Handles a SOAP request
     * @link https://php.net/manual/en/soapserver.handle.php
     * @param string $request [optional] <p>
     * The SOAP request. If this argument is omitted, the request is assumed to be
     * in the raw POST data of the HTTP request.
     * </p>
     * @return void No value is returned.
     * @since 5.0.1
     */
    #[TentativeType]
    public function handle(#[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $request = null): void {}

    /**
     * Issue SoapServer fault indicating an error
     * @link https://php.net/manual/en/soapserver.fault.php
     * @param string $code <p>
     * The error code to return
     * </p>
     * @param string $string <p>
     * A brief description of the error
     * </p>
     * @param string $actor [optional] <p>
     * A string identifying the actor that caused the fault.
     * </p>
     * @param string $details [optional] <p>
     * More details of the fault
     * </p>
     * @param string $name [optional] <p>
     * The name of the fault. This can be used to select a name from a WSDL file.
     * </p>
     * @return void No value is returned.
     * @since 5.0.1
     */
    #[TentativeType]
    public function fault(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $code,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $string,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $actor = null,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $details = null,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name = null
    ): void {}

    /**
     * Add a SOAP header to the response
     * @link https://php.net/manual/en/soapserver.addsoapheader.php
     * @param SoapHeader $header <p>
     * The header to be returned.
     * </p>
     * @return void No value is returned.
     * @since 5.0.1
     */
    #[TentativeType]
    public function addSoapHeader(SoapHeader $header): void {}
}

/**
 * Represents a SOAP fault.
 * @link https://php.net/manual/en/class.soapfault.php
 */
class SoapFault extends Exception
{
    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $faultcode;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $faultstring;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $faultactor;

    /**
     * @var mixed
     */
    #[LanguageLevelTypeAware(['8.1' => 'mixed'], default: '')]
    public $detail;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $faultname;

    /**
     * @var mixed
     */
    #[LanguageLevelTypeAware(['8.1' => 'mixed'], default: '')]
    public $headerfault;

    /**
     * @var string|null
     * @since 8.1
     */
    public string|null $faultcodens;

    /**
     * @var string|null
     * @since 8.1
     */
    public string|null $_name;

    /**
     * SoapFault constructor
     * @link https://php.net/manual/en/soapfault.soapfault.php
     * @param string $code <p>
     * The error code of the <b>SoapFault</b>.
     * </p>
     * @param string $string <p>
     * The error message of the <b>SoapFault</b>.
     * </p>
     * @param string $actor [optional] <p>
     * A string identifying the actor that caused the error.
     * </p>
     * @param mixed $details [optional] <p>
     * More details about the cause of the error.
     * </p>
     * @param string $name [optional] <p>
     * Can be used to select the proper fault encoding from WSDL.
     * </p>
     * @param mixed $headerFault [optional] <p>
     * Can be used during SOAP header handling to report an error in the
     * response header.
     * </p>
     * @since 5.0.1
     */
    #[Pure]
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'array|string|null'], default: '')] $code,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $string,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $actor = null,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $details = null,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $name = null,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $headerFault = null
    ) {}

    /**
     * SoapFault constructor
     * @link https://php.net/manual/en/soapfault.soapfault.php
     * @param string $faultcode <p>
     * The error code of the <b>SoapFault</b>.
     * </p>
     * @param string $faultstring <p>
     * The error message of the <b>SoapFault</b>.
     * </p>
     * @param string $faultactor [optional] <p>
     * A string identifying the actor that caused the error.
     * </p>
     * @param string $detail [optional] <p>
     * More details about the cause of the error.
     * </p>
     * @param string $faultname [optional] <p>
     * Can be used to select the proper fault encoding from WSDL.
     * </p>
     * @param mixed $headerfault [optional] <p>
     * Can be used during SOAP header handling to report an error in the
     * response header.
     * </p>
     * @since 5.0.1
     * @removed 8.0
     */
    public function SoapFault($faultcode, $faultstring, $faultactor = null, $detail = null, $faultname = null, $headerfault = null) {}

    /**
     * Obtain a string representation of a SoapFault
     * @link https://php.net/manual/en/soapfault.tostring.php
     * @return string A string describing the SoapFault.
     * @since 5.0.1
     */
    #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')]
    public function __toString() {}
}

/**
 * Represents parameter to a SOAP call.
 * @link https://php.net/manual/en/class.soapparam.php
 */
class SoapParam
{
    /**
     * @var string
     * @since 8.1
     */
    public string $param_name;

    /**
     * @var mixed
     * @since 8.1
     */
    public mixed $param_data;

    /**
     * SoapParam constructor
     * @link https://php.net/manual/en/soapparam.soapparam.php
     * @param mixed $data <p>
     * The data to pass or return. This parameter can be passed directly as PHP
     * value, but in this case it will be named as paramN and
     * the SOAP service may not understand it.
     * </p>
     * @param string $name <p>
     * The parameter name.
     * </p>
     * @since 5.0.1
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $data,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name
    ) {}

    /**
     * SoapParam constructor
     * @link https://php.net/manual/en/soapparam.soapparam.php
     * @param mixed $data <p>
     * The data to pass or return. This parameter can be passed directly as PHP
     * value, but in this case it will be named as paramN and
     * the SOAP service may not understand it.
     * </p>
     * @param string $name <p>
     * The parameter name.
     * </p>
     * @since 5.0.1
     * @removed 8.0
     */
    public function SoapParam($data, $name) {}
}

/**
 * Represents a SOAP header.
 * @link https://php.net/manual/en/class.soapheader.php
 */
class SoapHeader
{
    /**
     * @var string
     * @since 8.1
     */
    public string $namespace;

    /**
     * @var string
     * @since 8.1
     */
    public string $name;

    /**
     * @var mixed
     * @since 8.1
     */
    public mixed $data;

    /**
     * @var bool
     * @since 8.1
     */
    public bool $mustUnderstand;

    /**
     * @var string|int|null
     * @since 8.1
     */
    public string|int|null $actor;

    /**
     * SoapHeader constructor
     * @link https://www.php.net/manual/en/soapheader.construct.php
     * @param string $namespace <p>
     * The namespace of the SOAP header element.
     * </p>
     * @param string $name <p>
     * The name of the SoapHeader object.
     * </p>
     * @param mixed $data [optional] <p>
     * A SOAP header's content. It can be a PHP value or a
     * <b>SoapVar</b> object.
     * </p>
     * @param bool $mustUnderstand [optional]
     * @param string $actor [optional] <p>
     * Value of the actor attribute of the SOAP header
     * element.
     * </p>
     * @since 5.0.1
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $namespace,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $data = null,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $mustUnderstand = false,
        #[LanguageLevelTypeAware(['8.0' => 'string|int|null'], default: '')] $actor = null
    ) {}

    /**
     * SoapHeader constructor
     * @link https://php.net/manual/en/soapheader.soapheader.php
     * @param string $namespace <p>
     * The namespace of the SOAP header element.
     * </p>
     * @param string $name <p>
     * The name of the SoapHeader object.
     * </p>
     * @param mixed $data [optional] <p>
     * A SOAP header's content. It can be a PHP value or a
     * <b>SoapVar</b> object.
     * </p>
     * @param bool $mustunderstand [optional]
     * @param string $actor [optional] <p>
     * Value of the actor attribute of the SOAP header
     * element.
     * </p>
     * @since 5.0.1
     * @removed 8.0
     */
    public function SoapHeader($namespace, $name, $data = null, $mustunderstand = false, $actor = null) {}
}

/**
 * Set whether to use the SOAP error handler
 * @link https://php.net/manual/en/function.use-soap-error-handler.php
 * @param bool $enable [optional] <p>
 * Set to <b>TRUE</b> to send error details to clients.
 * </p>
 * @return bool the original value.
 */
function use_soap_error_handler(bool $enable = true): bool {}

/**
 * Checks if a SOAP call has failed
 * @link https://php.net/manual/en/function.is-soap-fault.php
 * @param mixed $object <p>
 * The object to test.
 * </p>
 * @return bool This will return <b>TRUE</b> on error, and <b>FALSE</b> otherwise.
 */
function is_soap_fault(mixed $object): bool {}

define('SOAP_1_1', 1);
define('SOAP_1_2', 2);
define('SOAP_PERSISTENCE_SESSION', 1);
define('SOAP_PERSISTENCE_REQUEST', 2);
define('SOAP_FUNCTIONS_ALL', 999);
define('SOAP_ENCODED', 1);
define('SOAP_LITERAL', 2);
define('SOAP_RPC', 1);
define('SOAP_DOCUMENT', 2);
define('SOAP_ACTOR_NEXT', 1);
define('SOAP_ACTOR_NONE', 2);
define('SOAP_ACTOR_UNLIMATERECEIVER', 3);
define('SOAP_COMPRESSION_ACCEPT', 32);
define('SOAP_COMPRESSION_GZIP', 0);
define('SOAP_COMPRESSION_DEFLATE', 16);
define('SOAP_AUTHENTICATION_BASIC', 0);
define('SOAP_AUTHENTICATION_DIGEST', 1);
define('UNKNOWN_TYPE', 999998);
define('XSD_STRING', 101);
define('XSD_BOOLEAN', 102);
define('XSD_DECIMAL', 103);
define('XSD_FLOAT', 104);
define('XSD_DOUBLE', 105);
define('XSD_DURATION', 106);
define('XSD_DATETIME', 107);
define('XSD_TIME', 108);
define('XSD_DATE', 109);
define('XSD_GYEARMONTH', 110);
define('XSD_GYEAR', 111);
define('XSD_GMONTHDAY', 112);
define('XSD_GDAY', 113);
define('XSD_GMONTH', 114);
define('XSD_HEXBINARY', 115);
define('XSD_BASE64BINARY', 116);
define('XSD_ANYURI', 117);
define('XSD_QNAME', 118);
define('XSD_NOTATION', 119);
define('XSD_NORMALIZEDSTRING', 120);
define('XSD_TOKEN', 121);
define('XSD_LANGUAGE', 122);
define('XSD_NMTOKEN', 123);
define('XSD_NAME', 124);
define('XSD_NCNAME', 125);
define('XSD_ID', 126);
define('XSD_IDREF', 127);
define('XSD_IDREFS', 128);
define('XSD_ENTITY', 129);
define('XSD_ENTITIES', 130);
define('XSD_INTEGER', 131);
define('XSD_NONPOSITIVEINTEGER', 132);
define('XSD_NEGATIVEINTEGER', 133);
define('XSD_LONG', 134);
define('XSD_INT', 135);
define('XSD_SHORT', 136);
define('XSD_BYTE', 137);
define('XSD_NONNEGATIVEINTEGER', 138);
define('XSD_UNSIGNEDLONG', 139);
define('XSD_UNSIGNEDINT', 140);
define('XSD_UNSIGNEDSHORT', 141);
define('XSD_UNSIGNEDBYTE', 142);
define('XSD_POSITIVEINTEGER', 143);
define('XSD_NMTOKENS', 144);
define('XSD_ANYTYPE', 145);
define('XSD_ANYXML', 147);
define('APACHE_MAP', 200);
define('SOAP_ENC_OBJECT', 301);
define('SOAP_ENC_ARRAY', 300);
define('XSD_1999_TIMEINSTANT', 401);
define('XSD_NAMESPACE', "http://www.w3.org/2001/XMLSchema");
define('XSD_1999_NAMESPACE', "http://www.w3.org/1999/XMLSchema");
define('SOAP_SINGLE_ELEMENT_ARRAYS', 1);
define('SOAP_WAIT_ONE_WAY_CALLS', 2);
define('SOAP_USE_XSI_ARRAY_TYPE', 4);
define('WSDL_CACHE_NONE', 0);
define('WSDL_CACHE_DISK', 1);
define('WSDL_CACHE_MEMORY', 2);
define('WSDL_CACHE_BOTH', 3);

/**
 * @link https://php.net/manual/en/soap.constants.php
 * @since 5.5
 */
define('SOAP_SSL_METHOD_TLS', 0);

/**
 * @link https://php.net/manual/en/soap.constants.php
 * @since 5.5
 */
define('SOAP_SSL_METHOD_SSLv2', 1);

/**
 * @link https://php.net/manual/en/soap.constants.php
 * @since 5.5
 */
define('SOAP_SSL_METHOD_SSLv3', 2);

/**
 * @link https://php.net/manual/en/soap.constants.php
 * @since 5.5
 */
define('SOAP_SSL_METHOD_SSLv23', 3);

// End of soap v.
