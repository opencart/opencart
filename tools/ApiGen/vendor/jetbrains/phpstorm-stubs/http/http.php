<?php

// Start of http v.1.6.6

use JetBrains\PhpStorm\Pure;

class HttpException extends Exception
{
    public $innerException;
}
class HttpRuntimeException extends HttpException {}
class HttpInvalidParamException extends HttpException {}
class HttpHeaderException extends HttpException {}
class HttpMalformedHeadersException extends HttpException {}
class HttpRequestMethodException extends HttpException {}
class HttpMessageTypeException extends HttpException {}
class HttpEncodingException extends HttpException {}
class HttpRequestException extends HttpException {}
class HttpRequestPoolException extends HttpException {}
class HttpSocketException extends HttpException {}
class HttpResponseException extends HttpException {}
class HttpUrlException extends HttpException {}
class HttpQueryStringException extends HttpException {}

/**
 * @link https://php.net/manual/en/class.httpdeflatestream.php
 */
class HttpDeflateStream
{
    public const TYPE_GZIP = 16;
    public const TYPE_ZLIB = 0;
    public const TYPE_RAW = 32;
    public const LEVEL_DEF = 0;
    public const LEVEL_MIN = 1;
    public const LEVEL_MAX = 9;
    public const STRATEGY_DEF = 0;
    public const STRATEGY_FILT = 256;
    public const STRATEGY_HUFF = 512;
    public const STRATEGY_RLE = 768;
    public const STRATEGY_FIXED = 1024;
    public const FLUSH_NONE = 0;
    public const FLUSH_SYNC = 1048576;
    public const FLUSH_FULL = 2097152;

    /**
     * (PECL pecl_http &gt;= 0.21.0)<br/>
     * HttpDeflateStream class constructor
     * @link https://php.net/manual/en/function.httpdeflatestream-construct.php
     * @param int $flags [optional] <p>
     * initialization flags
     * </p>
     */
    public function __construct($flags = null) {}

    /**
     * (PECL pecl_http &gt;= 0.21.0)<br/>
     * Update deflate stream
     * @link https://php.net/manual/en/function.httpdeflatestream-update.php
     * @param string $data <p>
     * data to deflate
     * </p>
     * @return string|false deflated data on success or false on failure.
     */
    public function update($data) {}

    /**
     * (PECL pecl_http &gt;= 0.21.0)<br/>
     * Flush deflate stream
     * @link https://php.net/manual/en/function.httpdeflatestream-flush.php
     * @param string $data [optional] <p>
     * more data to deflate
     * </p>
     * @return string|false some deflated data as string on success or false on failure.
     */
    public function flush($data = null) {}

    /**
     * (PECL pecl_http &gt;= 0.21.0)<br/>
     * Finalize deflate stream
     * @link https://php.net/manual/en/function.httpdeflatestream-finish.php
     * @param string $data [optional] <p>
     * data to deflate
     * </p>
     * @return string the final part of deflated data.
     */
    public function finish($data = null) {}

    /**
     * (PECL pecl_http &gt;= 1.4.0)<br/>
     * HttpDeflateStream class factory
     * @link https://php.net/manual/en/function.httpdeflatestream-factory.php
     * @param int $flags [optional] <p>
     * initialization flags
     * </p>
     * @param string $class_name [optional] <p>
     * name of a subclass of HttpDeflateStream
     * </p>
     * @return HttpDeflateStream
     */
    public static function factory($flags = null, $class_name = null) {}
}

/**
 * @link https://php.net/manual/en/class.httpinflatestream.php
 */
class HttpInflateStream
{
    public const FLUSH_NONE = 0;
    public const FLUSH_SYNC = 1048576;
    public const FLUSH_FULL = 2097152;

    /**
     * (PECL pecl_http &gt;= 1.0.0)<br/>
     * HttpInflateStream class constructor
     * @link https://php.net/manual/en/function.httpinflatestream-construct.php
     * @param int $flags [optional] <p>
     * initialization flags
     * </p>
     */
    public function __construct($flags = null) {}

    /**
     * (PECL pecl_http &gt;= 0.21.0)<br/>
     * Update inflate stream
     * @link https://php.net/manual/en/function.httpinflatestream-update.php
     * @param string $data <p>
     * data to inflate
     * </p>
     * @return string|false inflated data on success or false on failure.
     */
    public function update($data) {}

    /**
     * (PECL pecl_http &gt;= 0.21.0)<br/>
     * Flush inflate stream
     * @link https://php.net/manual/en/function.httpinflatestream-flush.php
     * @param string $data [optional] <p>
     * more data to inflate
     * </p>
     * @return string|false some inflated data as string on success or false on failure.
     */
    public function flush($data = null) {}

    /**
     * (PECL pecl_http &gt;= 0.21.0)<br/>
     * Finalize inflate stream
     * @link https://php.net/manual/en/function.httpinflatestream-finish.php
     * @param string $data [optional] <p>
     * data to inflate
     * </p>
     * @return string the final part of inflated data.
     */
    public function finish($data = null) {}

    /**
     * (PECL pecl_http &gt;= 1.4.0)<br/>
     * HttpInflateStream class factory
     * @link https://php.net/manual/en/function.httpinflatestream-factory.php
     * @param int $flags [optional] <p>
     * initialization flags
     * </p>
     * @param string $class_name [optional] <p>
     * name of a subclass of HttpInflateStream
     * </p>
     * @return HttpInflateStream
     */
    public static function factory($flags = null, $class_name = null) {}
}

/**
 * @link https://php.net/manual/en/class.httpmessage.php
 */
