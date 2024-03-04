<?php
/**
 * @xglobal $GLOBALS array
 * Contains a reference to every variable which is currently available within the global scope of the script.
 *   The keys of this array are the names of the global variables.
 *   $GLOBALS has existed since PHP 3.
 *
 * <p><a href="https://secure.php.net/manual/en/reserved.variables.php">https://secure.php.net/manual/en/reserved.variables.php</a>
 */
$GLOBALS = [];

/**
 * @xglobal $_COOKIE array
 * Variables provided to the script via HTTP cookies. Analogous to the old $HTTP_COOKIE_VARS array
 * (which is still available, but deprecated).
 *
 * <p><a href="https://secure.php.net/manual/en/reserved.variables.php">
 * https://secure.php.net/manual/en/reserved.variables.php</a>
 */
$_COOKIE = [];

/**
 * @xglobal $_ENV array
 * @xglobal $HTTP_ENV_VARS array
 *
 * Variables provided to the script via the environment.
 * Analogous to the old $HTTP_ENV_VARS array (which is still available, but deprecated).
 *
 * <p><a href="https://secure.php.net/manual/en/reserved.variables.php">
 * https://secure.php.net/manual/en/reserved.variables.php</a>
 */
$_ENV = [];
/**
 * @deprecated 4.1
 * @removed 5.4
 */
$HTTP_ENV_VARS = [];

/**
 * @xglobal $_FILES array
 * @xglobal $HTTP_POST_FILES array
 *
 * Variables provided to the script via HTTP post file uploads. Analogous to the old $HTTP_POST_FILES array
 * (which is still available, but deprecated).
 * See POST method uploads for more information.
 *
 * <p><a href="https://secure.php.net/manual/en/reserved.variables.php">
 * https://secure.php.net/manual/en/reserved.variables.php</a>
 */
$_FILES = [];
/**
 * @deprecated 4.1
 * @removed 5.4
 */
$HTTP_POST_FILES = [];

/**
 * @xglobal $_GET array
 * @xglobal $HTTP_GET_VARS array
 *
 * Variables provided to the script via URL query string.
 *  Analogous to the old $HTTP_GET_VARS array (which is still available, but deprecated).
 *
 * <p><a href="https://secure.php.net/manual/en/reserved.variables.php">
 * https://secure.php.net/manual/en/reserved.variables.php</a>
 */
$_GET = [];
/**
 * @deprecated 4.1
 * @removed 5.4
 */
$HTTP_GET_VARS = [];

/**
 * @xglobal $_POST array
 * @xglobal $HTTP_POST_VARS array
 *
 * Variables provided to the script via HTTP POST. Analogous to the old $HTTP_POST_VARS array
 * (which is still available, but deprecated).
 * @link https://secure.php.net/manual/en/language.variables.predefined.php
 *
 * <p><a href="https://secure.php.net/manual/en/reserved.variables.php">
 * https://secure.php.net/manual/en/reserved.variables.php</a>
 */
$_POST = [];
/**
 * @deprecated 4.1
 * @removed 5.4
 */
$HTTP_POST_VARS = [];

/**
 *  @xglobal $_REQUEST array
 * Variables provided to the script via the GET, POST, and COOKIE input mechanisms,
 * and which therefore cannot be trusted.
 * The presence and order of variable inclusion in this array is defined according to the
 * PHP variables_order configuration directive.
 * This array has no direct analogue in versions of PHP prior to 4.1.0.
 * See also import_request_variables().
 * <p>
 * Caution
 *  <p>Since PHP 4.3.0, FILE information from $_FILES does not exist in $_REQUEST.
 * <p>
 * Note: When running on the command line , this will not include the argv and argc entries; these are present in the $_SERVER array.
 *
 *
 * <p><a href="https://secure.php.net/manual/en/reserved.variables.php">
 * https://secure.php.net/manual/en/reserved.variables.php</a>
 */
$_REQUEST = [];

/**
 * @xglobal $_SERVER array
 * @xglobal $HTTP_SERVER_VARS array
 *
 * Variables set by the web server or otherwise directly related to the execution environment of the current script.
 * Analogous to the old $HTTP_SERVER_VARS array (which is still available, but deprecated).
 *
 * <p><a href="https://secure.php.net/manual/en/reserved.variables.php">
 * https://secure.php.net/manual/en/reserved.variables.php</a>
 */
