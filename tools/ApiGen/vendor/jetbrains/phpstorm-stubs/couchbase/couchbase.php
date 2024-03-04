<?php
/**
 * Couchbase extension stubs
 * Gathered from https://docs.couchbase.com/sdk-api/couchbase-php-client-3.2.2/index.html
 * Maintainer: sergey@couchbase.com
 *
 * https://github.com/couchbase/php-couchbase/tree/master/api
 */

/**
 * INI entries:
 *
 * * `couchbase.log_level` (string), default: `"WARN"`
 *
 *   controls amount of information, the module will send to PHP error log. Accepts the following values in order of
 *   increasing verbosity: `"FATAL"`, `"ERROR"`, `"WARN"`, `"INFO"`, `"DEBUG"`, `"TRACE"`.
 *
 * * `couchbase.encoder.format` (string), default: `"json"`
 *
 *   selects serialization format for default encoder (\Couchbase\defaultEncoder). Accepts the following values:
 *   * `"json"` - encodes objects and arrays as JSON object (using `json_encode()`), primitives written in stringified form,
 *      which is allowed for most of the JSON parsers as valid values. For empty arrays JSON array preferred, if it is
 *      necessary, use `new stdClass()` to persist empty JSON object. Note, that only JSON format considered supported by
 *      all Couchbase SDKs, everything else is private implementation (i.e. `"php"` format won't be readable by .NET SDK).
 *   * `"php"` - uses PHP serialize() method to encode the document.
 *
 * * `couchbase.encoder.compression` (string), default: `"none"`
 *
 *   selects compression algorithm. Also see related compression options below. Accepts the following values:
 *   * `"fastlz"` - uses FastLZ algorithm. The module might be configured to use system fastlz library during build,
 *     othewise vendored version will be used. This algorithm is always available.
 *   * `"zlib"` - uses compression implemented by libz. Might not be available, if the system didn't have libz headers
 *     during build phase. In this case \Couchbase\HAVE_ZLIB will be false.
 *   * `"off"` or `"none"` - compression will be disabled, but the library will still read compressed values.
 *
 * * `couchbase.encoder.compression_threshold` (int), default: `0`
 *
 *   controls minimum size of the document value in bytes to use compression. For example, if threshold 100 bytes,
 *   and the document size is 50, compression will be disabled for this particular document.
 *
 * * `couchbase.encoder.compression_factor` (float), default: `0.0`
 *
 *   controls the minimum ratio of the result value and original document value to proceed with persisting compressed
 *   bytes. For example, the original document consists of 100 bytes. In this case factor 1.0 will require compressor
 *   to yield values not larger than 100 bytes (100/1.0), and 1.5 -- not larger than 66 bytes (100/1.5).
 *
 * * `couchbase.decoder.json_arrays` (boolean), default: `true`
 *
 *   controls the form of the documents, returned by the server if they were in JSON format. When true, it will generate
 *   arrays of arrays, otherwise instances of stdClass.
 *
 * * `couchbase.pool.max_idle_time_sec` (int), default: `60`
 *
 *   controls the maximum interval the underlying connection object could be idle, i.e. without any data/query
 *   operations. All connections which idle more than this interval will be closed automatically. Cleanup function
 *   executed after each request using RSHUTDOWN hook.
 *
 * * `couchbase.allow_fallback_to_bucket_connection` (boolean), default: `false`
 *
 *   allows the library to switch to bucket connection when the connection string includes bucket name. It is useful
 *   when the application connects to older Couchbase Server, that does not have G3CP feature.
 *
 * @package Couchbase
 */

namespace Couchbase;

use JsonSerializable;
use Exception;
use Throwable;
use DateTimeInterface;

/**
 * An object which contains meta information of the document needed to enforce query consistency.
 */
interface MutationToken
{
    /**
     * Returns bucket name
     *
     * @return string
     */
    public function bucketName();

    /**
     * Returns partition number
     *
     * @return int
     */
    public function partitionId();

    /**
     * Returns UUID of the partition
     *
     * @return string
     */
    public function partitionUuid();

    /**
     * Returns the sequence number inside partition
     *
     * @return string
     */
    public function sequenceNumber();
}

/**
 * Interface for retrieving metadata such as errors and metrics generated during N1QL queries.
 */
interface QueryMetaData
{
    /**
     * Returns the query execution status
     *
     * @return string|null
     */
    public function status(): ?string;

    /**
     * Returns the identifier associated with the query
     *
     * @return string|null
     */
    public function requestId(): ?string;

    /**
     * Returns the client context id associated with the query
     *
     * @return string|null
     */
    public function clientContextId(): ?string;

    /**
     * Returns the signature of the query
     *
     * @return array|null
     */
    public function signature(): ?array;

    /**
     * Returns any warnings generated during query execution
     *
     * @return array|null
     */
    public function warnings(): ?array;

    /**
     * Returns any errors generated during query execution
     *
     * @return array|null
     */
    public function errors(): ?array;

    /**
     * Returns metrics generated during query execution such as timings and counts
     *
     * @return array|null
     */
    public function metrics(): ?array;

    /**
     * Returns the profile of the query if enabled
     *
     * @return array|null
     */
    public function profile(): ?array;
}

/**
 * Interface for retrieving metadata such as error counts and metrics generated during search queries.
 */
interface SearchMetaData
{
    /**
     * Returns the number of pindexes successfully queried
     *
     * @return int|null
     */
    public function successCount(): ?int;

    /**
     * Returns the number of errors messages reported by individual pindexes
     *
     * @return int|null
     */
    public function errorCount(): ?int;

    /**
     * Returns the time taken to complete the query
     *
     * @return int|null
     */
    public function took(): ?int;

    /**
     * Returns the total number of matches for this result
     *
     * @return int|null
     */
    public function totalHits(): ?int;

    /**
     * Returns the highest score of all documents for this search query.
     *
     * @return float|null
     */
    public function maxScore(): ?float;

    /**
     * Returns the metrics generated during execution of this search query.
     *
     * @return array|null
     */
    public function metrics(): ?array;
}

/**
 * Interface for retrieving metadata generated during view queries.
 */
interface ViewMetaData
{
    /**
     * Returns the total number of rows returned by this view query
     *
     * @return int|null
     */
    public function totalRows(): ?int;

    /**
     * Returns debug information for this view query if enabled
     *
     * @return array|null
     */
    public function debug(): ?array;
}

/**
 * Base interface for all results generated by KV operations.
 */
interface Result
{
    /**
     * Returns the CAS value for the document
     *
     * @return string|null
     */
    public function cas(): ?string;
}

/**
 * Interface for results created by the get operation.
 */
interface GetResult extends Result
{
    /**
     * Returns the content of the document fetched
     *
     * @return array|null
     */
    public function content(): ?array;

    /**
     * Returns the document expiration time or null if the document does not expire.
     *
     * Note, that this function will return expiry only when GetOptions had withExpiry set to true.
     *
     * @return DateTimeInterface|null
     */
    public function expiryTime(): ?DateTimeInterface;
}

/**
 * Interface for results created by the getReplica operation.
 */
interface GetReplicaResult extends Result
{
    /**
     * Returns the content of the document fetched
     *
     * @return array|null
     */
    public function content(): ?array;

    /**
     * Returns whether or not the document came from a replica server
     *
     * @return bool
     */
    public function isReplica(): bool;
}

/**
 * Interface for results created by the exists operation.
 */
interface ExistsResult extends Result
{
    /**
     * Returns whether or not the document exists
     *
     * @return bool
     */
    public function exists(): bool;
}

/**
 * Interface for results created by operations that perform mutations.
 */
interface MutationResult extends Result
{
    /**
     * Returns the mutation token generated during the mutation
     *
     * @return MutationToken|null
     */
    public function mutationToken(): ?MutationToken;
}

/**
 * Interface for results created by the counter operation.
 */
interface CounterResult extends MutationResult
{
    /**
     * Returns the new value of the counter
     *
     * @return int
     */
    public function content(): int;
}

/**
 * Interface for results created by the lookupIn operation.
 */
interface LookupInResult extends Result
{
    /**
     * Returns the value located at the index specified
     *
     * @param int $index the index to retrieve content from
     * @return object|null
     */
    public function content(int $index): ?object;

    /**
     * Returns whether or not the path at the index specified exists
     *
     * @param int $index the index to check for existence
     * @return bool
     */
    public function exists(int $index): bool;

    /**
     * Returns any error code for the path at the index specified
     *
     * @param int $index the index to retrieve the error code for
     * @return int
     */
    public function status(int $index): int;

    /**
     * Returns the document expiration time or null if the document does not expire.
     *
     * Note, that this function will return expiry only when LookupInOptions had withExpiry set to true.
     *
     * @return DateTimeInterface|null
     */
    public function expiryTime(): ?DateTimeInterface;
}

/**
 * Interface for results created by the mutateIn operation.
 */
interface MutateInResult extends MutationResult
{
    /**
     * Returns any value located at the index specified
     *
     * @param int $index the index to retrieve content from
     * @return array|null
     */
    public function content(int $index): ?array;
}

/**
 * Interface for retrieving results from N1QL queries.
 */
interface QueryResult
{
    /**
     * Returns metadata generated during query execution such as errors and metrics
     *
     * @return QueryMetaData|null
     */
    public function metaData(): ?QueryMetaData;

    /**
     * Returns the rows returns during query execution
     *
     * @return array|null
     */
    public function rows(): ?array;
}

/**
 * Interface for retrieving results from analytics queries.
 */
interface AnalyticsResult
{
    /**
     * Returns metadata generated during query execution
     *
     * @return QueryMetaData|null
     */
    public function metaData(): ?QueryMetaData;

    /**
     * Returns the rows returned during query execution
     *
     * @return array|null
     */
    public function rows(): ?array;
}

/**
 * A range (or bucket) for a term search facet result.
 * Counts the number of occurrences of a given term.
 */
interface TermFacetResult
{
    /**
     * @return string
     */
    public function term(): string;

    /**
     * @return int
     */
    public function count(): int;
}

/**
 * A range (or bucket) for a numeric range facet result. Counts the number of matches
 * that fall into the named range (which can overlap with other user-defined ranges).
 */
interface NumericRangeFacetResult
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return int|float|null
     */
    public function min();

    /**
     * @return int|float|null
     */
    public function max();

    /**
     * @return int
     */
    public function count(): int;
}

/**
 * A range (or bucket) for a date range facet result. Counts the number of matches
 * that fall into the named range (which can overlap with other user-defined ranges).
 */
interface DateRangeFacetResult
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return string|null
     */
    public function start(): ?string;

    /**
     * @return string|null
     */
    public function end(): ?string;

    /**
     * @return int
     */
    public function count(): int;
}

/**
 * Interface representing facet results.
 *
 * Only one method might return non-null value among terms(), numericRanges() and dateRanges().
 */
interface SearchFacetResult
{
    /**
     * The field the SearchFacet was targeting.
     *
     * @return string
     */
    public function field(): string;

    /**
     * The total number of *valued* facet results. Total = other() + terms (but doesn't include * missing()).
     *
     * @return int
     */
    public function total(): int;

    /**
     * The number of results that couldn't be faceted, missing the adequate value. Not matter how many more
     * buckets are added to the original facet, these result won't ever be included in one.
     *
     * @return int
     */
    public function missing(): int;

    /**
     * The number of results that could have been faceted (because they have a value for the facet's field) but
     * weren't, due to not having a bucket in which they belong. Adding a bucket can result in these results being
     * faceted.
     *
     * @return int
     */
    public function other(): int;

    /**
     * @return array of pairs string name to TermFacetResult
     */
    public function terms(): ?array;

    /**
     * @return array of pairs string name to NumericRangeFacetResult
     */
    public function numericRanges(): ?array;

    /**
     * @return array of pairs string name to DateRangeFacetResult
     */
    public function dateRanges(): ?array;
}

/**
 * Interface for retrieving results from search queries.
 */
interface SearchResult
{
    /**
     * Returns metadata generated during query execution
     *
     * @return SearchMetaData|null
     */
    public function metaData(): ?SearchMetaData;

    /**
     * Returns any facets returned by the query
     *
     * Array contains instances of SearchFacetResult
     * @return array|null
     */
    public function facets(): ?array;

    /**
     * Returns any rows returned by the query
     *
     * @return array|null
     */
    public function rows(): ?array;
}

/**
 * Interface for retrieving results from view queries.
 */