class HttpMessage implements Countable, Serializable, Iterator
{
    public const TYPE_NONE = 0;
    public const TYPE_REQUEST = 1;
    public const TYPE_RESPONSE = 2;
    protected $type;
    protected $body;
    protected $requestMethod;
    protected $requestUrl;
    protected $responseStatus;
    protected $responseCode;
    protected $httpVersion;
    protected $headers;
    protected $parentMessage;

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * HttpMessage constructor
     * @link https://php.net/manual/en/function.httpmessage-construct.php
     * @param string $message [optional] <p>
     * a single or several consecutive HTTP messages
     * </p>
     */
    public function __construct($message = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get message body
     * @link https://php.net/manual/en/function.httpmessage-getbody.php
     * @return string the message body as string.
     */
    #[Pure]
    public function getBody() {}

    /**
     * (PECL pecl_http &gt;= 0.14.0)<br/>
     * Set message body
     * @link https://php.net/manual/en/function.httpmessage-setbody.php
     * @param string $body <p>
     * the new body of the message
     * </p>
     * @return void
     */
    public function setBody($body) {}

    /**
     * (PECL pecl_http &gt;= 1.1.0)<br/>
     * Get header
     * @link https://php.net/manual/en/function.httpmessage-getheader.php
     * @param string $header <p>
     * header name
     * </p>
     * @return string|null the header value on success or NULL if the header does not exist.
     */
    #[Pure]
    public function getHeader($header) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get message headers
     * @link https://php.net/manual/en/function.httpmessage-getheaders.php
     * @return array an associative array containing the messages HTTP headers.
     */
    #[Pure]
    public function getHeaders() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set headers
     * @link https://php.net/manual/en/function.httpmessage-setheaders.php
     * @param array $header <p>
     * associative array containing the new HTTP headers, which will replace all previous HTTP headers of the message
     * </p>
     * @return void
     */
    public function setHeaders(array $header) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Add headers
     * @link https://php.net/manual/en/function.httpmessage-addheaders.php
     * @param array $headers <p>
     * associative array containing the additional HTTP headers to add to the messages existing headers
     * </p>
     * @param bool $append [optional] <p>
     * if true, and a header with the same name of one to add exists already, this respective
     * header will be converted to an array containing both header values, otherwise
     * it will be overwritten with the new header value
     * </p>
     * @return void
     */
    public function addHeaders(array $headers, $append = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get message type
     * @link https://php.net/manual/en/function.httpmessage-gettype.php
     * @return int the HttpMessage::TYPE.
     */
    #[Pure]
    public function getType() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set message type
     * @link https://php.net/manual/en/function.httpmessage-settype.php
     * @param int $type <p>
     * the HttpMessage::TYPE
     * </p>
     * @return void
     */
    public function setType($type) {}

    #[Pure]
    public function getInfo() {}

    /**
     * @param $http_info
     */
    public function setInfo($http_info) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get response code
     * @link https://php.net/manual/en/function.httpmessage-getresponsecode.php
     * @return int|false the HTTP response code if the message is of type HttpMessage::TYPE_RESPONSE, else FALSE.
     */
    #[Pure]
    public function getResponseCode() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set response code
     * @link https://php.net/manual/en/function.httpmessage-setresponsecode.php
     * @param int $code <p>
     * HTTP response code
     * </p>
     * @return bool TRUE on success, or FALSE if the message is not of type
     * HttpMessage::TYPE_RESPONSE or the response code is out of range (100-510).
     */
    public function setResponseCode($code) {}

    /**
     * (PECL pecl_http &gt;= 0.23.0)<br/>
     * Get response status
     * @link https://php.net/manual/en/function.httpmessage-getresponsestatus.php
     * @return string the HTTP response status string if the message is of type
     * HttpMessage::TYPE_RESPONSE, else FALSE.
     */
    #[Pure]
    public function getResponseStatus() {}

    /**
     * (PECL pecl_http &gt;= 0.23.0)<br/>
     * Set response status
     * @link https://php.net/manual/en/function.httpmessage-setresponsestatus.php
     * @param string $status <p>
     * the response status text
     * </p>
     * @return bool TRUE on success or FALSE if the message is not of type
     * HttpMessage::TYPE_RESPONSE.
     */
    public function setResponseStatus($status) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get request method
     * @link https://php.net/manual/en/function.httpmessage-getrequestmethod.php
     * @return string|false the request method name on success, or FALSE if the message is
     * not of type HttpMessage::TYPE_REQUEST.
     */
    #[Pure]
    public function getRequestMethod() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set request method
     * @link https://php.net/manual/en/function.httpmessage-setrequestmethod.php
     * @param string $method <p>
     * the request method name
     * </p>
     * @return bool TRUE on success, or FALSE if the message is not of type
     * HttpMessage::TYPE_REQUEST or an invalid request method was supplied.
     */
    public function setRequestMethod($method) {}

    /**
     * (PECL pecl_http &gt;= 0.21.0)<br/>
     * Get request URL
     * @link https://php.net/manual/en/function.httpmessage-getrequesturl.php
     * @return string|false the request URL as string on success, or FALSE if the message
     * is not of type HttpMessage::TYPE_REQUEST.
     */
    #[Pure]
    public function getRequestUrl() {}

    /**
     * (PECL pecl_http &gt;= 0.21.0)<br/>
     * Set request URL
     * @link https://php.net/manual/en/function.httpmessage-setrequesturl.php
     * @param string $url <p>
     * the request URL
     * </p>
     * @return bool TRUE on success, or FALSE if the message is not of type
     * HttpMessage::TYPE_REQUEST or supplied URL was empty.
     */
    public function setRequestUrl($url) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get HTTP version
     * @link https://php.net/manual/en/function.httpmessage-gethttpversion.php
     * @return string the HTTP protocol version as string.
     */
    #[Pure]
    public function getHttpVersion() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set HTTP version
     * @link https://php.net/manual/en/function.httpmessage-sethttpversion.php
     * @param string $version <p>
     * the HTTP protocol version
     * </p>
     * @return bool TRUE on success, or FALSE if supplied version is out of range (1.0/1.1).
     */
    public function setHttpVersion($version) {}

    /**
     * (PECL pecl_http &gt;= 1.0.0)<br/>
     * Guess content type
     * @link https://php.net/manual/en/function.httpmessage-guesscontenttype.php
     * @param string $magic_file <p>
     * the magic.mime database to use
     * </p>
     * @param int $magic_mode [optional] <p>
     * flags for libmagic
     * </p>
     * @return string|false the guessed content type on success or false on failure.
     */
    public function guessContentType($magic_file, $magic_mode = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get parent message
     * @link https://php.net/manual/en/function.httpmessage-getparentmessage.php
     * @return HttpMessage the parent HttpMessage object.
     */
    #[Pure]
    public function getParentMessage() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Send message
     * @link https://php.net/manual/en/function.httpmessage-send.php
     * @return bool true on success or false on failure.
     */
    public function send() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get string representation
     * @link https://php.net/manual/en/function.httpmessage-tostring.php
     * @param bool $include_parent [optional] <p>
     * specifies whether the returned string should also contain any parent messages
     * </p>
     * @return string the message as string.
     */
    public function toString($include_parent = null) {}

    /**
     * (PECL pecl_http &gt;= 0.22.0)<br/>
     * Create HTTP object regarding message type
     * @link https://php.net/manual/en/function.httpmessage-tomessagetypeobject.php
     * @return HttpRequest|HttpResponse|null either an HttpRequest or HttpResponse object on success, or NULL on failure.
     */
    public function toMessageTypeObject() {}

    public function count() {}

    public function serialize() {}

    /**
     * @param $serialized
     */
    public function unserialize($serialized) {}

    public function rewind() {}

    public function valid() {}

    public function current() {}

    public function key() {}

    public function next() {}

    /**
     * @return string
     */
    public function __toString() {}

    /**
     * (PECL pecl_http &gt;= 1.4.0)<br/>
     * Create HttpMessage from string
     * @link https://php.net/manual/en/function.httpmessage-factory.php
     * @param string $raw_message [optional] <p>
     * a single or several consecutive HTTP messages
     * </p>
     * @param string $class_name [optional] <p>
     * a class extending HttpMessage
     * </p>
     * @return HttpMessage|null an HttpMessage object on success or NULL on failure.
     */
    public static function factory($raw_message = null, $class_name = null) {}

    /**
     * (PECL pecl_http 0.10.0-1.3.3)<br/>
     * Create HttpMessage from string
     * @link https://php.net/manual/en/function.httpmessage-fromstring.php
     * @param string $raw_message [optional] <p>
     * a single or several consecutive HTTP messages
     * </p>
     * @param string $class_name [optional] <p>
     * a class extending HttpMessage
     * </p>
     * @return HttpMessage|null an HttpMessage object on success or NULL on failure.
     */
    public static function fromString($raw_message = null, $class_name = null) {}

    /**
     * (PECL pecl_http &gt;= 1.5.0)<br/>
     * Create HttpMessage from environment
     * @link https://php.net/manual/en/function.httpmessage-fromenv.php
     * @param int $message_type <p>
     * The message type. See HttpMessage type constants.
     * </p>
     * @param string $class_name [optional] <p>
     * a class extending HttpMessage
     * </p>
     * @return HttpMessage|null an HttpMessage object on success or NULL on failure.
     */
    public static function fromEnv($message_type, $class_name = null) {}

    /**
     * (PECL pecl_http &gt;= 0.22.0)<br/>
     * Detach HttpMessage
     * @link https://php.net/manual/en/function.httpmessage-detach.php
     * @return HttpMessage detached HttpMessage object copy.
     */
    public function detach() {}

    /**
     * (PECL pecl_http &gt;= 0.22.0)<br/>
     * Prepend message(s)
     * @link https://php.net/manual/en/function.httpmessage-prepend.php
     * @param HttpMessage $message <p>
     * HttpMessage object to prepend
     * </p>
     * @param bool $top [optional] <p>
     * whether to prepend to the top most or right this message
     * </p>
     * @return void
     */
    public function prepend(HttpMessage $message, $top = null) {}

    /**
     * (PECL pecl_http &gt;= 0.23.0)<br/>
     * Reverse message chain
     * @link https://php.net/manual/en/function.httpmessage-reverse.php
     * @return HttpMessage the most parent HttpMessage object.
     */
    public function reverse() {}
}

/**
 * @link https://php.net/manual/en/class.httpquerystring.php
 */
class HttpQueryString implements Serializable, ArrayAccess
{
    public const TYPE_BOOL = 3;
    public const TYPE_INT = 1;
    public const TYPE_FLOAT = 2;
    public const TYPE_STRING = 6;
    public const TYPE_ARRAY = 4;
    public const TYPE_OBJECT = 5;
    private static $instance;
    private $queryArray;
    private $queryString;

    /**
     * (PECL pecl_http &gt;= 0.22.0)<br/>
     * HttpQueryString constructor
     * @link https://php.net/manual/en/function.httpquerystring-construct.php
     * @param bool $global [optional] <p>
     * whether to operate on $_GET and
     * $_SERVER['QUERY_STRING']
     * </p>
     * @param mixed $add [optional] <p>
     * additional/initial query string parameters
     * </p>
     */
    final public function __construct($global = null, $add = null) {}

    /**
     * (PECL pecl_http &gt;= 0.22.0)<br/>
     * Get query string as array
     * @link https://php.net/manual/en/function.httpquerystring-toarray.php
     * @return array the array representation of the query string.
     */
    public function toArray() {}

    /**
     * (PECL pecl_http &gt;= 0.22.0)<br/>
     * Get query string
     * @link https://php.net/manual/en/function.httpquerystring-tostring.php
     * @return string the string representation of the query string.
     */
    public function toString() {}

    /**
     * @return string
     */
    public function __toString() {}

    /**
     * (PECL pecl_http &gt;= 0.22.0)<br/>
     * Get (part of) query string
     * @link https://php.net/manual/en/function.httpquerystring-get.php
     * @param string $key [optional] <p>
     * key of the query string param to retrieve
     * </p>
     * @param mixed $type [optional] <p>
     * which variable type to enforce
     * </p>
     * @param mixed $defval [optional] <p>
     * default value if key does not exist
     * </p>
     * @param bool $delete [optional] <p>
     * whether to remove the key/value pair from the query string
     * </p>
     * @return mixed the value of the query string param or the whole query string if no key was specified on success or defval if key does not exist.
     */
    #[Pure]
    public function get($key = null, $type = null, $defval = null, $delete = null) {}

    /**
     * (PECL pecl_http &gt;= 0.22.0)<br/>
     * Set query string params
     * @link https://php.net/manual/en/function.httpquerystring-set.php
     * @param mixed $params <p>
     * query string params to add
     * </p>
     * @return string the current query string.
     */
    public function set($params) {}

    /**
     * (PECL pecl_http &gt;= 1.1.0)<br/>
     * Modifiy query string copy
     * @link https://php.net/manual/en/function.httpquerystring-mod.php
     * @param mixed $params <p>
     * query string params to add
     * </p>
     * @return HttpQueryString a new HttpQueryString object
     */
    public function mod($params) {}

    /**
     * @param $name
     * @param $defval [optional]
     * @param $delete [optional]
     */
    #[Pure]
    public function getBool($name, $defval, $delete) {}

    /**
     * @param $name
     * @param $defval [optional]
     * @param $delete [optional]
     */
    #[Pure]
    public function getInt($name, $defval, $delete) {}

    /**
     * @param $name
     * @param $defval [optional]
     * @param $delete [optional]
     */
    #[Pure]
    public function getFloat($name, $defval, $delete) {}

    /**
     * @param $name
     * @param $defval [optional]
     * @param $delete [optional]
     */
    #[Pure]
    public function getString($name, $defval, $delete) {}

    /**
     * @param $name
     * @param $defval [optional]
     * @param $delete [optional]
     */
    #[Pure]
    public function getArray($name, $defval, $delete) {}

    /**
     * @param $name
     * @param $defval [optional]
     * @param $delete [optional]
     */
    #[Pure]
    public function getObject($name, $defval, $delete) {}

    /**
     * @param $global [optional]
     * @param $params [optional]
     * @param $class_name [optional]
     */
    public static function factory($global, $params, $class_name) {}

    /**
     * (PECL pecl_http &gt;= 0.25.0)<br/>
     * HttpQueryString singleton
     * @link https://php.net/manual/en/function.httpquerystring-singleton.php
     * @param bool $global [optional] <p>
     * whether to operate on $_GET and
     * $_SERVER['QUERY_STRING']
     * </p>
     * @return HttpQueryString always the same HttpQueryString instance regarding the global setting.
     */
    public static function singleton($global = null) {}

    /**
     * (PECL pecl_http &gt;= 0.25.0)<br/>
     * Change query strings charset
     * @link https://php.net/manual/en/function.httpquerystring-xlate.php
     * @param string $ie <p>
     * input encoding
     * </p>
     * @param string $oe <p>
     * output encoding
     * </p>
     * @return bool true on success or false on failure.
     */
    public function xlate($ie, $oe) {}

    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize() {}

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset) {}

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized) {}

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return bool true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset) {}

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value) {}

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset) {}
}

