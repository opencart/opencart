<?php

/**
 * Stubs for the LuaSandbox PECL extension.
 *
 * <p><i>LuaSandbox</i> is an extension for PHP 5, PHP 7, and HHVM to allow safely running
 * untrusted Lua 5.1 code from within PHP.</p>
 *
 * @link https://www.php.net/manual/en/book.luasandbox.php
 * @package luasandbox
 * @version 3.0.3
 */

/**
 * The <i>LuaSandbox</i> class creates a Lua environment and allows for execution of Lua code.
 *
 * @link https://www.php.net/manual/en/class.luasandbox.php
 * @since luasandbox >= 1.0.0
 */
class LuaSandbox
{
    /**
     * Used with <code>LuaSandbox::getProfilerFunctionReport()</code>
     * to return timings in samples.
     */
    public const SAMPLES = 0;

    /**
     * Used with <code>LuaSandbox::getProfilerFunctionReport()</code>
     * to return timings in seconds.
     */
    public const SECONDS = 1;

    /**
     * Used with <code>LuaSandbox::getProfilerFunctionReport()</code>
     * to return timings in percentages of the total.
     */
    public const PERCENT = 2;

    /**
     * Call a function in a Lua global variable.
     *
     * <p>If the name contains "." characters, the function is located via recursive table accesses,
     * as if the name were a Lua expression.</p>
     *
     * <p>If the variable does not exist, or is not a function,
     * false will be returned and a warning issued.</p>
     *
     * <p>For more information about calling Lua functions and the return values,
     * see <code>LuaSandboxFunction::call()</code>.</p>
     *
     * @link https://www.php.net/manual/en/luasandbox.callFunction.php
     * @param string $name <p>Lua variable name.</p>
     * @param mixed[] $arguments <p>Arguments to the function.</p>
     * @return array|bool <p>Returns an array of values returned by the Lua function,
     * which may be empty, or false in case of failure.</p>
     * @see LuaSandboxFunction::call()
     * @since luasandbox >= 1.0.0
     */
    public function callFunction($name, array $arguments) {}

    /**
     * Disable the profiler.
     *
     * @link https://www.php.net/manual/en/luasandbox.disableProfiler.php
     * @since luasandbox >= 1.1.0
     * @see LuaSandbox::enableProfiler()
     * @see LuaSandbox::getProfilerFunctionReport()
     */
    public function disableProfiler() {}

    /**
     * Enable the profiler.
     *
     * <p>The profiler periodically samples the Lua environment
     * to record the running function. Testing indicates that
     * at least on Linux, setting a period less than 1ms will
     * lead to a high overrun count but no performance problems.</p>
     *
     * @link https://www.php.net/manual/en/luasandbox.enableprofiler.php
     * @param float $period [optional] <p>Sampling period in seconds.</p>
     * @return bool <p>Returns a boolean indicating whether the profiler is enabled.</p>
     * @since luasandbox >= 1.1.0
     * @see LuaSandbox::disableProfiler()
     * @see LuaSandbox::getProfilerFunctionReport()
     */
    public function enableProfiler($period = 0.02) {}

    /**
     * Fetch the current CPU time usage of the Lua environment.
     *
     * <p>This includes time spent in PHP callbacks.</p>
     *
     * <p>Note: On Windows, this function always returns zero.
     * On operating systems that do not support <i>CLOCK_THREAD_CPUTIME_ID</i>,
     * such as FreeBSD and Mac OS X, this function will return the
     * elapsed wall-clock time, not CPU time.</p>
     *
     * @link https://www.php.net/manual/en/luasandbox.getcpuusage.php
     * @return float <p>Returns the current CPU time usage in seconds.</p>
     * @since luasandbox >= 1.0.0
     * @see LuaSandbox::getMemoryUsage()
     * @see LuaSandbox::getPeakMemoryUsage()
     * @see LuaSandbox::setCPULimit()
     */
    public function getCPUUsage() {}

    /**
     * Fetch the current memory usage of the Lua environment.
     *
     * @link https://www.php.net/manual/en/luasandbox.getmemoryusage.php
     * @return int
     * @since luasandbox >= 1.0.0
     * @see LuaSandbox::getMemoryUsage()
     * @see LuaSandbox::getCPUUsage()
     * @see LuaSandbox::setMemoryLimit()
     */
    public function getMemoryUsage() {}

    /**
     * Fetch the peak memory usage of the Lua environment.
     *
     * @link https://www.php.net/manual/en/luasandbox.getpeakmemoryusage.php
     * @return int <p>Returns the current memory usage in bytes.</p>
     * @since luasandbox >= 1.0.0
     * @see LuaSandbox::getMemoryUsage()
     * @see LuaSandbox::getCPUUsage()
     * @see LuaSandbox::setMemoryLimit()
     */
    public function getPeakMemoryUsage() {}