interface ViewResult
{
    /**
     * Returns metadata generated during query execution
     *
     * @return ViewMetaData|null
     */
    public function metaData(): ?ViewMetaData;

    /**
     * Returns any rows returned by the query
     *
     * @return array|null
     */
    public function rows(): ?array;
}

/**
 * Object for accessing a row returned as a part of the results from a viery query.
 */
class ViewRow
{
    /**
     * Returns the id of the row
     *
     * @return string|null
     */
    public function id(): ?string {}

    /**
     * Returns the key of the document
     */
    public function key() {}

    /**
     * Returns the value of the row
     */
    public function value() {}

    /**
     * Returns the corresponding document for the row, if enabled
     */
    public function document() {}
}

/**
 *  Base exception for exceptions that are thrown originating from Couchbase operations.
 */
class BaseException extends Exception implements Throwable
{
    /**
     * Returns the underling reference string, if any
     *
     * @return string|null
     */
    public function ref(): ?string {}

    /**
     * Returns the underling error context, if any
     *
     * @return object|null
     */
    public function context(): ?object {}
}

class RequestCanceledException extends BaseException implements Throwable {}

class RateLimitedException extends BaseException implements Throwable {}

class QuotaLimitedException extends BaseException implements Throwable {}

/**
 *  Thrown for exceptions that originate from underlying Http operations.
 */
class HttpException extends BaseException implements Throwable {}

class ParsingFailureException extends HttpException implements Throwable {}

class IndexNotFoundException extends HttpException implements Throwable {}

class PlanningFailureException extends HttpException implements Throwable {}

class IndexFailureException extends HttpException implements Throwable {}

class KeyspaceNotFoundException extends HttpException implements Throwable {}

/**
 *  Thrown for exceptions that originate from query operations.
 */
class QueryException extends HttpException implements Throwable {}

/**
 *  Thrown for exceptions that originate from query operations.
 */
class QueryErrorException extends QueryException implements Throwable {}

class DmlFailureException extends QueryException implements Throwable {}

class PreparedStatementException extends QueryException implements Throwable {}

class QueryServiceException extends QueryException implements Throwable {}

/**
 *  Thrown for exceptions that originate from search operations.
 */
class SearchException extends HttpException implements Throwable {}

/**
 *  Thrown for exceptions that originate from analytics operations.
 */
class AnalyticsException extends HttpException implements Throwable {}

/**
 *  Thrown for exceptions that originate from view operations.
 */
class ViewException extends HttpException implements Throwable {}

class PartialViewException extends HttpException implements Throwable {}

class BindingsException extends BaseException implements Throwable {}

class InvalidStateException extends BaseException implements Throwable {}

/**
 *  Base for exceptions that originate from key value operations
 */
class KeyValueException extends BaseException implements Throwable {}

/**
 *  Occurs when the requested document could not be found.
 */
class DocumentNotFoundException extends KeyValueException implements Throwable {}

/**
 *  Occurs when an attempt is made to insert a document but a document with that key already exists.
 */
class KeyExistsException extends KeyValueException implements Throwable {}

/**
 *  Occurs when a document has gone over the maximum size allowed by the server.
 */
class ValueTooBigException extends KeyValueException implements Throwable {}

/**
 *  Occurs when a mutation operation is attempted against a document that is locked.
 */
class KeyLockedException extends KeyValueException implements Throwable {}

/**
 *  Occurs when an operation has failed for a reason that is temporary.
 */
class TempFailException extends KeyValueException implements Throwable {}

/**
 *  Occurs when a sub-document operation targets a path which does not exist in the specified document.
 */
class PathNotFoundException extends KeyValueException implements Throwable {}

/**
 *  Occurs when a sub-document operation expects a path not to exists, but the path was found in the document.
 */
class PathExistsException extends KeyValueException implements Throwable {}

/**
 *  Occurs when a sub-document counter operation is performed and the specified delta is not valid.
 */
class InvalidRangeException extends KeyValueException implements Throwable {}

/**
 *  Occurs when a multi-operation sub-document operation is performed on a soft-deleted document.
 */
class KeyDeletedException extends KeyValueException implements Throwable {}

/**
 *  Occurs when an operation has been performed with a cas value that does not the value on the server.
 */
class CasMismatchException extends KeyValueException implements Throwable {}

/**
 *  Occurs when an invalid configuration has been specified for an operation.
 */
class InvalidConfigurationException extends BaseException implements Throwable {}

/**
 *  Occurs when the requested service is not available.
 */
class ServiceMissingException extends BaseException implements Throwable {}

/**
 *  Occurs when various generic network errors occur.
 */
class NetworkException extends BaseException implements Throwable {}

/**
 *  Occurs when an operation does not receive a response in a timely manner.
 */
class TimeoutException extends BaseException implements Throwable {}

/**
 *  Occurs when the specified bucket does not exist.
 */
class BucketMissingException extends BaseException implements Throwable {}

/**
 *  Occurs when the specified scope does not exist.
 */
class ScopeMissingException extends BaseException implements Throwable {}

/**
 *  Occurs when the specified collection does not exist.
 */
class CollectionMissingException extends BaseException implements Throwable {}

/**
 *  Occurs when authentication has failed.
 */
class AuthenticationException extends BaseException implements Throwable {}

/**
 *  Occurs when an operation is attempted with bad input.
 */
class BadInputException extends BaseException implements Throwable {}

/**
 *  Occurs when the specified durability could not be met for a mutation operation.
 */
class DurabilityException extends BaseException implements Throwable {}

/**
 *  Occurs when a subdocument operation could not be completed.
 */
class SubdocumentException extends BaseException implements Throwable {}

class QueryIndex
{
    public function name(): string {}

    public function isPrimary(): bool {}

    public function type(): string {}

    public function state(): string {}

    public function keyspace(): string {}

    public function indexKey(): array {}

    public function condition(): ?string {}
}

class CreateQueryIndexOptions
{
    public function condition(string $condition): CreateQueryIndexOptions {}

    public function ignoreIfExists(bool $shouldIgnore): CreateQueryIndexOptions {}

    public function numReplicas(int $number): CreateQueryIndexOptions {}

    public function deferred(bool $isDeferred): CreateQueryIndexOptions {}
}

class CreateQueryPrimaryIndexOptions
{
    public function indexName(string $name): CreateQueryPrimaryIndexOptions {}

    public function ignoreIfExists(bool $shouldIgnore): CreateQueryPrimaryIndexOptions {}

    public function numReplicas(int $number): CreateQueryPrimaryIndexOptions {}

    public function deferred(bool $isDeferred): CreateQueryPrimaryIndexOptions {}
}

class DropQueryIndexOptions
{
    public function ignoreIfNotExists(bool $shouldIgnore): DropQueryIndexOptions {}
}

class DropQueryPrimaryIndexOptions
{
    public function indexName(string $name): DropQueryPrimaryIndexOptions {}

    public function ignoreIfNotExists(bool $shouldIgnore): DropQueryPrimaryIndexOptions {}
}

class WatchQueryIndexesOptions
{
    public function watchPrimary(bool $shouldWatch): WatchQueryIndexesOptions {}
}

class QueryIndexManager
{
    public function getAllIndexes(string $bucketName): array {}

    public function createIndex(string $bucketName, string $indexName, array $fields, CreateQueryIndexOptions $options = null) {}

    public function createPrimaryIndex(string $bucketName, CreateQueryPrimaryIndexOptions $options = null) {}

    public function dropIndex(string $bucketName, string $indexName, DropQueryIndexOptions $options = null) {}

    public function dropPrimaryIndex(string $bucketName, DropQueryPrimaryIndexOptions $options = null) {}

    public function watchIndexes(string $bucketName, array $indexNames, int $timeout, WatchQueryIndexesOptions $options = null) {}

    public function buildDeferredIndexes(string $bucketName) {}
}

class CreateAnalyticsDataverseOptions
{
    public function ignoreIfExists(bool $shouldIgnore): CreateAnalyticsDataverseOptions {}
}

class DropAnalyticsDataverseOptions
{
    public function ignoreIfNotExists(bool $shouldIgnore): DropAnalyticsDataverseOptions {}
}

class CreateAnalyticsDatasetOptions
{
    public function ignoreIfExists(bool $shouldIgnore): CreateAnalyticsDatasetOptions {}

    public function condition(string $condition): CreateAnalyticsDatasetOptions {}

    public function dataverseName(string $dataverseName): CreateAnalyticsDatasetOptions {}
}

class DropAnalyticsDatasetOptions
{
    public function ignoreIfNotExists(bool $shouldIgnore): DropAnalyticsDatasetOptions {}

    public function dataverseName(string $dataverseName): DropAnalyticsDatasetOptions {}
}

class CreateAnalyticsIndexOptions
{
    public function ignoreIfExists(bool $shouldIgnore): CreateAnalyticsIndexOptions {}

    public function dataverseName(string $dataverseName): CreateAnalyticsIndexOptions {}
}

class DropAnalyticsIndexOptions
{
    public function ignoreIfNotExists(bool $shouldIgnore): DropAnalyticsIndexOptions {}

    public function dataverseName(string $dataverseName): DropAnalyticsIndexOptions {}
}

class ConnectAnalyticsLinkOptions
{
    public function linkName(bstring $linkName): ConnectAnalyticsLinkOptions {}

    public function dataverseName(string $dataverseName): ConnectAnalyticsLinkOptions {}
}

class DisconnectAnalyticsLinkOptions
{
    public function linkName(bstring $linkName): DisconnectAnalyticsLinkOptions {}

    public function dataverseName(string $dataverseName): DisconnectAnalyticsLinkOptions {}
}

class CreateAnalyticsLinkOptions
{
    public function timeout(int $arg): CreateAnalyticsLinkOptions {}
}

class ReplaceAnalyticsLinkOptions
{
    public function timeout(int $arg): ReplaceAnalyticsLinkOptions {}
}

class DropAnalyticsLinkOptions
{
    public function timeout(int $arg): DropAnalyticsLinkOptions {}
}

interface AnalyticsLinkType
{
    public const COUCHBASE = "couchbase";
    public const S3 = "s3";
    public const AZURE_BLOB = "azureblob";
}

class GetAnalyticsLinksOptions
{
    public function timeout(int $arg): DropAnalyticsLinkOptions {}

    /**
     * @param string $tupe restricts the results to the given link type.
     *
     * @see AnalyticsLinkType::COUCHBASE
     * @see AnalyticsLinkType::S3
     * @see AnalyticsLinkType::AZURE_BLOB
     */
    public function linkType(string $type): DropAnalyticsLinkOptions {}

    /**
     * @param string $dataverse restricts the results to a given dataverse, can be given in the form of "namepart" or "namepart1/namepart2".
     */
    public function dataverse(string $dataverse): DropAnalyticsLinkOptions {}

    /**
     * @param string $name restricts the results to the link with the specified name. If set then dataverse must also be set.
     */
    public function name(string $name): DropAnalyticsLinkOptions {}
}

interface AnalyticsEncryptionLevel
{
    public const NONE = "none";
    public const HALF = "half";
    public const FULL = "full";
}

class EncryptionSettings
{
    /**
     * Sets encryption level.
     *
     * @param string $level Accepted values are 'none', 'half', 'full'.
     *
     * @see AnalyticsEncryptionLevel::NONE
     * @see AnalyticsEncryptionLevel::HALF
     * @see AnalyticsEncryptionLevel::FULL
     *
     * @return EncryptionSettings
     */
    public function level(string $level) {}

    public function certificate(string $certificate) {}

    public function clientCertificate(string $certificate) {}

    public function clientKey(string $key) {}
}

interface AnalyticsLink {}

class CouchbaseRemoteAnalyticsLink implements AnalyticsLink
{
    /**
     * Sets name of the link
     *
     * @param string $name
     * @return CouchbaseRemoteAnalyticsLink
     */
    public function name(string $name): CouchbaseRemoteAnalyticsLink {}

    /**
     * Sets dataverse this link belongs to
     *
     * @param string $dataverse
     * @return CouchbaseRemoteAnalyticsLink
     */
    public function dataverse(string $dataverse): CouchbaseRemoteAnalyticsLink {}

    /**
     * Sets the hostname of the target Couchbase cluster
     *
     * @param string $hostname
     * @return CouchbaseRemoteAnalyticsLink
     */
    public function hostname(string $hostname): CouchbaseRemoteAnalyticsLink {}

