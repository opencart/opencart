<?php

namespace Elastic\Apm;

/**
 * Class ElasticApm is a facade (as in Facade design pattern) to the rest of Elastic APM public API.
 */
final class ElasticApm
{
    public const VERSION = '1.3.1';

    private function __construct() {}

    /**
     * Begins a new transaction and sets it as the current transaction.
     *
     * @param string      $name                      New transaction's name
     * @param string      $type                      New transaction's type
     * @param float|null  $timestamp                 Start time of the new transaction
     * @param string|null $serializedDistTracingData - DEPRECATED since version 1.3 -
     *                                               use newTransaction()->distributedTracingHeaderExtractor() instead
     *
     * @return TransactionInterface New transaction
     *
     * @see TransactionInterface::setName() For the description.
     * @see TransactionInterface::setType() For the description.
     * @see TransactionInterface::getTimestamp() For the description.
     */
    public static function beginCurrentTransaction(
        string $name,
        string $type,
        ?float $timestamp = null,
        ?string $serializedDistTracingData = null
    ): TransactionInterface {}

    /**
     * Begins a new transaction, sets as the current transaction,
     * runs the provided callback as the new transaction and automatically ends the new transaction.
     *
     * @param string      $name      New transaction's name
     * @param string      $type      New transaction's type
     * @param \Closure     $callback  Callback to execute as the new transaction
     * @param float|null  $timestamp Start time of the new transaction
     * @param string|null $serializedDistTracingData - DEPRECATED since version 1.3 -
     *                                               use newTransaction()->distributedTracingHeaderExtractor() instead
     *
     * @return mixed The return value of $callback
     *
     * @see             TransactionInterface::setName() For the description.
     * @see             TransactionInterface::setType() For the description.
     * @see             TransactionInterface::getTimestamp() For the description.
     */
    public static function captureCurrentTransaction(
        string $name,
        string $type,
        \Closure $callback,
        ?float $timestamp = null,
        ?string $serializedDistTracingData = null
    ) {}

    /**
     * Returns the current transaction.
     *
     * @return TransactionInterface The current transaction
     */
    public static function getCurrentTransaction(): TransactionInterface {}

    /**
     * If there is the current span then it returns the current span.
     * Otherwise if there is the current transaction then it returns the current transaction.
     * Otherwise it returns the noop execution segment.
     *
     * @return ExecutionSegmentInterface The current execution segment
     */
    public static function getCurrentExecutionSegment(): ExecutionSegmentInterface {}

    /**
     * Begins a new transaction.
     *
     * @param string      $name      New transaction's name
     * @param string      $type      New transaction's type
     * @param float|null  $timestamp Start time of the new transaction
     * @param string|null $serializedDistTracingData - DEPRECATED since version 1.3 -
     *                                               use newTransaction()->distributedTracingHeaderExtractor() instead
     *
     * @return TransactionInterface New transaction
     *
     * @see TransactionInterface::setName() For the description.
     * @see TransactionInterface::setType() For the description.
     * @see TransactionInterface::getTimestamp() For the description.
     */
    public static function beginTransaction(
        string $name,
        string $type,
        ?float $timestamp = null,
        ?string $serializedDistTracingData = null
    ): TransactionInterface {}

    /**
     * Begins a new transaction,
     * runs the provided callback as the new transaction and automatically ends the new transaction.
     *
     * @param string      $name      New transaction's name
     * @param string      $type      New transaction's type
     * @param \Closure     $callback  Callback to execute as the new transaction
     * @param float|null  $timestamp Start time of the new transaction
     * @param string|null $serializedDistTracingData - DEPRECATED since version 1.3 -
     *                                               use newTransaction()->distributedTracingHeaderExtractor() instead
     *
     * @return mixed The return value of $callback
     *
     * @see             TransactionInterface::setName() For the description.
     * @see             TransactionInterface::setType() For the description.
     * @see             TransactionInterface::getTimestamp() For the description.
     */
    public static function captureTransaction(
        string $name,
        string $type,
        \Closure $callback,
        ?float $timestamp = null,
        ?string $serializedDistTracingData = null
    ) {}