    /**
     * Fetch profiler data.
     *
     * <p>For a profiling instance previously started by <code>LuaSandbox::enableProfiler()</code>,
     * get a report of the cost of each function.</p>
     *
     * <p>The measurement unit used for the cost is determined by the $units parameter:</p>
     * <li><code>LuaSandbox::SAMPLES</code> Measure in number of samples.</li>
     * <li><code>LuaSandbox::SECONDS</code> Measure in seconds of CPU time.</li>
     * <li><code>LuaSandbox::PERCENT</code> Measure percentage of CPU time.</li>
     *
     * <p>Note: On Windows, this function always returns an empty array.
     * On operating systems that do not support <i>CLOCK_THREAD_CPUTIME_ID</i>,
     * such as FreeBSD and Mac OS X, this function will report the
     * elapsed wall-clock time, not CPU time.</p>
     *
     * @link https://www.php.net/manual/en/luasandbox.getprofilerfunctionreport.php
     * @param int $units Measurement unit constant.
     * @return array <p>Returns profiler measurements, sorted in descending order, as an associative array.
     * Keys are the Lua function names (with source file and line defined in angle brackets), values are the
     * measurements as integer or float.</p>
     * @since luasandbox >= 1.1.0
     * @see LuaSandbox::SAMPLES
     * @see LuaSandbox::SECONDS
     * @see LuaSandbox::PERCENT
     */
    public function getProfilerFunctionReport($units = LuaSandbox::SECONDS) {}

    /**
     * Return the versions of LuaSandbox and Lua.
     *
     * @link https://www.php.net/manual/en/luasandbox.getversioninfo.php
     * @return array <p>Returns an array with two keys:</p>
     * <li>LuaSandbox (string), the version of the LuaSandbox extension.</li>
     * <li>Lua (string), the library name and version as defined by the LUA_RELEASE macro, for example, "Lua 5.1.5".</li>
     * @since luasandbox >= 1.6.0
     */
    public static function getVersionInfo() {}

    /**
     * Load a precompiled binary chunk into the Lua environment.
     *
     * <p>Loads data generated by <code>LuaSandboxFunction::dump()</code>.</p>
     *
     * @link https://www.php.net/manual/en/luasandbox.loadbinary.php
     * @param string $code <p>Data from <code>LuaSandboxFunction::dump()</code>.</p>
     * @param string $chunkName [optional] <p>Name for the loaded function.</p>
     * @return LuaSandboxFunction
     * @since luasandbox >= 1.0.0
     * @see LuaSandbox::loadString()
     */
    public function loadBinary($code, $chunkName = '') {}

    /**
     * Load Lua code into the Lua environment.
     *
     * <p>This is the equivalent of standard Lua's <code>loadstring()</code> function.</p>
     *
     * @link https://www.php.net/manual/en/luasandbox.loadString.php
     * @param string $code <p>Lua code.</p>
     * @param string $chunkName [optional] <p>Name for the loaded chunk, for use in error traces.</p>
     * @return LuaSandboxFunction <p>Returns a <code>LuaSandboxFunction</code> which, when executed,
     * will execute the passed <code>$code</code>.</p>
     * @since luasandbox >= 1.0.0
     * @see LuaSandbox::registerLibrary()
     * @see LuaSandbox::wrapPhpFunction()
     */
    public function loadString($code, $chunkName = '') {}

    /**
     * Pause the CPU usage timer.
     *
     * <p>This only has effect when called from within a callback from Lua.
     * When execution returns to Lua, the timer will be automatically unpaused.
     * If a new call into Lua is made, the timer will be unpaused
     * for the duration of that call.</p>
     *
     * <p>If a PHP callback calls into Lua again with timer not paused,
     * and then that Lua function calls into PHP again,
     * the second PHP call will not be able to pause the timer.
     * The logic is that even though the second PHP call would
     * avoid counting the CPU usage against the limit,
     * the first call still counts it.</p>
     *
     * @link https://www.php.net/manual/en/luasandbox.pauseusagetimer.php
     * @return bool <p>Returns a boolean indicating whether the timer is now paused.</p>
     * @since luasandbox >= 1.4.0
     * @see LuaSandbox::setCPULimit()
     * @see LuaSandbox::unpauseUsageTimer()
     */
    public function pauseUsageTimer() {}

