<?php
namespace Aws\BedrockRuntime;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Bedrock Runtime** service.
 * @method \Aws\Result applyGuardrail(array $args = [])
 * @method \GuzzleHttp\Promise\Promise applyGuardrailAsync(array $args = [])
 * @method \Aws\Result converse(array $args = [])
 * @method \GuzzleHttp\Promise\Promise converseAsync(array $args = [])
 * @method \Aws\Result converseStream(array $args = [])
 * @method \GuzzleHttp\Promise\Promise converseStreamAsync(array $args = [])
 * @method \Aws\Result invokeModel(array $args = [])
 * @method \GuzzleHttp\Promise\Promise invokeModelAsync(array $args = [])
 * @method \Aws\Result invokeModelWithResponseStream(array $args = [])
 * @method \GuzzleHttp\Promise\Promise invokeModelWithResponseStreamAsync(array $args = [])
 */
class BedrockRuntimeClient extends AwsClient {}
