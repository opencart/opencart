<?php
namespace Aws\DocDB;

use Aws\AwsClient;
use Aws\PresignUrlMiddleware;

/**
 * This client is used to interact with the **Amazon DocumentDB with MongoDB compatibility** service.
 * @method \Aws\Result addSourceIdentifierToSubscription(array $args = [])
 * @method \GuzzleHttp\Promise\Promise addSourceIdentifierToSubscriptionAsync(array $args = [])
 * @method \Aws\Result addTagsToResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise addTagsToResourceAsync(array $args = [])
 * @method \Aws\Result applyPendingMaintenanceAction(array $args = [])
 * @method \GuzzleHttp\Promise\Promise applyPendingMaintenanceActionAsync(array $args = [])
 * @method \Aws\Result copyDBClusterParameterGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise copyDBClusterParameterGroupAsync(array $args = [])
 * @method \Aws\Result copyDBClusterSnapshot(array $args = [])
 * @method \GuzzleHttp\Promise\Promise copyDBClusterSnapshotAsync(array $args = [])
 * @method \Aws\Result createDBCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createDBClusterAsync(array $args = [])
 * @method \Aws\Result createDBClusterParameterGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createDBClusterParameterGroupAsync(array $args = [])
 * @method \Aws\Result createDBClusterSnapshot(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createDBClusterSnapshotAsync(array $args = [])
 * @method \Aws\Result createDBInstance(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createDBInstanceAsync(array $args = [])
 * @method \Aws\Result createDBSubnetGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createDBSubnetGroupAsync(array $args = [])
 * @method \Aws\Result createEventSubscription(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createEventSubscriptionAsync(array $args = [])
 * @method \Aws\Result createGlobalCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createGlobalClusterAsync(array $args = [])
 * @method \Aws\Result deleteDBCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteDBClusterAsync(array $args = [])
 * @method \Aws\Result deleteDBClusterParameterGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteDBClusterParameterGroupAsync(array $args = [])
 * @method \Aws\Result deleteDBClusterSnapshot(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteDBClusterSnapshotAsync(array $args = [])
 * @method \Aws\Result deleteDBInstance(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteDBInstanceAsync(array $args = [])
 * @method \Aws\Result deleteDBSubnetGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteDBSubnetGroupAsync(array $args = [])
 * @method \Aws\Result deleteEventSubscription(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteEventSubscriptionAsync(array $args = [])
 * @method \Aws\Result deleteGlobalCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteGlobalClusterAsync(array $args = [])
 * @method \Aws\Result describeCertificates(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeCertificatesAsync(array $args = [])
 * @method \Aws\Result describeDBClusterParameterGroups(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeDBClusterParameterGroupsAsync(array $args = [])
 * @method \Aws\Result describeDBClusterParameters(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeDBClusterParametersAsync(array $args = [])
 * @method \Aws\Result describeDBClusterSnapshotAttributes(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeDBClusterSnapshotAttributesAsync(array $args = [])
 * @method \Aws\Result describeDBClusterSnapshots(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeDBClusterSnapshotsAsync(array $args = [])
 * @method \Aws\Result describeDBClusters(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeDBClustersAsync(array $args = [])
 * @method \Aws\Result describeDBEngineVersions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeDBEngineVersionsAsync(array $args = [])
 * @method \Aws\Result describeDBInstances(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeDBInstancesAsync(array $args = [])
 * @method \Aws\Result describeDBSubnetGroups(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeDBSubnetGroupsAsync(array $args = [])
 * @method \Aws\Result describeEngineDefaultClusterParameters(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeEngineDefaultClusterParametersAsync(array $args = [])
 * @method \Aws\Result describeEventCategories(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeEventCategoriesAsync(array $args = [])
 * @method \Aws\Result describeEventSubscriptions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeEventSubscriptionsAsync(array $args = [])
 * @method \Aws\Result describeEvents(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeEventsAsync(array $args = [])
 * @method \Aws\Result describeGlobalClusters(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeGlobalClustersAsync(array $args = [])
 * @method \Aws\Result describeOrderableDBInstanceOptions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeOrderableDBInstanceOptionsAsync(array $args = [])
 * @method \Aws\Result describePendingMaintenanceActions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describePendingMaintenanceActionsAsync(array $args = [])
 * @method \Aws\Result failoverDBCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise failoverDBClusterAsync(array $args = [])
 * @method \Aws\Result failoverGlobalCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise failoverGlobalClusterAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result modifyDBCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise modifyDBClusterAsync(array $args = [])
 * @method \Aws\Result modifyDBClusterParameterGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise modifyDBClusterParameterGroupAsync(array $args = [])
 * @method \Aws\Result modifyDBClusterSnapshotAttribute(array $args = [])
 * @method \GuzzleHttp\Promise\Promise modifyDBClusterSnapshotAttributeAsync(array $args = [])
 * @method \Aws\Result modifyDBInstance(array $args = [])
 * @method \GuzzleHttp\Promise\Promise modifyDBInstanceAsync(array $args = [])
 * @method \Aws\Result modifyDBSubnetGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise modifyDBSubnetGroupAsync(array $args = [])
 * @method \Aws\Result modifyEventSubscription(array $args = [])
 * @method \GuzzleHttp\Promise\Promise modifyEventSubscriptionAsync(array $args = [])
 * @method \Aws\Result modifyGlobalCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise modifyGlobalClusterAsync(array $args = [])
 * @method \Aws\Result rebootDBInstance(array $args = [])
 * @method \GuzzleHttp\Promise\Promise rebootDBInstanceAsync(array $args = [])
 * @method \Aws\Result removeFromGlobalCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise removeFromGlobalClusterAsync(array $args = [])
 * @method \Aws\Result removeSourceIdentifierFromSubscription(array $args = [])
 * @method \GuzzleHttp\Promise\Promise removeSourceIdentifierFromSubscriptionAsync(array $args = [])
 * @method \Aws\Result removeTagsFromResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise removeTagsFromResourceAsync(array $args = [])
 * @method \Aws\Result resetDBClusterParameterGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise resetDBClusterParameterGroupAsync(array $args = [])
 * @method \Aws\Result restoreDBClusterFromSnapshot(array $args = [])
 * @method \GuzzleHttp\Promise\Promise restoreDBClusterFromSnapshotAsync(array $args = [])
 * @method \Aws\Result restoreDBClusterToPointInTime(array $args = [])
 * @method \GuzzleHttp\Promise\Promise restoreDBClusterToPointInTimeAsync(array $args = [])
 * @method \Aws\Result startDBCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startDBClusterAsync(array $args = [])
 * @method \Aws\Result stopDBCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise stopDBClusterAsync(array $args = [])
 * @method \Aws\Result switchoverGlobalCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise switchoverGlobalClusterAsync(array $args = [])
 */
class DocDBClient extends AwsClient {
    public function __construct(array $args)
    {
        $args['with_resolved'] = function (array $args) {
            $this->getHandlerList()->appendInit(
                PresignUrlMiddleware::wrap(
                    $this,
                    $args['endpoint_provider'],
                    [
                        'operations' => [
                            'CopyDBClusterSnapshot',
                            'CreateDBCluster',
                        ],
                        'service' => 'rds',
                        'presign_param' => 'PreSignedUrl',
                        'require_different_region' => true,
                        'extra_query_params' => [
                            'CopyDBClusterSnapshot' => ['DestinationRegion'],
                            'CreateDBCluster' => ['DestinationRegion'],
                        ]
                    ]
                ),
                'rds.presigner'
            );
        };
        parent::__construct($args);
    }
}
