<?php

// Start of session v.
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;

/**
 * Get and/or set the current session name.<br/>
 * Before 7.2.0 checked cookie status and since 7.2.0 checks both cookie and session status to avoid PHP crash.
 * @link https://php.net/manual/en/function.session-name.php
 * @param string|null $name [optional] <p>
 * The session name references the name of the session, which is
 * used in cookies and URLs (e.g. PHPSESSID). It
 * should contain only alphanumeric characters; it should be short and
 * descriptive (i.e. for users with enabled cookie warnings).
 * If <i>name</i> is specified, the name of the current
 * session is changed to its value.
 * </p>
 * <p>
 * <p>
 * The session name can't consist of digits only, at least one letter
 * must be present. Otherwise a new session id is generated every time.
 * </p>
 * </p>
 * @return string|false the name of the current session.
 */
#[LanguageLevelTypeAware(['8.0' => 'string|false'], default: 'string')]
function session_name(#[LanguageLevelTypeAware(['8.0' => 'null|string'], default: 'string')] $name) {}

/**
 * Get and/or set the current session module.<br/>
 * Since 7.2.0 it is forbidden to set the module name to "user".
 * @link https://php.net/manual/en/function.session-module-name.php
 * @param string|null $module [optional] <p>
 * If <i>module</i> is specified, that module will be
 * used instead.
 * </p>
 * @return string|false the name of the current session module.
 */
#[LanguageLevelTypeAware(['8.0' => 'string|false'], default: 'string')]
function session_module_name(#[LanguageLevelTypeAware(['8.0' => 'null|string'], default: 'string')] $module) {}

/**
 * Get and/or set the current session save path
 * @link https://php.net/manual/en/function.session-save-path.php
 * @param string|null $path [optional] <p>
 * Session data path. If specified, the path to which data is saved will
 * be changed. <b>session_save_path</b> needs to be called
 * before <b>session_start</b> for that purpose.
 * </p>
 * <p>
 * <p>
 * On some operating systems, you may want to specify a path on a
 * filesystem that handles lots of small files efficiently. For example,
 * on Linux, reiserfs may provide better performance than ext2fs.
 * </p>
 * </p>
 * @return string|false the path of the current directory used for data storage.
 */
#[LanguageLevelTypeAware(['8.0' => 'string|false'], default: 'string')]
function session_save_path(#[LanguageLevelTypeAware(['8.0' => 'null|string'], default: 'string')] $path) {}

/**
 * Get and/or set the current session id
 * @link https://php.net/manual/en/function.session-id.php
 * @param string|null $id [optional] <p>
 * If <i>id</i> is specified, it will replace the current
 * session id. <b>session_id</b> needs to be called before
 * <b>session_start</b> for that purpose. Depending on the
 * session handler, not all characters are allowed within the session id.
 * For example, the file session handler only allows characters in the
 * range a-z A-Z 0-9 , (comma) and - (minus)!
 * </p>
 * When using session cookies, specifying an <i>id</i>
 * for <b>session_id</b> will always send a new cookie
 * when <b>session_start</b> is called, regardless if the
 * current session id is identical to the one being set.
 * @return string|false <b>session_id</b> returns the session id for the current
 * session or the empty string ("") if there is no current
 * session (no current session id exists).
 */
#[LanguageLevelTypeAware(['8.0' => 'string|false'], default: 'string')]
function session_id(#[LanguageLevelTypeAware(['8.0' => 'null|string'], default: 'string')] $id) {}

/**
 * Update the current session id with a newly generated one
 * @link https://php.net/manual/en/function.session-regenerate-id.php
 * @param bool $delete_old_session [optional] <p>
 * Whether to delete the old associated session file or not.
 * </p>
 * @return bool true on success or false on failure.
 */
function session_regenerate_id(bool $delete_old_session = false): bool {}

/**
 * PHP > 5.4.0 <br/>
 * Session shutdown function
 * @link https://secure.php.net/manual/en/function.session-register-shutdown.php
 * @return void
 */
function session_register_shutdown(): void {}

/**
 * Decodes session data from a string
 * @link https://php.net/manual/en/function.session-decode.php
 * @param string $data <p>
 * The encoded data to be stored.
 * </p>
 * @return bool true on success or false on failure.
 */
function session_decode(string $data): bool {}

/**
 * Register one or more global variables with the current session
 * @link https://php.net/manual/en/function.session-register.php
 * @param mixed $name <p>
 * A string holding the name of a variable or an array consisting of
 * variable names or other arrays.
 * </p>
 * @param mixed ...$_ [optional]
 * @return bool true on success or false on failure.
 * @removed 5.4
 */
#[Deprecated(since: '5.3')]
function session_register(mixed $name, ...$_): bool {}

/**
 * Unregister a global variable from the current session
 * @link https://php.net/manual/en/function.session-unregister.php
 * @param string $name <p>
 * The variable name.
 * </p>
 * @return bool true on success or false on failure.
 * @removed 5.4
 */
#[Deprecated(since: '5.3')]
function session_unregister(string $name): bool {}

/**
 * Find out whether a global variable is registered in a session
 * @link https://php.net/manual/en/function.session-is-registered.php
 * @param string $name <p>
 * The variable name.
 * </p>
 * @return bool <b>session_is_registered</b> returns true if there is a
 * global variable with the name <i>name</i> registered in
 * the current session, false otherwise.
 * @removed 5.4
 */
#[Deprecated(since: '5.3')]
function session_is_registered(string $name): bool {}

/**
 * Encodes the current session data as a string
 * @link https://php.net/manual/en/function.session-encode.php
 * @return string|false the contents of the current session encoded.
 */
#[LanguageLevelTypeAware(["8.0" => "string|false"], default: "string")]
function session_encode() {}

/**
 * Initialize session data
 * @link https://php.net/manual/en/function.session-start.php
 * @param array $options [optional] <p>If provided, this is an associative array of options that will override the currently set session configuration directives. The keys should not include the session. prefix.
 * In addition to the normal set of configuration directives, a read_and_close option may also be provided. If set to TRUE, this will result in the session being closed immediately after being read, thereby avoiding unnecessary locking if the session data won't be changed.</p>
 * @return bool This function returns true if a session was successfully started,
 * otherwise false.
 */
function session_start(#[PhpStormStubsElementAvailable(from: '7.0')] array $options = []): bool {}

/**
 * Create new session id
 * @link https://www.php.net/manual/en/function.session-create-id.php
 * @param string $prefix [optional] If prefix is specified, new session id is prefixed by prefix.
 * Not all characters are allowed within the session id.
 * Characters in the range a-z A-Z 0-9 , (comma) and - (minus) are allowed.
 * @return string|false new collision free session id for the current session.
 * If it is used without active session, it omits collision check.
 * @since 7.1
 */
#[LanguageLevelTypeAware(["8.0" => "string|false"], default: "string")]
function session_create_id(string $prefix = '') {}

/**
 * Perform session data garbage collection
 * @return int|false number of deleted session data for success, false for failure.
 * @since 7.1
 */
#[LanguageLevelTypeAware(["8.0" => "int|false"], default: "int")]
function session_gc() {}

/**
 * Destroys all data registered to a session
 * @link https://php.net/manual/en/function.session-destroy.php
 * @return bool true on success or false on failure.
 */
function session_destroy(): bool {}

/**
 * Free all session variables
 * @link https://php.net/manual/en/function.session-unset.php
 * @return void|bool since 7.2.0 returns true on success or false on failure.
 */
#[LanguageLevelTypeAware(["7.2" => "bool"], default: "void")]
function session_unset() {}

/**
 * Sets user-level session storage functions
 * @link https://php.net/manual/en/function.session-set-save-handler.php
 * @param callable $open <p>
 * Open function, this works like a constructor in classes and is
 * executed when the session is being opened. The open function
 * expects two parameters, where the first is the save path and
 * the second is the session name.
 * </p>
 * @param callable $close <p>
 * Close function, this works like a destructor in classes and is
 * executed when the session operation is done.
 * </p>
 * @param callable $read <p>
 * Read function must return string value always to make save handler
 * work as expected. Return empty string if there is no data to read.
 * Return values from other handlers are converted to boolean expression.
 * true for success, false for failure.
 * </p>
 * @param callable $write <p>
 * Write function that is called when session data is to be saved. This
 * function expects two parameters: an identifier and the data associated
 * with it.
 * </p>
 * <p>
 * The "write" handler is not executed until after the output stream is
 * closed. Thus, output from debugging statements in the "write"
 * handler will never be seen in the browser. If debugging output is
 * necessary, it is suggested that the debug output be written to a
 * file instead.
 * </p>
 * @param callable $destroy <p>
 * The destroy handler, this is executed when a session is destroyed with
 * <b>session_destroy</b> and takes the session id as its
 * only parameter.
 * </p>
 * @param callable $gc <p>
 * The garbage collector, this is executed when the session garbage collector
 * is executed and takes the max session lifetime as its only parameter.
 * </p>
 * @param callable|null $create_sid [optional]
 * <p>This callback is executed when a new session ID is required.
 * No parameters are provided, and the return value should be a string that is a valid
 * session ID for your handler.</p>
 * @param callable|null $validate_sid [optional]
 * @param callable|null $update_timestamp [optional]
 * @return bool true on success or false on failure.
 */
function session_set_save_handler(callable $open, callable $close, callable $read, callable $write, callable $destroy, callable $gc, ?callable $create_sid = null, ?callable $validate_sid = null, ?callable $update_timestamp = null): bool {}

/**
 * (PHP 5.4)<br/>
 * Sets user-level session storage functions
 * @link https://php.net/manual/en/function.session-set-save-handler.php
 * @param SessionHandlerInterface $session_handler An instance of a class implementing SessionHandlerInterface,
 * and optionally SessionIdInterface and/or SessionUpdateTimestampHandlerInterface, such as SessionHandler,
 * to register as the session handler. Since PHP 5.4 only.
 * @param bool $register_shutdown [optional] Register session_write_close() as a register_shutdown_function() function.
 * @return bool true on success or false on failure.
 */
function session_set_save_handler(SessionHandlerInterface $sessionhandler, bool $register_shutdown = true): bool {}

/**
 * Get and/or set the current cache limiter
 * @link https://php.net/manual/en/function.session-cache-limiter.php
 * @param string|null $value [optional] <p>
 * If <i>cache_limiter</i> is specified, the name of the
 * current cache limiter is changed to the new value.
 * </p>
 * <table>
 * Possible values
 * <tr valign="top">
 * <td>Value</td>
 * <td>Headers sent</td>
 * </tr>
 * <tr valign="top">
 * <td>public</td>
 * <td>
 * <pre>
 * Expires: (sometime in the future, according session.cache_expire)
 * Cache-Control: public, max-age=(sometime in the future, according to session.cache_expire)
 * Last-Modified: (the timestamp of when the session was last saved)
 * </pre>
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>private_no_expire</td>
 * <td>
 * <pre>
 * Cache-Control: private, max-age=(session.cache_expire in the future), pre-check=(session.cache_expire in the future)
 * Last-Modified: (the timestamp of when the session was last saved)
 * </pre>
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>private</td>
 * <td>
 * <pre>
 * Expires: Thu, 19 Nov 1981 08:52:00 GMT
 * Cache-Control: private, max-age=(session.cache_expire in the future), pre-check=(session.cache_expire in the future)
 * Last-Modified: (the timestamp of when the session was last saved)
 * </pre>
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>nocache</td>
 * <td>
 * <pre>
 * Expires: Thu, 19 Nov 1981 08:52:00 GMT
 * Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0
 * Pragma: no-cache
 * </pre>
 * </td>
 * </tr>
 * </table>
 * @return string|false the name of the current cache limiter.
 */
#[LanguageLevelTypeAware(["8.0" => "string|false"], default: "string")]
function session_cache_limiter(#[LanguageLevelTypeAware(['8.0' => 'null|string'], default: 'string')] $value) {}

/**
 * Return current cache expire
 * @link https://php.net/manual/en/function.session-cache-expire.php
 * @param int|null $value [optional] <p>
 * If <i>new_cache_expire</i> is given, the current cache
 * expire is replaced with <i>new_cache_expire</i>.
 * </p>
 * <p>
 * Setting <i>new_cache_expire</i> is of value only, if
 * session.cache_limiter is set to a value
 * different from nocache.
 * </p>
 * @return int|false the current setting of session.cache_expire.
 * The value returned should be read in minutes, defaults to 180.
 */
#[LanguageLevelTypeAware(["8.0" => "int|false"], default: "int")]
function session_cache_expire(#[LanguageLevelTypeAware(['8.0' => 'null|int'], default: 'int')] $value) {}

/**
 * Set the session cookie parameters
 * @link https://php.net/manual/en/function.session-set-cookie-params.php
 * @param array $lifetime_or_options <p>
 * An associative array which may have any of the keys lifetime, path, domain,
 * secure, httponly and samesite. The values have the same meaning as described
 * for the parameters with the same name. The value of the samesite element
 * should be either Lax or Strict. If any of the allowed options are not given,
 * their default values are the same as the default values of the explicit
 * parameters. If the samesite element is omitted, no SameSite cookie attribute
 * is set.
 * </p>
 * @return bool returns true on success or false on failure.
 * @since 7.3
 */
function session_set_cookie_params(array $lifetime_or_options): bool {}

/**
 * Set the session cookie parameters
 * @link https://php.net/manual/en/function.session-set-cookie-params.php
 * @param int $lifetime_or_options <p>
 * Lifetime of the
 * session cookie, defined in seconds.
 * </p>
 * @param string|null $path [optional] <p>
 * Path on the domain where
 * the cookie will work. Use a single slash ('/') for all paths on the
 * domain.
 * </p>
 * @param string|null $domain [optional] <p>
 * Cookie domain, for
 * example 'www.php.net'. To make cookies visible on all subdomains then
 * the domain must be prefixed with a dot like '.php.net'.
 * </p>
 * @param bool|null $secure [optional] <p>
 * If true cookie will only be sent over
 * secure connections.
 * </p>
 * @param bool|null $httponly [optional] <p>
 * If set to true then PHP will attempt to send the
 * httponly
 * flag when setting the session cookie.
 * </p>
 * @return void|bool since 7.2.0 returns true on success or false on failure.
 */
#[LanguageLevelTypeAware(["7.2" => "bool"], default: "void")]
function session_set_cookie_params(int $lifetime_or_options, ?string $path = null, ?string $domain = null, ?bool $secure = null, ?bool $httponly = null) {}

/**
 * Get the session cookie parameters
 * @link https://php.net/manual/en/function.session-get-cookie-params.php
 * @return array an array with the current session cookie information, the array
 * contains the following items:
 * "lifetime" - The
 * lifetime of the cookie in seconds.
 * "path" - The path where
 * information is stored.
 * "domain" - The domain
 * of the cookie.
 * "secure" - The cookie
 * should only be sent over secure connections.
 * "httponly" - The
 * cookie can only be accessed through the HTTP protocol.
 */
#[ArrayShape(["lifetime" => "int", "path" => "string", "domain" => "string", "secure" => "bool", "httponly" => "bool", "samesite" => "string"])]
function session_get_cookie_params(): array {}

/**
 * Write session data and end session
 * @link https://php.net/manual/en/function.session-write-close.php
 * @return void|bool since 7.2.0 returns true on success or false on failure.
 */
#[LanguageLevelTypeAware(["7.2" => "bool"], default: "void")]
function session_write_close() {}

/**
 * Alias of <b>session_write_close</b>
 * @link https://php.net/manual/en/function.session-commit.php
 * @return void|bool since 7.2.0 returns true on success or false on failure.
 */
#[LanguageLevelTypeAware(["7.2" => "bool"], default: "void")]
function session_commit() {}

/**
 * (PHP 5 >= 5.4.0)<br>
 * Returns the current session status
 * @link https://php.net/manual/en/function.session-status.php
 * @return int <b>PHP_SESSION_DISABLED</b> if sessions are disabled.
 * <b>PHP_SESSION_NONE</b> if sessions are enabled, but none exists.
 * <b>PHP_SESSION_ACTIVE</b> if sessions are enabled, and one exists.
 * @since 5.4
 */
function session_status(): int {}

/**
 * (PHP 5 >= 5.6.0)<br>
 * Discard session array changes and finish session
 * @link https://php.net/manual/en/function.session-abort.php
 * @return void|bool since 7.2.0 returns true if a session was successfully reinitialized or false on failure.
 * @since 5.6
 */
#[LanguageLevelTypeAware(["7.2" => "bool"], default: "void")]
function session_abort() {}

/**
 * (PHP 5 >= 5.6.0)<br>
 * Re-initialize session array with original values
 * @link https://php.net/manual/en/function.session-reset.php
 * @return void|bool since 7.2.0 returns true if a session was successfully reinitialized or false on failure.
 * @since 5.6
 */
#[LanguageLevelTypeAware(["7.2" => "bool"], default: "void")]
function session_reset() {}

// End of session v.
