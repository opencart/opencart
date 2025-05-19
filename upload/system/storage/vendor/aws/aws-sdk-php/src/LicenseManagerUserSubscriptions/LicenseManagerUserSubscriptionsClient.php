<?php
namespace Aws\LicenseManagerUserSubscriptions;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS License Manager User Subscriptions** service.
 * @method \Aws\Result associateUser(array $args = [])
 * @method \GuzzleHttp\Promise\Promise associateUserAsync(array $args = [])
 * @method \Aws\Result createLicenseServerEndpoint(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createLicenseServerEndpointAsync(array $args = [])
 * @method \Aws\Result deleteLicenseServerEndpoint(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteLicenseServerEndpointAsync(array $args = [])
 * @method \Aws\Result deregisterIdentityProvider(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deregisterIdentityProviderAsync(array $args = [])
 * @method \Aws\Result disassociateUser(array $args = [])
 * @method \GuzzleHttp\Promise\Promise disassociateUserAsync(array $args = [])
 * @method \Aws\Result listIdentityProviders(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listIdentityProvidersAsync(array $args = [])
 * @method \Aws\Result listInstances(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listInstancesAsync(array $args = [])
 * @method \Aws\Result listLicenseServerEndpoints(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listLicenseServerEndpointsAsync(array $args = [])
 * @method \Aws\Result listProductSubscriptions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listProductSubscriptionsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result listUserAssociations(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listUserAssociationsAsync(array $args = [])
 * @method \Aws\Result registerIdentityProvider(array $args = [])
 * @method \GuzzleHttp\Promise\Promise registerIdentityProviderAsync(array $args = [])
 * @method \Aws\Result startProductSubscription(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startProductSubscriptionAsync(array $args = [])
 * @method \Aws\Result stopProductSubscription(array $args = [])
 * @method \GuzzleHttp\Promise\Promise stopProductSubscriptionAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateIdentityProviderSettings(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateIdentityProviderSettingsAsync(array $args = [])
 */
class LicenseManagerUserSubscriptionsClient extends AwsClient {}