    /**
     * Sets the username to use for authentication with the remote cluster.
     *
     * Optional if client-certificate authentication is being used.
     *
     * @param string $username
     * @return CouchbaseRemoteAnalyticsLink
     */
    public function username(string $username): CouchbaseRemoteAnalyticsLink {}

    /**
     * Sets the password to use for authentication with the remote cluster.
     *
     * Optional if client-certificate authentication is being used.
     *
     * @param string $password
     * @return CouchbaseRemoteAnalyticsLink
     */
    public function password(string $password): CouchbaseRemoteAnalyticsLink {}

    /**
     * Sets settings for connection encryption
     *
     * @param EncryptionSettings $settings
     * @return CouchbaseRemoteAnalyticsLink
     */
    public function encryption(EncryptionSettings $settings): CouchbaseRemoteAnalyticsLink {}
}

class AzureBlobExternalAnalyticsLink implements AnalyticsLink
{
    /**
     * Sets name of the link
     *
     * @param string $name
     * @return AzureBlobExternalAnalyticsLink
     */
    public function name(string $name): AzureBlobExternalAnalyticsLink {}

    /**
     * Sets dataverse this link belongs to
     *
     * @param string $dataverse
     * @return AzureBlobExternalAnalyticsLink
     */
    public function dataverse(string $dataverse): AzureBlobExternalAnalyticsLink {}

    /**
     * Sets the connection string can be used as an authentication method, '$connectionString' contains other
     * authentication methods embedded inside the string. Only a single authentication method can be used.
     * (e.g. "AccountName=myAccountName;AccountKey=myAccountKey").
     *
     * @param string $connectionString
     * @return AzureBlobExternalAnalyticsLink
     */
    public function connectionString(string $connectionString): AzureBlobExternalAnalyticsLink {}

    /**
     * Sets Azure blob storage account name
     *
     * @param string $accountName
     * @return AzureBlobExternalAnalyticsLink
     */
    public function accountName(string $accountName): AzureBlobExternalAnalyticsLink {}

    /**
     * Sets Azure blob storage account key
     *
     * @param string $accountKey
     * @return AzureBlobExternalAnalyticsLink
     */
    public function accountKey(string $accountKey): AzureBlobExternalAnalyticsLink {}

    /**
     * Sets token that can be used for authentication
     *
     * @param string $signature
     * @return AzureBlobExternalAnalyticsLink
     */
    public function sharedAccessSignature(string $signature): AzureBlobExternalAnalyticsLink {}

    /**
     * Sets Azure blob storage endpoint
     *
     * @param string $blobEndpoint
     * @return AzureBlobExternalAnalyticsLink
     */
    public function blobEndpoint(string $blobEndpoint): AzureBlobExternalAnalyticsLink {}

    /**
     * Sets Azure blob endpoint suffix
     *
     * @param string $suffix
     * @return AzureBlobExternalAnalyticsLink
     */
    public function endpointSuffix(string $suffix): AzureBlobExternalAnalyticsLink {}
}

class S3ExternalAnalyticsLink implements AnalyticsLink
{
    /**
     * Sets name of the link
     *
     * @param string $name
     * @return S3ExternalAnalyticsLink
     */
    public function name(string $name): S3ExternalAnalyticsLink {}

    /**
     * Sets dataverse this link belongs to
     *
     * @param string $dataverse
     * @return S3ExternalAnalyticsLink
     */
    public function dataverse(string $dataverse): S3ExternalAnalyticsLink {}

    /**
     * Sets AWS S3 access key ID
     *
     * @param string $accessKeyId
     * @return S3ExternalAnalyticsLink
     */
    public function accessKeyId(string $accessKeyId): S3ExternalAnalyticsLink {}

    /**
     * Sets AWS S3 secret key
     *
     * @param string $secretAccessKey
     * @return S3ExternalAnalyticsLink
     */
    public function secretAccessKey(string $secretAccessKey): S3ExternalAnalyticsLink {}

    /**
     * Sets AWS S3 region
     *
     * @param string $region
     * @return S3ExternalAnalyticsLink
     */
    public function region(string $region): S3ExternalAnalyticsLink {}

    /**
     * Sets AWS S3 token if temporary credentials are provided. Only available in 7.0+
     *
     * @param string $sessionToken
     * @return S3ExternalAnalyticsLink
     */
    public function sessionToken(string $sessionToken): S3ExternalAnalyticsLink {}

    /**
     * Sets AWS S3 service endpoint
     *
     * @param string $serviceEndpoint
     * @return S3ExternalAnalyticsLink
     */
    public function serviceEndpoint(string $serviceEndpoint): S3ExternalAnalyticsLink {}
}

class AnalyticsIndexManager
{
    public function createDataverse(string $dataverseName, CreateAnalyticsDataverseOptions $options = null) {}

    public function dropDataverse(string $dataverseName, DropAnalyticsDataverseOptions $options = null) {}

    public function createDataset(string $datasetName, string $bucketName, CreateAnalyticsDatasetOptions $options = null) {}

    public function dropDataset(string $datasetName, DropAnalyticsDatasetOptions $options = null) {}

    public function getAllDatasets() {}

    public function createIndex(string $datasetName, string $indexName, array $fields, CreateAnalyticsIndexOptions $options = null) {}

    public function dropIndex(string $datasetName, string $indexName, DropAnalyticsIndexOptions $options = null) {}

    public function getAllIndexes() {}

    public function connectLink(ConnectAnalyticsLinkOptions $options = null) {}

    public function disconnectLink(DisconnectAnalyticsLinkOptions $options = null) {}

    public function getPendingMutations() {}

    public function createLink(AnalyticsLink $link, CreateAnalyticsLinkOptions $options = null) {}

    public function replaceLink(AnalyticsLink $link, ReplaceAnalyticsLinkOptions $options = null) {}

    public function dropLink(string $linkName, string $dataverseName, DropAnalyticsLinkOptions $options = null) {}

    public function getLinks(GetAnalyticsLinksOptions $options = null) {}
}

class SearchIndex implements JsonSerializable
{
    public function jsonSerialize() {}

    public function type(): string {}

    public function uuid(): string {}

    public function params(): array {}

    public function sourceType(): string {}

    public function sourceUuid(): string {}

    public function sourceName(): string {}

    public function sourceParams(): array {}

    public function setType(string $type): SearchIndex {}

    public function setUuid(string $uuid): SearchIndex {}

    public function setParams(string $params): SearchIndex {}

    public function setSourceType(string $type): SearchIndex {}

    public function setSourceUuid(string $uuid): SearchIndex {}

    public function setSourcename(string $params): SearchIndex {}

    public function setSourceParams(string $params): SearchIndex {}
}

class SearchIndexManager
{
    public function getIndex(string $name): SearchIndex {}

    public function getAllIndexes(): array {}

    public function upsertIndex(SearchIndex $indexDefinition) {}

    public function dropIndex(string $name) {}

    public function getIndexedDocumentsCount(string $indexName): int {}

    public function pauseIngest(string $indexName) {}

    public function resumeIngest(string $indexName) {}

    public function allowQuerying(string $indexName) {}

    public function disallowQuerying(string $indexName) {}

    public function freezePlan(string $indexName) {}

    public function unfreezePlan(string $indexName) {}

    public function analyzeDocument(string $indexName, $document) {}
}

/**
 * Cluster is an object containing functionality for performing cluster level operations
 * against a cluster and for access to buckets.
 */
class Cluster
{
    public function __construct(string $connstr, ClusterOptions $options) {}

    /**
     * Returns a new bucket object.
     *
     * @param string $name the name of the bucket
     * @return Bucket
     */
    public function bucket(string $name): Bucket {}

    /**
     * Executes a N1QL query against the cluster.
     * Note: On Couchbase Server versions < 6.5 a bucket must be opened before using query.
     *
     * @param string $statement the N1QL query statement to execute
     * @param QueryOptions $options the options to use when executing the query
     * @return QueryResult
     */
    public function query(string $statement, QueryOptions $options = null): QueryResult {}

    /**
     * Executes an analytics query against the cluster.
     * Note: On Couchbase Server versions < 6.5 a bucket must be opened before using analyticsQuery.
     *
     * @param string $statement the analytics query statement to execute
     * @param AnalyticsOptions $options the options to use when executing the query
     * @return AnalyticsResult
     */
    public function analyticsQuery(string $statement, AnalyticsOptions $options = null): AnalyticsResult {}

    /**
     * Executes a full text search query against the cluster.
     * Note: On Couchbase Server versions < 6.5 a bucket must be opened before using searchQuery.
     *
     * @param string $indexName the fts index to use for the query
     * @param SearchQuery $query the search query to execute
     * @param SearchOptions $options the options to use when executing the query
     * @return SearchResult
     */
    public function searchQuery(string $indexName, SearchQuery $query, SearchOptions $options = null): SearchResult {}

    /**
     * Creates a new bucket manager object for managing buckets.
     *
     * @return BucketManager
     */
    public function buckets(): BucketManager {}

    /**
     * Creates a new user manager object for managing users and groups.
     *
     * @return UserManager
     */
    public function users(): UserManager {}

    /**
     * Creates a new query index manager object for managing analytics query indexes.
     *
     * @return AnalyticsIndexManager
     */
    public function analyticsIndexes(): AnalyticsIndexManager {}

    /**
     * Creates a new query index manager object for managing N1QL query indexes.
     *
     * @return QueryIndexManager
     */
    public function queryIndexes(): QueryIndexManager {}

    /**
     * Creates a new search index manager object for managing search query indexes.
     *
     * @return SearchIndexManager
     */
    public function searchIndexes(): SearchIndexManager {}
}

interface EvictionPolicy
{
    /**
     * During ejection, everything (including key, metadata, and value) will be ejected.
     *
     * Full Ejection reduces the memory overhead requirement, at the cost of performance.
     *
     * This value is only valid for buckets of type COUCHBASE.
     */
    public const FULL = "fullEviction";

    /**
     * During ejection, only the value will be ejected (key and metadata will remain in memory).
     *
     * Value Ejection needs more system memory, but provides better performance than Full Ejection.
     *
     * This value is only valid for buckets of type COUCHBASE.
     */
    public const VALUE_ONLY = "valueOnly";

    /**
     * Couchbase Server keeps all data until explicitly deleted, but will reject
     * any new data if you reach the quota (dedicated memory) you set for your bucket.
     *
     * This value is only valid for buckets of type EPHEMERAL.
     */
    public const NO_EVICTION = "noEviction";

    /**
     * When the memory quota is reached, Couchbase Server ejects data that has not been used recently.
     *
     * This value is only valid for buckets of type EPHEMERAL.
     */
    public const NOT_RECENTLY_USED = "nruEviction";
}

interface StorageBackend
{
    public const COUCHSTORE = "couchstore";
    public const MAGMA = "magma";
}

class BucketSettings
{
    public function name(): string {}

    public function flushEnabled(): bool {}

    public function ramQuotaMb(): int {}

    public function numReplicas(): int {}

    public function replicaIndexes(): bool {}

    public function bucketType(): string {}

    public function evictionPolicy(): string {}

    public function storageBackend(): string {}

    public function maxTtl(): int {}

    public function compressionMode(): string {}

    public function setName(string $name): BucketSettings {}

    public function enableFlush(bool $enable): BucketSettings {}

    public function setRamQuotaMb(int $sizeInMb): BucketSettings {}

    public function setNumReplicas(int $numReplicas): BucketSettings {}

    public function enableReplicaIndexes(bool $enable): BucketSettings {}

    public function setBucketType(string $type): BucketSettings {}

    /**
     * Configures eviction policy for the bucket.
     *
     * @param string $policy eviction policy. Use constants FULL, VALUE_ONLY,
     *   NO_EVICTION, NOT_RECENTLY_USED.
     *
     * @see \EvictionPolicy::FULL
     * @see \EvictionPolicy::VALUE_ONLY
     * @see \EvictionPolicy::NO_EVICTION
     * @see \EvictionPolicy::NOT_RECENTLY_USED
     */
    public function setEvictionPolicy(string $policy): BucketSettings {}

    /**
     * Configures storage backend for the bucket.
     *
     * @param string $policy storage backend. Use constants COUCHSTORE, MAGMA.
     *
     * @see \StorageBackend::COUCHSTORE
     * @see \StorageBackend::MAGMA
     */
    public function setStorageBackend(string $policy): BucketSettings {}

    public function setMaxTtl(int $ttlSeconds): BucketSettings {}

    public function setCompressionMode(string $mode): BucketSettings {}

