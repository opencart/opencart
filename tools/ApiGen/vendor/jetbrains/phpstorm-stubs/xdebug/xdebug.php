<?php

/**
 * Show diagnostic information
 */
function xdebug_info(string $category = '') {}

/**
 * Returns an array of ALL valid ini options with values and is not the same as ini_get_all() which returns only
 * registered ini options. Only useful for devs to debug php.ini scanner/parser!
 */
function config_get_hash(): array {}

/**
 * Returns the stack depth level.
 * The main body of a script is level 0 and each include and/or function call adds one to the stack depth level.
 * @return int
 */
function xdebug_get_stack_depth(): int {}

/**
 * Returns an array which resembles the stack trace up to this point.
 * @return array
 */
function xdebug_get_function_stack(): array {}

/**
 * Displays the current function stack, in a similar way as what Xdebug would display in an error situation.
 * @param string $message
 * @param int $options A bit mask of the following constants: XDEBUG_STACK_NO_DESC
 * @return void
 */
function xdebug_print_function_stack(string $message = 'user triggered', int $options = 0) {}

/**
 * Returns an array where each element is a variable name which is defined in the current scope.
 * @return array
 */
function xdebug_get_declared_vars(): array {}

/**
 * This function returns the filename from where the current function/method was executed from, or NULL
 * if the stack frame does not exist
 * @param int $depth
 * @return mixed
 */
function xdebug_call_file(int $depth = 2) {}

/**
 *  This function returns the name of the class that defined the current method, NULL if the stack frame does not exist,
 * or FALSE if no class is associated with this call.
 * @param int $depth
 * @return mixed
 */
function xdebug_call_class(int $depth = 2) {}

/**
 * This function returns the name of the current function/method, NULL if the stack frame does not exist, or FALSE
 * if the stack frame has no function/method information
 * @param int $depth
 * @return mixed
 */
function xdebug_call_function(int $depth = 2) {}

/**
 * This function returns the line number from where the current function/method was called from, or NULL
 * if the stack frame does not exist
 * @param int $depth
 * @return mixed
 */
function xdebug_call_line(int $depth = 2) {}

/**
 * This function starts the monitoring of functions that were given in a list as argument to this function.
 * Function monitoring allows you to find out where in your code the functions that you provided as argument are called from.
 * This can be used to track where old, or, discouraged functions are used.
 * The defined functions are case sensitive, and a dynamic call to a static method will not be caught.
 * @param string[] $listOfFunctionsToMonitor
 * @return void
 */
function xdebug_start_function_monitor(array $listOfFunctionsToMonitor) {}

/**
 * This function stops the function monitor.
 * In order to get the list of monitored functions, you need to use the xdebug_get_monitored_functions() function.
 * @return void
 */
function xdebug_stop_function_monitor() {}

/**
 * Returns a structure which contains information about where the monitored functions were executed in your script.
 * @return array
 */
function xdebug_get_monitored_functions(): array {}

/**
 * This function displays structured information about one or more expressions that includes its type and value.
 * Arrays are explored recursively with values.
 * @param mixed $var
 * @return void
 */
function xdebug_var_dump(mixed ...$variable) {}

/**
 * This function displays structured information about one or more variables that includes its type, value and refcount information.
 * Arrays are explored recursively with values.
 * This function is implemented differently from PHP's debug_zval_dump() function in order to work around the problems
 * that that function has because the variable itself is actually passed to the function.
 * Xdebug's version is better as it uses the variable name to lookup the variable in the internal symbol table and
 * accesses all the properties directly without having to deal with actually passing a variable to a function.
 * The result is that the information that this function returns is much more accurate than PHP's own function
 * for showing zval information.
 * @param string ...$varname
 * @return void
 */
function xdebug_debug_zval(string ...$varname) {}

/**
 * This function displays structured information about one or more variables that includes its type,
 * value and refcount information.
 * Arrays are explored recursively with values.
 * The difference with xdebug_debug_zval() is that the information is not displayed through a web server API layer,
 * but directly shown on stdout (so that when you run it with apache in single process mode it ends up on the console).
 * @param string ...$varname
 * @return void
 */