    /**
     * Advanced API to begin a new transaction
     *
     * @param string $name New transaction's name
     * @param string $type New transaction's type
     *
     * @return TransactionBuilderInterface New transaction builder
     *
     * @see TransactionInterface::setName() For the description.
     * @see TransactionInterface::setType() For the description.
     */
    public static function newTransaction(string $name, string $type): TransactionBuilderInterface {}

    /**
     * Creates an error based on the given Throwable instance
     * with the current execution segment (if there is one) as the parent.
     *
     * @param \Throwable $throwable
     *
     * @return string|null ID of the reported error event or null if no event was reported
     *                      (for example, because recording is disabled)
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/errors/error.json
     */
    public static function createErrorFromThrowable(\Throwable $throwable): ?string {}

    /**
     * Creates an error based on the given data
     * with the current execution segment (if there is one) as the parent.
     *
     * @param CustomErrorData $customErrorData
     *
     * @return string|null ID of the reported error event or null if no event was reported
     *                      (for example, because recording is disabled)
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/errors/error.json
     */
    public static function createCustomError(CustomErrorData $customErrorData): ?string {}

    /**
     * Pauses recording
     */
    public static function pauseRecording(): void {}

    /**
     * Resumes recording
     */
    public static function resumeRecording(): void {}

    /**
     * @deprecated      Deprecated since version 1.3 - use injectDistributedTracingHeaders() instead
     * @see             injectDistributedTracingHeaders() Use it instead of this method
     *
     * Returns distributed tracing data for the current span/transaction
     */
    public static function getSerializedCurrentDistributedTracingData(): string {}
}

/**
 * Class to gather optional parameters to start a new transaction
 *
 * @see             ElasticApm::beginCurrentTransaction()
 * @see             ElasticApm::captureCurrentTransaction()
 */
interface TransactionBuilderInterface
{
    /**
     * New transaction will be set as the current one
     *
     * @return TransactionBuilderInterface
     */
    public function asCurrent(): self;

    /**
     * Set start time of the new transaction
     *
     * @param float $timestamp
     *
     * @return TransactionBuilderInterface
     */
    public function timestamp(float $timestamp): self;

    /**
     * @param \Closure $headerExtractor
     *
     * @return TransactionBuilderInterface
     */
    public function distributedTracingHeaderExtractor(\Closure $headerExtractor): self;

    /**
     * Begins a new transaction.
     *
     * @return TransactionInterface New transaction
     */
    public function begin(): TransactionInterface;

    /**
     * Begins a new transaction,
     * runs the provided callback as the new transaction and automatically ends the new transaction.
     *
     * @param \Closure $callback
     *
     * @return mixed The return value of $callback
     */
    public function capture(\Closure $callback);
}

interface TransactionInterface extends ExecutionSegmentInterface
{
    /**
     * Transactions that are 'sampled' will include all available information
     * Transactions that are not sampled will not have 'spans' or 'context'.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/transactions/transaction.json#L72
     */
    public function isSampled(): bool;

    /**
     * Hex encoded 64 random bits ID of the parent transaction or span.
     * Only a root transaction of a trace does not have a parent ID, otherwise it needs to be set.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/transactions/transaction.json#L19
     */
    public function getParentId(): ?string;

    /**
     * Begins a new span with the current execution segment
     * as the new span's parent and sets as the new span as the current span for this transaction.
     * The current execution segment is the current span if there is one or this transaction itself otherwise.
     *
     * @param string      $name      New span's name.
     * @param string      $type      New span's type
     * @param string|null $subtype   New span's subtype
     * @param string|null $action    New span's action
     * @param float|null  $timestamp Start time of the new span
     *
     * @see SpanInterface::setName() For the description.
     * @see SpanInterface::setType() For the description.
     * @see SpanInterface::setSubtype() For the description.
     * @see SpanInterface::setAction() For the description.
     * @see SpanInterface::getTimestamp() For the description.
     *
     * @return SpanInterface New span
     */
    public function beginCurrentSpan(
        string $name,
        string $type,
        ?string $subtype = null,
        ?string $action = null,
        ?float $timestamp = null
    ): SpanInterface;

