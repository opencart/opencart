<?php

use JetBrains\PhpStorm\Pure;

/**
 * Terminate apache process after this request
 * apache_child_terminate() will register the Apache process executing the current PHP request for termination once execution of PHP code is completed. It may be used to terminate a process after a script with high memory consumption has been run as memory will usually only be freed internally but not given back to the operating system.
 * @link https://php.net/manual/en/function.apache-child-terminate.php
 * @return bool <b>TRUE</b> if PHP is running as an Apache 1 module, the Apache version is non-multithreaded, and the child_terminate PHP directive is enabled (disabled by default). If these conditions are not met, <b>FALSE</b> is returned and an error of level <b>E_WARNING</b> is generated.
 */
function apache_child_terminate() {}

/**
 * Get a list of loaded Apache modules
 * @link https://php.net/manual/en/function.apache-get-modules.php
 * @return array of loaded Apache modules.
 */
#[Pure]
function apache_get_modules() {}

/**
 * Fetch the Apache version
 * @link https://php.net/manual/en/function.apache-get-version.php
 * @return string|false the Apache version on success or <b>FALSE</b> on failure.
 */
#[Pure]
function apache_get_version() {}

/**
 * Get an Apache subprocess_env variable
 * Retrieve an Apache environment variable specified by $variable.
 * This function requires Apache 2 otherwise it's undefined.
 * @link https://php.net/manual/en/function.apache-getenv.php
 * @param string $variable <p>
 * The Apache environment variable.
 * </p>
 * @param bool $walk_to_top <p>
 * Whether to get the top-level variable available to all Apache layers.
 * </p>
 * @return string|false The value of the Apache environment variable on success, or <b>FALSE</b> on failure.
 */
#[Pure]
function apache_getenv($variable, $walk_to_top = false) {}

/**
 * Perform a partial request for the specified URI and return all info about it
 * This performs a partial request for a URI. It goes just far enough to obtain all the important information about the given resource.
 * This function is supported when PHP is installed as an Apache module or by the NSAPI server module in Netscape/iPlanet/SunONE webservers.
 * @link https://php.net/manual/en/function.apache-lookup-uri.php
 * @param string $filename <p>
 * The filename (URI) that's being requested.
 * </p>
 * @return object of related URI information.
 */
function apache_lookup_uri($filename) {}

/**
 * Get and set apache request notes
 * This function is a wrapper for Apache's table_get and table_set. It edits the table of notes that exists during a request. The table's purpose is to allow Apache modules to communicate.
 * The main use for apache_note() is to pass information from one module to another within the same request.
 * @link https://php.net/manual/en/function.apache-note.php
 * @param string $note_name <p>
 * The name of the note.
 * </p>
 * @param string $note_value <p>
 * The value of the note.
 * </p>
 * @return string|false If called with one argument, it returns the current value of note note_name. If called with two arguments, it sets the value of note note_name to note_value and returns the previous value of note note_name. If the note cannot be retrieved, <b>FALSE</b> is returned.
 */
function apache_note($note_name, $note_value = '') {}

/**
 * Reset the Apache write timer
 * apache_reset_timeout() resets the Apache write timer, which defaults to 300 seconds. With set_time_limit(0); ignore_user_abort(true) and periodic apache_reset_timeout() calls, Apache can theoretically run forever.
 * This function requires Apache 1.
 * @link https://php.net/manual/en/function.apache-reset-timeout.php
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function apache_reset_timeout() {}

/**
 * Fetch all HTTP response headers
 * @link https://php.net/manual/en/function.apache-response-headers.php
 * @return array|false An array of all Apache response headers on success or <b>FALSE</b> on failure.
 */
function apache_response_headers() {}

/**
 * Sets the value of the Apache environment variable specified by variable.
 * Note: When setting an Apache environment variable, the corresponding $_SERVER variable is not changed.
 * @link https://php.net/manual/en/function.apache-setenv.php
 * @param string $variable <p>
 * The environment variable that's being set.
 * </p>
 * @param string $value <p>
 * The new variable value.
 * </p>
 * @param bool $walk_to_top <p>
 * Whether to set the top-level variable available to all Apache layers.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function apache_setenv($variable, $value, $walk_to_top = false) {}

/**
 * Perform an Apache sub-request
 * virtual() is an Apache-specific function which is similar to <!--#include virtual...--> in mod_include. It performs an Apache sub-request. It is useful for including CGI scripts or .shtml files, or anything else that you would parse through Apache. Note that for a CGI script, the script must generate valid CGI headers. At the minimum that means it must generate a Content-Type header.
 * To run the sub-request, all buffers are terminated and flushed to the browser, pending headers are sent too.
 * This function is supported when PHP is installed as an Apache module or by the NSAPI server module in Netscape/iPlanet/SunONE webservers.
 * @link https://secure.php.net/manual/en/function.virtual.php
 * @param string $filename <p>
 * The file that the virtual command will be performed on.
 * </p>
 * @return bool Performs the virtual command on success, or returns FALSE on failure.
 */
function virtual($filename) {}