    /**
     * Register a set of PHP functions as a Lua library.
     *
     * <p>Registers a set of PHP functions as a Lua library,
     * so that Lua can call the relevant PHP code.</p>
     *
     * <p>For more information about calling Lua functions and the return values,
     * see <code>LuaSandboxFunction::call()</code>.</p>
     *
     * @link https://www.php.net/manual/en/luasandbox.registerlibrary.php
     * @param string $libname <p>The name of the library.
     * In the Lua state, the global variable of this name will be set to the table of functions.
     * If the table already exists, the new functions will be added to it.</p>
     * @param array $functions <p>Returns an array, where each key is a function name,
     * and each value is a corresponding PHP callable.</p>
     * @since luasandbox >= 1.0.0
     * @see LuaSandbox::loadString()
     * @see LuaSandbox::wrapPhpFunction()
     */
    public function registerLibrary($libname, $functions) {}

    /**
     * Set the CPU time limit for the Lua environment.
     *
     * <p>If the total user and system time used by the environment after the call
     * to this method exceeds this limit, a <code>LuaSandboxTimeoutError</code> exception is thrown.</p>
     *
     * <p>Time used in PHP callbacks is included in the limit.</p>
     *
     * <p>Setting the time limit from a callback while Lua is running causes the timer to be reset,
     * or started if it was not already running.</p>
     *
     * <p>Note: On Windows, the CPU limit will be ignored. On operating systems
     * that do not support <i>CLOCK_THREAD_CPUTIME_ID</i>, such as FreeBSD and
     * Mac OS X, wall-clock time rather than CPU time will be limited.</p>
     *
     * @link https://www.php.net/manual/en/luasandbox.setcpulimit.php
     * @param bool|float $limit <p>Limit as a float in seconds, or false for no limit.</p>
     * @since luasandbox >= 1.0.0
     * @see LuaSandbox::getCPUUsage()
     * @see LuaSandbox::setMemoryLimit()
     */
    public function setCPULimit($limit) {}

    /**
     * Set the memory limit for the Lua environment.
     *
     * @link https://www.php.net/manual/en/luasandbox.setmemorylimit.php
     * @param int $limit <p>Memory limit in bytes.</p>
     * @throws LuaSandboxMemoryError <p>Exception is thrown if this limit is exceeded.</p>
     * @since luasandbox >= 1.0.0
     * @see LuaSandbox::getMemoryUsage()
     * @see LuaSandbox::getPeakMemoryUsage()
     * @see LuaSandbox::setCPULimit()
     */
    public function setMemoryLimit($limit) {}

    /**
     * Unpause the timer paused by <code>LuaSandbox::pauseUsageTimer()</code>.
     *
     * @link https://www.php.net/manual/en/luasandbox.unpauseusagetimer.php
     * @since luasandbox >= 1.0.0
     * @see LuaSandbox::setCPULimit()
     * @see LuaSandbox::unpauseUsageTimer()
     */
    public function unpauseUsageTimer() {}

    /**
     * Wrap a PHP callable in a LuaSandboxFunction.
     *
     * <p>Wraps a PHP callable in a <code>LuaSandboxFunction</code>,
     * so it can be passed into Lua as an anonymous function.</p>
     *
     * <p>The function must return either an array of values (which may be empty),
     * or NULL which is equivalent to returning the empty array.</p>
     *
     * <p>Exceptions will be raised as errors in Lua, however only <code>LuaSandboxRuntimeError<code>
     * exceptions may be caught inside Lua with <code>pcall()<code/> or <code>xpcall()<code/>.</p>
     *
     * <p>For more information about calling Lua functions and the return values,
     * see <code>LuaSandboxFunction::call()</code>.</p>
     *
     * @link https://www.php.net/manual/en/luasandbox.wrapPhpFunction.php
     * @param callable $function <p>Callable to wrap.</p>
     * @return LuaSandboxFunction
     * @since luasandbox >= 1.2.0
     * @see LuaSandbox::loadString()
     * @see LuaSandbox::registerLibrary()
     */
    public function wrapPhpFunction($function) {}
}

/**
 * Represents a Lua function, allowing it to be called from PHP.
 *
 * <p>A <i>LuaSandboxFunction</i> may be obtained as a return value from Lua,
 * as a parameter passed to a callback from Lua,
 * or by using <code>LuaSandbox::wrapPhpFunction()</code>, <code>LuaSandbox::loadString()</code>,
 * or <code>LuaSandbox::loadBinary()</code>.</p>
 *
 * @since luasandbox >= 1.0.0
 */