    /**
     * Begins a new span with the current execution segment as the new span's parent and
     * sets the new span as the current span for this transaction.
     * The current execution segment is the current span if there is one or this transaction itself otherwise.
     *
     * @param string      $name      New span's name
     * @param string      $type      New span's type
     * @param \Closure     $callback  Callback to execute as the new span
     * @param string|null $subtype   New span's subtype
     * @param string|null $action    New span's action
     * @param float|null  $timestamp Start time of the new span
     *
     * @see             SpanInterface::setName() For the description.
     * @see             SpanInterface::setType() For the description.
     * @see             SpanInterface::setSubtype() For the description.
     * @see             SpanInterface::setAction() For the description.
     * @see             SpanInterface::getTimestamp() For the description.
     *
     * @return mixed The return value of $callback
     */
    public function captureCurrentSpan(
        string $name,
        string $type,
        \Closure $callback,
        ?string $subtype = null,
        ?string $action = null,
        ?float $timestamp = null
    );

    /**
     * Returns the current span.
     *
     * @return SpanInterface The current span
     */
    public function getCurrentSpan(): SpanInterface;

    /**
     * Returns context (context allows to set labels, etc.)
     */
    public function context(): TransactionContextInterface;

    /**
     * The result of the transaction.
     * For HTTP-related transactions, this should be the status code formatted like 'HTTP 2xx'.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/transactions/transaction.json#L52
     *
     * @param string|null $result
     *
     * @return void
     */
    public function setResult(?string $result): void;

    /**
     * @see setResult() For the description
     */
    public function getResult(): ?string;

    /**
     * If the transaction does not have a parent ID yet,
     * calling this method generates a new ID,
     * sets it as the parent ID of this transaction, and returns it as a string.
     *
     * @return string
     */
    public function ensureParentId(): string;
}

interface SpanInterface extends ExecutionSegmentInterface
{
    /**
     * Hex encoded 64 random bits ID of the correlated transaction.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L14
     */
    public function getTransactionId(): string;

    /**
     * Hex encoded 64 random bits ID of the parent.
     * If this span is the root span of the correlated transaction the its parent is the correlated transaction
     * otherwise its parent is the parent span.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L24
     */
    public function getParentId(): string;

    /**
     * The specific kind of event within the sub-type represented by the span
     * e.g., 'query' for type/sub-type 'db'/'mysql', 'connect' for type/sub-type 'db'/'cassandra'
     *
     * The length of this string is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L38
     *
     * @param string|null $action
     *
     * @return void
     */
    public function setAction(?string $action): void;

    /**
     * A further sub-division of the type
     * e.g., 'mysql', 'postgresql' or 'elasticsearch' for type 'db', 'http' for type 'external', etc.
     *
     * The length of this string is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L33
     *
     * @param string|null $subtype
     */
    public function setSubtype(?string $subtype): void;

    /**
     * Returns context (context allows to set labels, etc.)
     */
    public function context(): SpanContextInterface;

    /**
     * Extended version of ExecutionSegmentInterface::end()
     *
     * @param int        $numberOfStackFramesToSkip Number of stack frames to skip when capturing stack trace.
     * @param float|null $duration                  In milliseconds with 3 decimal points.
     *
     * @see ExecutionSegmentInterface::end() For the description
     */
    public function endSpanEx(int $numberOfStackFramesToSkip, ?float $duration = null): void;
}

interface SpanContextInterface extends ExecutionSegmentContextInterface
{
    /**
     * Returns an object containing contextual data for database spans
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L47
     */
    public function db(): SpanContextDbInterface;

    /**
     * Returns an object containing contextual data of the related http request
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L69
     */
    public function http(): SpanContextHttpInterface;

    /**
     * Returns an object containing contextual data about the destination for spans
     *
     * @link https://github.com/elastic/apm-server/blob/7.6/docs/spec/spans/span.json#L44
     */
    public function destination(): SpanContextDestinationInterface;
}

interface SpanContextDbInterface
{
    /**
     * A database statement (e.g. query) for the given database type
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L55
     *
     * @param string|null $statement
     *
     * @return void
     */
    public function setStatement(?string $statement): void;
}