/**
 * @link https://php.net/manual/en/class.httprequest.php
 */
class HttpRequest
{
    public const METH_GET = 1;
    public const METH_HEAD = 2;
    public const METH_POST = 3;
    public const METH_PUT = 4;
    public const METH_DELETE = 5;
    public const METH_OPTIONS = 6;
    public const METH_TRACE = 7;
    public const METH_CONNECT = 8;
    public const METH_PROPFIND = 9;
    public const METH_PROPPATCH = 10;
    public const METH_MKCOL = 11;
    public const METH_COPY = 12;
    public const METH_MOVE = 13;
    public const METH_LOCK = 14;
    public const METH_UNLOCK = 15;
    public const METH_VERSION_CONTROL = 16;
    public const METH_REPORT = 17;
    public const METH_CHECKOUT = 18;
    public const METH_CHECKIN = 19;
    public const METH_UNCHECKOUT = 20;
    public const METH_MKWORKSPACE = 21;
    public const METH_UPDATE = 22;
    public const METH_LABEL = 23;
    public const METH_MERGE = 24;
    public const METH_BASELINE_CONTROL = 25;
    public const METH_MKACTIVITY = 26;
    public const METH_ACL = 27;
    public const VERSION_1_0 = 1;
    public const VERSION_1_1 = 2;
    public const VERSION_NONE = 0;
    public const VERSION_ANY = 0;
    public const SSL_VERSION_TLSv1 = 1;
    public const SSL_VERSION_SSLv2 = 2;
    public const SSL_VERSION_SSLv3 = 3;
    public const SSL_VERSION_ANY = 0;
    public const IPRESOLVE_V4 = 1;
    public const IPRESOLVE_V6 = 2;
    public const IPRESOLVE_ANY = 0;
    public const AUTH_BASIC = 1;
    public const AUTH_DIGEST = 2;
    public const AUTH_NTLM = 8;
    public const AUTH_GSSNEG = 4;
    public const AUTH_ANY = -1;
    public const PROXY_SOCKS4 = 4;
    public const PROXY_SOCKS5 = 5;
    public const PROXY_HTTP = 0;
    private $options;
    private $postFields;
    private $postFiles;
    private $responseInfo;
    private $responseMessage;
    private $responseCode;
    private $responseStatus;
    private $method;
    private $url;
    private $contentType;
    private $requestBody;
    private $queryData;
    private $putFile;
    private $putData;
    private $history;
    public $recordHistory;

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * HttpRequest constructor
     * @link https://php.net/manual/en/function.httprequest-construct.php
     * @param string $url [optional] <p>
     * the target request url
     * </p>
     * @param int $request_method [optional] <p>
     * the request method to use
     * </p>
     * @param null|array $options [optional] <p>
     * an associative array with request options
     * </p>
     */
    public function __construct($url = null, $request_method = null, ?array $options = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set options
     * @link https://php.net/manual/en/function.httprequest-setoptions.php
     * @param null|array $options [optional] <p>
     * an associative array, which values will overwrite the
     * currently set request options;
     * if empty or omitted, the options of the HttpRequest object will be reset
     * </p>
     * @return bool true on success or false on failure.
     */
    public function setOptions(?array $options = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get options
     * @link https://php.net/manual/en/function.httprequest-getoptions.php
     * @return array an associative array containing currently set options.
     */
    #[Pure]
    public function getOptions() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set ssl options
     * @link https://php.net/manual/en/function.httprequest-setssloptions.php
     * @param null|array $options [optional] <p>
     * an associative array containing any SSL specific options;
     * if empty or omitted, the SSL options will be reset
     * </p>
     * @return bool true on success or false on failure.
     */
    public function setSslOptions(?array $options = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get ssl options
     * @link https://php.net/manual/en/function.httprequest-getssloptions.php
     * @return array an associative array containing any previously set SSL options.
     */
    #[Pure]
    public function getSslOptions() {}

    /**
     * (PECL pecl_http &gt;= 0.12.0)<br/>
     * Add ssl options
     * @link https://php.net/manual/en/function.httprequest-addssloptions.php
     * @param array $option <p>
     * an associative array as parameter containing additional SSL specific options
     * </p>
     * @return bool true on success or false on failure.
     */
    public function addSslOptions(array $option) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Add headers
     * @link https://php.net/manual/en/function.httprequest-addheaders.php
     * @param array $headers <p>
     * an associative array as parameter containing additional header name/value pairs
     * </p>
     * @return bool true on success or false on failure.
     */
    public function addHeaders(array $headers) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get headers
     * @link https://php.net/manual/en/function.httprequest-getheaders.php
     * @return array an associative array containing all currently set headers.
     */
    #[Pure]
    public function getHeaders() {}

    /**
     * (PECL pecl_http &gt;= 0.12.0)<br/>
     * Set headers
     * @link https://php.net/manual/en/function.httprequest-setheaders.php
     * @param null|array $headers [optional] <p>
     * an associative array as parameter containing header name/value pairs;
     * if empty or omitted, all previously set headers will be unset
     * </p>
     * @return bool true on success or false on failure.
     */
    public function setHeaders(?array $headers = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Add cookies
     * @link https://php.net/manual/en/function.httprequest-addcookies.php
     * @param array $cookies <p>
     * an associative array containing any cookie name/value pairs to add
     * </p>
     * @return bool true on success or false on failure.
     */
    public function addCookies(array $cookies) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get cookies
     * @link https://php.net/manual/en/function.httprequest-getcookies.php
     * @return array an associative array containing any previously set cookies.
     */
    #[Pure]
    public function getCookies() {}

    /**
     * (PECL pecl_http &gt;= 0.12.0)<br/>
     * Set cookies
     * @link https://php.net/manual/en/function.httprequest-setcookies.php
     * @param null|array $cookies [optional] <p>
     * an associative array as parameter containing cookie name/value pairs;
     * if empty or omitted, all previously set cookies will be unset
     * </p>
     * @return bool true on success or false on failure.
     */
    public function setCookies(?array $cookies = null) {}

    /**
     * (PECL pecl_http &gt;= 1.0.0)<br/>
     * Enable cookies
     * @link https://php.net/manual/en/function.httprequest-enablecookies.php
     * @return bool true on success or false on failure.
     */
    public function enableCookies() {}

    /**
     * (PECL pecl_http &gt;= 1.0.0)<br/>
     * Reset cookies
     * @link https://php.net/manual/en/function.httprequest-resetcookies.php
     * @param bool $session_only [optional] <p>
     * whether only session cookies should be reset (needs libcurl >= v7.15.4, else libcurl >= v7.14.1)
     * </p>
     * @return bool true on success or false on failure.
     */
    public function resetCookies($session_only = null) {}

    public function flushCookies() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set method
     * @link https://php.net/manual/en/function.httprequest-setmethod.php
     * @param int $request_method <p>
     * the request method to use
     * </p>
     * @return bool true on success or false on failure.
     */
    public function setMethod($request_method) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get method
     * @link https://php.net/manual/en/function.httprequest-getmethod.php
     * @return int the currently set request method.
     */
    #[Pure]
    public function getMethod() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set URL
     * @link https://php.net/manual/en/function.httprequest-seturl.php
     * @param string $url <p>
     * the request url
     * </p>
     * @return bool true on success or false on failure.
     */
    public function setUrl($url) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get url
     * @link https://php.net/manual/en/function.httprequest-geturl.php
     * @return string the currently set request url as string.
     */
    #[Pure]
    public function getUrl() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set content type
     * @link https://php.net/manual/en/function.httprequest-setcontenttype.php
     * @param string $content_type <p>
     * the content type of the request (primary/secondary)
     * </p>
     * @return bool TRUE on success, or FALSE if the content type does not seem to
     * contain a primary and a secondary part.
     */
    public function setContentType($content_type) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get content type
     * @link https://php.net/manual/en/function.httprequest-getcontenttype.php
     * @return string the previously set content type as string.
     */
    #[Pure]
    public function getContentType() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set query data
     * @link https://php.net/manual/en/function.httprequest-setquerydata.php
     * @param mixed $query_data <p>
     * a string or associative array parameter containing the pre-encoded
     * query string or to be encoded query fields;
     * if empty, the query data will be unset
     * </p>
     * @return bool true on success or false on failure.
     */
    public function setQueryData($query_data) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get query data
     * @link https://php.net/manual/en/function.httprequest-getquerydata.php
     * @return string a string containing the urlencoded query.
     */
    #[Pure]
    public function getQueryData() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Add query data
     * @link https://php.net/manual/en/function.httprequest-addquerydata.php
     * @param array $query_params <p>
     * an associative array as parameter containing the query fields to add
     * </p>
     * @return bool true on success or false on failure.
     */
    public function addQueryData(array $query_params) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set post fields
     * @link https://php.net/manual/en/function.httprequest-setpostfields.php
     * @param array $post_data <p>
     * an associative array containing the post fields;
     * if empty, the post data will be unset
     * </p>
     * @return bool true on success or false on failure.
     */
    public function setPostFields(array $post_data) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get post fields
     * @link https://php.net/manual/en/function.httprequest-getpostfields.php
     * @return array the currently set post fields as associative array.
     */
    #[Pure]
    public function getPostFields() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Add post fields
     * @link https://php.net/manual/en/function.httprequest-addpostfields.php
     * @param array $post_data <p>
     * an associative array as parameter containing the post fields
     * </p>
     * @return bool true on success or false on failure.
     */
    public function addPostFields(array $post_data) {}

    /**
     * @param $request_body_data [optional]
     */
    public function setBody($request_body_data) {}

    #[Pure]
    public function getBody() {}

    /**
     * @param $request_body_data
     */
    public function addBody($request_body_data) {}

    /**
     * (PECL pecl_http 0.14.0-1.4.1)<br/>
     * Set raw post data
     * @link https://php.net/manual/en/function.httprequest-setrawpostdata.php
     * @param string $raw_post_data [optional] <p>
     * raw post data
     * </p>
     * @return bool true on success or false on failure.
     */
    public function setRawPostData($raw_post_data = null) {}

    /**
     * (PECL pecl_http 0.14.0-1.4.1)<br/>
     * Get raw post data
     * @link https://php.net/manual/en/function.httprequest-getrawpostdata.php
     * @return string a string containing the currently set raw post data.
     */
    #[Pure]
    public function getRawPostData() {}

    /**
     * (PECL pecl_http 0.14.0-1.4.1)<br/>
     * Add raw post data
     * @link https://php.net/manual/en/function.httprequest-addrawpostdata.php
     * @param string $raw_post_data <p>
     * the raw post data to concatenate
     * </p>
     * @return bool true on success or false on failure.
     */
    public function addRawPostData($raw_post_data) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set post files
     * @link https://php.net/manual/en/function.httprequest-setpostfiles.php
     * @param array $post_files <p>
     * an array containing the files to post;
     * if empty, the post files will be unset
     * </p>
     * @return bool true on success or false on failure.
     */
    public function setPostFiles(array $post_files) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Add post file
     * @link https://php.net/manual/en/function.httprequest-addpostfile.php
     * @param string $name <p>
     * the form element name
     * </p>
     * @param string $file <p>
     * the path to the file
     * </p>
     * @param string $content_type [optional] <p>
     * the content type of the file
     * </p>
     * @return bool TRUE on success, or FALSE if the content type seems not to contain a
     * primary and a secondary content type part.
     */
    public function addPostFile($name, $file, $content_type = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get post files
     * @link https://php.net/manual/en/function.httprequest-getpostfiles.php
     * @return array an array containing currently set post files.
     */
    #[Pure]
    public function getPostFiles() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set put file
     * @link https://php.net/manual/en/function.httprequest-setputfile.php
     * @param string $file [optional] <p>
     * the path to the file to send;
     * if empty or omitted the put file will be unset
     * </p>
     * @return bool true on success or false on failure.
     */
    public function setPutFile($file = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get put file
     * @link https://php.net/manual/en/function.httprequest-getputfile.php
     * @return string a string containing the path to the currently set put file.
     */
    #[Pure]
    public function getPutFile() {}

    /**
     * (PECL pecl_http &gt;= 0.25.0)<br/>
     * Set put data
     * @link https://php.net/manual/en/function.httprequest-setputdata.php
     * @param string $put_data [optional] <p>
     * the data to upload
     * </p>
     * @return bool true on success or false on failure.
     */
    public function setPutData($put_data = null) {}

    /**
     * (PECL pecl_http &gt;= 0.25.0)<br/>
     * Get put data
     * @link https://php.net/manual/en/function.httprequest-getputdata.php
     * @return string a string containing the currently set PUT data.
     */
    #[Pure]
    public function getPutData() {}

    /**
     * (PECL pecl_http &gt;= 0.25.0)<br/>
     * Add put data
     * @link https://php.net/manual/en/function.httprequest-addputdata.php
     * @param string $put_data <p>
     * the data to concatenate
     * </p>
     * @return bool true on success or false on failure.
     */
    public function addPutData($put_data) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Send request
     * @link https://php.net/manual/en/function.httprequest-send.php
     * @return HttpMessage the received response as HttpMessage object.
     */
    public function send() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get response data
     * @link https://php.net/manual/en/function.httprequest-getresponsedata.php
     * @return array an associative array with the key "headers" containing an associative
     * array holding all response headers, as well as the key "body" containing a
     * string with the response body.
     */
    #[Pure]
    public function getResponseData() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get response header(s)
     * @link https://php.net/manual/en/function.httprequest-getresponseheader.php
     * @param string $name [optional] <p>
     * header to read; if empty, all response headers will be returned
     * </p>
     * @return mixed either a string with the value of the header matching name if requested,
     * FALSE on failure, or an associative array containing all response headers.
     */
    #[Pure]
    public function getResponseHeader($name = null) {}

    /**
     * (PECL pecl_http &gt;= 0.23.0)<br/>
     * Get response cookie(s)
     * @link https://php.net/manual/en/function.httprequest-getresponsecookies.php
     * @param int $flags [optional] <p>
     * http_parse_cookie flags
     * </p>
     * @param null|array $allowed_extras [optional] <p>
     * allowed keys treated as extra information instead of cookie names
     * </p>
     * @return stdClass[] an array of stdClass objects like http_parse_cookie would return.
     */
    #[Pure]
    public function getResponseCookies($flags = null, ?array $allowed_extras = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get response code
     * @link https://php.net/manual/en/function.httprequest-getresponsecode.php
     * @return int an int representing the response code.
     */
    #[Pure]
    public function getResponseCode() {}

    /**
     * (PECL pecl_http &gt;= 0.23.0)<br/>
     * Get response status
     * @link https://php.net/manual/en/function.httprequest-getresponsestatus.php
     * @return string a string containing the response status text.
     */
    #[Pure]
    public function getResponseStatus() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get response body
     * @link https://php.net/manual/en/function.httprequest-getresponsebody.php
     * @return string a string containing the response body.
     */
    #[Pure]
    public function getResponseBody() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get response info
     * @link https://php.net/manual/en/function.httprequest-getresponseinfo.php
     * @param string $name [optional] <p>
     * the info to read; if empty or omitted, an associative array containing
     * all available info will be returned
     * </p>
     * @return mixed either a scalar containing the value of the info matching name if
     * requested, FALSE on failure, or an associative array containing all
     * available info.
     */
    #[Pure]
    public function getResponseInfo($name = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get response message
     * @link https://php.net/manual/en/function.httprequest-getresponsemessage.php
     * @return HttpMessage an HttpMessage object of the response.
     */
    #[Pure]
    public function getResponseMessage() {}

    /**
     * (PECL pecl_http &gt;= 0.21.0)<br/>
     * Get raw response message
     * @link https://php.net/manual/en/function.httprequest-getrawresponsemessage.php
     * @return string the complete web server response, including the headers in a form of a string.
     */
    #[Pure]
    public function getRawResponseMessage() {}

    /**
     * (PECL pecl_http &gt;= 0.11.0)<br/>
     * Get request message
     * @link https://php.net/manual/en/function.httprequest-getrequestmessage.php
     * @return HttpMessage an HttpMessage object representing the sent request.
     */
    #[Pure]
    public function getRequestMessage() {}

    /**
     * (PECL pecl_http &gt;= 0.21.0)<br/>
     * Get raw request message
     * @link https://php.net/manual/en/function.httprequest-getrawrequestmessage.php
     * @return string an HttpMessage in a form of a string.
     */
    #[Pure]
    public function getRawRequestMessage() {}

    /**
     * (PECL pecl_http &gt;= 0.15.0)<br/>
     * Get history
     * @link https://php.net/manual/en/function.httprequest-gethistory.php
     * @return HttpMessage an HttpMessage object representing the complete request/response history.
     */
    #[Pure]
    public function getHistory() {}

    /**
     * (PECL pecl_http &gt;= 0.15.0)<br/>
     * Clear history
     * @link https://php.net/manual/en/function.httprequest-clearhistory.php
     * @return void
     */
    public function clearHistory() {}

    /**
     * @param $url [optional]
     * @param $method [optional]
     * @param $options [optional]
     * @param $class_name [optional]
     */
    public static function factory($url, $method, $options, $class_name) {}

    /**
     * @param $url
     * @param $options [optional]
     * @param &$info [optional]
     */
    public static function get($url, $options, &$info) {}

    /**
     * @param $url
     * @param $options [optional]
     * @param &$info [optional]
     */
    public static function head($url, $options, &$info) {}

    /**
     * @param $url
     * @param $data
     * @param $options [optional]
     * @param &$info [optional]
     */
    public static function postData($url, $data, $options, &$info) {}

    /**
     * @param $url
     * @param $data
     * @param $options [optional]
     * @param &$info [optional]
     */
    public static function postFields($url, $data, $options, &$info) {}

    /**
     * @param $url
     * @param $data
     * @param $options [optional]
     * @param &$info [optional]
     */
    public static function putData($url, $data, $options, &$info) {}

    /**
     * @param $url
     * @param $file
     * @param $options [optional]
     * @param &$info [optional]
     */
    public static function putFile($url, $file, $options, &$info) {}

    /**
     * @param $url
     * @param $stream
     * @param $options [optional]
     * @param &$info [optional]
     */
    public static function putStream($url, $stream, $options, &$info) {}

    /**
     * @param $method_name
     */
    public static function methodRegister($method_name) {}

    /**
     * @param $method
     */
    public static function methodUnregister($method) {}

    /**
     * @param $method_id
     */
    public static function methodName($method_id) {}

    /**
     * @param $method
     */
    public static function methodExists($method) {}

    /**
     * @param $fields
     * @param $files
     */
    public static function encodeBody($fields, $files) {}
}

class HttpRequestDataShare implements Countable
{
    private static $instance;
    public $cookie;
    public $dns;
    public $ssl;
    public $connect;

    public function __destruct() {}

    public function count() {}

    /**
     * @param HttpRequest $request
     */
    public function attach(HttpRequest $request) {}

    /**
     * @param HttpRequest $request
     */
    public function detach(HttpRequest $request) {}

    public function reset() {}

    /**
     * @param $global [optional]
     * @param $class_name [optional]
     */
    public static function factory($global, $class_name) {}

    /**
     * @param $global [optional]
     */
    public static function singleton($global) {}
}

/**
 * @link https://php.net/manual/en/class.httprequestpool.php
 */
class HttpRequestPool implements Countable, Iterator
{
    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * HttpRequestPool constructor
     * @link https://php.net/manual/en/function.httprequestpool-construct.php
     * @param null|HttpRequest $request [optional] <p>
     * HttpRequest object to attach
     * </p>
     */
    public function __construct(?HttpRequest $request = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * HttpRequestPool destructor
     * @link https://php.net/manual/en/function.httprequestpool-destruct.php
     * @return void
     */
    public function __destruct() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Attach HttpRequest
     * @link https://php.net/manual/en/function.httprequestpool-attach.php
     * @param HttpRequest $request <p>
     * an HttpRequest object not already attached to any HttpRequestPool object
     * </p>
     * @return bool true on success or false on failure.
     */
    public function attach(HttpRequest $request) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Detach HttpRequest
     * @link https://php.net/manual/en/function.httprequestpool-detach.php
     * @param HttpRequest $request <p>
     * an HttpRequest object attached to this HttpRequestPool object
     * </p>
     * @return bool true on success or false on failure.
     */
    public function detach(HttpRequest $request) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Send all requests
     * @link https://php.net/manual/en/function.httprequestpool-send.php
     * @return bool true on success or false on failure.
     */
    public function send() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Reset request pool
     * @link https://php.net/manual/en/function.httprequestpool-reset.php
     * @return void
     */
    public function reset() {}

    /**
     * (PECL pecl_http &gt;= 0.15.0)<br/>
     * Perform socket actions
     * @link https://php.net/manual/en/function.httprequestpool-socketperform.php
     * @return bool TRUE until each request has finished its transaction.
     */
    protected function socketPerform() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Perform socket select
     * @link https://php.net/manual/en/function.httprequestpool-socketselect.php
     * @return bool true on success or false on failure.
     */
    protected function socketSelect() {}

    public function valid() {}

    public function current() {}

    public function key() {}

    public function next() {}

    public function rewind() {}

    public function count() {}

    /**
     * (PECL pecl_http &gt;= 0.16.0)<br/>
     * Get attached requests
     * @link https://php.net/manual/en/function.httprequestpool-getattachedrequests.php
     * @return array an array containing all currently attached HttpRequest objects.
     */
    #[Pure]
    public function getAttachedRequests() {}

    /**
     * (PECL pecl_http &gt;= 0.16.0)<br/>
     * Get finished requests
     * @link https://php.net/manual/en/function.httprequestpool-getfinishedrequests.php
     * @return array an array containing all attached HttpRequest objects that already have finished their work.
     */
    #[Pure]
    public function getFinishedRequests() {}

    /**
     * @param $enable [optional]
     */
    public function enablePipelining($enable) {}

    /**
     * @param $enable [optional]
     */
    public function enableEvents($enable) {}
}

/**
 * @link https://php.net/manual/en/class.httpresponse.php
 */
class HttpResponse
{
    public const REDIRECT = 0;
    public const REDIRECT_PERM = 301;
    public const REDIRECT_FOUND = 302;
    public const REDIRECT_POST = 303;
    public const REDIRECT_PROXY = 305;
    public const REDIRECT_TEMP = 307;
    private static $sent;
    private static $catch;
    private static $mode;
    private static $stream;
    private static $file;
    private static $data;
    protected static $cache;
    protected static $gzip;
    protected static $eTag;
    protected static $lastModified;
    protected static $cacheControl;
    protected static $contentType;
    protected static $contentDisposition;
    protected static $bufferSize;
    protected static $throttleDelay;

    /**
     * (PECL pecl_http &gt;= 0.12.0)<br/>
     * Set header
     * @link https://php.net/manual/en/function.httpresponse-setheader.php
     * @param string $name <p>
     * the name of the header
     * </p>
     * @param mixed $value [optional] <p>
     * the value of the header;
     * if not set, no header with this name will be sent
     * </p>
     * @param bool $replace [optional] <p>
     * whether an existing header should be replaced
     * </p>
     * @return bool true on success or false on failure.
     */
    public static function setHeader($name, $value = null, $replace = null) {}

    /**
     * (PECL pecl_http &gt;= 0.12.0)<br/>
     * Get header
     * @link https://php.net/manual/en/function.httpresponse-getheader.php
     * @param string $name [optional] <p>
     * specifies the name of the header to read;
     * if empty or omitted, an associative array with all headers will be returned
     * </p>
     * @return mixed either a string containing the value of the header matching name,
     * false on failure, or an associative array with all headers.
     */
    public static function getHeader($name = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set ETag
     * @link https://php.net/manual/en/function.httpresponse-setetag.php
     * @param string $etag <p>
     * unquoted string as parameter containing the ETag
     * </p>
     * @return bool true on success or false on failure.
     */
    public static function setETag($etag) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get ETag
     * @link https://php.net/manual/en/function.httpresponse-getetag.php
     * @return string the calculated or previously set ETag as unquoted string.
     */
    public static function getETag() {}

    /**
     * (PECL pecl_http &gt;= 0.12.0)<br/>
     * Set last modified
     * @link https://php.net/manual/en/function.httpresponse-setlastmodified.php
     * @param int $timestamp <p>
     * Unix timestamp representing the last modification time of the sent entity
     * </p>
     * @return bool true on success or false on failure.
     */
    public static function setLastModified($timestamp) {}

    /**
     * (PECL pecl_http &gt;= 0.12.0)<br/>
     * Get last modified
     * @link https://php.net/manual/en/function.httpresponse-getlastmodified.php
     * @return int the calculated or previously set Unix timestamp.
     */
    public static function getLastModified() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set content disposition
     * @link https://php.net/manual/en/function.httpresponse-setcontentdisposition.php
     * @param string $filename <p>
     * the file name the &quot;Save as...&quot; dialog should display
     * </p>
     * @param bool $inline [optional] <p>
     * if set to true and the user agent knows how to handle the content type,
     * it will probably not cause the popup window to be shown
     * </p>
     * @return bool true on success or false on failure.
     */
    public static function setContentDisposition($filename, $inline = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get content disposition
     * @link https://php.net/manual/en/function.httpresponse-getcontentdisposition.php
     * @return string the current content disposition as string like sent in a header.
     */
    public static function getContentDisposition() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set content type
     * @link https://php.net/manual/en/function.httpresponse-setcontenttype.php
     * @param string $content_type <p>
     * the content type of the sent entity (primary/secondary)
     * </p>
     * @return bool true on success, or false if the content type does not seem to
     * contain a primary and secondary content type part.
     */
    public static function setContentType($content_type) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get content type
     * @link https://php.net/manual/en/function.httpresponse-getcontenttype.php
     * @return string the currently set content type as string.
     */
    public static function getContentType() {}

    /**
     * (PECL pecl_http &gt;= 0.13.0)<br/>
     * Guess content type
     * @link https://php.net/manual/en/function.httpresponse-guesscontenttype.php
     * @param string $magic_file <p>
     * specifies the magic.mime database to use
     * </p>
     * @param int $magic_mode [optional] <p>
     * flags for libmagic
     * </p>
     * @return string|false the guessed content type on success or false on failure.
     */
    public static function guessContentType($magic_file, $magic_mode = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set cache
     * @link https://php.net/manual/en/function.httpresponse-setcache.php
     * @param bool $cache <p>
     * whether caching should be attempted
     * </p>
     * @return bool true on success or false on failure.
     */
    public static function setCache($cache) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get cache
     * @link https://php.net/manual/en/function.httpresponse-getcache.php
     * @return bool true if caching should be attempted, else false.
     */
    public static function getCache() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set cache control
     * @link https://php.net/manual/en/function.httpresponse-setcachecontrol.php
     * @param string $control <p>
     * the primary cache control setting
     * </p>
     * @param int $max_age [optional] <p>
     * the max-age in seconds, suggesting how long the cache entry is valid on the client side
     * </p>
     * @param bool $must_revalidate [optional] <p>
     * whether the cached entity should be revalidated by the client for every request
     * </p>
     * @return bool true on success, or false if control does not match one of public, private or no-cache.
     */
    public static function setCacheControl($control, $max_age = null, $must_revalidate = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get cache control
     * @link https://php.net/manual/en/function.httpresponse-getcachecontrol.php
     * @return string the current cache control setting as a string like sent in a header.
     */
    public static function getCacheControl() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set gzip
     * @link https://php.net/manual/en/function.httpresponse-setgzip.php
     * @param bool $gzip <p>
     * whether GZip compression should be enabled
     * </p>
     * @return bool true on success or false on failure.
     */
    public static function setGzip($gzip) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get gzip
     * @link https://php.net/manual/en/function.httpresponse-getgzip.php
     * @return bool true if GZip compression is enabled, else false.
     */
    public static function getGzip() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set throttle delay
     * @link https://php.net/manual/en/function.httpresponse-setthrottledelay.php
     * @param float $seconds <p>
     * seconds to sleep after each chunk sent
     * </p>
     * @return bool true on success or false on failure.
     */
    public static function setThrottleDelay($seconds) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get throttle delay
     * @link https://php.net/manual/en/function.httpresponse-getthrottledelay.php
     * @return float a float representing the throttle delay in seconds.
     */
    public static function getThrottleDelay() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set buffer size
     * @link https://php.net/manual/en/function.httpresponse-setbuffersize.php
     * @param int $bytes <p>
     * the chunk size in bytes
     * </p>
     * @return bool true on success or false on failure.
     */
    public static function setBufferSize($bytes) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get buffer size
     * @link https://php.net/manual/en/function.httpresponse-getbuffersize.php
     * @return int an int representing the current buffer size in bytes.
     */
    public static function getBufferSize() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set data
     * @link https://php.net/manual/en/function.httpresponse-setdata.php
     * @param mixed $data <p>
     * data to send
     * </p>
     * @return bool true on success or false on failure.
     */
    public static function setData($data) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get data
     * @link https://php.net/manual/en/function.httpresponse-getdata.php
     * @return string a string containing the previously set data to send.
     */
    public static function getData() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set file
     * @link https://php.net/manual/en/function.httpresponse-setfile.php
     * @param string $file <p>
     * the path to the file to send
     * </p>
     * @return bool true on success or false on failure.
     */
    public static function setFile($file) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get file
     * @link https://php.net/manual/en/function.httpresponse-getfile.php
     * @return string the previously set path to the file to send as string.
     */
    public static function getFile() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Set stream
     * @link https://php.net/manual/en/function.httpresponse-setstream.php
     * @param resource $stream <p>
     * already opened stream from which the data to send will be read
     * </p>
     * @return bool true on success or false on failure.
     */
    public static function setStream($stream) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get Stream
     * @link https://php.net/manual/en/function.httpresponse-getstream.php
     * @return resource the previously set resource.
     */
    public static function getStream() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Send response
     * @link https://php.net/manual/en/function.httpresponse-send.php
     * @param bool $clean_ob [optional] <p>
     * whether to destroy all previously started output handlers and their buffers
     * </p>
     * @return bool true on success or false on failure.
     */
    public static function send($clean_ob = null) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Capture script output
     * @link https://php.net/manual/en/function.httpresponse-capture.php
     * @return void
     */
    public static function capture() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Redirect
     * @link https://php.net/manual/en/function.httpresponse-redirect.php
     * @param null|string $url [optional]
     * @param null|array $params [optional]
     * @param null|bool $session [optional]
     * @param null|int $status [optional]
     * @return void
     */
    public static function redirect($url = null, ?array $params = null, $session = null, $status = null) {}

    /**
     * (PECL pecl_http &gt;= 0.12.0)<br/>
     * Send HTTP response status
     * @link https://php.net/manual/en/function.httpresponse-status.php
     * @param int $status
     * @return bool
     */
    public static function status($status) {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get request headers
     * @link https://php.net/manual/en/function.httpresponse-getrequestheaders.php
     * @return array
     */
    public static function getRequestHeaders() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get request body
     * @link https://php.net/manual/en/function.httpresponse-getrequestbody.php
     * @return string
     */
    public static function getRequestBody() {}

    /**
     * (PECL pecl_http &gt;= 0.10.0)<br/>
     * Get request body stream
     * @link https://php.net/manual/en/function.httpresponse-getrequestbodystream.php
     * @return resource
     */
    public static function getRequestBodyStream() {}
}

class HttpUtil
{
    /**
     * @param $timestamp [optional]
     */
    public static function date($timestamp) {}

    /**
     * @param $url
     * @param $parts [optional]
     * @param $flags [optional]
     * @param &$composed [optional]
     */
    public static function buildUrl($url, $parts, $flags, &$composed) {}

    /**
     * @param $query
     * @param $prefix [optional]
     * @param $arg_sep [optional]
     */
    public static function buildStr($query, $prefix, $arg_sep) {}

    /**
     * @param $supported
     * @param &$result [optional]
     */
    public static function negotiateLanguage($supported, &$result) {}

    /**
     * @param $supported
     * @param &$result [optional]
     */
    public static function negotiateCharset($supported, &$result) {}

    /**
     * @param $supported
     * @param &$result [optional]
     */
    public static function negotiateContentType($supported, &$result) {}

    /**
     * @param $last_modified
     * @param $for_range [optional]
     */
    public static function matchModified($last_modified, $for_range) {}

    /**
     * @param $plain_etag
     * @param $for_range [optional]
     */
    public static function matchEtag($plain_etag, $for_range) {}

    /**
     * @param $header_name
     * @param $header_value
     * @param $case_sensitive [optional]
     */
    public static function matchRequestHeader($header_name, $header_value, $case_sensitive) {}

    /**
     * @param $message_string
     */
    public static function parseMessage($message_string) {}

    /**
     * @param $headers_string
     */
    public static function parseHeaders($headers_string) {}

    /**
     * @param $cookie_string
     */
    public static function parseCookie($cookie_string) {}

    /**
     * @param $cookie_array
     */
    public static function buildCookie($cookie_array) {}

    /**
     * @param $param_string
     * @param $flags [optional]
     */
    public static function parseParams($param_string, $flags) {}

    /**
     * @param $encoded_string
     */
    public static function chunkedDecode($encoded_string) {}

    /**
     * @param $plain
     * @param $flags [optional]
     */
    public static function deflate($plain, $flags) {}

    /**
     * @param $encoded
     */
    public static function inflate($encoded) {}

    /**
     * @param $feature [optional]
     */
    public static function support($feature) {}
}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Compose HTTP RFC compliant date
 * @link https://php.net/manual/en/function.http-date.php
 * @param int $timestamp [optional] <p>
 * Unix timestamp; current time if omitted
 * </p>
 * @return string the HTTP date as string.
 */
#[Pure]
function http_date($timestamp = null) {}

/**
 * (PECL pecl_http &gt;= 0.21.0)<br/>
 * Build an URL
 * @link https://php.net/manual/en/function.http-build-url.php
 * @param mixed $url [optional] <p>
 * (part(s) of) an URL in form of a string or associative array like parse_url returns
 * </p>
 * @param mixed $parts [optional] <p>
 * same as the first argument
 * </p>
 * @param null|int $flags [optional] <p>
 * a bitmask of binary or'ed HTTP_URL constants;
 * HTTP_URL_REPLACE is the default
 * </p>
 * @param null|array &$new_url [optional] <p>
 * if set, it will be filled with the parts of the composed url like parse_url would return
 * </p>
 * @return string|false the new URL as string on success or false on failure.
 */
function http_build_url($url = null, $parts = null, $flags = null, ?array &$new_url = null) {}

/**
 * (PECL pecl_http &gt;= 0.23.0)<br/>
 * Build query string
 * @link https://php.net/manual/en/function.http-build-str.php
 * @param array $query <p>
 * associative array of query string parameters
 * </p>
 * @param string $prefix [optional] <p>
 * top level prefix
 * </p>
 * @param string $arg_separator [optional] <p>
 * argument separator to use (by default the INI setting arg_separator.output will be used, or &quot;&amp;&quot; if neither is set
 * </p>
 * @return string|false the built query as string on success or false on failure.
 */
#[Pure]
function http_build_str(array $query, $prefix = null, $arg_separator = null) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Negotiate clients preferred language
 * @link https://php.net/manual/en/function.http-negotiate-language.php
 * @param array $supported <p>
 * array containing the supported languages as values
 * </p>
 * @param null|array &$result [optional] <p>
 * will be filled with an array containing the negotiation results
 * </p>
 * @return string the negotiated language or the default language (i.e. first array entry) if none match.
 */
function http_negotiate_language(array $supported, ?array &$result = null) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Negotiate clients preferred character set
 * @link https://php.net/manual/en/function.http-negotiate-charset.php
 * @param array $supported <p>
 * array containing the supported charsets as values
 * </p>
 * @param null|array &$result [optional] <p>
 * will be filled with an array containing the negotiation results
 * </p>
 * @return string the negotiated charset or the default charset (i.e. first array entry) if none match.
 */
function http_negotiate_charset(array $supported, ?array &$result = null) {}

/**
 * (PECL pecl_http &gt;= 0.19.0)<br/>
 * Negotiate clients preferred content type
 * @link https://php.net/manual/en/function.http-negotiate-content-type.php
 * @param array $supported <p>
 * array containing the supported content types as values
 * </p>
 * @param null|array &$result [optional] <p>
 * will be filled with an array containing the negotiation results
 * </p>
 * @return string the negotiated content type or the default content type (i.e. first array entry) if none match.
 */
function http_negotiate_content_type(array $supported, ?array &$result = null) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Issue HTTP redirect
 * @link https://php.net/manual/en/function.http-redirect.php
 * @param string $url [optional] <p>
 * the URL to redirect to
 * </p>
 * @param null|array $params [optional] <p>
 * associative array of query parameters
 * </p>
 * @param null|bool $session [optional] <p>
 * whether to append session information
 * </p>
 * @param null|int $status [optional] <p>
 * custom response status code
 * </p>
 * @return void|false returns false or exits with the specified redirection status code
 */
function http_redirect($url = null, ?array $params = null, $session = null, $status = null) {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * HTTP throttling
 * @link https://php.net/manual/en/function.http-throttle.php
 * @param float $sec [optional] <p>
 * seconds to sleep after each chunk sent
 * </p>
 * @param int $bytes [optional] <p>
 * the chunk size in bytes
 * </p>
 * @return void
 */
function http_throttle($sec = null, $bytes = null) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Send HTTP response status
 * @link https://php.net/manual/en/function.http-send-status.php
 * @param int $status <p>
 * HTTP status code (100-599)
 * </p>
 * @return bool true on success or false on failure.
 */
function http_send_status($status) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Send Last-Modified
 * @link https://php.net/manual/en/function.http-send-last-modified.php
 * @param int $timestamp [optional] <p>
 * a Unix timestamp, converted to a valid HTTP date;
 * if omitted, the current time will be sent
 * </p>
 * @return bool true on success or false on failure.
 */
function http_send_last_modified($timestamp = null) {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * Send Content-Type
 * @link https://php.net/manual/en/function.http-send-content-type.php
 * @param string $content_type [optional] <p>
 * the desired content type (primary/secondary)
 * </p>
 * @return bool true on success or false on failure.
 */
function http_send_content_type($content_type = null) {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * Send Content-Disposition
 * @link https://php.net/manual/en/function.http-send-content-disposition.php
 * @param string $filename <p>
 * the file name the &quot;Save as...&quot; dialog should display
 * </p>
 * @param bool $inline [optional] <p>
 * if set to true and the user agent knows how to handle the content type,
 * it will probably not cause the popup window to be shown
 * </p>
 * @return bool true on success or false on failure.
 */
function http_send_content_disposition($filename, $inline = null) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Match last modification
 * @link https://php.net/manual/en/function.http-match-modified.php
 * @param int $timestamp [optional] <p>
 * Unix timestamp; current time, if omitted
 * </p>
 * @param bool $for_range [optional] <p>
 * if set to true, the header usually used to validate HTTP ranges will be checked
 * </p>
 * @return bool true if timestamp represents an earlier date than the header, else false.
 */
#[Pure]
function http_match_modified($timestamp = null, $for_range = null) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Match ETag
 * @link https://php.net/manual/en/function.http-match-etag.php
 * @param string $etag <p>
 * the ETag to match
 * </p>
 * @param bool $for_range [optional] <p>
 * if set to true, the header usually used to validate HTTP ranges will be checked
 * </p>
 * @return bool true if ETag matches or the header contained the asterisk (&quot;*&quot;), else false.
 */
#[Pure]
function http_match_etag($etag, $for_range = null) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Caching by last modification
 * @link https://php.net/manual/en/function.http-cache-last-modified.php
 * @param int $timestamp_or_expires [optional] <p>
 * Unix timestamp
 * </p>
 * @return bool with 304 Not Modified if the entity is cached.
 * &see.http.configuration.force_exit;
 */
#[Pure]
function http_cache_last_modified($timestamp_or_expires = null) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Caching by ETag
 * @link https://php.net/manual/en/function.http-cache-etag.php
 * @param string $etag [optional] <p>
 * custom ETag
 * </p>
 * @return bool with 304 Not Modified if the entity is cached.
 * &see.http.configuration.force_exit;
 */
#[Pure]
function http_cache_etag($etag = null) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Send arbitrary data
 * @link https://php.net/manual/en/function.http-send-data.php
 * @param string $data <p>
 * data to send
 * </p>
 * @return bool true on success or false on failure.
 */
function http_send_data($data) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Send file
 * @link https://php.net/manual/en/function.http-send-file.php
 * @param string $file <p>
 * the file to send
 * </p>
 * @return bool true on success or false on failure.
 */
function http_send_file($file) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Send stream
 * @link https://php.net/manual/en/function.http-send-stream.php
 * @param resource $stream <p>
 * stream to read from (must be seekable)
 * </p>
 * @return bool true on success or false on failure.
 */
function http_send_stream($stream) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Decode chunked-encoded data
 * @link https://php.net/manual/en/function.http-chunked-decode.php
 * @param string $encoded <p>
 * chunked encoded string
 * </p>
 * @return string|false the decoded string on success or false on failure.
 */
#[Pure]
function http_chunked_decode($encoded) {}

/**
 * (PECL pecl_http &gt;= 0.12.0)<br/>
 * Parse HTTP messages
 * @link https://php.net/manual/en/function.http-parse-message.php
 * @param string $message <p>
 * string containing a single HTTP message or several consecutive HTTP messages
 * </p>
 * @return object a hierarchical object structure of the parsed messages.
 */
#[Pure]
function http_parse_message($message) {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * Parse HTTP headers
 * @link https://php.net/manual/en/function.http-parse-headers.php
 * @param string $header <p>
 * string containing HTTP headers
 * </p>
 * @return array|false an array on success or false on failure.
 */
#[Pure]
function http_parse_headers($header) {}

/**
 * (PECL pecl_http &gt;= 0.20.0)<br/>
 * Parse HTTP cookie
 * @link https://php.net/manual/en/function.http-parse-cookie.php
 * @param string $cookie <p>
 * string containing the value of a Set-Cookie response header
 * </p>
 * @param int $flags [optional] <p>
 * parse flags (HTTP_COOKIE_PARSE_RAW)
 * </p>
 * @param null|array $allowed_extras [optional] <p>
 * array containing recognized extra keys;
 * by default all unknown keys will be treated as cookie names
 * </p>
 * @return stdClass|false a stdClass object on success or false on failure.
 */
#[Pure]
function http_parse_cookie($cookie, $flags = null, ?array $allowed_extras = null) {}

/**
 * (PECL pecl_http &gt;= 1.2.0)<br/>
 * Build cookie string
 * @link https://php.net/manual/en/function.http-build-cookie.php
 * @param array $cookie <p>
 * a cookie list like returned from http_parse_cookie
 * </p>
 * @return string the cookie(s) as string.
 */
#[Pure]
function http_build_cookie(array $cookie) {}

/**
 * (PECL pecl_http &gt;= 1.0.0)<br/>
 * Parse parameter list
 * @link https://php.net/manual/en/function.http-parse-params.php
 * @param string $param <p>
 * Parameters
 * </p>
 * @param int $flags [optional] <p>
 * Parse flags
 * </p>
 * @return stdClass parameter list as stdClass object.
 */
#[Pure]
function http_parse_params($param, $flags = null) {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * Get request headers as array
 * @link https://php.net/manual/en/function.http-get-request-headers.php
 * @return array an associative array of incoming request headers.
 */
#[Pure]
function http_get_request_headers() {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * Get request body as string
 * @link https://php.net/manual/en/function.http-get-request-body.php
 * @return string|null the raw request body as string on success or NULL on failure.
 */
#[Pure]
function http_get_request_body() {}

/**
 * (PECL pecl_http &gt;= 0.22.0)<br/>
 * Get request body as stream
 * @link https://php.net/manual/en/function.http-get-request-body-stream.php
 * @return resource|null the raw request body as stream on success or NULL on failure.
 */
#[Pure]
function http_get_request_body_stream() {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * Match any header
 * @link https://php.net/manual/en/function.http-match-request-header.php
 * @param string $header <p>
 * the header name (case-insensitive)
 * </p>
 * @param string $value <p>
 * the header value that should be compared
 * </p>
 * @param bool $match_case [optional] <p>
 * whether the value should be compared case sensitively
 * </p>
 * @return bool true if header value matches, else false.
 */
#[Pure]
function http_match_request_header($header, $value, $match_case = null) {}

/**
 * (PECL pecl_http &gt;= 1.5.0)<br/>
 * Stat persistent handles
 * @link https://php.net/manual/en/function.http-persistent-handles-count.php
 * @return stdClass|false persistent handles statistics as stdClass object on success or false on failure.
 */
function http_persistent_handles_count() {}

/**
 * (PECL pecl_http &gt;= 1.5.0)<br/>
 * Clean up persistent handles
 * @link https://php.net/manual/en/function.http-persistent-handles-clean.php
 * @param string $ident [optional]
 * @return string
 */
function http_persistent_handles_clean($ident = null) {}

/**
 * (PECL pecl_http &gt;= 1.5.0)<br/>
 * Get/set ident of persistent handles
 * @link https://php.net/manual/en/function.http-persistent-handles-ident.php
 * @param string $ident <p>
 * the identification string
 * </p>
 * @return string|false the prior ident as string on success or false on failure.
 */
function http_persistent_handles_ident($ident) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Perform GET request
 * @link https://php.net/manual/en/function.http-get.php
 * @param string $url <p>
 * URL
 * </p>
 * @param null|array $options [optional] <p>
 * </p>
 * @param null|array &$info [optional] <p>
 * Will be filled with request/response information
 * </p>
 * @return string
 */
function http_get($url, ?array $options = null, ?array &$info = null) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Perform HEAD request
 * @link https://php.net/manual/en/function.http-head.php
 * @param string $url [optional] <p>
 * URL
 * </p>
 * @param null|array $options [optional] <p>
 * </p>
 * @param null|array &$info [optional] <p>
 * </p>
 * @return string
 */
function http_head($url = null, ?array $options = null, ?array &$info = null) {}

/**
 * (PECL pecl_http &gt;= 0.1.0)<br/>
 * Perform POST request with pre-encoded data
 * @link https://php.net/manual/en/function.http-post-data.php
 * @param string $url <p>
 * URL
 * </p>
 * @param string $data [optional] <p>
 * String containing the pre-encoded post data
 * </p>
 * @param null|array $options [optional] <p>
 * </p>
 * @param null|array &$info [optional] <p>
 * </p>
 * @return string
 */
function http_post_data($url, $data = null, ?array $options = null, ?array &$info = null) {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * Perform POST request with data to be encoded
 * @link https://php.net/manual/en/function.http-post-fields.php
 * @param string $url <p>
 * URL
 * </p>
 * @param null|array $data [optional] <p>
 * Associative array of POST values
 * </p>
 * @param null|array $files [optional] <p>
 * Array of files to post
 * </p>
 * @param null|array $options [optional] <p>
 * </p>
 * @param null|array &$info [optional] <p>
 * </p>
 * @return string
 */
function http_post_fields($url, ?array $data = null, ?array $files = null, ?array $options = null, ?array &$info = null) {}

/**
 * (PECL pecl_http &gt;= 0.25.0)<br/>
 * Perform PUT request with data
 * @link https://php.net/manual/en/function.http-put-data.php
 * @param string $url <p>
 * URL
 * </p>
 * @param null|string $data [optional] <p>
 * PUT request body
 * </p>
 * @param null|array $options [optional] <p>
 * </p>
 * @param null|array &$info [optional] <p>
 * </p>
 * @return string
 */
function http_put_data($url, $data = null, ?array $options = null, ?array &$info = null) {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * Perform PUT request with file
 * @link https://php.net/manual/en/function.http-put-file.php
 * @param string $url <p>
 * URL
 * </p>
 * @param null|string $file [optional] <p>
 * The file to put
 * </p>
 * @param null|array $options [optional] <p>
 * </p>
 * @param null|array &$info [optional] <p>
 * </p>
 * @return string
 */
function http_put_file($url, $file = null, ?array $options = null, ?array &$info = null) {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * Perform PUT request with stream
 * @link https://php.net/manual/en/function.http-put-stream.php
 * @param string $url <p>
 * URL
 * </p>
 * @param null|resource $stream [optional] <p>
 * The stream to read the PUT request body from
 * </p>
 * @param null|array $options [optional] <p>
 * </p>
 * @param null|array &$info [optional] <p>
 * </p>
 * @return string
 */
function http_put_stream($url, $stream = null, ?array $options = null, ?array &$info = null) {}

/**
 * (PECL pecl_http &gt;= 1.0.0)<br/>
 * Perform custom request
 * @link https://php.net/manual/en/function.http-request.php
 * @param int $method <p>
 * Request method
 * </p>
 * @param null|string $url [optional] <p>
 * URL
 * </p>
 * @param null|string $body [optional] <p>
 * Request body
 * </p>
 * @param null|array $options [optional] <p>
 * </p>
 * @param null|array &$info [optional] <p>
 * </p>
 * @return string
 */
function http_request($method, $url = null, $body = null, ?array $options = null, ?array &$info = null) {}

/**
 * (PECL pecl_http &gt;= 1.0.0)<br/>
 * Encode request body
 * @link https://php.net/manual/en/function.http-request-body-encode.php
 * @param array $fields <p>
 * POST fields
 * </p>
 * @param array $files <p>
 * POST files
 * </p>
 * @return string|false encoded string on success or false on failure.
 */
#[Pure]
function http_request_body_encode(array $fields, array $files) {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * Register request method
 * @link https://php.net/manual/en/function.http-request-method-register.php
 * @param string $method <p>
 * the request method name to register
 * </p>
 * @return int|false the ID of the request method on success or false on failure.
 */
function http_request_method_register($method) {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * Unregister request method
 * @link https://php.net/manual/en/function.http-request-method-unregister.php
 * @param mixed $method <p>
 * The request method name or ID
 * </p>
 * @return bool true on success or false on failure.
 */
function http_request_method_unregister($method) {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * Check whether request method exists
 * @link https://php.net/manual/en/function.http-request-method-exists.php
 * @param mixed $method <p>
 * request method name or ID
 * </p>
 * @return bool true if the request method is known, else false.
 */
#[Pure]
function http_request_method_exists($method) {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * Get request method name
 * @link https://php.net/manual/en/function.http-request-method-name.php
 * @param int $method <p>
 * request method ID
 * </p>
 * @return string|false the request method name as string on success or false on failure.
 */
#[Pure]
function http_request_method_name($method) {}

/**
 * (PECL pecl_http &gt;= 0.10.0)<br/>
 * ETag output handler
 * @link https://php.net/manual/en/function.ob-etaghandler.php
 * @param string $data
 * @param int $mode
 * @return string
 */
#[Pure]
function ob_etaghandler($data, $mode) {}

/**
 * (PECL pecl_http &gt;= 0.15.0)<br/>
 * Deflate data
 * @link https://php.net/manual/en/function.http-deflate.php
 * @param string $data <p>
 * String containing the data that should be encoded
 * </p>
 * @param int $flags [optional] <p>
 * deflate options
 * </p>
 * @return string|null the encoded string on success, or NULL on failure.
 */
#[Pure]
function http_deflate($data, $flags = null) {}

/**
 * (PECL pecl_http &gt;= 0.15.0)<br/>
 * Inflate data
 * @link https://php.net/manual/en/function.http-inflate.php
 * @param string $data <p>
 * string containing the compressed data
 * </p>
 * @return string|null the decoded string on success, or NULL on failure.
 */
#[Pure]
function http_inflate($data) {}

/**
 * (PECL pecl_http &gt;= 0.21.0)<br/>
 * Deflate output handler
 * @link https://php.net/manual/en/function.ob-deflatehandler.php
 * @param string $data
 * @param int $mode
 * @return string
 */
function ob_deflatehandler($data, $mode) {}

/**
 * (PECL pecl_http &gt;= 0.21.0)<br/>
 * Inflate output handler
 * @link https://php.net/manual/en/function.ob-inflatehandler.php
 * @param string $data
 * @param int $mode
 * @return string
 */
function ob_inflatehandler($data, $mode) {}

/**
 * (PECL pecl_http &gt;= 0.15.0)<br/>
 * Check built-in HTTP support
 * @link https://php.net/manual/en/function.http-support.php
 * @param int $feature [optional] <p>
 * feature to probe for
 * </p>
 * @return int integer, whether requested feature is supported,
 * or a bitmask with all supported features if feature was omitted.
 */
#[Pure]
function http_support($feature = null) {}

/**
 * don't urldecode values
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_COOKIE_PARSE_RAW', 1);

/**
 * whether &quot;secure&quot; was found in the cookie's parameters list
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_COOKIE_SECURE', 16);

/**
 * whether &quot;httpOnly&quot; was found in the cookie's parameter list
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_COOKIE_HTTPONLY', 32);
define('HTTP_DEFLATE_LEVEL_DEF', 0);
define('HTTP_DEFLATE_LEVEL_MIN', 1);
define('HTTP_DEFLATE_LEVEL_MAX', 9);
define('HTTP_DEFLATE_TYPE_ZLIB', 0);
define('HTTP_DEFLATE_TYPE_GZIP', 16);
define('HTTP_DEFLATE_TYPE_RAW', 32);
define('HTTP_DEFLATE_STRATEGY_DEF', 0);
define('HTTP_DEFLATE_STRATEGY_FILT', 256);
define('HTTP_DEFLATE_STRATEGY_HUFF', 512);
define('HTTP_DEFLATE_STRATEGY_RLE', 768);
define('HTTP_DEFLATE_STRATEGY_FIXED', 1024);

/**
 * don't flush
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_ENCODING_STREAM_FLUSH_NONE', 0);

/**
 * synchronized flush only
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_ENCODING_STREAM_FLUSH_SYNC', 1048576);

/**
 * full data flush
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_ENCODING_STREAM_FLUSH_FULL', 2097152);

/**
 * use &quot;basic&quot; authentication
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_AUTH_BASIC', 1);

/**
 * use &quot;digest&quot; authentication
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_AUTH_DIGEST', 2);

/**
 * use &quot;NTLM&quot; authentication
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_AUTH_NTLM', 8);

/**
 * use &quot;GSS-NEGOTIATE&quot; authentication
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_AUTH_GSSNEG', 4);

/**
 * try any authentication scheme
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_AUTH_ANY', -1);
define('HTTP_VERSION_NONE', 0);

/**
 * HTTP version 1.0
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_VERSION_1_0', 1);

/**
 * HTTP version 1.1
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_VERSION_1_1', 2);

/**
 * no specific HTTP protocol version
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_VERSION_ANY', 0);

/**
 * use TLSv1 only
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_SSL_VERSION_TLSv1', 1);

/**
 * use SSLv2 only
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_SSL_VERSION_SSLv2', 2);

/**
 * use SSLv3 only
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_SSL_VERSION_SSLv3', 3);

/**
 * no specific SSL protocol version
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_SSL_VERSION_ANY', 0);

/**
 * use IPv4 only for name lookups
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_IPRESOLVE_V4', 1);

/**
 * use IPv6 only for name lookups
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_IPRESOLVE_V6', 2);

/**
 * use any IP mechanism only for name lookups
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_IPRESOLVE_ANY', 0);

/**
 * the proxy is a SOCKS4 type proxy
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_PROXY_SOCKS4', 4);

/**
 * the proxy is a SOCKS5 type proxy
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_PROXY_SOCKS5', 5);

/**
 * standard HTTP proxy
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_PROXY_HTTP', 0);
define('HTTP_METH_GET', 1);
define('HTTP_METH_HEAD', 2);
define('HTTP_METH_POST', 3);
define('HTTP_METH_PUT', 4);
define('HTTP_METH_DELETE', 5);
define('HTTP_METH_OPTIONS', 6);
define('HTTP_METH_TRACE', 7);
define('HTTP_METH_CONNECT', 8);
define('HTTP_METH_PROPFIND', 9);
define('HTTP_METH_PROPPATCH', 10);
define('HTTP_METH_MKCOL', 11);
define('HTTP_METH_COPY', 12);
define('HTTP_METH_MOVE', 13);
define('HTTP_METH_LOCK', 14);
define('HTTP_METH_UNLOCK', 15);
define('HTTP_METH_VERSION_CONTROL', 16);
define('HTTP_METH_REPORT', 17);
define('HTTP_METH_CHECKOUT', 18);
define('HTTP_METH_CHECKIN', 19);
define('HTTP_METH_UNCHECKOUT', 20);
define('HTTP_METH_MKWORKSPACE', 21);
define('HTTP_METH_UPDATE', 22);
define('HTTP_METH_LABEL', 23);
define('HTTP_METH_MERGE', 24);
define('HTTP_METH_BASELINE_CONTROL', 25);
define('HTTP_METH_MKACTIVITY', 26);
define('HTTP_METH_ACL', 27);

/**
 * guess applicable redirect method
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_REDIRECT', 0);

/**
 * permanent redirect (301 Moved permanently)
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_REDIRECT_PERM', 301);

/**
 * standard redirect (302 Found)
 * RFC 1945 and RFC 2068 specify that the client is not allowed
 * to change the method on the redirected request. However, most
 * existing user agent implementations treat 302 as if it were a 303
 * response, performing a GET on the Location field-value regardless
 * of the original request method. The status codes 303 and 307 have
 * been added for servers that wish to make unambiguously clear which
 * kind of reaction is expected of the client.
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_REDIRECT_FOUND', 302);

/**
 * redirect applicable to POST requests (303 See other)
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_REDIRECT_POST', 303);

/**
 * proxy redirect (305 Use proxy)
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_REDIRECT_PROXY', 305);

/**
 * temporary redirect (307 Temporary Redirect)
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_REDIRECT_TEMP', 307);

/**
 * querying for this constant will always return true
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_SUPPORT', 1);

/**
 * whether support to issue HTTP requests is given, ie. libcurl support was compiled in
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_SUPPORT_REQUESTS', 2);

/**
 * whether support to guess the Content-Type of HTTP messages is given, ie. libmagic support was compiled in
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_SUPPORT_MAGICMIME', 4);

/**
 * whether support for zlib encodings is given, ie. libz support was compiled in
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_SUPPORT_ENCODINGS', 8);

/**
 * whether support to issue HTTP requests over SSL is given, ie. linked libcurl was built with SSL support
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_SUPPORT_SSLREQUESTS', 32);
define('HTTP_SUPPORT_EVENTS', 128);

/**
 * allow commands additionally to semicolons as separator
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_PARAMS_ALLOW_COMMA', 1);

/**
 * continue parsing after an error occurred
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_PARAMS_ALLOW_FAILURE', 2);

/**
 * raise PHP warnings on parse errors
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_PARAMS_RAISE_ERROR', 4);

/**
 * all three values above, bitwise or'ed
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_PARAMS_DEFAULT', 7);

/**
 * replace every part of the first URL when there's one of the second URL
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_URL_REPLACE', 0);

/**
 * join relative paths
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_URL_JOIN_PATH', 1);

/**
 * join query strings
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_URL_JOIN_QUERY', 2);

/**
 * strip any user authentication information
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_URL_STRIP_USER', 4);

/**
 * strip any password authentication information
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_URL_STRIP_PASS', 8);

/**
 * strip any authentication information
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_URL_STRIP_AUTH', 12);

/**
 * strip explicit port numbers
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_URL_STRIP_PORT', 32);

/**
 * strip complete path
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_URL_STRIP_PATH', 64);

/**
 * strip query string
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_URL_STRIP_QUERY', 128);

/**
 * strip any fragments (#identifier)
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_URL_STRIP_FRAGMENT', 256);

/**
 * strip anything but scheme and host
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_URL_STRIP_ALL', 492);
define('HTTP_URL_FROM_ENV', 4096);

/**
 * runtime error
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_E_RUNTIME', 1);

/**
 * an invalid parameter was passed
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_E_INVALID_PARAM', 2);

/**
 * header() or similar operation failed
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_E_HEADER', 3);

/**
 * HTTP header parse error
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_E_MALFORMED_HEADERS', 4);

/**
 * unknown/invalid request method
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_E_REQUEST_METHOD', 5);

/**
 * with operation incompatible message type
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_E_MESSAGE_TYPE', 6);

/**
 * encoding/decoding error
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_E_ENCODING', 7);

/**
 * request failure
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_E_REQUEST', 8);

/**
 * request pool failure
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_E_REQUEST_POOL', 9);

/**
 * socket exception
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_E_SOCKET', 10);

/**
 * response failure
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_E_RESPONSE', 11);

/**
 * invalid URL
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_E_URL', 12);

/**
 * querystring operation failure
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_E_QUERYSTRING', 13);

/**
 * the message is of no specific type
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_MSG_NONE', 0);

/**
 * request style message
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_MSG_REQUEST', 1);

/**
 * response style message
 * @link https://php.net/manual/en/http.constants.php
 */
define('HTTP_MSG_RESPONSE', 2);
define('HTTP_QUERYSTRING_TYPE_BOOL', 3);
define('HTTP_QUERYSTRING_TYPE_INT', 1);
define('HTTP_QUERYSTRING_TYPE_FLOAT', 2);
define('HTTP_QUERYSTRING_TYPE_STRING', 6);
define('HTTP_QUERYSTRING_TYPE_ARRAY', 4);
define('HTTP_QUERYSTRING_TYPE_OBJECT', 5);