function xdebug_debug_zval_stdout(string ...$varname) {}

/**
 * Enable showing stack traces on error conditions.
 * @return void
 */
function xdebug_enable() {}

/**
 * Disable showing stack traces on error conditions.
 * @return void
 */
function xdebug_disable() {}

/**
 * Return whether stack traces would be shown in case of an error or not.
 * @return bool
 */
function xdebug_is_enabled() {}

/**
 * Starts recording all notices, warnings and errors and prevents their display
 *
 * When this function is executed, Xdebug will cause PHP not to display any notices, warnings or errors.
 * Instead, they are formatted according to Xdebug's normal error formatting rules (ie, the error table
 * with the red exclamation mark) and then stored in a buffer.
 * This will continue until you call xdebug_stop_error_collection().
 *
 * This buffer's contents can be retrieved by calling xdebug_get_collected_errors() and then subsequently displayed.
 * This is really useful if you want to prevent Xdebug's powerful error reporting features from destroying your layout.
 * @return void
 */
function xdebug_start_error_collection() {}

/**
 * When this function is executed, error collection as started by xdebug_start_error_collection() is aborted.
 * The errors stored in the collection buffer are not deleted and still available to be fetched through xdebug_get_collected_errors().
 * @return void
 */
function xdebug_stop_error_collection() {}

/**
 * This function returns all errors from the collection buffer that contains all errors that were stored there when error collection was started with xdebug_start_error_collection().
 * By default this function will not clear the error collection buffer. If you pass true as argument to this function then the buffer will be cleared as well.
 * This function returns a string containing all collected errors formatted as an "Xdebug table".
 * @param bool $emptyList
 * @return array
 */
function xdebug_get_collected_errors(bool $emptyList = false): array {}

/**
 * This function makes the debugger break on the specific line as if a normal file/line breakpoint was set on this line.
 *
 * @return bool
 */
function xdebug_break(): bool {}

/**
 * Start tracing function calls from this point to the file in the trace_file parameter.
 * If no filename is given, then the trace file will be placed in the directory as configured by the xdebug.trace_output_dir setting.
 * In case a file name is given as first parameter, the name is relative to the current working directory.
 * This current working directory might be different than you expect it to be, so please use an absolute path in case you specify a file name.
 * Use the PHP function getcwd() to figure out what the current working directory is.
 * @param string|null $traceFile
 * @param int $options
 * @return string|null
 */
function xdebug_start_trace(?string $traceFile = null, int $options = 0): ?string {}

/**
 * Stop tracing function calls and closes the trace file.
 *
 * @return string
 */
function xdebug_stop_trace(): string {}

/**
 * Returns the name of the file which is used to trace the output of this script too.
 * This is useful when xdebug.auto_trace is enabled.
 * @return string|null
 */
function xdebug_get_tracefile_name() {}

/**
 * Returns the name of the file which is used to save profile information to.
 *
 * @return string|false
 */
function xdebug_get_profiler_filename() {}

/**
 * @param $prefix [optional]
 * @return bool
 */
function xdebug_dump_aggr_profiling_data($prefix) {}

/**
 * @return bool
 */
function xdebug_clear_aggr_profiling_data() {}

/**
 * Returns the current amount of memory the script uses.
 * Before PHP 5.2.1, this only works if PHP is compiled with --enable-memory-limit.
 * From PHP 5.2.1 and later this function is always available.
 *
 * @return int
 */
function xdebug_memory_usage(): int {}

/**
 * Returns the maximum amount of memory the script used until now.
 * Before PHP 5.2.1, this only works if PHP is compiled with --enable-memory-limit.
 * From PHP 5.2.1 and later this function is always available.
 *
 * @return int
 */
function xdebug_peak_memory_usage(): int {}

/**
 * Returns the current time index since the starting of the script in seconds.
 *
 * @return float
 */
function xdebug_time_index(): float {}

