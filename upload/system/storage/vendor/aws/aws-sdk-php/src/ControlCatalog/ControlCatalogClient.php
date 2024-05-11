<?php
namespace Aws\ControlCatalog;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Control Catalog** service.
 * @method \Aws\Result listCommonControls(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listCommonControlsAsync(array $args = [])
 * @method \Aws\Result listDomains(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listDomainsAsync(array $args = [])
 * @method \Aws\Result listObjectives(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listObjectivesAsync(array $args = [])
 */
class ControlCatalogClient extends AwsClient {}
