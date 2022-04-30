<?php
namespace Braintree\Dispute;

use Braintree\Instance;

/**
 * Status History for a dispute
 *
 * @package    Braintree
 *
 * @property-read \DateTime $disbursementDate
 * @property-read \DateTime $effectiveDate
 * @property-read string $status
 * @property-read \DateTime $timestamp
 */
class StatusHistoryDetails extends Instance
{
}

class_alias('Braintree\Dispute\StatusHistoryDetails', 'Braintree_Dispute_StatusHistoryDetails');
