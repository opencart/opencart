<?php
namespace Aws\Braket;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Braket** service.
 * @method \Aws\Result cancelQuantumTask(array $args = [])
 * @method \GuzzleHttp\Promise\Promise cancelQuantumTaskAsync(array $args = [])
 * @method \Aws\Result createQuantumTask(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createQuantumTaskAsync(array $args = [])
 * @method \Aws\Result getDevice(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getDeviceAsync(array $args = [])
 * @method \Aws\Result getQuantumTask(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getQuantumTaskAsync(array $args = [])
 * @method \Aws\Result searchDevices(array $args = [])
 * @method \GuzzleHttp\Promise\Promise searchDevicesAsync(array $args = [])
 * @method \Aws\Result searchQuantumTasks(array $args = [])
 * @method \GuzzleHttp\Promise\Promise searchQuantumTasksAsync(array $args = [])
 */
class BraketClient extends AwsClient {}
