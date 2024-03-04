<?php

// Start of Zend Extensions

/**
 * causes a job to fail logically
 * can be used to indicate an error in the script logic (e.g. database connection problem)
 * @param string $error_string the error string to display
 */
function set_job_failed($error_string) {};

/**
 * returns array containing following fields:
 * "license_ok" - whether license allows use of JobQueue
 * "expires" - license expiration date
 */
function jobqueue_license_info() {};

/**
 * Create Java object
 *
 * @return object
 * @param string $class
 */
function java($class) {}

/**
 * Return Java exception object for last exception
 * @return object|false Java Exception object, if there was an exception, false otherwise
 */
function java_last_exception_get() {}

/**
 * Clear last Java exception object record.
 * @return void
 */
function java_last_exception_clear() {}

/**
 * Set case sensitivity for Java calls.
 * @param bool $ignore if set, Java attribute and method names would be resolved disregarding case. NOTE: this does not make any Java functions case insensi
tive, just things like $foo->bar and $foo->bar() would match Bar too.
 * @return void
 */
function java_set_ignore_case($ignore) {}

/**
 * Set encoding for strings received by Java from PHP. Default is UTF-8.
 * @param string $encoding
 * @return array
 */
function java_set_encoding($encoding) {}

/**
 * Control if exceptions are thrown on Java exception. Only for PHP5.
 * @param bool $throw If true, PHP exception is thrown when Java exception happens. If set to false, use java_last_exception_get() to check for exception.
 * @return void
 */
function java_throw_exceptions($throw) {}

/**
 * Reload Jar's that were dynamically loaded
 *
 * @return array
 * @param string $new_jarpath
 */
function java_reload($new_jarpath) {}

/**
 * Add to Java's classpath in runtime
 *
 * @return array
 * @param string $new_classpath
 */
function java_require($new_classpath) {}

/**
 * Shown if loader is enabled
 * @return bool
 */
function zend_loader_enabled() {}

/**
 * Returns true if the current file is a Zend-encoded file.
 * @return bool
 */
function zend_loader_file_encoded() {}

/**
 * Returns license (array with fields) if the current file has a valid license and is encoded, otherwise it returns false.
 * @return array
 */
function zend_loader_file_licensed() {}

/**
 * Returns the name of the file currently being executed.
 * @return string
 */
function zend_loader_current_file() {}

/**
 * Dynamically loads a license for applications encoded with Zend SafeGuard. The Override controls if it will override old licenses for the same product.
 * @param string $license_file
 * @param bool $override [optional]
 * @return bool
 */
function zend_loader_install_license($license_file, $override) {}

/**
 * Obfuscate and return the given function name with the internal obfuscation function.
 * @param string $function_name
 * @return string
 */
function zend_obfuscate_function_name($function_name) {}

/**
 * Obfuscate and return the given class name with the internal obfuscation function.
 * @param string $class_name
 * @return string
 */
function zend_obfuscate_class_name($class_name) {}

/**
 * Returns the current obfuscation level support (set by zend_optimizer.obfuscation_level_support)
 * @return int
 */
function zend_current_obfuscation_level() {}

/**
 * Start runtime-obfuscation support that allows limited mixing of obfuscated and un-obfuscated code.
 * @return void
 */
function zend_runtime_obfuscate() {}

/**
 * Returns array of the host ids. If all_ids is true, then all IDs are returned, otehrwise only IDs considered "primary" are returned.
 * @param bool $all_ids [optional]
 * @return array
 */
function zend_get_id($all_ids = false) {}

/**
 * Returns Optimizer version. Alias: zend_loader_version()
 * @return string
 */
function zend_optimizer_version() {}

// End of Zend Extensions
