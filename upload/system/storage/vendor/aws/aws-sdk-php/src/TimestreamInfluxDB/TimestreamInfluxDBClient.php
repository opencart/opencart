<?php
namespace Aws\TimestreamInfluxDB;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Timestream InfluxDB** service.
 * @method \Aws\Result createDbInstance(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createDbInstanceAsync(array $args = [])
 * @method \Aws\Result createDbParameterGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createDbParameterGroupAsync(array $args = [])
 * @method \Aws\Result deleteDbInstance(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteDbInstanceAsync(array $args = [])
 * @method \Aws\Result getDbInstance(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getDbInstanceAsync(array $args = [])
 * @method \Aws\Result getDbParameterGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getDbParameterGroupAsync(array $args = [])
 * @method \Aws\Result listDbInstances(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listDbInstancesAsync(array $args = [])
 * @method \Aws\Result listDbParameterGroups(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listDbParameterGroupsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateDbInstance(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateDbInstanceAsync(array $args = [])
 */
class TimestreamInfluxDBClient extends AwsClient {}