interface SpanContextHttpInterface
{
    /**
     * The raw url of the correlating http request
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L73
     *
     * @param string|null $url
     *
     * @return void
     */
    public function setUrl(?string $url): void;

    /**
     * The status code of the http request
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L77
     *
     * @param int|null $statusCode
     *
     * @return void
     */
    public function setStatusCode(?int $statusCode): void;

    /**
     * The method of the http request
     *
     * The length of a value is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L81
     *
     * @param string|null $method
     *
     * @return void
     */
    public function setMethod(?string $method): void;
}

/**
 * An object containing contextual data about the destination for spans
 *
 * @link https://github.com/elastic/apm-server/blob/7.6/docs/spec/spans/span.json#L44
 */
interface SpanContextDestinationInterface
{
    /**
     * Sets destination service context
     *
     * @link https://github.com/elastic/apm-server/blob/v7.11.0/docs/spec/v2/span.json#L106
     *
     * @param string $name
     * @param string $resource
     * @param string $type
     */
    public function setService(string $name, string $resource, string $type): void;
}

/**
 * This interface has functionality shared between Transaction and Span.
 */
interface ExecutionSegmentInterface
{
    /**
     * Hex encoded 64 random bits (== 8 bytes == 16 hex digits) ID.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/transactions/transaction.json#L9
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L9
     */
    public function getId(): string;

    /**
     * Hex encoded 128 random bits (== 16 bytes == 32 hex digits) ID of the correlated trace.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/transactions/transaction.json#L14
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L19
     */
    public function getTraceId(): string;

    /**
     * Recorded time of the event.
     * For events that have non-zero duration this time corresponds to the start of the event.
     * UTC based and in microseconds since Unix epoch.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/transactions/transaction.json#L6
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L6
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/timestamp_epoch.json#L7
     */
    public function getTimestamp(): float;

    /**
     * Begins a new span with this execution segment as the new span's parent.
     *
     * @param string      $name      New span's name
     * @param string      $type      New span's type
     * @param string|null $subtype   New span's subtype
     * @param string|null $action    New span's action
     * @param float|null  $timestamp Start time of the new span
     *
     * @see SpanInterface::setName() For the description.
     * @see SpanInterface::setType() For the description.
     * @see SpanInterface::setSubtype() For the description.
     * @see SpanInterface::setAction() For the description.
     * @see SpanInterface::setTimestamp() For the description.
     *
     * @return SpanInterface New span
     */
    public function beginChildSpan(
        string $name,
        string $type,
        ?string $subtype = null,
        ?string $action = null,
        ?float $timestamp = null
    ): SpanInterface;

    /**
     * Begins a new span with this execution segment as the new span's parent,
     * runs the provided callback as the new span and automatically ends the new span.
     *
     * @param string      $name      New span's name
     * @param string      $type      New span's type
     * @param \Closure     $callback  Callback to execute as the new span
     * @param string|null $subtype   New span's subtype
     * @param string|null $action    New span's action
     * @param float|null  $timestamp Start time of the new span
     *
     * @see             SpanInterface::setName() For the description.
     * @see             SpanInterface::setType() For the description.
     * @see             SpanInterface::setSubtype() For the description.
     * @see             SpanInterface::setAction() For the description.
     * @see             SpanInterface::setTimestamp() For the description.
     *
     * @return mixed The return value of $callback
     */
    public function captureChildSpan(
        string $name,
        string $type,
        \Closure $callback,
        ?string $subtype = null,
        ?string $action = null,
        ?float $timestamp = null
    );

    /**
     * - For transactions:
     *      The name of this transaction.
     *      Generic designation of a transaction in the scope of a single service (eg: 'GET /users/:id').
     *
     * - For spans:
     *      Generic designation of a span in the scope of a transaction.
     *
     * The length of this string is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/transactions/transaction.json#L47
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L136
     *
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * Type is a keyword of specific relevance in the service's domain
     * e.g.,
     *      - For transaction: 'db', 'external' for a span and 'request', 'backgroundjob' for a transaction, etc.
     *      - For span: 'db.postgresql.query', 'template.erb', etc.
     *
     * The length of this string is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/transactions/transaction.json#L57
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L149
     *
     * @param string $type
     */
    public function setType(string $type): void;

