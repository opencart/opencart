<?php

namespace Braintree;

/**
 * Braintree PaymentMethodCustomerDataUpdatedMetadata module
 */
class PaymentMethodCustomerDataUpdatedMetadata extends Base
{
    /**
     *  factory method: returns an instance of PaymentMethodCustomerDataUpdatedMetadata
     *  to the requesting method, with populated properties
     *
     * @param array $attributes used to create the object
     *
     * @return PaymentMethodCustomerDataUpdatedMetadata
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    protected function _initialize($metadataAttribs)
    {
        // set the attributes
        $this->_attributes = $metadataAttribs;
        if (isset($metadataAttribs['paymentMethod'])) {
            $this->paymentMethod = PaymentMethodParser::parsePaymentMethod($metadataAttribs['paymentMethod']);
        }

        if (isset($metadataAttribs['enrichedCustomerData'])) {
            $this->_set(
                'enrichedCustomerData',
                EnrichedCustomerData::factory(
                    $metadataAttribs['enrichedCustomerData']
                )
            );
        }
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }
}