/**
 * This function starts gathering the information for code coverage.
 * The information that is collected consists of an two dimensional array with as primary index the executed filename and as secondary key the line number.
 * The value in the elements represents the total number of execution units on this line have been executed.
 * Options to this function are: XDEBUG_CC_UNUSED Enables scanning of code to figure out which line has executable code.
 * XDEBUG_CC_DEAD_CODE Enables branch analyzes to figure out whether code can be executed.
 *
 * @param int $options
 * @return void
 */
function xdebug_start_code_coverage(int $options = 0) {}

/**
 * This function stops collecting information, the information in memory will be destroyed.
 * If you pass 0 ("false") as argument, then the code coverage information will not be destroyed so that you can resume
 * the gathering of information with the xdebug_start_code_coverage() function again.
 * @param bool $cleanUp Destroy collected information in memory
 * @return void
 */
function xdebug_stop_code_coverage(bool $cleanUp = true) {}

/**
 * Returns whether code coverage is active.
 * @return bool
 */
function xdebug_code_coverage_started(): bool {}

/**
 * Returns a structure which contains information about which lines
 * were executed in your script (including include files).
 *
 * @return array
 */
function xdebug_get_code_coverage(): array {}

/**
 * Returns the number of functions called, including constructors, desctructors and methods.
 *
 * @return int
 */
function xdebug_get_function_count(): int {}

/**
 * This function dumps the values of the elements of the super globals
 * as specified with the xdebug.dump.* php.ini settings.
 *
 * @return void
 */
function xdebug_dump_superglobals() {}

/**
 * Returns all the headers that are set with PHP's header() function,
 * or any other header set internally within PHP (such as through setcookie()), as an array.
 *
 * @return array
 */
function xdebug_get_headers(): array {}

function xdebug_get_formatted_function_stack() {}

/**
 * Returns whether a debugging session is active.
 *
 * Returns true if a debugging session through DBGp is currently active with a client attached; false, if not.
 *
 * @return bool
 */
function xdebug_is_debugger_active(): bool {}

/**
 * @param string|null $gcstatsFile
 * @return mixed
 */
function xdebug_start_gcstats(?string $gcstatsFile = null) {}

/**
 * Stop garbage collection statistics collection and closes the output file.
 * @return string The function returns the filename of the file where the statistics were written to.
 */
function xdebug_stop_gcstats(): string {}

/**
 * Returns the name of the file which is used to save garbage collection information to, or false if
 * statistics collection is not active.
 * @return mixed
 */
function xdebug_get_gcstats_filename() {}

/**
 * @return int
 */
function xdebug_get_gc_run_count(): int {}

/**
 * @return int
 */
function xdebug_get_gc_total_collected_roots(): int {}

/**
 * @param int $group
 * @param int $listType
 * @param array $configuration
 * @return void
 */
function xdebug_set_filter(int $group, int $listType, array $configuration) {}

function xdebug_connect_to_client(): bool {}

function xdebug_notify(mixed $data): bool {}

define('XDEBUG_STACK_NO_DESC', 1);
define('XDEBUG_TRACE_APPEND', 1);
define('XDEBUG_TRACE_COMPUTERIZED', 2);
define('XDEBUG_TRACE_HTML', 4);
define('XDEBUG_TRACE_NAKED_FILENAME', 8);
define('XDEBUG_CC_UNUSED', 1);
define('XDEBUG_CC_DEAD_CODE', 2);
define('XDEBUG_CC_BRANCH_CHECK', 4);
define('XDEBUG_FILTER_TRACING', 768);
define('XDEBUG_FILTER_STACK', 512);
define('XDEBUG_FILTER_CODE_COVERAGE', 256);
define('XDEBUG_FILTER_NONE', 0);
define('XDEBUG_PATH_WHITELIST', 1);
define('XDEBUG_PATH_BLACKLIST', 2);
define('XDEBUG_NAMESPACE_WHITELIST', 17);
define('XDEBUG_NAMESPACE_BLACKLIST', 18);
define('XDEBUG_NAMESPACE_EXCLUDE', 18);
define('XDEBUG_NAMESPACE_INCLUDE', 17);
define('XDEBUG_PATH_EXCLUDE', 2);
define('XDEBUG_PATH_INCLUDE', 1);
