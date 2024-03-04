<?php

/**
 * The constants below are defined by this extension,
 * and will only be available when the extension has either been compiled into PHP or dynamically loaded at runtime.
 * @link https://secure.php.net/manual/en/yar.constants.php
 */
define('YAR_VERSION', '2.2.0');
define('YAR_CLIENT_PROTOCOL_HTTP', 1);
define('YAR_OPT_PACKAGER', 1);
define('YAR_OPT_TIMEOUT', 4);
define('YAR_OPT_CONNECT_TIMEOUT', 8);
define('YAR_OPT_PERSISTENT', 2);
/**
 * @since 2.0.4
 */
define('YAR_OPT_HEADER', 16);
define('YAR_PACKAGER_PHP', 'PHP');
define('YAR_PACKAGER_JSON', 'JSON');
define('YAR_ERR_OUTPUT', 8);
define('YAR_ERR_OKEY', 0);
define('YAR_ERR_TRANSPORT', 16);
define('YAR_ERR_REQUEST', 4);
define('YAR_ERR_PROTOCOL', 2);
define('YAR_ERR_PACKAGER', 1);
define('YAR_ERR_EXCEPTION', 64);

define('YAR_CLIENT_PROTOCOL_TCP', 2);
define('YAR_CLIENT_PROTOCOL_UNIX', 4);

define('YAR_OPT_RESOLVE', 32);
/**
 * Class Yar_Server
 * Date 2018/6/9 下午3:02
 * @author weizhimiao001@lianjia.com
 * @link https://secure.php.net/manual/en/class.yar-server.php
 */
class Yar_Server
{
    protected $_executor;

    /**
     * Register a server
     * Set up a Yar HTTP RPC Server, All the public methods of $obj will be register as a RPC service.
     *
     * Yar_Server constructor.
     * @param object $obj An Object, all public methods of its will be registered as RPC services.
     * @link https://secure.php.net/manual/en/yar-server.construct.php
     */
    final public function __construct($obj, $protocol = null) {}

    /**
     * Start RPC Server
     * Start a RPC HTTP server, and ready for accpet RPC requests.
     * Note:
     *  Usual RPC calls will be issued as HTTP POST requests.
     *  If a HTTP GET request is issued to the uri,
     *  the service information (commented section above) will be printed on the page
     * @return bool
     * @link https://secure.php.net/manual/en/yar-server.handle.php
     */
    public function handle() {}
}

class Yar_Client
{
    protected $_protocol;
    protected $_uri;
    protected $_options;
    protected $_running;

    /**
     * Call service
     * Issue a call to remote RPC method.
     *
     * @param string $method Remote RPC method name.
     * @param array $parameters Parameters.
     * @link https://secure.php.net/manual/en/yar-client.call.php
     */
    public function __call($method, $parameters) {}

    /**
     * Create a client
     * Yar_Client constructor.
     * @param string $url Yar Server URL.
     * @link https://secure.php.net/manual/en/yar-client.construct.php
     */
    final public function __construct($url, $async = null) {}

    public function call($method, $parameters) {}

    /**
     * Set calling contexts
     *
     * @param int $type it can be:
     * - YAR_OPT_PACKAGER,
     * - YAR_OPT_PERSISTENT (Need server support),
     * - YAR_OPT_TIMEOUT,
     * - YAR_OPT_CONNECT_TIMEOUT
     * - YAR_OPT_HEADER (Since 2.0.4)
     * @param $value
     * @return static|false Returns $this on success or FALSE on failure.
     * @link https://secure.php.net/manual/en/yar-client.setopt.php
     */
    public function setOpt($type, $value) {}

    public function getOpt($type) {}
}

class Yar_Concurrent_Client
{
    protected static $_callstack;
    protected static $_callback;
    protected static $_error_callback;
    protected static $_start;

    /**
     * Register a concurrent call
     * @param string $uri The RPC server URI(http, tcp)
     * @param string $method Service name(aka the method name)
     * @param array $parameters Parameters
     * @param callable $callback A function callback, which will be called while the response return.
     * @param callable $error_callback A function callback, which will be called while the response return.
     * @param array $options
     * @return int An unique id, can be used to identified which call it is.
     * @link https://secure.php.net/manual/en/yar-concurrent-client.call.php
     */
    public static function call($uri, $method, $parameters, callable $callback = null, callable $error_callback, array $options) {}

    /**
     * Send all calls
     * @param callable $callback
     *  If this callback is set, then Yar will call this callback after all calls are sent and before any response return, with a $callinfo NULL.
     *  Then, if user didn't specify callback when registering concurrent call, this callback will be used to handle response, otherwise, the callback specified while registering will be used.
     * @param callable $error_callback
     *  If this callback is set, then Yar will call this callback while error occurred.
     * @return bool
     * @link https://secure.php.net/manual/en/yar-concurrent-client.loop.php
     */
    public static function loop($callback = null, $error_callback = null) {}

    /**
     * Clean all registered calls
     * Clean all registered calls
     * @return bool
     * @link https://secure.php.net/manual/en/yar-concurrent-client.reset.php
     */
    public static function reset() {}
}

/**
 * Class Yar_Server_Exception
 * Date 2018/6/9 下午3:06
 * @author weizhimiao001@lianjia.com
 * @link https://secure.php.net/manual/en/class.yar-server-exception.php
 */
class Yar_Server_Exception extends Exception
{
    protected $_type;

    /**
     * Retrieve exception's type
     * Get the exception original type threw by server
     * @return string
     * @link https://secure.php.net/manual/en/yar-server-exception.gettype.php
     */
    public function getType() {}
}

/**
 * Class Yar_Client_Exception
 * Date 2018/6/9 下午3:05
 * @author weizhimiao001@lianjia.com
 * @link https://secure.php.net/manual/en/class.yar-client-exception.php
 */
class Yar_Client_Exception extends Exception
{
    /**
     * Retrieve exception's type
     * @return string "Yar_Exception_Client".
     * @link https://secure.php.net/manual/en/yar-client-exception.gettype.php
     */
    public function getType() {}
}

class Yar_Server_Request_Exception extends Yar_Server_Exception {}

class Yar_Server_Protocol_Exception extends Yar_Server_Exception {}

class Yar_Server_Packager_Exception extends Yar_Server_Exception {}

class Yar_Server_Output_Exception extends Yar_Server_Exception {}

class Yar_Client_Transport_Exception extends Yar_Client_Exception {}

class Yar_Client_Packager_Exception extends Yar_Client_Exception {}

class Yar_Client_Protocol_Exception extends Yar_Client_Exception {}