    /**
     * Retrieves minimal durability level configured for the bucket
     *
     * @see \DurabilityLevel::NONE
     * @see \DurabilityLevel::MAJORITY
     * @see \DurabilityLevel::MAJORITY_AND_PERSIST_TO_ACTIVE
     * @see \DurabilityLevel::PERSIST_TO_MAJORITY
     */
    public function minimalDurabilityLevel(): int {}

    /**
     * Configures minimal durability level for the bucket
     *
     * @param int $durabilityLevel durability level.
     *
     * @see \DurabilityLevel::NONE
     * @see \DurabilityLevel::MAJORITY
     * @see \DurabilityLevel::MAJORITY_AND_PERSIST_TO_ACTIVE
     * @see \DurabilityLevel::PERSIST_TO_MAJORITY
     */
    public function setMinimalDurabilityLevel(int $durabilityLevel): BucketSettings {}
}

class BucketManager
{
    public function createBucket(BucketSettings $settings) {}

    public function removeBucket(string $name) {}

    public function getBucket(string $name): BucketSettings {}

    public function getAllBuckets(): array {}

    public function flush(string $name) {}
}

class Role
{
    public function name(): string {}

    public function bucket(): ?string {}

    public function scope(): ?string {}

    public function collection(): ?string {}

    public function setName(string $name): Role {}

    public function setBucket(string $bucket): Role {}

    public function setScope(string $bucket): Role {}

    public function setCollection(string $bucket): Role {}
}

class RoleAndDescription
{
    public function role(): Role {}

    public function displayName(): string {}

    public function description(): string {}
}

class Origin
{
    public function type(): string {}

    public function name(): string {}
}

class RoleAndOrigin
{
    public function role(): Role {}

    public function origins(): array {}
}

class User
{
    public function username(): string {}

    public function displayName(): string {}

    public function groups(): array {}

    public function roles(): array {}

    public function setUsername(string $username): User {}

    public function setPassword(string $password): User {}

    public function setDisplayName(string $name): User {}

    public function setGroups(array $groups): User {}

    public function setRoles(array $roles): User {}
}

class Group
{
    public function name(): string {}

    public function description(): string {}

    public function roles(): array {}

    public function ldapGroupReference(): ?string {}

    public function setName(string $name): Group {}

    public function setDescription(string $description): Group {}

    public function setRoles(array $roles): Group {}
}

class UserAndMetadata
{
    public function domain(): string {}

    public function user(): User {}

    public function effectiveRoles(): array {}

    public function passwordChanged(): string {}

    public function externalGroups(): array {}
}

class GetAllUsersOptions
{
    public function domainName(string $name): GetAllUsersOptions {}
}

class GetUserOptions
{
    public function domainName(string $name): GetUserOptions {}
}

class DropUserOptions
{
    public function domainName(string $name): DropUserOptions {}
}

class UpsertUserOptions
{
    public function domainName(string $name): DropUserOptions {}
}

class UserManager
{
    public function getUser(string $name, GetUserOptions $options = null): UserAndMetadata {}

    public function getAllUsers(GetAllUsersOptions $options = null): array {}

    public function upsertUser(User $user, UpsertUserOptions $options = null) {}

    public function dropUser(string $name, DropUserOptions $options = null) {}

    public function getRoles(): array {}

    public function getGroup(string $name): Group {}

    public function getAllGroups(): array {}

    public function upsertGroup(Group $group) {}

    public function dropGroup(string $name) {}
}

/**
 * BinaryCollection is an object containing functionality for performing KeyValue operations against the server with binary documents.
 */
class BinaryCollection
{
    /**
     * Get the name of the binary collection.
     *
     * @return string
     */
    public function name(): string {}

    /**
     * Appends a value to a document.
     *
     * @param string $id the key of the document
     * @param string $value the value to append
     * @param AppendOptions $options the options to use for the operation
     * @return MutationResult
     */
    public function append(string $id, string $value, AppendOptions $options = null): MutationResult {}

    /**
     * Prepends a value to a document.
     *
     * @param string $id the key of the document
     * @param string $value the value to prepend
     * @param PrependOptions $options the options to use for the operation
     * @return MutationResult
     */
    public function prepend(string $id, string $value, PrependOptions $options = null): MutationResult {}

    /**
     * Increments a counter document by a value.
     *
     * @param string $id the key of the document
     * @param IncrementOptions $options the options to use for the operation
     * @return CounterResult
     */
    public function increment(string $id, IncrementOptions $options = null): CounterResult {}

    /**
     * Decrements a counter document by a value.
     *
     * @param string $id the key of the document
     * @param DecrementOptions $options the options to use for the operation
     * @return CounterResult
     */
    public function decrement(string $id, DecrementOptions $options = null): CounterResult {}
}

/**
 * Collection is an object containing functionality for performing KeyValue operations against the server.
 */
class Collection
{
    /**
     * Get the name of the collection.
     *
     * @return string
     */
    public function name(): string {}

    /**
     * Gets a document from the server.
     *
     * This can take 3 paths, a standard full document fetch, a subdocument full document fetch also
     * fetching document expiry (when withExpiry is set), or a subdocument fetch (when projections are
     * used).
     *
     * @param string $id the key of the document to fetch
     * @param GetOptions $options the options to use for the operation
     * @return GetResult
     */
    public function get(string $id, GetOptions $options = null): GetResult {}

    /**
     * Checks if a document exists on the server.
     *
     * @param string $id the key of the document to check if exists
     * @param ExistsOptions $options the options to use for the operation
     * @return ExistsResult
     */
    public function exists(string $id, ExistsOptions $options = null): ExistsResult {}

    /**
     * Gets a document from the server, locking the document so that no other processes can
     * perform mutations against it.
     *
     * @param string $id the key of the document to get
     * @param int $lockTime the length of time to lock the document in ms
     * @param GetAndLockOptions $options the options to use for the operation
     * @return GetResult
     */
    public function getAndLock(string $id, int $lockTime, GetAndLockOptions $options = null): GetResult {}

    /**
     * Gets a document from the server and simultaneously updates its expiry time.
     *
     * @param string $id the key of the document
     * @param int $expiry the length of time to update the expiry to in ms
     * @param GetAndTouchOptions $options the options to use for the operation
     * @return GetResult
     */
    public function getAndTouch(string $id, int $expiry, GetAndTouchOptions $options = null): GetResult {}

    /**
     * Gets a document from any replica server in the cluster.
     *
     * @param string $id the key of the document
     * @param GetAnyReplicaOptions $options the options to use for the operation
     * @return GetReplicaResult
     */
    public function getAnyReplica(string $id, GetAnyReplicaOptions $options = null): GetReplicaResult {}

    /**
     * Gets a document from the active server and all replica servers in the cluster.
     * Returns an array of documents, one per server.
     *
     * @param string $id the key of the document
     * @param GetAllReplicasOptions $options the options to use for the operation
     * @return array
     */
    public function getAllReplicas(string $id, GetAllReplicasOptions $options = null): array {}

    /**
     * Creates a document if it doesn't exist, otherwise updates it.
     *
     * @param string $id the key of the document
     * @param mixed $value the value to use for the document
     * @param UpsertOptions $options the options to use for the operation
     * @return MutationResult
     */
    public function upsert(string $id, $value, UpsertOptions $options = null): MutationResult {}

    /**
     * Inserts a document if it doesn't exist, errors if it does exist.
     *
     * @param string $id the key of the document
     * @param mixed $value the value to use for the document
     * @param InsertOptions $options the options to use for the operation
     * @return MutationResult
     */
    public function insert(string $id, $value, InsertOptions $options = null): MutationResult {}

    /**
     * Replaces a document if it exists, errors if it doesn't exist.
     *
     * @param string $id the key of the document
     * @param mixed $value the value to use for the document
     * @param ReplaceOptions $options the options to use for the operation
     * @return MutationResult
     */
    public function replace(string $id, $value, ReplaceOptions $options = null): MutationResult {}

    /**
     * Removes a document.
     *
     * @param string $id the key of the document
     * @param RemoveOptions $options the options to use for the operation
     * @return MutationResult
     */
    public function remove(string $id, RemoveOptions $options = null): MutationResult {}

    /**
     * Unlocks a document which was locked using getAndLock. This frees the document to be
     * modified by other processes.
     *
     * @param string $id the key of the document
     * @param string $cas the current cas value of the document
     * @param UnlockOptions $options the options to use for the operation
     * @return Result
     */
    public function unlock(string $id, string $cas, UnlockOptions $options = null): Result {}

    /**
     * Touches a document, setting a new expiry time.
     *
     * @param string $id the key of the document
     * @param int $expiry the expiry time for the document in ms
     * @param TouchOptions $options the options to use for the operation
     * @return MutationResult
     */
    public function touch(string $id, int $expiry, TouchOptions $options = null): MutationResult {}

    /**
     * Performs a set of subdocument lookup operations against the document.
     *
     * @param string $id the key of the document
     * @param array $specs the LookupInSpecs to perform against the document
     * @param LookupInOptions $options the options to use for the operation
     * @return LookupInResult
     */
    public function lookupIn(string $id, array $specs, LookupInOptions $options = null): LookupInResult {}

    /**
     * Performs a set of subdocument lookup operations against the document.
     *
     * @param string $id the key of the document
     * @param array $specs the MutateInSpecs to perform against the document
     * @param MutateInOptions $options the options to use for the operation
     * @return MutateInResult
     */
    public function mutateIn(string $id, array $specs, MutateInOptions $options = null): MutateInResult {}

    /**
     * Retrieves a group of documents. If the document does not exist, it will not raise an exception, but rather fill
     * non-null value in error() property of the corresponding result object.
     *
     * @param array $ids array of IDs, organized like this ["key1", "key2", ...]
     * @param GetOptions $options the options to use for the operation
     * @return array array of GetResult, one for each of the entries
     */
    public function getMulti(array $ids, RemoveOptions $options = null): array {}

    /**
     * Removes a group of documents. If second element of the entry (CAS) is null, then the operation will
     * remove the document unconditionally.
     *
     * @param array $entries array of arrays, organized like this [["key1", "encodedCas1"], ["key2", , "encodedCas2"], ...] or ["key1", "key2", ...]
     * @param RemoveOptions $options the options to use for the operation
     * @return array array of MutationResult, one for each of the entries
     */
    public function removeMulti(array $entries, RemoveOptions $options = null): array {}

    /**
     * Creates a group of documents if they don't exist, otherwise updates them.
     *
     * @param array $entries array of arrays, organized like this [["key1", $value1], ["key2", $value2], ...]
     * @param UpsertOptions $options the options to use for the operation
     * @return array array of MutationResult, one for each of the entries
     */
    public function upsertMulti(array $entries, UpsertOptions $options = null): array {}

    /**
     * Creates and returns a BinaryCollection object for use with binary type documents.
     *
     * @return BinaryCollection
     */
    public function binary(): BinaryCollection {}
}

/**
 * Scope is an object for providing access to collections.
 */
class Scope
{
    public function __construct(Bucket $bucket, string $name) {}

    /**
     * Returns the name of the scope.
     *
     * @return string
     */
    public function name(): string {}

    /**
     * Returns a new Collection object representing the collection specified.
     *
     * @param string $name the name of the collection
     * @return Collection
     */
    public function collection(string $name): Collection {}

    /**
     * Executes a N1QL query against the cluster with scopeName set implicitly.
     *
     * @param string $statement the N1QL query statement to execute
     * @param QueryOptions $options the options to use when executing the query
     * @return QueryResult
     */
    public function query(string $statement, QueryOptions $options = null): QueryResult {}

    /**
     * Executes an analytics query against the cluster with scopeName set implicitly.
     *
     * @param string $statement the analytics query statement to execute
     * @param AnalyticsOptions $options the options to use when executing the query
     * @return AnalyticsResult
     */
    public function analyticsQuery(string $statement, AnalyticsOptions $options = null): AnalyticsResult {}
}

class ScopeSpec
{
    public function name(): string {}

    public function collections(): array {}
}

class CollectionSpec
{
    public function name(): string {}

    public function scopeName(): string {}

    public function setName(string $name): CollectionSpec {}

    public function setScopeName(string $name): CollectionSpec {}

    public function setMaxExpiry(int $ms): CollectionSpec {}
}

class CollectionManager
{
    public function getScope(string $name): ScopeSpec {}

    public function getAllScopes(): array {}

    public function createScope(string $name) {}

    public function dropScope(string $name) {}

    public function createCollection(CollectionSpec $collection) {}