    /**
     * @deprecated      Deprecated since version 1.3 - use injectDistributedTracingHeaders() instead
     * @see             injectDistributedTracingHeaders() Use it instead of this method
     *
     * Returns distributed tracing data
     */
    public function getDistributedTracingData(): ?DistributedTracingData;

    /**
     * Returns distributed tracing data for the current span/transaction
     *
     * $headerInjector is callback to inject headers with signature
     *
     *      (string $headerName, string $headerValue): void
     *
     * @param \Closure $headerInjector Callback that actually injects header(s) for the underlying transport
     */
    public function injectDistributedTracingHeaders(\Closure $headerInjector): void;

    /**
     * Sets the end timestamp and finalizes this object's state.
     *
     * If any mutating method (for example any `set...` method is a mutating method)
     * is called on a instance which has already then a warning is logged.
     * For example, end() is a mutating method as well.
     *
     * @param float|null $duration In milliseconds with 3 decimal points.
     */
    public function end(?float $duration = null): void;

    /**
     * Returns true if this execution segment has already ended.
     */
    public function hasEnded(): bool;

    /**
     * Creates an error based on the given Throwable instance with this execution segment as the parent.
     *
     * @param \Throwable $throwable
     *
     * @return string|null ID of the reported error event or null if no event was reported
     *                      (for example, because recording is disabled)
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/errors/error.json
     */
    public function createErrorFromThrowable(\Throwable $throwable): ?string;

    /**
     * Creates an error based on the given Throwable instance with this execution segment as the parent.
     *
     * @param CustomErrorData $customErrorData
     *
     * @return string|null ID of the reported error event or null if no event was reported
     *                      (for example, because recording is disabled)
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/errors/error.json
     */
    public function createCustomError(CustomErrorData $customErrorData): ?string;

    /**
     * The outcome of the transaction/span: success, failure, or unknown.
     * Outcome may be one of a limited set of permitted values
     * describing the success or failure of the transaction/span.
     * This field can be used for calculating error rates for incoming/outgoing requests.
     *
     * @link https://github.com/elastic/apm-server/blob/v7.10.0/docs/spec/transactions/transaction.json#L59
     * @link https://github.com/elastic/apm-server/blob/v7.10.0/docs/spec/spans/span.json#L54
     * @link https://github.com/elastic/apm-server/blob/v7.10.0/docs/spec/outcome.json
     *
     * @param string|null $outcome
     *
     * @return void
     */
    public function setOutcome(?string $outcome): void;

    /**
     * @see setOutcome() For the description
     */
    public function getOutcome(): ?string;

    /**
     * Returns true if this execution segment is a no-op (for example when recording is disabled).
     */
    public function isNoop(): bool;

    /**
     * Discards this execution segment.
     */
    public function discard(): void;
}

final class DistributedTracingData
{
    /** @var string */
    public $traceId;

    /** @var string */
    public $parentId;

    /** @var bool */
    public $isSampled;

    /**
     * @deprecated Deprecated since version 1.3 - use injectHeaders() instead
     * @see             injectHeaders() Use it instead of this method
     *
     * Returns distributed tracing data for the current span/transaction
     */
    public function serializeToString(): string {}

    /**
     * Gets distributed tracing data for the current span/transaction
     *
     * $headerInjector is callback to inject headers with signature
     *
     *      (string $headerName, string $headerValue): void
     *
     * @param \Closure $headerInjector Callback that actually injects header(s) for the underlying transport
     */
    public function injectHeaders(\Closure $headerInjector): void {}
}

/**
 * Data to create custom error event
 *
 * @see  ElasticApm::createCustomError
 *
 * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/errors/error.json#L53
 *
 * Code in this file is part of implementation internals and thus it is not covered by the backward compatibility.
 */
class CustomErrorData
{
    /**
     * @var int|string|null
     *
     * The error code set when the error happened, e.g. database error code
     *
     * The length of a string value is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/errors/error.json#L56
     */
    public $code = null;

    /**
     * @var string|null
     *
     * The original error message
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/errors/error.json#L61
     */
    public $message = null;