$_SERVER = [];
/**
 * @deprecated 4.1
 * @removed 5.4
 */
$HTTP_SERVER_VARS = [];

$_SERVER['PHP_SELF'] = '';
$_SERVER['argv'] = '';
$_SERVER['argc'] = '';
$_SERVER['GATEWAY_INTERFACE'] = 'CGI/1.1';
$_SERVER['SERVER_ADDR'] = '127.0.0.1';
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['SERVER_SOFTWARE'] = '';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.0';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_TIME'] = '';
$_SERVER['QUERY_STRING'] = '';
$_SERVER['DOCUMENT_ROOT'] = '';
$_SERVER['HTTP_ACCEPT'] = '';
$_SERVER['HTTP_ACCEPT_CHARSET'] = 'iso-8859-1,*,utf-8';
$_SERVER['HTTP_ACCEPT_ENCODING'] = 'gzip';
$_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en';
$_SERVER['HTTP_CONNECTION'] = 'Keep-Alive';
$_SERVER['HTTP_HOST'] = '';
$_SERVER['HTTP_REFERER'] = '';
$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586).';
$_SERVER['HTTPS'] = '';
$_SERVER['REMOTE_ADDR'] = '';
$_SERVER['REMOTE_HOST'] = '';
$_SERVER['REMOTE_PORT'] = '';
$_SERVER['SCRIPT_FILENAME'] = '';
$_SERVER['SERVER_ADMIN'] = '';
$_SERVER['SERVER_PORT'] = '80';
$_SERVER['SERVER_SIGNATURE'] = '';
$_SERVER['PATH_TRANSLATED'] = '';
$_SERVER['SCRIPT_NAME'] = '';
$_SERVER['REQUEST_URI'] = '/index.html';
$_SERVER['PHP_AUTH_DIGEST'] = '';
$_SERVER['PHP_AUTH_USER'] = '';
$_SERVER['PHP_AUTH_PW'] = '';
$_SERVER['AUTH_TYPE'] = '';
$_SERVER['PATH_INFO'] = '';
$_SERVER['ORIG_PATH_INFO'] = '';

/**
 *  @xglobal $_SESSION array
 *  @xglobal $HTTP_SESSION_VARS array
 *
 * Variables which are currently registered to a script's session.
 * Analogous to the old $HTTP_SESSION_VARS array (which is still available, but deprecated).
 * See the Session handling functions section for more information.
 *
 * <p><a href="https://secure.php.net/manual/en/reserved.variables.php">
 * https://secure.php.net/manual/en/reserved.variables.php</a>
 */
$_SESSION = [];
/**
 * @deprecated 4.1
 * @removed 5.4
 */
$HTTP_SESSION_VARS = [];

/**
 * @xglobal $argc int
 * @type int<1, max>
 *
 * The number of arguments passed to script
 *
 * <p><a href="https://secure.php.net/manual/en/reserved.variables.php">
 * https://secure.php.net/manual/en/reserved.variables.php</a>
 */
$argc = 0;

/**
 *  @xglobal $argv array
 *
 * Array of arguments passed to script
 *
 * <p><a href="https://secure.php.net/manual/en/reserved.variables.php">
 * https://secure.php.net/manual/en/reserved.variables.php</a>
 */
$argv = [1 + 1 => "a" . "b"];

/**
 * @xglobal $HTTP_RAW_POST_DATA string
 *
 * Raw POST data
 *
 * <p><a href="https://secure.php.net/manual/en/reserved.variables.php">
 * https://secure.php.net/manual/en/reserved.variables.php</a>
 *
 * @deprecated 5.6 Deprecated as of PHP 5.6.0. Use the php://input stream instead.
 * @removed 7.0
 */
$HTTP_RAW_POST_DATA = '';

/**
 * @xglobal $http_response_header array
 *
 * HTTP response headers
 *
 * <p><a href="https://secure.php.net/manual/en/reserved.variables.php">
 * https://secure.php.net/manual/en/reserved.variables.php</a>
 */
$http_response_header = [];

/**
 * @xglobal $php_errormsg string
 *  The previous error message
 *
 * <p><a href="https://secure.php.net/manual/en/reserved.variables.php">
 * https://secure.php.net/manual/en/reserved.variables.php</a>
 * @deprecated 7.2
 */
$php_errormsg = '';
