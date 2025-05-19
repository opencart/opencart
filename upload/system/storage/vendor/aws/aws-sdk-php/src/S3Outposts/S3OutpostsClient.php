<?php
namespace Aws\S3Outposts;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon S3 on Outposts** service.
 * @method \Aws\Result createEndpoint(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createEndpointAsync(array $args = [])
 * @method \Aws\Result deleteEndpoint(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteEndpointAsync(array $args = [])
 * @method \Aws\Result listEndpoints(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listEndpointsAsync(array $args = [])
 * @method \Aws\Result listOutpostsWithS3(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listOutpostsWithS3Async(array $args = [])
 * @method \Aws\Result listSharedEndpoints(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listSharedEndpointsAsync(array $args = [])
 */
class S3OutpostsClient extends AwsClient {}
