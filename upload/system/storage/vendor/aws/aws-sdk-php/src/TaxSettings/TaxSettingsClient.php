<?php
namespace Aws\TaxSettings;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Tax Settings** service.
 * @method \Aws\Result batchDeleteTaxRegistration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise batchDeleteTaxRegistrationAsync(array $args = [])
 * @method \Aws\Result batchPutTaxRegistration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise batchPutTaxRegistrationAsync(array $args = [])
 * @method \Aws\Result deleteTaxRegistration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteTaxRegistrationAsync(array $args = [])
 * @method \Aws\Result getTaxRegistration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getTaxRegistrationAsync(array $args = [])
 * @method \Aws\Result getTaxRegistrationDocument(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getTaxRegistrationDocumentAsync(array $args = [])
 * @method \Aws\Result listTaxRegistrations(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTaxRegistrationsAsync(array $args = [])
 * @method \Aws\Result putTaxRegistration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putTaxRegistrationAsync(array $args = [])
 */
class TaxSettingsClient extends AwsClient {}
