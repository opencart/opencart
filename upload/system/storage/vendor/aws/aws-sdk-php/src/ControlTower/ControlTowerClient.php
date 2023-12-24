<?php
namespace Aws\ControlTower;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Control Tower** service.
 * @method \Aws\Result disableControl(array $args = [])
 * @method \GuzzleHttp\Promise\Promise disableControlAsync(array $args = [])
 * @method \Aws\Result enableControl(array $args = [])
 * @method \GuzzleHttp\Promise\Promise enableControlAsync(array $args = [])
 * @method \Aws\Result getControlOperation(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getControlOperationAsync(array $args = [])
 * @method \Aws\Result getEnabledControl(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getEnabledControlAsync(array $args = [])
 * @method \Aws\Result listEnabledControls(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listEnabledControlsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class ControlTowerClient extends AwsClient {}
