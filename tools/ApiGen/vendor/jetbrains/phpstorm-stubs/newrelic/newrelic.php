<?php

namespace {
use JetBrains\PhpStorm\Deprecated;

/**
 * Attaches a custom attribute (key/value pair) to the current transaction and the current span (if enabled).
 *
 * Add a custom attribute (a key and a value data pair) to the current web transaction. (The call name is
 * newrelic_add_custom_parameter because "custom attributes" were previously called "custom parameters.") For
 * example,
 * you can add a customer's full name from your customer database. This attribute appears in any transaction trace
 * that results from this transaction. You can also query the Transaction event for your custom attributes.
 *
 * If the value given is a float with a value of NaN, Infinity, denorm or negative zero, the behavior of this
 * function
 * is undefined. For other floating point values, the agent may discard 1 or more bits of precision (ULPs) from the
 * given value.
 *
 * Returns true if the parameter was added successfully.
 *
 * Important: If you want to use your custom attributes, avoid using any of the reserved terms used by NRQL.
 *
 * @link  https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_add_custom_parameter/
 * @since 4.4.5.35
 *
 * @param string                $key   The name of the custom attribute. Only the first 255 characters are retained.
 * @param bool|float|int|string $value The value to associate with this custom attribute.
 *
 * @return bool
 */
function newrelic_add_custom_parameter(string $key, bool|float|int|string $value): bool {}

/**
 * Specify functions or methods for the agent to instrument with custom instrumentation.
 *
 * Specify functions or methods for the agent to target for custom instrumentation. This is the API equivalent of the
 * newrelic.transaction_tracer.custom setting. You cannot apply custom tracing to internal PHP functions.
 *
 * The name can be formatted either as function_name for procedural functions, or as "ClassName::method" for methods.
 * Both static and instance methods will be instrumented if the method syntax is used, and the class name must be
 * fully qualified: it must include the full namespace if the class was defined within a namespace.
 *
 * This function will return true if the tracer was added successfully.
 *
 * @link https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_add_custom_tracer/
 *
 * @param string $functionName
 *
 * @return bool
 */
function newrelic_add_custom_tracer(string $functionName): bool {}

/**
 * Manually specify that a transaction is a background job or a web transaction.
 *
 * Tell the agent to treat this "web" transaction as a "non-web" transaction (the APM UI separates web and non-web
 * transactions, for example in the Transactions page). Call as early as possible. This is most commonly used for
 * cron
 * jobs or other long-lived background tasks. However, this call is usually unnecessary since the agent usually
 * detects whether a transaction is a web or non-web transaction automatically.
 *
 * You can also reverse the functionality by setting the optional flag to false, which marks a "non-web" transaction
 * as a "web" transaction.
 *
 * @link https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_background_job/
 *
 * @param bool $flag [optional]
 *
 * @return void
 */
function newrelic_background_job(bool $flag = true): void {}

/**
 * Enable or disable the capture of URL parameters.
 *
 * Enable or disable the capture of URL parameters (everything after the ? in the URL). This overrides the
 * newrelic.capture_params config file setting.
 *
 * IMPORTANT: If you pass sensitive information directly in the URL, keep this disabled.
 *
 * @link https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_capture_params/
 *
 * @param bool $enable_flag [optional]
 *
 * @return void
 */
function newrelic_capture_params(bool $enable_flag = true): void {}

/**
 * Add a custom metric (in milliseconds) to time a component of your app not captured by default.
 *
 * Name your custom metrics with a Custom/ prefix (for example, Custom/MyMetric). This helps the UI organize your
 * custom metrics in one place, and it makes them easily findable via the Metric Explorer. Records timing in
 * milliseconds. For example: a value of 4 is stored as .004 seconds in New Relic's systems. If the value is NaN,
 * Infinity, denorm or negative zero, the behavior of this function is undefined. New Relic may discard 1 or more bits
 * of precision (ULPs) from the given value.
 *
 * This function will return true if the metric was added successfully.
 *
 * @link https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newreliccustommetric-php-agent-api/
 * @see  https://docs.newrelic.com/docs/agents/manage-apm-agents/agent-data/custom-metrics/
 *
 * @param string $metric_name
 * @param float  $value
 *
 * @return bool
 */
function newrelic_custom_metric(string $metric_name, float $value): bool {}

/**
 * Disable automatic injection of the browser monitoring snippet on particular pages.
 *
 * This call disables automatic injection of the browser monitoring agent for the current transaction. Call as early
 * as possible. You can use this call to remove the JavaScript if the insertion is causing problems or if you are
 * serving pages to third-party services that do not allow JavaScript (for example, Google's accelerated mobile
 * pages).
 *
 * Returns true if called within a New Relic transaction. Otherwise returns null if outside a transaction (for
 * example, if newrelic_end_transaction() has been called).
 *
 * @link https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_disable_autorum/
 *
 * @return bool|null
 */
function newrelic_disable_autorum(): ?bool {}

#[Deprecated(replacement: 'newrelic_capture_params()')]
function newrelic_enable_params() {}

/**
 * Stop timing the current transaction, but continue instrumenting it.
 *
 * Stop timing the web transaction immediately. Useful when a page is done with app code and is about to stream data
 * (file download, audio or video streaming, and so on), and you don't want streaming time to count as part of the
 * transaction run time. The agent sends data to the daemon at the end of the transaction.
 *
 * This is especially relevant when the time taken to complete the operation is completely outside the bounds of your
 * application. For example, a user on a very slow connection may take a very long time to download even small files,
 * and you wouldn't want that download time to skew the real transaction time.
 *
 * @link https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_end_of_transaction/
 *
 * @return void
 */
function newrelic_end_of_transaction(): void {}

/**
 * Stop instrumenting the current transaction immediately.
 *
 * Stop instrumenting the current transaction immediately, and send the data to the daemon. This call simulates what
 * the agent normally does when PHP terminates the current transaction. The most common use for this call is to
 * improve instrumentation of command line scripts that handle job queue processing. Call this method at the end of a
 * particular job, and then call newrelic_start_transaction() when a new task is pulled off the queue.
 *
 * Normally, when you end a transaction you want the agent to record the associated data. However, you can also
 * discard the data by setting $ignore to true.
 *
 * This function will return true if the transaction was successfully ended and data was sent to the New Relic
 * daemon.
 *
 * @link  https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_end_transaction/
 * @since 3.0.5.95
 *
 * @param bool $ignore [optional]
 *
 * @return bool
 */
function newrelic_end_transaction(bool $ignore = false): bool {}

/**
 * Returns a browser monitoring snippet to inject at the end of the HTML output.
 *
 * Returns a JavaScript string to inject at the very end of the HTML output. Use this call with
 * newrelic_get_browser_timing_header() to manually add browser monitoring to a webpage. If possible, New Relic
 * recommends instead enabling browser monitoring via the New Relic UI or copy/pasting the JavaScript snippet. For
 * instructions on using these options, see Browser monitoring and the PHP agent.
 *
 * If includeTags omitted or set to true, the returned JavaScript string will be enclosed in a "script"-tag.
 *
 * @link https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_get_browser_timing_footer/
 *
 * @param bool $includeTags [optional]
 *
 * @return string
 */
function newrelic_get_browser_timing_footer(bool $includeTags = true): string {}

/**
 * Returns a browser monitoring snippet to inject in the head of your HTML output.
 *
 * Returns a JavaScript string to inject in the "head"-tag of your HTML output. Use this call with
 * newrelic_get_browser_timing_footer to manually add browser monitoring to a webpage. If possible, New Relic
 * recommends instead enabling browser monitoring via the New Relic UI or copy/pasting the JavaScript snippet. For
 * instructions on using these options, see browser monitoring and PH
 *
 * If includeTags are omitted or set to true, the returned JavaScript string will be enclosed in a "script"-tag.
 *
 * @link https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_get_browser_timing_header/
 *
 * @param bool $includeTags
 *
 * @return string
 */
function newrelic_get_browser_timing_header(bool $includeTags = true): string {}

/**
 * Ignore the current transaction when calculating Apdex.
 *
 * Ignores the current transaction when calculating your Apdex score. This is useful when you have either very short
 * or very long transactions (such as file downloads) that can skew your Apdex score.
 *
 * @link https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_ignore_apdex/
 *
 * @return void
 */
function newrelic_ignore_apdex(): void {}

/**
 * Do not instrument the current transaction.
 *
 * Do not generate data for this transaction. This is useful when you have transactions that are particularly slow for
 * known reasons and you do not want them frequently generating transaction traces or skewing your site averages.
 *
 * @link https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_ignore_transaction/
 *
 * @return void
 */
function newrelic_ignore_transaction(): void {}

/**
 * Set custom name for current transaction.
 *
 * Sets the name of the transaction to the specified name. This can be useful if you have implemented your own
 * dispatching scheme and want to name transactions according to their purpose. Call this function as early as
 * possible.
 *
 * IMPORTANT: Do not use brackets [suffix] at the end of your transaction name. New Relic automatically strips
 * brackets from the name. Instead, use parentheses (suffix) or other symbols if needed.
 *
 * Unique values like URLs, Page Titles, Hex Values, Session IDs, and uniquely identifiable values should not be used
 * in naming your transactions. Instead, add that data to the transaction as a custom parameter with the
 * newrelic_add_custom_parameter() call.
 *
 * IMPORTANT: Do not create more than 1000 unique transaction names (for example, avoid naming by URL if possible).
 * This will make your charts less useful, and you may run into limits New Relic sets on the number of unique
 * transaction names per account. It also can slow down the performance of your application.
 *
 * Returns true if the transaction name was successfully changed. If false is returned, check the agent log for more
 * information.
 *
 * @link https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_name_transaction/
 *
 * @param string $name Name of the transaction.
 *
 * @return bool
 */
function newrelic_name_transaction(string $name): bool {}

/**
 * Use these calls to collect errors that the PHP agent does not collect automatically and to set the callback for
 * your own error and exception handler.
 *
 * CAUTION: If you include an exception ($e), there are differences depending on the PHP version you are using:
 * - PHP version 5 or lower: You must pass a valid PHP Exception class.
 * - PHP version 7 or higher: You must pass a valid PHP Throwable interface.
 *
 * The PHP agent handles PHP errors and exceptions automatically for supported frameworks. If you want to collect
 * errors that are not handled automatically so that you can query for those errors in New Relic and view error
 * traces, you can use newrelic_notice_error.
 *
 * If you want to use your own error and exception handlers, you can set newrelic_notice_error as the callback.
 *
 * This function can handle a variable number of parameters. You can pass-in 1 or 5 parameters, depending on your use
 * case:
 * - newrelic_notice_error(string $message)
 * - newrelic_notice_error(Throwable|Exception $e)
 * - newrelic_notice_error(string $errstr, Throwable|Exception $e)
 * - newrelic_notice_error(int $errno, string $errstr, string $errfile, int $errline, string $errcontext)
 *
 * @link  https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_notice_error/
 * @since 2.6
 *
 * @param string|Throwable|Exception|int  $messageOrExceptionOrCode Either an error message, an exception object, or
 *                                                                 an
 *                                                                 integer error code
 * @param string|Throwable|Exception|null $errstrOrException       [optional] Required if first argument is an
 *                                                                 integer, Provide an error message that will be
 *                                                                 meaningful to you when it displays in error
 *                                                                 traces or an exception object.
 * @param string|null                     $errfile                  [optional] The name of the file that the error
 *                                                                 occurred in.
 * @param int|null                        $errline                  [optional] The line number where the error
 *                                                                 occurred.
 * @param string|null                     $errcontext               [optional] An array that points to the symbol
 *                                                                 table
 *                                                                 that was active when the error occurred.
 *
 * @return null
 */
function newrelic_notice_error(
    string|Throwable|Exception|int $messageOrExceptionOrCode,
    string|Throwable|Exception $errstrOrException = null,
    string $errfile = null,
    int $errline = null,
    string $errcontext = null
) {}

/**
 * Record a custom event with the given name and attributes
 *
 * Records a custom event for use in New Relic. For more information, see Inserting custom events with the PHP agent.
 *
 * TIP: When creating custom events, follow these rules:
 *
 * - The agent records a maximum of 10,000 events per minute. Limit the number of unique event type names that you
 * create, and do not generate these names dynamically.
 * - Avoid using reserved words and characters for the event and
 * attributes names.
 * - Ensure you do not exceed the event size and rate restrictions.
 *
 * @link  https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_record_custom_event/
 * @see   https://docs.newrelic.com/docs/telemetry-data-platform/custom-data/custom-events/apm-report-custom-events-attributes/
 * @since 4.18.0.89
 *
 * @param string $name       Name of the custom event.
 * @param array  $attributes Supply custom attributes as an associative array. Supply the attribute names as keys of
 *                           up to 255 characters, and supply the values as scalar values. Arrays and objects are not
 *                           supported.
 *
 * @return void
 */
function newrelic_record_custom_event(string $name, array $attributes): void {}

/**
 * Set the New Relic application name, which the New Relic UI uses to connect your data to the correct application.
 *
 * The recommended and preferred method for setting the New Relic application name is to use global or per-directory
 * INI settings to associate your application to the desired name at the start of execution.
 *
 * If you cannot edit your agent config file (for example, many shared hosting environments do not allow you to edit
 * config files), the newrelic_set_appname API call can be used to configure app name, license key (optional, for if
 * you use multiple accounts), and a true/false flag (optional, to determine whether to keep or discard previously
 * recorded data). To ensure maximal APM trace continuity, call this as early as possible. This API call will discard
 * all current transaction data and start a new transaction after it has reconnected with the given app name.
 *
 * For other app-naming options, see Name your PHP application. When possible, INI-based solutions are recommended.
 *
 * New Relic highly recommends that you call this function as soon as possible and in as shallow of a call stack as
 * possible. Applications are discrete entities in APM, and discontinuity of transaction traces is inherent to this
 * method's usage. After connecting with the new app name, the new transactions start reporting to the new application
 * without the context of the previous application or the data that has been reported to it.
 *
 * This method is intended to be called once, as each call to the API (even with the same app name) will cause the
 * current transaction data to be discarded and lead to further discontinuity of transaction traces.
 *
 * This function can be called with 1 to 3 parameters:
 * - newrelic_set_appname(string $name)
 * - newrelic_set_appname(string $name, string $license[, bool $xmit])
 *
 *
 * @link   https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_set_appname/
 * @since  3.1.5.111
 *
 * @param string $name    Name(s) of app metrics should be reported under in New Relic user interface. Uses the same
 *                        format as newrelic.appname and can set multiple application names by separating each with a
 *                        semi-colon ;.
 * @param string $license [optional] Required if you use multiple accounts
 * @param bool   $xmit    [optional] If false or omitted, the agent discards the current transaction and all data
 *                        captured up to this call is lost. If true, the agent sends the data that was gathered right
 *                        before executing this call. The data is associated with the old app name. This has a very
 *                        slight performance impact as it takes a few milliseconds for the agent to dump its data.
 *
 * @return bool Will return true if the application name was successfully changed.
 */
function newrelic_set_appname(string $name, string $license, bool $xmit = false): bool {}

/**
 * Create user-related custom attributes. newrelic_add_custom_parameter is more flexible.
 *
 * TIP: This call only allows you to assign values to pre-existing keys. For a more flexible method to create
 * key/value pairs, use newrelic_add_custom_parameter.
 *
 * As of release 4.4, calling newrelic_set_user_attributes("a", "b", "c"); is equivalent to calling
 * newrelic_add_custom_parameter("user", "a"); newrelic_add_custom_parameter("account", "b");
 * newrelic_add_custom_parameter("product", "c"); All three parameters are required, but they may be empty strings.
 *
 * @link  https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_set_user_attributes/
 * @since 3.1.5.111
 *
 * @param string $user_value    Specify a name or username to associate with this page view. This value is assigned
 *                              to
 *                              the user key.
 * @param string $account_value Specify the name of a user account to associate with this page view. This value is
 *                              assigned to the account key.
 * @param string $product_value Specify the name of a product to associate with this page view. This value is
 *                              assigned
 *                              to the product key.
 *
 * @return bool
 */
function newrelic_set_user_attributes(string $user_value, string $account_value, string $product_value): bool {}

/**
 * Start a new transaction manually. Usually used after manually ending a transaction with
 * newrelic_end_transaction(),
 * for example when separating tasks in a job queue manager. When instrumenting this new transaction, the agent
 * performs the same operations as when the script first started.
 *
 * This function will return true if the transaction was successfully started.
 *
 * @link  https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_start_transaction/
 * @since 3.0.5.95
 *
 * @param string      $appname The application name to associate with data from this transaction. Uses the same
 *                             format
 *                             as newrelic.appname and can set multiple application names by separating each with a
 *                             semicolon
 *                             ;. While this option is required, you can read the app name from newrelic.ini with
 *                             ini_get("newrelic.appname").
 * @param string|null $license [optional] Defaults to the license key set in the New Relic agent's newrelic.ini file.
 *                             Provide a different license key if you want the transaction to report to a different
 *                             New Relic account. If set, this license will supersede all per-directory and global
 *                             default licenses configured in INI files.
 *
 * @return bool
 */
function newrelic_start_transaction(string $appname, string $license = null): bool {}

/**
 * Records a datastore segment.
 *
 * Datastore segments appear in the Breakdown table and Databases tab of the Transactions
 * page in the New Relic UI. This function allows an unsupported datastore to be instrumented in the same way as the
 * PHP agent automatically instruments its supported datastores.
 *
 * The supported keys in the $parameters array are as follows:
 * - product: (string) Required. The name of the datastore product being used: for example, MySQL to indicate that
 * the
 * segment represents a query against a MySQL database.
 * - collection: (string) Optional. The table or collection being used or queried against.
 * - operation: (string) Optional. The operation being performed: for example, select for an SQL SELECT query, or set
 * for a Memcached set operation. While operations may be specified with any case, New Relic suggests using lowercase
 * to better line up with the operation names used by the PHP agent's automated datastore instrumentation.
 * - host: (string) Optional. The datastore host name.
 * - portPathOrId: (string) Optional. The port or socket used to connect to the datastore.
 * - databaseName: (string) Optional. The database name or number in use.
 * - query: (string) Optional. The query that was sent to the server. For security reasons, this value is only used
 * if
 * you set product to a supported datastore. This allows the agent to correctly obfuscate the query. The supported
 * product values (which are matched in a case insensitive manner) are: MySQL, MSSQL, Oracle, Postgres, SQLite,
 * Firebird, Sybase, and Informix.
 * - inputQueryLabel: (string) Optional. The name of the ORM in use (for example: Doctrine).
 * - inputQuery: (string) Optional. The input query that was provided to the ORM. For security reasons, and as with
 * the query parameter, this value will be ignored if the product is not a supported datastore.
 *
 * @link  https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelic_record_datastore_segment/
 * @since 7.5.0.199
 *
 * @param callable $func The function that should be timed to create the datastore segment.
 * @param array    $parameters
 *
 * @return mixed The return value of $callback is returned. If an error occurs, false is returned, and
 * an error at the E_WARNING level will be triggered
 */
function newrelic_record_datastore_segment(callable $func, array $parameters): mixed {}

/**
 * Accepts an array of distributed trace headers.
 *
 * Distributed tracing allows you to see the path that a request takes as it travels through a distributed system.
 * When distributed tracing is enabled, use newrelic_accept_distributed_trace_headers to accept a payload of headers.
 * These include both W3C Trace Context and New Relic distributed trace headers.
 *
 * It is possible to only accept only W3C Trace Context headers and disable the New Relic Distributed Tracing header
 * via the newrelic.distributed_tracing_exclude_newrelic_header INI setting.
 *
 * Returns True if the headers were accepted successfully, otherwise returns False.
 *
 * @link    https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelicacceptdistributedtraceheaders/
 * @example https://docs.newrelic.com/docs/agents/php-agent/features/distributed-tracing-php/#manual
 * @since   9.8
 *
 * @param array  $headers        An array containing distributed tracing headers.
 * @param string $transport_type [optional] A string overriding the default transport type.
 *
 * @return bool
 */
function newrelic_accept_distributed_trace_headers(array $headers, string $transport_type = 'HTTP'): bool {}

/**
 * Accepts a distributed trace payload.
 *
 * Distributed tracing allows you to see the path that a request takes as it travels through a distributed system.
 *
 * @link       https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelicacceptdistributedtracepayload-php-agent-api/
 * @example    https://docs.newrelic.com/docs/agents/php-agent/features/distributed-tracing-php/#manual
 * @since      8.4
 * @deprecated 9.10
 *
 * @param string $payload A JSON formatted string created by using newrelic_create_distributed_trace_payload.
 *
 * @return void
 */
#[Deprecated(replacement: 'newrelic_accept_distributed_trace_headers()')]
function newrelic_accept_distributed_trace_payload(string $payload): void {}

/**
 * Accepts a distributed trace payload that includes an HTTPSafe (Base64 encoded) JSON string.
 *
 * Distributed tracing allows you to see the path that a request takes as it travels through a distributed system.
 *
 * Returns true to indicate success, or false if an error occurs.
 *
 * @link       https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelicacceptdistributedtracepayloadhttpsafe-php-agent-api/
 * @example    https://docs.newrelic.com/docs/agents/php-agent/features/distributed-tracing-php/#manual
 * @since      8.4
 * @deprecated 9.10
 *
 * @param string $httpsafe_payload An HTTPSafe (Base64 encoded) JSON string representation of the payload.
 * @param string $transport_type   [optional] A string overriding the default transport type.
 *
 * @return bool
 */
#[Deprecated(replacement: 'newrelic_accept_distributed_trace_headers()')]
function newrelic_accept_distributed_trace_payload_httpsafe(
    string $httpsafe_payload,
    string $transport_type = 'HTTP'
): bool {}

/**
 * Attaches a custom attribute (key/value pair) to the current span.
 *
 * Add a custom attribute (a key and a value data pair) to the current span. (The call name is
 * newrelic_add_custom_span_parameter because "custom attributes" were previously called "custom parameters.") For
 * example, you can add a customer's full name from your customer database. This attribute appears in any span. You
 * can also query the Span for your custom attributes.
 *
 * IMPORTANT: On spans, attributes added with newrelic_add_custom_span_parameter will take precedence over attributes
 * added with newrelic_add_custom_parameter.
 *
 * IMPORTANT: If you want to use your custom attributes, avoid using any of the reserved terms used by NRQL.
 *
 * @link  https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelicaddcustomspanparameter-php-agent-api/
 * @since 9.12.0.268
 *
 * @param string                $key   The name of the custom attribute. Only the first 255 characters are retained.
 * @param bool|float|int|string $value The value to associate with this custom attribute.
 *
 * @return bool
 */
function newrelic_add_custom_span_parameter(string $key, bool|float|int|string $value): bool {}

/**
 *Creates a distributed trace payload.
 *
 * Distributed tracing allows you to see the path that a request takes as it travels through a distributed system.
 *
 * @link       https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newreliccreatedistributedtracepayload-php-agent-api/
 * @example    https://docs.newrelic.com/docs/agents/php-agent/features/distributed-tracing-php/#manual
 * @since      8.4
 * @deprecated 9.10
 *
 * @return     newrelic\DistributedTracePayload
 */
#[Deprecated(replacement: 'newrelic_insert_distributed_trace_headers()')]
function newrelic_create_distributed_trace_payload(): newrelic\DistributedTracePayload {}

/**
 * Returns a collection of metadata necessary for linking data to a trace or an entity.
 *
 * This call returns an opaque map of key-value pairs that can be used to correlate this application to other data in
 * the New Relic backend.
 *
 * @link https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelicgetlinkingmetadata/
 *
 * @return array
 */
function newrelic_get_linking_metadata(): array {}

/**
 * Returns an associative array containing the identifiers of the current trace and the parent span.
 *
 * Returns an associative array containing the identifiers of the current trace and the parent span. This information
 * is useful for integrating with third party distributed tracing tools, such as Zipkin.
 *
 * @link https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelicgettracemetadata/
 *
 * @return array
 */
function newrelic_get_trace_metadata(): array {}

/**
 * Inserts W3C Trace Context headers and New Relic Distributed Tracing headers into an outbound array of headers.
 *
 * Use newrelic_insert_distributed_trace_headers to manually add distributed tracing headers an array of outbound
 * headers. When Distributed Tracing is enabled, newrelic_insert_distributed_trace_headers will always insert W3C
 * trace context headers. It also, by default, inserts the New Relic Distributed Tracing header, but this can be
 * disabled via the newrelic.distributed_tracing_exclude_newrelic_header INI setting.
 *
 * The $headers argument is passed by reference, and therefore must be a variable as opposed to a literal.
 *
 * @link    https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelicinsertdistributedtraceheaders/
 * @example https://docs.newrelic.com/docs/agents/php-agent/features/distributed-tracing-php/#manual
 * @since   9.8
 *
 * @param array $headers An (optionally empty) array of outbound headers.
 *
 * @return bool True if any headers were successfully inserted into the provided array, otherwise returns False
 */
function newrelic_insert_distributed_trace_headers(array $headers): bool {}

/**
 * Returns a value indicating whether or not the current transaction is marked as sampled.
 *
 * @link  https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newrelicissampled/
 * @since 9.3
 *
 * @return bool
 */
function newrelic_is_sampled(): bool {}
}

namespace newrelic {
  /**
   * This object has two methods that will render a distributed trace payload as text.
   *
   * @link https://docs.newrelic.com/docs/agents/php-agent/php-agent-api/newreliccreatedistributedtracepayload-php-agent-api/#return-values
   * @see newrelic_create_distributed_trace_payload()
   * @since 8.4
   */
  class DistributedTracePayload
  {
    /**
     * Renders the payload as a JSON string
     *
     * @return string
     */
    public function text(): string {}

    /**
     * Renders the payload as an string suitable for transport via HTTP (query string, POST param, HTTP headers, etc.)
     *
     * @return string
     */
    public function httpSafe(): string {}
  }
}