    public function dropCollection(CollectionSpec $collection) {}
}

/**
 * Bucket is an object containing functionality for performing bucket level operations
 * against a cluster and for access to scopes and collections.
 */
class Bucket
{
    /**
     * Returns a new Scope object representing the default scope.
     *
     * @return Scope
     */
    public function defaultScope(): Scope {}

    /**
     * Returns a new Collection object representing the default collectiom.
     *
     * @return Collection
     */
    public function defaultCollection(): Collection {}

    /**
     * Returns a new Scope object representing the given scope.
     *
     * @param string $name the name of the scope
     * @return Scope
     */
    public function scope(string $name): Scope {}

    /**
     * Sets the default transcoder to be used when fetching or sending data.
     *
     * @param callable $encoder the encoder to use to encode data when sending data to the server
     * @param callable $decoder the decoder to use to decode data when retrieving data from the server
     */
    public function setTranscoder(callable $encoder, callable $decoder) {}

    /**
     * Returns the name of the Bucket.
     *
     * @return string
     */
    public function name(): string {}

    /**
     * Executes a view query against the cluster.
     *
     * @param string $designDoc the design document to use for the query
     * @param string $viewName the view to use for the query
     * @param ViewOptions $options the options to use when executing the query
     * @return ViewResult
     */
    public function viewQuery(string $designDoc, string $viewName, ViewOptions $options = null): ViewResult {}

    /**
     * Creates a new CollectionManager object for managing collections and scopes.
     *
     * @return CollectionManager
     */
    public function collections(): CollectionManager {}

    /**
     * Creates a new ViewIndexManager object for managing views and design documents.
     *
     * @return ViewIndexManager
     */
    public function viewIndexes(): ViewIndexManager {}

    /**
     * Executes a ping for each service against each node in the cluster. This can be used for determining
     * the current health of the cluster.
     *
     * @param mixed $services the services to ping against
     * @param mixed $reportId a name which will be included within the ping result
     */
    public function ping($services, $reportId) {}

    /**
     * Returns diagnostics information about connections that the SDK has to the cluster. This does not perform
     * any operations.
     *
     * @param mixed $reportId a name which will be included within the ping result
     */
    public function diagnostics($reportId) {}
}

class View
{
    public function name(): string {}

    public function map(): string {}

    public function reduce(): string {}

    public function setName(string $name): View {}

    public function setMap(string $mapJsCode): View {}

    public function setReduce(string $reduceJsCode): View {}
}

class DesignDocument
{
    public function name(): string {}

    public function views(): array {}

    public function setName(string $name): DesignDocument {}

    public function setViews(array $views): DesignDocument {}
}

class ViewIndexManager
{
    public function getAllDesignDocuments(): array {}

    public function getDesignDocument(string $name): DesignDocument {}

    public function dropDesignDocument(string $name) {}

    public function upsertDesignDocument(DesignDocument $document) {}
}

/**
 * MutationState is an object which holds and aggregates mutation tokens across operations.
 */
class MutationState
{
    public function __construct() {}

    /**
     * Adds the result of a mutation operation to this mutation state.
     *
     * @param MutationResult $source the result object to add to this state
     * @return MutationState
     */
    public function add(MutationResult $source): MutationState {}
}

class AnalyticsOptions
{
    public function timeout(int $arg): AnalyticsOptions {}

    public function namedParameters(array $pairs): AnalyticsOptions {}

    public function positionalParameters(array $args): AnalyticsOptions {}

    public function raw(string $key, $value): AnalyticsOptions {}

    public function clientContextId(string $value): AnalyticsOptions {}

    public function priority(bool $urgent): AnalyticsOptions {}

    public function readonly(bool $arg): AnalyticsOptions {}

    public function scanConsistency(string $arg): AnalyticsOptions {}
}

/**
 * LookupInSpec is an interface for providing subdocument lookup operations.
 */
interface LookupInSpec {}

/**
 * Indicates a path for a value to be retrieved from a document.
 */
class LookupGetSpec implements LookupInSpec
{
    public function __construct(string $path, bool $isXattr = false) {}
}

/**
 * Indicates to retrieve the count of array items or dictionary keys within a path in a document.
 */
class LookupCountSpec implements LookupInSpec
{
    public function __construct(string $path, bool $isXattr = false) {}
}

/**
 * Indicates to check if a path exists in a document.
 */
class LookupExistsSpec implements LookupInSpec
{
    public function __construct(string $path, bool $isXattr = false) {}
}

/**
 * Indicates to retreive a whole document.
 */
class LookupGetFullSpec implements LookupInSpec
{
    public function __construct() {}
}

/**
 * MutateInSpec is an interface for providing subdocument mutation operations.
 */
interface MutateInSpec {}

/**
 * Indicates to insert a value at a path in a document.
 */
class MutateInsertSpec implements MutateInSpec
{
    public function __construct(string $path, $value, bool $isXattr, bool $createPath, bool $expandMacros) {}
}

/**
 * Indicates to replace a value at a path if it doesn't exist, otherwise create the path, in a document.
 */
class MutateUpsertSpec implements MutateInSpec
{
    public function __construct(string $path, $value, bool $isXattr, bool $createPath, bool $expandMacros) {}
}

/**
 * Indicates to replace a value at a path if it doesn't exist in a document.
 */
class MutateReplaceSpec implements MutateInSpec
{
    public function __construct(string $path, $value, bool $isXattr) {}
}

/**
 * Indicates to remove a value at a path in a document.
 */
class MutateRemoveSpec implements MutateInSpec
{
    public function __construct(string $path, bool $isXattr) {}
}

/**
 * Indicates to append a value to an array at a path in a document.
 */
class MutateArrayAppendSpec implements MutateInSpec
{
    public function __construct(string $path, array $values, bool $isXattr, bool $createPath, bool $expandMacros) {}
}

/**
 * Indicates to prepend a value to an array at a path in a document.
 */
class MutateArrayPrependSpec implements MutateInSpec
{
    public function __construct(string $path, array $values, bool $isXattr, bool $createPath, bool $expandMacros) {}
}

/**
 * Indicates to insert a value into an array at a path in a document.
 */
class MutateArrayInsertSpec implements MutateInSpec
{
    public function __construct(string $path, array $values, bool $isXattr, bool $createPath, bool $expandMacros) {}
}

/**
 * Indicates to add a value into an array at a path in a document so long as that value does not already exist
 * in the array.
 */
class MutateArrayAddUniqueSpec implements MutateInSpec
{
    public function __construct(string $path, $value, bool $isXattr, bool $createPath, bool $expandMacros) {}
}

/**
 * Indicates to increment or decrement a counter value at a path in a document.
 */
class MutateCounterSpec implements MutateInSpec
{
    public function __construct(string $path, int $delta, bool $isXattr, bool $createPath) {}
}

class SearchOptions implements JsonSerializable
{
    public function jsonSerialize() {}

    /**
     * Sets the server side timeout in milliseconds
     *
     * @param int $ms the server side timeout to apply
     * @return SearchOptions
     */
    public function timeout(int $ms): SearchOptions {}

    /**
     * Add a limit to the query on the number of hits it can return
     *
     * @param int $limit the maximum number of hits to return
     * @return SearchOptions
     */
    public function limit(int $limit): SearchOptions {}

    /**
     * Set the number of hits to skip (eg. for pagination).
     *
     * @param int $skip the number of results to skip
     * @return SearchOptions
     */
    public function skip(int $skip): SearchOptions {}

    /**
     * Activates the explanation of each result hit in the response
     *
     * @param bool $explain
     * @return SearchOptions
     */
    public function explain(bool $explain): SearchOptions {}

    /**
     * If set to true, the server will not perform any scoring on the hits
     *
     * @param bool $disabled
     * @return SearchOptions
     */
    public function disableScoring(bool $disabled): SearchOptions {}

    /**
     * Sets the consistency to consider for this FTS query to AT_PLUS and
     * uses the MutationState to parameterize the consistency.
     *
     * This replaces any consistency tuning previously set.
     *
     * @param MutationState $state the mutation state information to work with
     * @return SearchOptions
     */
    public function consistentWith(string $index, MutationState $state): SearchOptions {}

    /**
     * Configures the list of fields for which the whole value should be included in the response.
     *
     * If empty, no field values are included. This drives the inclusion of the fields in each hit.
     * Note that to be highlighted, the fields must be stored in the FTS index.
     *
     * @param string[] $fields
     * @return SearchOptions
     */
    public function fields(array $fields): SearchOptions {}

    /**
     * Adds one SearchFacet-s to the query
     *
     * This is an additive operation (the given facets are added to any facet previously requested),
     * but if an existing facet has the same name it will be replaced.
     *
     * Note that to be faceted, a field's value must be stored in the FTS index.
     *
     * @param SearchFacet[] $facets
     * @return SearchOptions
     *
     * @see \SearchFacet
     * @see \TermSearchFacet
     * @see \NumericRangeSearchFacet
     * @see \DateRangeSearchFacet
     */
    public function facets(array $facets): SearchOptions {}

    /**
     * Configures the list of fields (including special fields) which are used for sorting purposes.
     * If empty, the default sorting (descending by score) is used by the server.
     *
     * The list of sort fields can include actual fields (like "firstname" but then they must be stored in the
     * index, configured in the server side mapping). Fields provided first are considered first and in a "tie" case
     * the next sort field is considered. So sorting by "firstname" and then "lastname" will first sort ascending by
     * the firstname and if the names are equal then sort ascending by lastname. Special fields like "_id" and
     * "_score" can also be used. If prefixed with "-" the sort order is set to descending.
     *
     * If no sort is provided, it is equal to sort("-_score"), since the server will sort it by score in descending
     * order.
     *
     * @param array $specs sort the fields that should take part in the sorting.
     * @return SearchOptions
     */
    public function sort(array $specs): SearchOptions {}

    /**
     * Configures the highlighting of matches in the response
     *
     * @param string $style highlight style to apply. Use constants HIGHLIGHT_HTML,
     *   HIGHLIGHT_ANSI, HIGHLIGHT_SIMPLE.
     * @param string ...$fields the optional fields on which to highlight.
     *   If none, all fields where there is a match are highlighted.
     * @return SearchOptions
     *
     * @see \SearchHighlightMode::HTML
     * @see \SearchHighlightMode::ANSI
     * @see \SearchHighlightMode::SIMPLE
     */
    public function highlight(string $style = null, array $fields = null): SearchOptions {}

    /**
     * Configures the list of collections to use for restricting results.
     *
     * @param string[] $collectionNames
     * @return SearchOptions
     */
    public function collections(array $collectionNames): SearchOptions {}
}

interface SearchHighlightMode
{
    public const HTML = "html";
    public const ANSI = "ansi";
    public const SIMPLE = "simple";
}

/**
 * Common interface for all classes, which could be used as a body of SearchQuery
 *
 * Represents full text search query
 *
 * @see https://developer.couchbase.com/documentation/server/4.6/sdk/php/full-text-searching-with-sdk.html
 *   Searching from the SDK
 */
interface SearchQuery {}

/**
 * A FTS query that queries fields explicitly indexed as boolean.
 */
class BooleanFieldSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct(bool $arg) {}

    /**
     * @param float $boost
     * @return BooleanFieldSearchQuery
     */
    public function boost(float $boost): BooleanFieldSearchQuery {}

    /**
     * @param string $field
     * @return BooleanFieldSearchQuery
     */
    public function field(string $field): BooleanFieldSearchQuery {}
}

/**
 * A compound FTS query that allows various combinations of sub-queries.
 */
class BooleanSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct() {}

    /**
     * @param float $boost
     * @return BooleanSearchQuery
     */
    public function boost($boost): BooleanSearchQuery {}

    /**
     * @param ConjunctionSearchQuery $query
     * @return BooleanSearchQuery
     */
    public function must(ConjunctionSearchQuery $query): BooleanSearchQuery {}

    /**
     * @param DisjunctionSearchQuery $query
     * @return BooleanSearchQuery
     */
    public function mustNot(DisjunctionSearchQuery $query): BooleanSearchQuery {}

    /**
     * @param DisjunctionSearchQuery $query
     * @return BooleanSearchQuery
     */
    public function should(DisjunctionSearchQuery $query): BooleanSearchQuery {}
}

/**
 * A compound FTS query that performs a logical AND between all its sub-queries (conjunction).
 */
class ConjunctionSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct(array $queries) {}

    /**
     * @param float $boost
     * @return ConjunctionSearchQuery
     */
    public function boost($boost): ConjunctionSearchQuery {}

    /**
     * @param SearchQuery ...$queries
     * @return ConjunctionSearchQuery
     */
    public function every(SearchQuery ...$queries): ConjunctionSearchQuery {}
}

/**
 * A FTS query that matches documents on a range of values. At least one bound is required, and the
 * inclusiveness of each bound can be configured.
 */
class DateRangeSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct() {}

    /**
     * @param float $boost
     * @return DateRangeSearchQuery
     */
    public function boost(float $boost): DateRangeSearchQuery {}

    /**
     * @param string $field
     * @return DateRangeSearchQuery
     */
    public function field(string $field): DateRangeSearchQuery {}

    /**
     * @param int|string $start The strings will be taken verbatim and supposed to be formatted with custom date
     *      time formatter (see dateTimeParser). Integers interpreted as unix timestamps and represented as RFC3339
     *      strings.
     * @param bool $inclusive
     * @return DateRangeSearchQuery
     */
    public function start($start, bool $inclusive = false): DateRangeSearchQuery {}

    /**
     * @param int|string $end The strings will be taken verbatim and supposed to be formatted with custom date
     *      time formatter (see dateTimeParser). Integers interpreted as unix timestamps and represented as RFC3339
     *      strings.
     * @param bool $inclusive
     * @return DateRangeSearchQuery
     */
    public function end($end, bool $inclusive = false): DateRangeSearchQuery {}

    /**
     * @param string $dateTimeParser
     * @return DateRangeSearchQuery
     */
    public function dateTimeParser(string $dateTimeParser): DateRangeSearchQuery {}
}

/**
 * A compound FTS query that performs a logical OR between all its sub-queries (disjunction). It requires that a
 * minimum of the queries match. The minimum is configurable (default 1).
 */
class DisjunctionSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct(array $queries) {}

    /**
     * @param float $boost
     * @return DisjunctionSearchQuery
     */
    public function boost(float $boost): DisjunctionSearchQuery {}

    /**
     * @param SearchQuery ...$queries
     * @return DisjunctionSearchQuery
     */
    public function either(SearchQuery ...$queries): DisjunctionSearchQuery {}

    /**
     * @param int $min
     * @return DisjunctionSearchQuery
     */
    public function min(int $min): DisjunctionSearchQuery {}
}

/**
 * A FTS query that matches on Couchbase document IDs. Useful to restrict the search space to a list of keys (by using
 * this in a compound query).
 */
class DocIdSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct() {}

    /**
     * @param float $boost
     * @return DocIdSearchQuery
     */
    public function boost(float $boost): DocIdSearchQuery {}

    /**
     * @param string $field
     * @return DocIdSearchQuery
     */
    public function field(string $field): DocIdSearchQuery {}

    /**
     * @param string ...$documentIds
     * @return DocIdSearchQuery
     */
    public function docIds(string ...$documentIds): DocIdSearchQuery {}
}

/**
 * A FTS query which allows to match geo bounding boxes.
 */
class GeoBoundingBoxSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct(float $top_left_longitude, float $top_left_latitude, float $buttom_right_longitude, float $buttom_right_latitude) {}

    /**
     * @param float $boost
     * @return GeoBoundingBoxSearchQuery
     */
    public function boost(float $boost): GeoBoundingBoxSearchQuery {}

    /**
     * @param string $field
     * @return GeoBoundingBoxSearchQuery
     */
    public function field(string $field): GeoBoundingBoxSearchQuery {}
}

/**
 * A FTS query that finds all matches from a given location (point) within the given distance.
 *
 * Both the point and the distance are required.
 */
class GeoDistanceSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct(float $longitude, float $latitude, string $distance = null) {}

    /**
     * @param float $boost
     * @return GeoDistanceSearchQuery
     */
    public function boost(float $boost): GeoDistanceSearchQuery {}

    /**
     * @param string $field
     * @return GeoDistanceSearchQuery
     */
    public function field(string $field): GeoDistanceSearchQuery {}
}

class Coordinate implements JsonSerializable
{
    public function jsonSerialize() {}

    /**
     * @param float $longitude
     * @param float $latitude
     *
     * @see GeoPolygonQuery
     */
    public function __construct(float $longitude, float $latitude) {}
}

/**
 * A FTS query that finds all matches within the given polygon area.
 */
class GeoPolygonQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    /**
     * @param array $coordinates list of objects of type Coordinate
     *
     * @see Coordinate
     */
    public function __construct(array $coordinates) {}

    /**
     * @param float $boost
     * @return GeoPolygonQuery
     */
    public function boost(float $boost): GeoPolygonQuery {}

    /**
     * @param string $field
     * @return GeoPolygonQuery
     */
    public function field(string $field): GeoPolygonQuery {}
}

/**
 * A FTS query that matches all indexed documents (usually for debugging purposes).
 */
class MatchAllSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct() {}

    /**
     * @param float $boost
     * @return MatchAllSearchQuery
     */
    public function boost(float $boost): MatchAllSearchQuery {}
}

/**
 * A FTS query that matches 0 document (usually for debugging purposes).
 */
class MatchNoneSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct() {}

    /**
     * @param float $boost
     * @return MatchNoneSearchQuery
     */
    public function boost(float $boost): MatchNoneSearchQuery {}
}

/**
 * A FTS query that matches several given terms (a "phrase"), applying further processing
 * like analyzers to them.
 */
class MatchPhraseSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct(string $value) {}

    /**
     * @param float $boost
     * @return MatchPhraseSearchQuery
     */
    public function boost(float $boost): MatchPhraseSearchQuery {}

    /**
     * @param string $field
     * @return MatchPhraseSearchQuery
     */
    public function field(string $field): MatchPhraseSearchQuery {}

    /**
     * @param string $analyzer
     * @return MatchPhraseSearchQuery
     */
    public function analyzer(string $analyzer): MatchPhraseSearchQuery {}
}

/**
 * A FTS query that matches a given term, applying further processing to it
 * like analyzers, stemming and even #fuzziness(int).
 */
class MatchSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct(string $value) {}

    /**
     * @param float $boost
     * @return MatchSearchQuery
     */
    public function boost(float $boost): MatchSearchQuery {}

    /**
     * @param string $field
     * @return MatchSearchQuery
     */
    public function field(string $field): MatchSearchQuery {}

    /**
     * @param string $analyzer
     * @return MatchSearchQuery
     */
    public function analyzer(string $analyzer): MatchSearchQuery {}

    /**
     * @param int $prefixLength
     * @return MatchSearchQuery
     */
    public function prefixLength(int $prefixLength): MatchSearchQuery {}

    /**
     * @param int $fuzziness
     * @return MatchSearchQuery
     */
    public function fuzziness(int $fuzziness): MatchSearchQuery {}
}

/**
 * A FTS query that matches documents on a range of values. At least one bound is required, and the
 * inclusiveness of each bound can be configured.
 */
class NumericRangeSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct() {}

    /**
     * @param float $boost
     * @return NumericRangeSearchQuery
     */
    public function boost(float $boost): NumericRangeSearchQuery {}

    /**
     * @param string $field
     * @return NumericRangeSearchQuery
     */
    public function field($field): NumericRangeSearchQuery {}

    /**
     * @param float $min
     * @param bool $inclusive
     * @return NumericRangeSearchQuery
     */
    public function min(float $min, bool $inclusive = false): NumericRangeSearchQuery {}

    /**
     * @param float $max
     * @param bool $inclusive
     * @return NumericRangeSearchQuery
     */
    public function max(float $max, bool $inclusive = false): NumericRangeSearchQuery {}
}

/**
 * A FTS query that matches several terms (a "phrase") as is. The order of the terms mater and no further processing is
 * applied to them, so they must appear in the index exactly as provided.  Usually for debugging purposes, prefer
 * MatchPhraseQuery.
 */
class PhraseSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct(string ...$terms) {}

    /**
     * @param float $boost
     * @return PhraseSearchQuery
     */
    public function boost(float $boost): PhraseSearchQuery {}

    /**
     * @param string $field
     * @return PhraseSearchQuery
     */
    public function field(string $field): PhraseSearchQuery {}
}

/**
 * A FTS query that allows for simple matching on a given prefix.
 */
class PrefixSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct(string $prefix) {}

    /**
     * @param float $boost
     * @return PrefixSearchQuery
     */
    public function boost(float $boost): PrefixSearchQuery {}

    /**
     * @param string $field
     * @return PrefixSearchQuery
     */
    public function field(string $field): PrefixSearchQuery {}
}

/**
 * A FTS query that performs a search according to the "string query" syntax.
 */
class QueryStringSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct(string $query_string) {}

    /**
     * @param float $boost
     * @return QueryStringSearchQuery
     */
    public function boost(float $boost): QueryStringSearchQuery {}
}

/**
 * A FTS query that allows for simple matching of regular expressions.
 */
class RegexpSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct(string $regexp) {}

    /**
     * @param float $boost
     * @return RegexpSearchQuery
     */
    public function boost(float $boost): RegexpSearchQuery {}

    /**
     * @param string $field
     * @return RegexpSearchQuery
     */
    public function field(string $field): RegexpSearchQuery {}
}

/**
 * A facet that gives the number of occurrences of the most recurring terms in all hits.
 */
class TermSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct(string $term) {}

    /**
     * @param float $boost
     * @return TermSearchQuery
     */
    public function boost(float $boost): TermSearchQuery {}

    /**
     * @param string $field
     * @return TermSearchQuery
     */
    public function field(string $field): TermSearchQuery {}

    /**
     * @param int $prefixLength
     * @return TermSearchQuery
     */
    public function prefixLength(int $prefixLength): TermSearchQuery {}

    /**
     * @param int $fuzziness
     * @return TermSearchQuery
     */
    public function fuzziness(int $fuzziness): TermSearchQuery {}
}

/**
 * A FTS query that matches documents on a range of values. At least one bound is required, and the
 * inclusiveness of each bound can be configured.
 */
class TermRangeSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct() {}

    /**
     * @param float $boost
     * @return TermRangeSearchQuery
     */
    public function boost(float $boost): TermRangeSearchQuery {}

    /**
     * @param string $field
     * @return TermRangeSearchQuery
     */
    public function field(string $field): TermRangeSearchQuery {}

    /**
     * @param string $min
     * @param bool $inclusive
     * @return TermRangeSearchQuery
     */
    public function min(string $min, bool $inclusive = true): TermRangeSearchQuery {}

    /**
     * @param string $max
     * @param bool $inclusive
     * @return TermRangeSearchQuery
     */
    public function max(string $max, bool $inclusive = false): TermRangeSearchQuery {}
}

/**
 * A FTS query that allows for simple matching using wildcard characters (* and ?).
 */
class WildcardSearchQuery implements JsonSerializable, SearchQuery
{
    public function jsonSerialize() {}

    public function __construct(string $wildcard) {}

    /**
     * @param float $boost
     * @return WildcardSearchQuery
     */
    public function boost(float $boost): WildcardSearchQuery {}

    /**
     * @param string $field
     * @return WildcardSearchQuery
     */
    public function field(string $field): WildcardSearchQuery {}
}

/**
 * Common interface for all search facets
 *
 * @see \SearchQuery::addFacet()
 * @see \TermSearchFacet
 * @see \DateRangeSearchFacet
 * @see \NumericRangeSearchFacet
 */
interface SearchFacet {}

/**
 * A facet that gives the number of occurrences of the most recurring terms in all hits.
 */
class TermSearchFacet implements JsonSerializable, SearchFacet
{
    public function jsonSerialize() {}

    public function __construct(string $field, int $limit) {}
}

/**
 * A facet that categorizes hits into numerical ranges (or buckets) provided by the user.
 */
class NumericRangeSearchFacet implements JsonSerializable, SearchFacet
{
    public function jsonSerialize() {}

    public function __construct(string $field, int $limit) {}

    /**
     * @param string $name
     * @param float $min
     * @param float $max
     * @return NumericRangeSearchFacet
     */
    public function addRange(string $name, float $min = null, float $max = null): NumericRangeSearchFacet {}
}

/**
 * A facet that categorizes hits inside date ranges (or buckets) provided by the user.
 */
class DateRangeSearchFacet implements JsonSerializable, SearchFacet
{
    public function jsonSerialize() {}

    public function __construct(string $field, int $limit) {}

    /**
     * @param string $name
     * @param int|string $start
     * @param int|string $end
     * @return DateRangeSearchFacet
     */
    public function addRange(string $name, $start = null, $end = null): DateRangeSearchFacet {}
}