class LuaSandboxFunction
{
    /**
     * Call a Lua function.
     *
     * <p>Errors considered to be the fault of the PHP code will result in the
     * function returning false and <i>E_WARNING</i> being raised, for example,
     * a resource type being used as an argument. Lua errors will result
     * in a LuaSandboxRuntimeError exception being thrown.</p>
     *
     * <p>PHP and Lua types are converted as follows:</p>
     * <ul>
     * <li>PHP NULL is Lua nil, and vice versa.</li>
     * <li>PHP integers and floats are converted to Lua numbers. Infinity and NAN are supported.</li>
     * <li>Lua numbers without a fractional part between approximately -2**53 and 2**53 are
     * converted to PHP integers, with others being converted to PHP floats.</li>
     * <li>PHP booleans are Lua booleans, and vice versa.</li>
     * <li>PHP strings are Lua strings, and vice versa.</li>
     * <li>Lua functions are PHP LuaSandboxFunction objects, and vice versa.
     * General PHP callables are not supported.</li>
     * <li>PHP arrays are converted to Lua tables, and vice versa.</li>
     * <ul>
     *   <li>Note that Lua typically indexes arrays from 1, while PHP indexes arrays from 0.
     *   No adjustment is made for these differing conventions.</li>
     *   <li>Self-referential arrays are not supported in either direction.</li>
     *   <li>PHP references are dereferenced.</li>
     *   <li>Lua <i>__pairs</i> and <i>__ipairs</i> are processed. <i>__index</i> is ignored.</li>
     *   <li>When converting from PHP to Lua, integer keys between -2**53 and 2**53 are represented as Lua numbers.
     *   All other keys are represented as Lua strings.</li>
     *   <li>When converting from Lua to PHP, keys other than strings and numbers will result in an error,
     *   as will collisions when converting numbers to strings or vice versa
     *   (since PHP considers things like <code>$a[0]</code> and <code>$a["0"]</code> as being equivalent).</li>
     * </ul>
     * <li>All other types are unsupported and will raise an error/exception,
     * including general PHP objects and Lua userdata and thread types.</li>
     * </ul>
     *
     * <p>Lua functions inherently return a list of results. So on success,
     * this method returns an array containing all of the values returned by Lua,
     * with integer keys starting from zero.
     * Lua may return no results, in which case an empty array is returned.</p>
     *
     * @link https://www.php.net/manual/en/luasandboxfunction.call.php
     * @param string[] $arguments <p>Arguments passed to the function.</p>
     * @return array|bool <p>Returns an array of values returned by the function,
     * which may be empty, or false on error.</p>
     * @since luasandbox >= 1.0.0
     */
    public function call($arguments) {}

    /**
     * Dump the function as a binary blob.
     *
     * @link https://www.php.net/manual/en/luasandboxfunction.dump.php
     * @return string <p>Returns a string that may be passed to <code>LuaSandbox::loadBinary()</code>.</p>
     * @since luasandbox >= 1.0.0
     */
    public function dump() {}
}

/**
 * Base class for LuaSandbox exceptions.
 *
 * @since luasandbox >= 1.0.0
 */
class LuaSandboxError extends Exception
{
    public const RUN = 2;
    public const SYNTAX = 3;
    public const MEM = 4;
    public const ERR = 5;
}

/**
 * Catchable <i>LuaSandbox</i> runtime exceptions.
 *
 * <p>These may be caught inside Lua using <code>pcall()</code> or <code>xpcall()</code>.</p>
 *
 * @since luasandbox >= 1.0.0
 */
class LuaSandboxRuntimeError extends LuaSandboxError {}

/**
 * Uncatchable <i>LuaSandbox</i> exceptions.
 *
 * <p>These may not be caught inside Lua using <code>pcall()</code> or <code>xpcall()</code>.</p>
 *
 * @since luasandbox >= 1.0.0
 */
class LuaSandboxFatalError extends LuaSandboxError {}

/**
 * Exception thrown when Lua encounters an error inside an error handler.
 *
 * @since luasandbox >= 1.0.0
 */
class LuaSandboxErrorError extends LuaSandboxFatalError {}

/**
 * Exception thrown when Lua cannot allocate memory.
 *
 * @since luasandbox >= 1.0.0
 * @see LuaSandbox::setMemoryLimit()
 */
class LuaSandboxMemoryError extends LuaSandboxFatalError {}

/**
 * Exception thrown when Lua code cannot be parsed.
 *
 * @since luasandbox >= 1.0.0
 */
class LuaSandboxSyntaxError extends LuaSandboxFatalError {}

/**
 * Exception thrown when the configured CPU time limit is exceeded.
 *
 * @since luasandbox >= 1.0.0
 * @see LuaSandbox::setCPULimit()
 */
class LuaSandboxTimeoutError extends LuaSandboxFatalError {}
