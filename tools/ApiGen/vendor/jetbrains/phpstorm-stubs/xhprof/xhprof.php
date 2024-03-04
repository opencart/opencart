<?php

// Start of xhprof v.0.9.4

/**
 * (PHP &gt;= 5.2.0, PECL xhprof &gt;= 0.9.0)<br/>
 * Start xhprof profiler.
 *
 * @link https://php.net/manual/en/function.xhprof-enable.php
 *
 * @param int   $flags   <p>Optional flags to add additional information to the profiling. See the a
 *                       href="https://secure.php.net/manual/en/xhprof.constants.php">XHprof constants</a> for further
 *                       information about these flags, e.g., <strong>XHPROF_FLAGS_MEMORY</strong> to enable memory
 *                       profiling.</p>
 * @param array $options [optional] <p>An array of optional options, namely, the 'ignored_functions' option to pass in functions
 *                       to be ignored during profiling.</p>
 *
 * @return null
 */
function xhprof_enable($flags = 0, array $options = []) {}

/**
 * (PHP &gt;= 5.2.0, PECL xhprof &gt;= 0.9.0)<br/>
 * Stops the profiler, and returns xhprof data from the run.
 *
 * @link https://php.net/manual/en/function.xhprof-disable.php
 * @return array an array of xhprof data, from the run.
 */
function xhprof_disable() {}

/**
 * (PHP &gt;= 5.2.0, PECL xhprof &gt;= 0.9.0)<br/>
 * Starts profiling in sample mode, which is a lighter weight version of {@see xhprof_enable()}. The sampling interval
 * is 0.1 seconds, and samples record the full function call stack. The main use case is when lower overhead is
 * required when doing performance monitoring and diagnostics.
 *
 * @link https://php.net/manual/en/function.xhprof-sample-enable.php
 * @return null
 */
function xhprof_sample_enable() {}

/**
 * (PHP &gt;= 5.2.0, PECL xhprof &gt;= 0.9.0)<br/>
 * Stops the sample mode xhprof profiler, and returns xhprof data from the run.
 *
 * @link https://php.net/manual/en/function.xhprof-sample-disable.php
 * @return array an array of xhprof sample data, from the run.
 */
function xhprof_sample_disable() {}

/**
 * @link https://php.net/manual/en/xhprof.constants.php#constant.xhprof-flags-no-builtins
 */
const XHPROF_FLAGS_NO_BUILTINS = 1;
/**
 * @link https://php.net/manual/en/xhprof.constants.php#constant.xhprof-flags-cpu
 */
const XHPROF_FLAGS_CPU = 2;
/**
 * @link https://php.net/manual/en/xhprof.constants.php##constant.xhprof-flags-memory
 */
const XHPROF_FLAGS_MEMORY = 4;

// End of xhprof v.0.9.4