/**
 * Base interface for all FTS sort options in querying.
 */
interface SearchSort {}

/**
 * Sort by a field in the hits.
 */
class SearchSortField implements JsonSerializable, SearchSort
{
    public function jsonSerialize() {}

    public function __construct(string $field) {}

    /**
     * Direction of the sort
     *
     * @param bool $descending
     *
     * @return SearchSortField
     */
    public function descending(bool $descending): SearchSortField {}

    /**
     * Set type of the field
     *
     * @param string type the type
     *
     * @see SearchSortType::AUTO
     * @see SearchSortType::STRING
     * @see SearchSortType::NUMBER
     * @see SearchSortType::DATE
     */
    public function type(string $type): SearchSortField {}

    /**
     * Set mode of the sort
     *
     * @param string mode the mode
     *
     * @see SearchSortMode::MIN
     * @see SearchSortMode::MAX
     */
    public function mode(string $mode): SearchSortField {}

    /**
     * Set where the hits with missing field will be inserted
     *
     * @param string missing strategy for hits with missing fields
     *
     * @see SearchSortMissing::FIRST
     * @see SearchSortMissing::LAST
     */
    public function missing(string $missing): SearchSortField {}
}

interface SearchSortType
{
    public const AUTO = "auto";
    public const STRING = "string";
    public const NUMBER = "number";
    public const DATE = "date";
}

interface SearchSortMode
{
    public const DEFAULT = "default";
    public const MIN = "min";
    public const MAX = "max";
}

interface SearchSortMissing
{
    public const FIRST = "first";
    public const LAST = "last";
}

/**
 * Sort by a location and unit in the hits.
 */
class SearchSortGeoDistance implements JsonSerializable, SearchSort
{
    public function jsonSerialize() {}

    public function __construct(string $field, float $logitude, float $latitude) {}

    /**
     * Direction of the sort
     *
     * @param bool $descending
     *
     * @return SearchSortGeoDistance
     */
    public function descending(bool $descending): SearchSortGeoDistance {}

    /**
     * Name of the units
     *
     * @param string $unit
     *
     * @return SearchSortGeoDistance
     */
    public function unit(string $unit): SearchSortGeoDistance {}
}

/**
 * Sort by the document identifier.
 */
class SearchSortId implements JsonSerializable, SearchSort
{
    public function jsonSerialize() {}

    public function __construct() {}

    /**
     * Direction of the sort
     *
     * @param bool $descending
     *
     * @return SearchSortId
     */
    public function descending(bool $descending): SearchSortId {}
}

/**
 * Sort by the hit score.
 */
class SearchSortScore implements JsonSerializable, SearchSort
{
    public function jsonSerialize() {}

    public function __construct() {}

    /**
     * Direction of the sort
     *
     * @param bool $descending
     *
     * @return SearchSortScore
     */
    public function descending(bool $descending): SearchSortScore {}
}

class GetOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return GetOptions
     */
    public function timeout(int $arg): GetOptions {}

    /**
     * Sets whether to include document expiry with the document content.
     *
     * When used this option will transparently transform the Get
     * operation into a subdocument operation performing a full document
     * fetch as well as the expiry.
     *
     * @param bool $arg whether or not to include document expiry
     * @return GetOptions
     */
    public function withExpiry(bool $arg): GetOptions {}

    /**
     * Sets whether to cause the Get operation to only fetch the fields
     * from the document indicated by the paths provided.
     *
     * When used this option will transparently transform the Get
     * operation into a subdocument operation fetching only the required
     * fields.
     *
     * @param array $arg the array of field names
     * @return GetOptions
     */
    public function project(array $arg): GetOptions {}

    /**
     * Associate custom transcoder with the request.
     *
     * @param callable $arg decoding function with signature (returns decoded value):
     *
     *   `function decoder(string $bytes, int $flags, int $datatype): mixed`
     */
    public function decoder(callable $arg): GetOptions {}
}

class GetAndTouchOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return GetAndTouchOptions
     */
    public function timeout(int $arg): GetAndTouchOptions {}

    /**
     * Associate custom transcoder with the request.
     *
     * @param callable $arg decoding function with signature (returns decoded value):
     *
     *   `function decoder(string $bytes, int $flags, int $datatype): mixed`
     */
    public function decoder(callable $arg): GetAndTouchOptions {}
}

class GetAndLockOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return GetAndLockOptions
     */
    public function timeout(int $arg): GetAndLockOptions {}

    /**
     * Associate custom transcoder with the request.
     *
     * @param callable $arg decoding function with signature (returns decoded value):
     *
     *   `function decoder(string $bytes, int $flags, int $datatype): mixed`
     */
    public function decoder(callable $arg): GetAndLockOptions {}
}

class GetAllReplicasOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return GetAllReplicasOptions
     */
    public function timeout(int $arg): GetAllReplicasOptions {}

    /**
     * Associate custom transcoder with the request.
     *
     * @param callable $arg decoding function with signature (returns decoded value):
     *
     *   `function decoder(string $bytes, int $flags, int $datatype): mixed`
     */
    public function decoder(callable $arg): GetAllReplicasOptions {}
}

class GetAnyReplicaOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return GetAnyReplicaOptions
     */
    public function timeout(int $arg): GetAnyReplicaOptions {}

    /**
     * Associate custom transcoder with the request.
     *
     * @param callable $arg decoding function with signature (returns decoded value):
     *
     *   `function decoder(string $bytes, int $flags, int $datatype): mixed`
     */
    public function decoder(callable $arg): GetAnyReplicaOptions {}
}

class ExistsOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return ExistsOptions
     */
    public function timeout(int $arg): ExistsOptions {}
}

class UnlockOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return UnlockOptions
     */
    public function timeout(int $arg): UnlockOptions {}
}

class InsertOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return InsertOptions
     */
    public function timeout(int $arg): InsertOptions {}

    /**
     * Sets the expiry time for the document.
     *
     * @param int $arg the expiry time in ms
     * @return InsertOptions
     */
    public function expiry(int $arg): InsertOptions {}

    /**
     * Sets the durability level to enforce when writing the document.
     *
     * @param int $arg the durability level to enforce
     * @return InsertOptions
     */
    public function durabilityLevel(int $arg): InsertOptions {}

    /**
     * Associate custom transcoder with the request.
     *
     * @param callable $arg encoding function with signature (returns tuple of bytes, flags and datatype):
     *
     *   `function encoder($value): [string $bytes, int $flags, int $datatype]`
     */
    public function encoder(callable $arg): InsertOptions {}
}

class UpsertOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return UpsertOptions
     */
    public function timeout(int $arg): UpsertOptions {}

    /**
     * Sets the expiry time for the document.
     *
     * @param int|DateTimeInterface $arg the relative expiry time in seconds or DateTimeInterface object for absolute point in time
     * @return UpsertOptions
     */
    public function expiry(mixed $arg): UpsertOptions {}

    /**
     * Sets whether the original expiration should be preserved (by default Replace operation updates expiration).
     *
     * @param bool $shouldPreserve if true, the expiration time will not be updated
     * @return UpsertOptions
     */
    public function preserveExpiry(bool $shouldPreserve): UpsertOptions {}

    /**
     * Sets the durability level to enforce when writing the document.
     *
     * @param int $arg the durability level to enforce
     * @return UpsertOptions
     */
    public function durabilityLevel(int $arg): UpsertOptions {}

    /**
     * Associate custom transcoder with the request.
     *
     * @param callable $arg encoding function with signature (returns tuple of bytes, flags and datatype):
     *
     *   `function encoder($value): [string $bytes, int $flags, int $datatype]`
     */
    public function encoder(callable $arg): UpsertOptions {}
}

class ReplaceOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return ReplaceOptions
     */
    public function timeout(int $arg): ReplaceOptions {}

    /**
     * Sets the expiry time for the document.
     *
     * @param int|DateTimeInterface $arg the relative expiry time in seconds or DateTimeInterface object for absolute point in time
     * @return ReplaceOptions
     */
    public function expiry(mixed $arg): ReplaceOptions {}

    /**
     * Sets whether the original expiration should be preserved (by default Replace operation updates expiration).
     *
     * @param bool $shouldPreserve if true, the expiration time will not be updated
     * @return ReplaceOptions
     */
    public function preserveExpiry(bool $shouldPreserve): ReplaceOptions {}

    /**
     * Sets the cas value for the operation.
     *
     * @param string $arg the cas value
     * @return ReplaceOptions
     */
    public function cas(string $arg): ReplaceOptions {}

    /**
     * Sets the durability level to enforce when writing the document.
     *
     * @param int $arg the durability level to enforce
     * @return ReplaceOptions
     */
    public function durabilityLevel(int $arg): ReplaceOptions {}

    /**
     * Associate custom transcoder with the request.
     *
     * @param callable $arg encoding function with signature (returns tuple of bytes, flags and datatype):
     *
     *   `function encoder($value): [string $bytes, int $flags, int $datatype]`
     */
    public function encoder(callable $arg): ReplaceOptions {}
}

class AppendOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return AppendOptions
     */
    public function timeout(int $arg): AppendOptions {}

    /**
     * Sets the durability level to enforce when writing the document.
     *
     * @param int $arg the durability level to enforce
     * @return AppendOptions
     */
    public function durabilityLevel(int $arg): AppendOptions {}
}

class PrependOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return PrependOptions
     */
    public function timeout(int $arg): PrependOptions {}

    /**
     * Sets the durability level to enforce when writing the document.
     *
     * @param int $arg the durability level to enforce
     * @return PrependOptions
     */
    public function durabilityLevel(int $arg): PrependOptions {}
}

/**
 * An object which contains levels of durability that can be enforced when
 * using mutation operations.
 */
interface DurabilityLevel
{
    /**
     * Apply no durability level.
     */
    public const NONE = 0;

    /**
     * Apply a durability level where the document must be written to memory
     * on a majority of nodes in the cluster.
     */
    public const MAJORITY = 1;

    /**
     * Apply a durability level where the document must be written to memory
     * on a majority of nodes in the cluster and written to disk on the
     * active node.
     */
    public const MAJORITY_AND_PERSIST_TO_ACTIVE = 2;

    /**
     * Apply a durability level where the document must be written to disk
     * on a majority of nodes in the cluster.
     */
    public const PERSIST_TO_MAJORITY = 3;
}

class TouchOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return TouchOptions
     */
    public function timeout(int $arg): TouchOptions {}
}

class IncrementOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return IncrementOptions
     */
    public function timeout(int $arg): IncrementOptions {}

    /**
     * Sets the expiry time for the document.
     *
     * @param int|DateTimeInterface $arg the relative expiry time in seconds or DateTimeInterface object for absolute point in time
     * @return IncrementOptions
     */
    public function expiry(mixed $arg): IncrementOptions {}

    /**
     * Sets the durability level to enforce when writing the document.
     *
     * @param int $arg the durability level to enforce
     * @return IncrementOptions
     */
    public function durabilityLevel(int $arg): IncrementOptions {}

    /**
     * Sets the value to increment the counter by.
     *
     * @param int $arg the value to increment by
     * @return IncrementOptions
     */
    public function delta(int $arg): IncrementOptions {}

    /**
     * Sets the value to initialize the counter to if the document does
     * not exist.
     *
     * @param int $arg the initial value to use if counter does not exist
     * @return IncrementOptions
     */
    public function initial(int $arg): IncrementOptions {}
}

class DecrementOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return DecrementOptions
     */
    public function timeout(int $arg): DecrementOptions {}

    /**
     * Sets the expiry time for the document.
     *
     * @param int|DateTimeInterface $arg the relative expiry time in seconds or DateTimeInterface object for absolute point in time
     * @return DecrementOptions
     */
    public function expiry(mixed $arg): DecrementOptions {}

    /**
     * Sets the durability level to enforce when writing the document.
     *
     * @param int $arg the durability level to enforce
     * @return DecrementOptions
     */
    public function durabilityLevel(int $arg): DecrementOptions {}

    /**
     * Sets the value to decrement the counter by.
     *
     * @param int $arg the value to decrement by
     * @return DecrementOptions
     */
    public function delta(int $arg): DecrementOptions {}

    /**
     * Sets the value to initialize the counter to if the document does
     * not exist.
     *
     * @param int $arg the initial value to use if counter does not exist
     * @return DecrementOptions
     */
    public function initial(int $arg): DecrementOptions {}
}

class RemoveOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return RemoveOptions
     */
    public function timeout(int $arg): RemoveOptions {}

    /**
     * Sets the durability level to enforce when writing the document.
     *
     * @param int $arg the durability level to enforce
     * @return RemoveOptions
     */
    public function durabilityLevel(int $arg): RemoveOptions {}

    /**
     * Sets the cas value to use when performing this operation.
     *
     * @param string $arg the cas value to use
     * @return RemoveOptions
     */
    public function cas(string $arg): RemoveOptions {}
}

class LookupInOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return LookupInOptions
     */
    public function timeout(int $arg): LookupInOptions {}

    /**
     * Sets whether to include document expiry with the document content.
     *
     * When used this option will add one extra subdocument path into
     * the LookupIn operation. This can cause the set of subdocument paths
     * to exceed the maximum number (16) of paths allowed in a subdocument
     * operation.
     *
     * @param bool $arg whether or not to include document expiry
     * @return LookupInOptions
     */
    public function withExpiry(bool $arg): LookupInOptions {}
}

class MutateInOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return MutateInOptions
     */
    public function timeout(int $arg): MutateInOptions {}

    /**
     * Sets the cas value to use when performing this operation.
     *
     * @param string $arg the cas value to use
     * @return MutateInOptions
     */
    public function cas(string $arg): MutateInOptions {}

    /**
     * Sets the expiry time for the document.
     *
     * @param int|DateTimeInterface $arg the relative expiry time in seconds or DateTimeInterface object for absolute point in time
     * @return MutateInOptions
     */
    public function expiry(mixed $arg): MutateInOptions {}

    /**
     * Sets whether the original expiration should be preserved (by default Replace operation updates expiration).
     *
     * @param bool $shouldPreserve if true, the expiration time will not be updated
     * @return MutateInOptions
     */
    public function preserveExpiry(bool $shouldPreserve): MutateInOptions {}

    /**
     * Sets the durability level to enforce when writing the document.
     *
     * @param int $arg the durability level to enforce
     * @return MutateInOptions
     */
    public function durabilityLevel(int $arg): MutateInOptions {}

    /**
     * Sets the document level action to use when performing the operation.
     *
     * @param int $arg the store semantic to use
     * @return MutateInOptions
     */
    public function storeSemantics(int $arg): MutateInOptions {}
}

/**
 * An object which contains how to define the document level action to take
 * during a MutateIn operation.
 */
interface StoreSemantics
{
    /**
     * Replace the document, and fail if it does not exist.
     */
    public const REPLACE = 0;

    /**
     * Replace the document or create it if it does not exist.
     */
    public const UPSERT = 1;

    /**
     * Create the document or fail if it already exists.
     */
    public const INSERT = 2;
}

class ViewOptions
{
    public function timeout(int $arg): ViewOptions {}

    public function includeDocuments(bool $arg, int $maxConcurrentDocuments = 10): ViewOptions {}

    public function key($arg): ViewOptions {}

    public function keys(array $args): ViewOptions {}

    public function limit(int $arg): ViewOptions {}

    public function skip(int $arg): ViewOptions {}

    public function scanConsistency(int $arg): ViewOptions {}

    public function order(int $arg): ViewOptions {}

    public function reduce(bool $arg): ViewOptions {}

    public function group(bool $arg): ViewOptions {}

    public function groupLevel(int $arg): ViewOptions {}

    public function range($start, $end, $inclusiveEnd = false): ViewOptions {}

    public function idRange($start, $end, $inclusiveEnd = false): ViewOptions {}

    public function raw(string $key, $value): ViewOptions {}
}

interface ViewConsistency
{
    public const NOT_BOUNDED = 0;
    public const REQUEST_PLUS = 1;
    public const UPDATE_AFTER = 2;
}

interface ViewOrdering
{
    public const ASCENDING = 0;
    public const DESCENDING = 1;
}

class QueryOptions
{
    /**
     * Sets the operation timeout in milliseconds.
     *
     * @param int $arg the operation timeout to apply
     * @return QueryOptions
     */
    public function timeout(int $arg): QueryOptions {}

    /**
     * Sets the mutation state to achieve consistency with for read your own writes (RYOW).
     *
     * @param MutationState $arg the mutation state to achieve consistency with
     * @return QueryOptions
     */
    public function consistentWith(MutationState $arg): QueryOptions {}

    /**
     * Sets the scan consistency.
     *
     * @param int $arg the scan consistency level
     * @return QueryOptions
     */
    public function scanConsistency(int $arg): QueryOptions {}

    /**
     * Sets the maximum buffered channel size between the indexer client and the query service for index scans.
     *
     * @param int $arg the maximum buffered channel size
     * @return QueryOptions
     */
    public function scanCap(int $arg): QueryOptions {}

    /**
     * Sets the maximum number of items each execution operator can buffer between various operators.
     *
     * @param int $arg the maximum number of items each execution operation can buffer
     * @return QueryOptions
     */
    public function pipelineCap(int $arg): QueryOptions {}

    /**
     * Sets the number of items execution operators can batch for fetch from the KV service.
     *
     * @param int $arg the pipeline batch size
     * @return QueryOptions
     */
    public function pipelineBatch(int $arg): QueryOptions {}

    /**
     * Sets the maximum number of index partitions, for computing aggregation in parallel.
     *
     * @param int $arg the number of index partitions
     * @return QueryOptions
     */
    public function maxParallelism(int $arg): QueryOptions {}

    /**
     * Sets the query profile mode to use.
     *
     * @param int $arg the query profile mode
     * @return QueryOptions
     */
    public function profile(int $arg): QueryOptions {}

    /**
     * Sets whether or not this query is readonly.
     *
     * @param bool $arg whether the query is readonly
     * @return QueryOptions
     */
    public function readonly(bool $arg): QueryOptions {}

    /**
     * Sets whether or not this query allowed to use FlexIndex (full text search integration).
     *
     * @param bool $arg whether the FlexIndex allowed
     * @return QueryOptions
     */
    public function flexIndex(bool $arg): QueryOptions {}

    /**
     * Sets whether or not this query is adhoc.
     *
     * @param bool $arg whether the query is adhoc
     * @return QueryOptions
     */
    public function adhoc(bool $arg): QueryOptions {}

    /**
     * Sets the named parameters for this query.
     *
     * @param array $pairs the associative array of parameters
     * @return QueryOptions
     */
    public function namedParameters(array $pairs): QueryOptions {}

    /**
     * Sets the positional parameters for this query.
     *
     * @param array $args the array of parameters
     * @return QueryOptions
     */
    public function positionalParameters(array $args): QueryOptions {}

    /**
     * Sets any extra query parameters that the SDK does not provide an option for.
     *
     * @param string $key the name of the parameter
     * @param string $value the value of the parameter
     * @return QueryOptions
     */
    public function raw(string $key, $value): QueryOptions {}

    /**
     * Sets the client context id for this query.
     *
     * @param string $arg the client context id
     * @return QueryOptions
     */
    public function clientContextId(string $arg): QueryOptions {}

    /**
     * Sets whether or not to return metrics with the query.
     *
     * @param bool $arg whether to return metrics
     * @return QueryOptions
     */
    public function metrics(bool $arg): QueryOptions {}

    /**
     * Associate scope name with query
     *
     * @param string $arg the name of the scope
     * @return QueryOptions
     */
    public function scopeName(string $arg): QueryOptions {}

    /**
     * Associate scope qualifier (also known as `query_context`) with the query.
     *
     * The qualifier must be in form `${bucketName}.${scopeName}` or `default:${bucketName}.${scopeName}`
     *
     * @param string $arg the scope qualifier
     * @return QueryOptions
     */
    public function scopeQualifier(string $arg): QueryOptions {}
}

/**
 * Set of values for the scan consistency level of a query.
 */
interface QueryScanConsistency
{
    /**
     * Set scan consistency to not bounded
     */
    public const NOT_BOUNDED = 1;

    /**
     * Set scan consistency to not request plus
     */
    public const REQUEST_PLUS = 2;

    /**
     * Set scan consistency to statement plus
     */
    public const STATEMENT_PLUS = 3;
}

/**
 * Set of values for setting the profile mode of a query.
 */
interface QueryProfile
{
    /**
     * Set profiling to off
     */
    public const OFF = 1;

    /**
     * Set profiling to include phase timings
     */
    public const PHASES = 2;

    /**
     * Set profiling to include execution timings
     */
    public const TIMINGS = 3;
}

class ClusterOptions
{
    public function credentials(string $username, string $password): ClusterOptions {}
}

/**
 * Provides an interface for recording values.
 */
interface ValueRecorder
{
    /**
     * Records a new value.
     *
     * @param int $value The value to record.
     */
    public function recordValue(int $value): void;
}

/**
 * Providers an interface to create value recorders for recording metrics.
 */
interface Meter
{
    /**
     * Creates a new value recorder for a metric with the specified tags.
     *
     * @param string $name The name of the metric.
     * @param array $tags The tags to associate with the metric.
     *
     * @return ValueRecorder
     */
    public function valueRecorder(string $name, array $tags): ValueRecorder;
}

/**
 * Implements a no-op meter which performs no metrics instrumentation.  Note that
 * to reduce the performance impact of using this meter, this class is not
 * actually used by the SDK, and simply acts as a placeholder which triggers a
 * native implementation to be used instead.
 */
class NoopMeter implements Meter
{
    public function valueRecorder(string $name, array $tags): ValueRecorder {}
}

/**
 * Implements a default meter which logs metrics on a regular basis.  Note that
 * to reduce the performance impact of using this meter, this class is not
 * actually used by the SDK, and simply acts as a placeholder which triggers a
 * native implementation to be used instead.
 */
class LoggingMeter implements Meter
{
    /**
     * @param int $duration duration in microseconds how often the metrics should be flushed in the log.
     */
    public function flushInterval(int $duration): LoggingMeter {}

    public function valueRecorder(string $name, array $tags): ValueRecorder {}
}

/**
 * Represents a span of time an event occurs over.
 */
interface RequestSpan
{
    /**
     * Adds an tag to this span.
     *
     * @param string $key The key of the tag to add.
     * @param int|string $value The value to assign to the tag.
     */
    public function addTag(string $key, $value): void;

    /**
     * Ends this span.
     */
    public function end(): void;
}

/**
 * Represents a tracer capable of creating trace spans.
 */
interface RequestTracer
{
    /**
     * Creates a new request span.
     *
     * @param string $name The name of the span.
     * @param string|null $parent The parent of the span, if one exists.
     */
    public function requestSpan(string $name, RequestSpan $parent = null);
}

/**
 * This implements a basic default tracer which keeps track of operations
 * which falls outside a specified threshold.  Note that to reduce the
 * performance impact of using this tracer, this class is not actually
 * used by the SDK, and simply acts as a placeholder which triggers a
 * native implementation to be used instead.
 */
class ThresholdLoggingTracer implements RequestTracer
{
    public function requestSpan(string $name, RequestSpan $parent = null) {}

    /**
     * Specifies how often aggregated trace information should be logged,
     * specified in microseconds.
     */
    public function emitInterval(int $duration) {}

    /**
     * Specifies the threshold for when a kv request should be included
     * in the aggregated metrics, specified in microseconds.
     */
    public function kvThreshold(int $duration) {}

    /**
     * Specifies the threshold for when a query request should be included
     * in the aggregated metrics, specified in microseconds.
     */
    public function queryThreshold(int $duration) {}

    /**
     * Specifies the threshold for when a views request should be included
     * in the aggregated metrics, specified in microseconds.
     */
    public function viewsThreshold(int $duration) {}

    /**
     * Specifies the threshold for when a search request should be included
     * in the aggregated metrics, specified in microseconds.
     */
    public function searchThreshold(int $duration) {}

    /**
     * Specifies the threshold for when an analytics request should be included
     * in the aggregated metrics, specified in microseconds.
     */
    public function analyticsThreshold(int $duration) {}

    /**
     * Specifies the number of entries which should be kept between each
     * logging interval.
     */
    public function sampleSize(int $size) {}
}

/**
 * Implements a no-op tracer which performs no work.  Note that to reduce the
 * performance impact of using this tracer, this class is not actually
 * used by the SDK, and simply acts as a placeholder which triggers a
 * native implementation to be used instead.
 */
class NoopTracer implements RequestTracer
{
    public function requestSpan(string $name, RequestSpan $parent = null) {}
}

/**
 * vim: ts=4 sts=4 sw=4 et
 */
