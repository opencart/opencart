<?php

namespace Braintree\Dispute;

use Braintree\Instance;

/**
 * Evidence details for a dispute
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/dispute#evidence developer docs} for information on attributes
 */
class EvidenceDetails extends Instance
{
    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attributes)
    {
        if (array_key_exists('category', $attributes)) {
            $attributes['tag'] = $attributes['category'];
        }
        parent::__construct($attributes);
    }
}
