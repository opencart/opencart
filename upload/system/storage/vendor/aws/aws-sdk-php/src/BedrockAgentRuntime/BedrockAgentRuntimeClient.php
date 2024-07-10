<?php
namespace Aws\BedrockAgentRuntime;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Agents for Amazon Bedrock Runtime** service.
 * @method \Aws\Result deleteAgentMemory(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteAgentMemoryAsync(array $args = [])
 * @method \Aws\Result getAgentMemory(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getAgentMemoryAsync(array $args = [])
 * @method \Aws\Result invokeAgent(array $args = [])
 * @method \GuzzleHttp\Promise\Promise invokeAgentAsync(array $args = [])
 * @method \Aws\Result invokeFlow(array $args = [])
 * @method \GuzzleHttp\Promise\Promise invokeFlowAsync(array $args = [])
 * @method \Aws\Result retrieve(array $args = [])
 * @method \GuzzleHttp\Promise\Promise retrieveAsync(array $args = [])
 * @method \Aws\Result retrieveAndGenerate(array $args = [])
 * @method \GuzzleHttp\Promise\Promise retrieveAndGenerateAsync(array $args = [])
 */
class BedrockAgentRuntimeClient extends AwsClient {}