    /**
     * @var string|null
     *
     * Describes the exception type's module namespace
     *
     * The length of a value is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/errors/error.json#L65
     */
    public $module = null;

    /**
     * @var string|null
     *
     * The length of a value is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/errors/error.json#L80
     */
    public $type = null;
}

interface TransactionContextInterface extends ExecutionSegmentContextInterface
{
    /**
     * Returns an object that can be used to collect information about HTTP request
     *
     * @link https://github.com/elastic/apm-server/blob/v7.0.0/docs/spec/context.json#L43
     * @link https://github.com/elastic/apm-server/blob/v7.0.0/docs/spec/request.json
     */
    public function request(): TransactionContextRequestInterface;
}

/**
 * This interface has functionality shared between Transaction and Span contexts'.
 */
interface ExecutionSegmentContextInterface
{
    /**
     * @param string                     $key
     * @param string|bool|int|float|null $value
     *
     * Labels is a flat mapping of user-defined labels with string keys and null, string, boolean or number values.
     *
     * The length of a key and a string value is limited to 1024.
     *
     * @return void
     *
     * @link    https://github.com/elastic/apm-server/blob/7.0/docs/spec/transactions/transaction.json#L40
     * @link    https://github.com/elastic/apm-server/blob/7.0/docs/spec/context.json#L46
     * @link    https://github.com/elastic/apm-server/blob/7.0/docs/spec/spans/span.json#L88
     * @link    https://github.com/elastic/apm-server/blob/7.0/docs/spec/tags.json
     */
    public function setLabel(string $key, $value): void;
}

interface TransactionContextRequestInterface
{
    /**
     * HTTP method
     *
     * The length of a value is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/v7.0.0/docs/spec/request.json#L33
     *
     * @param string $method
     *
     * @return void
     */
    public function setMethod(string $method): void;

    /**
     * Returns an object that can be used to collect information about HTTP request's URL
     *
     * @link https://github.com/elastic/apm-server/blob/7.0/docs/spec/request.json#L50
     */
    public function url(): TransactionContextRequestUrlInterface;
}

interface TransactionContextRequestUrlInterface
{
    /**
     * The domain of the request, e.g. 'example.com'
     *
     * The length of a value is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/v7.0.0/docs/spec/request.json#L69
     *
     * @param ?string $domain
     *
     * @return void
     */
    public function setDomain(?string $domain): void;

    /**
     * The full, possibly agent-assembled URL of the request
     *
     * The length of a value is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/v7.0.0/docs/spec/request.json#L64
     *
     * @param ?string $full
     *
     * @return void
     */
    public function setFull(?string $full): void;

    /**
     * The raw, unparsed URL of the HTTP request line, e.g https://example.com:443/search?q=elasticsearch.
     * This URL may be absolute or relative.
     * For more details, see https://www.w3.org/Protocols/rfc2616/rfc2616-sec5.html#sec5.1.2
     *
     * The length of a value is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/v7.0.0/docs/spec/request.json#L54
     *
     * @param ?string $original
     *
     * @return void
     */
    public function setOriginal(?string $original): void;

    /**
     * The path of the request, e.g. '/search'
     *
     * The length of a value is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/v7.0.0/docs/spec/request.json#L79
     *
     * @param ?string $path
     *
     * @return void
     */
    public function setPath(?string $path): void;

    /**
     * The port of the request, e.g. 443
     *
     * @link https://github.com/elastic/apm-server/blob/v7.0.0/docs/spec/request.json#L74
     *
     * @param ?int $port
     *
     * @return void
     */
    public function setPort(?int $port): void;

    /**
     * The protocol of the request, e.g. 'http'
     *
     * The length of a value is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/v7.0.0/docs/spec/request.json#L59
     *
     * @param ?string $protocol
     *
     * @return void
     */
    public function setProtocol(?string $protocol): void;

    /**
     * Sets the query string information of the request.
     * It is expected to have values delimited by ampersands.
     *
     * The length of a value is limited to 1024.
     *
     * @link https://github.com/elastic/apm-server/blob/v7.0.0/docs/spec/request.json#L84
     *
     * @param ?string $query
     *
     * @return void
     */
    public function setQuery(?string $query): void;
}
